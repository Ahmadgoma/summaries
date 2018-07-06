# Session

* [Configuration](#configuration)
* [Retrieving Data](#retrieving-data)
    * The Global Session Helper
    * Retrieving All Session Data
    * Determining If An Item Exists In The Session
* [Storing Data](#storing-data)
    * Pushing To Array Session Values
    * Retrieving & Deleting An Item
* [Flash Data](#flash-data)
* [Deleting Data](#deleting-data)
* [Regenerating The Session ID](#regenerating-the-session-id)
* Adding Custom Session Drivers => read the docs.

### Configuration
The session <code>driver</code> configuration option defines where session data will be stored for each request. Laravel ships with several great drivers out of the box:

* (default) <code>file</code>    - sessions are stored in storage/framework/sessions.
* <code>cookie</code>            - sessions are stored in secure, encrypted cookies.
* <code>database</code>          - sessions are stored in a relational database.
* <code>memcached / redis</code> - sessions are stored in one of these fast, cache based stores.
* (testing) <code>array</code>   - sessions are stored in a PHP array and will not be persisted.

**please read more about that in docs.**

### Retrieving Data
```php
public function show(Request $request, $id)
{
    $value = $request->session()->get('key');
}
```
With a default.
```php
$value = $request->session()->get('key', 'default');

$value = $request->session()->get('key', function () {
    return 'default';
});
```
**The Global Session Helper**
```php
Route::get('home', function () {
    // Retrieve a piece of data from the session...
    $value = session('key');

    // Specifying a default value...
    $value = session('key', 'default');

    // Store a piece of data in the session...
    session(['key' => 'value']);
});
```
**Retrieving All Session Data**
```php
$data = $request->session()->all();
```
**Determining If An Item Exists In The Session**
```php
if ($request->session()->has('users')) {
    //
}

if ($request->session()->exists('users')) {
    //
}
```

### Storing Data
```php
// Via a request instance...
$request->session()->put('key', 'value');

// Via the global helper...
session(['key' => 'value']);
```
**Pushing To Array Session Values**
```php
$request->session()->push('user.teams', 'developers');
```
**Retrieving & Deleting An Item**
```php
$value = $request->session()->pull('key', 'default');
```

### Flash Data
```php
$request->session()->flash('status', 'Task was successful!');
```
If you need to keep your flash data around for several requests, you may use the <code>reflash</code> method, or If you only need to keep specific flash data, you may use the <code>keep</code> method.
```php
$request->session()->reflash();

$request->session()->keep(['username', 'email']);
```

### Deleting Data
```php
$request->session()->forget('key');

// for all data
$request->session()->flush();
```

### Regenerating The Session ID
Laravel automatically regenerates the session ID during authentication if you are using the built-in <code>LoginController</code>, however, if you need to manually regenerate use
```php
$request->session()->regenerate();
```
