# Middleware

* [Defining Middleware](#defining-middleware)
* [Before and After Middleware](#before-and-after-middleware)
* [Registering Middleware](#registering-middleware)
* [Middleware Parameters](#middleware-parameters)
* [Terminable Middleware](#terminable-middleware)

### Defining Middleware
```bash
php artisan make:middleware CheckAge
```

```php
public function handle($request, Closure $next)
{
    if ($request->age <= 200) {
        return redirect('home');
    }

    return $next($request);
}
```

### Before and After Middleware
**Before**
```php
public function handle($request, Closure $next)
{
    // Perform action

    return $next($request);
}
```
**After**
```php
public function handle($request, Closure $next)
{
    $response = $next($request);

    // Perform action

    return $response;
}
```

### Registering Middleware
**Global Middleware** In every request. <br>
From <code>$middleware</code> property of your <code>app/Http/Kernel.php</code> class.

**Assigning Middleware To Routes/Controller** <br>
From <code>$routeMiddleware</code> property of your <code>app/Http/Kernel.php</code> class.
```php
protected $routeMiddleware = [
    'auth' => \Illuminate\Auth\Middleware\Authenticate::class
];

// From route
Route::get('admin/profile', function () {
    //
})->middleware('auth');
```

**Middleware Groups** <br>
From <code>$middlewareGroups</code> property of your <code>app/Http/Kernel.php</code> class.
```php
protected $middlewareGroups = [
    'web' => [
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];

// From route
Route::get('/', function () {
    //
})->middleware('web');

Route::group(['middleware' => ['web']], function () {
    //
});
```

### Middleware Parameters
```php
public function handle($request, Closure $next, $role)
{
    if (! $request->user()->hasRole($role)) {
        // Redirect...
    }

    return $next($request);
}

// From route
Route::put('post/{id}', function ($id) {
    //
})->middleware('role:editor');
```

### Terminable Middleware
Sometimes a Middleware may need to do some work after the HTTP response has been prepared. <br>
Method <code>terminate</code> it will automatically be called after the response is ready to be sent to the browser.
```php
public function handle($request, Closure $next)
{
    return $next($request);
}

public function terminate($request, $response)
{
    // Store the session data...
}
```
