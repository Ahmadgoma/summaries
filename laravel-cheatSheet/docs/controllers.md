# Controllers

* [Single Action Controllers](#single-action-controllers)
* [Controller Middleware](#controller-middleware)
* [Resource Controllers](#resource-controllers)
    * Specifying The Resource Model
    * Partial Resource Routes
    * **API Resource Routes**
    * Naming Resource Routes
    * Naming Resource Route Parameters
    * **Localizing Resource URIs**
    * Supplementing Resource Controllers
* [**Route Caching**](#route-caching)

### Single Action Controllers
If you would like to define a controller that only handles a single action, you may place a single <code>__invoke</code> method on the controller.


### Controller Middleware
```php
public function __construct()
{
    $this->middleware('auth');

    $this->middleware('log')->only('index');

    $this->middleware('subscribed')->except('store');
}
```
You can also declare Middleware inside the controller.
```php
$this->middleware(function ($request, $next) {
    // ...

    return $next($request);
});
```

### Resource Controllers
```bash
php artisan make:controller PhotosController -r
```

```php
// From route
Route::resource('photos', 'PhotosController');
// or
Route::resources([
    'photos' => 'PhotosController',
    'posts' => 'PostsController'
]);
```
**Specifying The Resource Model**
```bash
php artisan make:controller PhotosController --resource --model=Photo
```
**Partial Resource Routes**
```php
Route::resource('photos', 'PhotoController')->only([
    'index', 'show'
]);

Route::resource('photos', 'PhotoController')->except([
    'create', 'store', 'update', 'destroy'
]);
```
**API Resource Routes**
```bash
php artisan make:controller API/PhotoController --api
```

```php
Route::apiResource('photos', 'PhotoController');
```
That's will remove <code>create</code> and <code>edit</code> cases.

**Naming Resource Routes**
```php
Route::resource('photos', 'PhotoController')->names([
    'create' => 'photos.build'
]);
```
**Naming Resource Route Parameters**
```php
Route::resource('user', 'AdminUserController')->parameters([
    'user' => 'admin_user'
]);
// /user/{admin_user}
```
**Localizing Resource URIs** <br>
From <code>AppServiceProvider</code> use <code>Route::resourceVerbs</code> method.

```php
public function boot()
{
    Route::resourceVerbs([
        'create' => 'crear',
        'edit' => 'editar',
    ]);
}
// /fotos/crear

// /fotos/{foto}/editar
```
**Supplementing Resource Controllers** <br>
If you need to add additional routes to a resource controller beyond the default set of resource routes, you should define those routes <code>before</code> your call to <code>Route::resource</code>.
```php
Route::get('photos/popular', 'PhotoController@method');

Route::resource('photos', 'PhotoController');
```

### Route Caching
You need it only in production.
```bash
php artisan route:cache

php artisan route:clear
```
