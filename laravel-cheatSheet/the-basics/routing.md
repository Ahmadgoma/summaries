# Routing

* [Redirect Routes](#redirect-routes)
* [View Routes](#view-routes)
* [Required Parameters](#required-parameters)
* [Optional Parameters](#optional-parameters)
* [Regular Expression Constraints](#regular-expression-constraints)
* [Named Routes](#named-routes)
* [Route Groups](#route-groups)
    * Middleware
    * Namespaces
    * Sub-Domain Routing
    * Route Prefixes
    * Route Name Prefixes
* [Route Model Binding](#route-model-binding)
    * Implicit Binding
    * **Explicit Binding**
        * Customizing The Resolution Logic
* [**Rate Limiting**](#rate-limiting)
* [Accessing The Current Route](#accessing-the-current-route)


### Redirect Routes
```php
Route::redirect('/here', '/there', 301);
```

### View Routes
```php
Route::view('/welcome', 'welcome');

Route::view('/welcome', 'welcome', ['name' => 'Taylor']);
```

### Required Parameters
```php
Route::get('posts/{post}/comments/{comment}', function ($postId, $commentId) {
    //
});
```

### Optional Parameters
```php
Route::get('user/{name?}', function ($name = 'John') {
    return $name;
});
```

### Regular Expression Constraints
```php
Route::get('user/{id}/{name}', function ($id, $name) {
    //
})->where(['id' => '[0-9]+', 'name' => '[a-z]+']);
```
**Global Constraints** from <code>RouteServiceProvider</code>
```php
public function boot()
{
    Route::pattern('id', '[0-9]+');

    parent::boot();
}

// when use
Route::get('user/{id}', function ($id) {
    // Only executed if {id} is numeric...
});
```

### Named Routes
```php
Route::get('user/profile', 'UserController@showProfile')->name('profile');
```
**Generating URLs To Named Routes**
```php
// Generating URLs...
$url = route('profile');

// with param
$url = route('profile', ['id' => 1]);

// Generating Redirects...
return redirect()->route('profile');
```
**Inspecting The Current Route**
```php
public function handle($request, Closure $next)
{
    if ($request->route()->named('profile')) {
        //
    }

    return $next($request);
}
```

### Route Groups
**Middleware**
```php
Route::middleware(['first', 'second'])->group(function () {
    Route::get('/', function () {
        // Uses first & second Middleware
    });

    Route::get('user/profile', function () {
        // Uses first & second Middleware
    });
});
```
**Namespaces**
```php
Route::namespace('Admin')->group(function () {
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
});
```
**Sub-Domain Routing**
```php
Route::domain('{account}.myapp.com')->group(function () {
    Route::get('user/{id}', function ($account, $id) {
        //
    });
});
```
**Route Prefixes**
```php
Route::prefix('admin')->group(function () {
    Route::get('users', function () {
        // Matches The "/admin/users" URL
    });
});
```
**Route Name Prefixes**
```php
Route::name('admin.')->group(function () {
    Route::get('users', function () {
        // Route assigned name "admin.users"...
    })->name('users');
});
```

### Route Model Binding
**Implicit Binding**
Laravel automatically resolves Eloquent models defined in routes or controller actions whose type-hinted variable names match a route segment name.
```php
// Select by id
Route::get('api/users/{user}', function (App\User $user) {
    return $user->email;
});
```
To customizing the key name
```php
// Model
public function getRouteKeyName()
{
    return 'slug';
}
```
**Explicit Binding**
To register an explicit binding, use the router's <code>model</code> method to specify the class for a given parameter. On <code>RouteServiceProvider</code>
```php
public function boot()
{
    parent::boot();

    Route::model('user', App\User::class);
}

// Then
Route::get('profile/{user}', function (App\User $user) {
    //
});
```
Now <code>user</code> param will only assign to <code>User</code> model.

**Customizing The Resolution Logic**
```php
public function boot()
{
    parent::boot();

    Route::bind('user', function ($value) {
        return App\User::where('name', $value)->first() ?? abort(404);
    });
}
```

### Rate Limiting
```php
Route::middleware('auth:api', 'throttle:60,1')->group(function () {
    Route::get('/user', function () {
        //
    });
});
```
**Dynamic Rate Limiting**
On your <code>model</code> declare attribute with same name on Route, in this case <code>rate_limit</code>.
```php
Route::middleware('auth:api', 'throttle:rate_limit,1')->group(function () {
    Route::get('/user', function () {
        //
    });
});
```

### Accessing The Current Route
```php
$route = Route::current();

$name = Route::currentRouteName();

$action = Route::currentRouteAction();
```
