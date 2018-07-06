# URL Generation

* [The Basics](#the-basics)
    * Generating Basic URLs
    * Accessing The Current URL
* [URLs For Named Routes](#urls-for-named-routes)
    * **Signed URLs**
    * **Validating Signed Route Requests**
* [URLs For Controller Actions](#urls-for-controller-actions)
* [**Default Values**](#default-values)


### The Basics
**Generating Basic URLs**
```php
$post = App\Post::find(1);
echo url("/posts/{$post->id}");
// http://example.com/posts/1
```
**Accessing The Current URL**
```php
// Get the current URL without the query string...
echo url()->current();

// Get the current URL including the query string...
echo url()->full();

// Get the full URL for the previous request...
echo url()->previous();

// or use URL facades
use Illuminate\Support\Facades\URL;
echo URL::current();
```

### URLs For Named Routes
```php
Route::get('/post/{post}', function () {
    //
})->name('post.show');

// then
echo route('post.show', ['post' => 1]);
echo route('post.show', ['post' => $post]);
// http://example.com/post/1
```
**Signed URLs**
```php
use Illuminate\Support\Facades\URL;
return URL::signedRoute('unsubscribe', ['user' => 1]);
// https://example.com/unsubscribe/1?signature=30a3877b00890fff0d7ca25f82c6387ff16a98d21008ddc9689ed3c20ef13cd4

// for temporary signed
return URL::temporarySignedRoute(
    'unsubscribe', now()->addMinutes(30), ['user' => 1]
);
```
**Validating Signed Route Requests**
```php
Route::get('/unsubscribe/{user}', function (Request $request) {
    if (! $request->hasValidSignature()) {
        abort(401);
    }

    // ...
})->name('unsubscribe');
```
Alternatively, you may assign the <code>Illuminate\Routing\Middleware\ValidateSignature</code> middleware to the route.
```php
protected $routeMiddleware = [
    'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
];

Route::post('/unsubscribe/{user}', function (Request $request) {
    // ...
})->name('unsubscribe')->middleware('signed');
```

### URLs For Controller Actions
```php
$url = action('HomeController@index');

// or
use App\Http\Controllers\HomeController;
$url = action([HomeController::class, 'index']);

// with param
$url = action('UserController@profile', ['id' => 1]);
```

### Default Values
```php
Route::get('/{locale}/posts', function () {
    //
})->name('post.index');

// /en/posts
```
then you can add <code>Middleware</code> to add the default value automatically from user settings.
```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;

class SetDefaultLocaleForUrls
{
    public function handle($request, Closure $next)
    {
        URL::defaults(['locale' => $request->user()->locale]);

        return $next($request);
    }
}
```
