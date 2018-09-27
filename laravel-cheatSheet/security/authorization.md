# Authorization

* [Gates](#gates)
    * Writing Gates
    * Resource Gates
    * Authorizing Actions
    * Intercepting Gate Checks
* [Creating Policies](#creating-policies)
    * Generating Policies
    * Registering Policies
* [Writing Policies](#writing-policies)
    * Policy Methods
    * Methods Without Models
    * Policy Filters
* [Authorizing Actions Using Policies](#authorizing-actions-using-policies)
    * Via The User Model
    * Via Middleware
    * Via Controller Helpers
    * Via Blade Templates

### Gates
**Writing Gates**
```php
public function boot()
{
    $this->registerPolicies();

    Gate::define('update-post', function ($user, $post) {
        return $user->id == $post->user_id;
    });

    // or
    Gate::define('update-post', 'PostPolicy@update');
}
```

**Resource Gates**
```php
public function boot()
{
    $this->registerPolicies();

    // view, create, update, delete
    Gate::resource('posts', 'App\Policies\PostPolicy');

    // For specific gates
    Gate::resource('posts', 'PostPolicy', [
        'image' => 'updateImage',
        'photo' => 'updatePhoto',
    ]);
}
```

**Authorizing Actions**
```php
if (Gate::allows('update-post', $post)) {
    // The current user can update the post...
}

if (Gate::denies('update-post', $post)) {
    // The current user can't update the post...
}
```
Or for particular user to if he has authorized to perform an action.
```php
if (Gate::forUser($user)->allows('update-post', $post)) {
    // The user can update the post...
}

if (Gate::forUser($user)->denies('update-post', $post)) {
    // The user can't update the post...
}
```

**Intercepting Gate Checks**
```php
Gate::before(function ($user, $ability) {
    if ($user->isSuperAdmin()) {
        return true;
    }
});
```
Or you can use after
```php
Gate::after(function ($user, $ability, $result, $arguments) {
    //
});
```

### Creating Policies
**Generating Policies**
```bash
php artisan make:policy PostPolicy

# To generate a class with the basic "CRUD" policy methods.
php artisan make:policy PostPolicy --model=Post
```

**Registering Policies**
```php
// App\Providers\AuthServiceProvider

protected $policies = [
    Post::class => PostPolicy::class,
];

```

### Writing Policies
**Policy Methods**
```php
public function update(User $user, Post $post)
{
    return $user->id === $post->user_id;
}
```

**Methods Without Models**
```php
public function create(User $user)
{
    //
}
```

**Policy Filters**
```php
public function before($user, $ability)
{
    if ($user->isSuperAdmin()) {
        return true;
    }
}
```

### Authorizing Actions Using Policies
**Via The User Model**
```php
if ($user->can('update', $post)) {
    //
}
```
Actions That Don't Require Models
```php
if ($user->can('create', Post::class)) {
    // Executes the "create" method on the relevant policy...
}
```

**Via Middleware**
```php
Route::put('/post/{post}', function (Post $post) {
    // The current user may update the post...
})->middleware('can:update,post');
```
Actions That Don't Require Models
```php
Route::post('/post', function () {
    // The current user may create posts...
})->middleware('can:create,App\Post');
```

**Via Controller Helpers**
```php
public function update(Request $request, Post $post)
{
    $this->authorize('update', $post);

    // The current user can update the blog post...
}
```
Actions That Don't Require Models
```php
public function create(Request $request)
{
    $this->authorize('create', Post::class);

    // The current user can create blog posts...
}
```

**Via Blade Templates**
```html
@can('update', $post)
    <!-- The Current User Can Update The Post -->
@elsecan('create', App\Post::class)
    <!-- The Current User Can Create New Post -->
@endcan

@cannot('update', $post)
    <!-- The Current User Can't Update The Post -->
@elsecannot('create', App\Post::class)
    <!-- The Current User Can't Create New Post -->
@endcannot

<!-- =================== -->
<!-- OR -->
@if (Auth::user()->can('update', $post))
    <!-- The Current User Can Update The Post -->
@endif

@unless (Auth::user()->can('update', $post))
    <!-- The Current User Can't Update The Post -->
@endunless
```
Actions That Don't Require Models
```html
@can('create', App\Post::class)
    <!-- The Current User Can Create Posts -->
@endcan

@cannot('create', App\Post::class)
    <!-- The Current User Can't Create Posts -->
@endcannot
```
