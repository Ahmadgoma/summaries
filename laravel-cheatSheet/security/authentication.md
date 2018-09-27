# Authentication

* [Authentication Quickstart](#authentication-quickstart)
    * **Path Customization**
    * **Username Customization**
    * Guard Customization
    * Validation / Storage Customization
    * **Retrieving The Authenticated User**
    * **Determining If The Current User Is Authenticated**
    * Protecting Routes
    * Redirecting Unauthenticated Users
    * Specifying A Guard
* [Manually Authenticating Users](#manually-authenticating-users)
    * **Specifying Additional Conditions**
    * Accessing Specific Guard Instances
    * Logging Out
    * **Remembering Users**
    * Authenticate A User Instance
    * Authenticate A User By ID
    * **Authenticate A User Once**
* [HTTP Basic Authentication](#http-basic-authentication)
    * Stateless HTTP Basic Authentication
* [Logging Out](#logging-out)
    * **Invalidating Sessions On Other Devices**
* [**Social Authentication**](https://github.com/laravel/socialite)
* Adding Custom Guards [Please read it in docs]
* Adding Custom User Providers [Please read it in docs]
* Events [Please read it in docs]

### Authentication Quickstart
All you need to create quick start authentication is:
```bash
php artisan make:auth
```
This command will create the route you need to active authentication controllers and Middlewares.

You will find all files in following directories:
<pre>
- App\Http\Controllers
    - Auth/
    - HomeController
- resources/views
    - auth/
    - layouts/
</pre>

**Path Customization**
```php
// Controller/LoginController
// Controller/RegisterController
// Controller/ResetPasswordController
// Middleware/RedirectIfAuthenticated

protected $redirectTo = '/';
```
Or you can define <code>redirectTo</code> method and It'll precedence over the <code>redirectTo</code> attribute.
```php
protected function redirectTo()
{
    return '/path';
}
```

**Username Customization**
```php
public function username()
{
    // instead email
    return 'username';
}
```

**Guard Customization**
```php
use Illuminate\Support\Facades\Auth;

protected function guard()
{
    return Auth::guard('guard-name');
}
```

**Validation / Storage Customization** <br>
Use <code>RegisterController</code> for edit <code>validator</code> and <code>create</code> methods if you add new fields to registration or just for edit.

**Retrieving The Authenticated User**
```php
use Illuminate\Support\Facades\Auth;

// Get the currently authenticated user...
$user = Auth::user();

// Get the currently authenticated user's ID...
$id = Auth::id();

// From controller
public function update(Request $request)
{
    // $request->user() returns an instance of the authenticated user...
}
```

**Determining If The Current User Is Authenticated**
```php
use Illuminate\Support\Facades\Auth;

if (Auth::check()) {
    // The user is logged in...
}
```

**Protecting Routes**
```php
Route::get('profile', function () {
    // Only authenticated users may enter...
})->middleware('auth');

// Or from controller
public function __construct()
{
    $this->middleware('auth');
}
```

**Redirecting Unauthenticated Users** <br>
If you wanna edit redirection behavior when failed.
```php
// app/Exceptions/Handler.php

use Illuminate\Auth\AuthenticationException;

protected function unauthenticated($request, AuthenticationException $exception)
{
    return $request->expectsJson()
                ? response()->json(['message' => $exception->getMessage()], 401)
                : redirect()->guest(route('login'));
}
```

**Specifying A Guard**
```php
public function __construct()
{
    $this->middleware('auth:api');
}
```

### Manually Authenticating Users
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        }
    }
}
```

**Specifying Additional Conditions**
```php
if (Auth::attempt(['email' => $email, 'password' => $password, 'active' => 1])) {
    // The user is active, not suspended, and exists.
}
```

**Accessing Specific Guard Instances**
```php
if (Auth::guard('admin')->attempt($credentials)) {
    //
}
```

**Logging Out**
```php
Auth::logout();
```

**Remembering Users**
```php
if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
    // The user is being remembered...
}
```
If you are "remembering" users, you may use the <code>viaRemember</code> method to determine if the user was authenticated using the <code>"remember me"</code> cookie.
```php
if (Auth::viaRemember()) {
    //
}
```

**Authenticate A User Instance** <br>
The <code>$user</code> variable is an object, and this object must be implement this contract <code>Illuminate\Contracts\Auth\Authenticatable</code>.
```php
Auth::login($user);

// Login and "remember" the given user...
Auth::login($user, true);

// Using guard
Auth::guard('admin')->login($user);
```

**Authenticate A User By ID**
```php
Auth::loginUsingId(1);

// Login and "remember" the given user...
Auth::loginUsingId(1, true);
```

**Authenticate A User Once**
```php
if (Auth::once($credentials)) {
    //
}
```

### HTTP Basic Authentication
```php
Route::get('profile', function () {
    // Only authenticated users may enter...
})->middleware('auth.basic');
```
If you use <code>FastCGI</code>, add following to <code>.htaccess</code>
```php
RewriteCond %{HTTP:Authorization} ^(.+)$
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
```

**Stateless HTTP Basic Authentication**
```php
// App\Http\Middleware
public function handle($request, $next)
{
    return Auth::onceBasic() ?: $next($request);
}

// web.php
Route::get('api/user', function () {
    // Only authenticated users may enter...
})->middleware('auth.basic.once');
```

### Logging Out
```php
use Illuminate\Support\Facades\Auth;

Auth::logout();
```

**Invalidating Sessions On Other Devices**
```php
// app/Http/Kernel.php
'web' => [
    // ...
    \Illuminate\Session\Middleware\AuthenticateSession::class,
    // ...
],

// Then
use Illuminate\Support\Facades\Auth;

Auth::logoutOtherDevices($password);
```
