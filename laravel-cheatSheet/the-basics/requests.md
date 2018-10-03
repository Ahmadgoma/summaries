# Requests

* [Accessing The Request](#accessing-the-request)
* [Request Path and Method](#request-path-and-method)
    * Retrieving The Request Path
    * Retrieving The Request URL
    * Retrieving The Request Method
* [Retrieving Input](#retrieving-input)
    * Retrieving All Input Data
    * Retrieving An Input Value
    * **Retrieving Input From The Query String**
    * Retrieving A Portion Of The Input Data
    * Determining If An Input Value Is Present
    * Determining If An Input Value Is Present
* [Old Input](#old-input)
    * **Flashing Input To The Session**
    * Flashing Input Then Redirecting
    * Retrieving Old Input
* [Cookies](#cookies)
    * **Retrieving Cookies From Requests**
    * Attaching Cookies To Responses
    * Generating Cookie Instances
* [Files](#files)
    * Retrieving Uploaded Files
    * Validating Successful Uploads
    * File Paths & Extensions
    * Storing Uploaded Files
* [Configuring Trusted Proxies](#configuring-trusted-proxies)

### Accessing The Request
```php
// Controller
public function store(Request $request)
{
    $name = $request->input('name');

    //
}
```
**Dependency Injection & Route Parameters**
```php
// Route
Route::put('user/{id}', 'UserController@update');

// Controller
public function store(Request $request, $id)
{
    $name = $request->input('name');

    //
}
```

### Request Path & Method
**Retrieving The Request Path**
```php
$uri = $request->path();

// check
if ($request->is('admin/*')) {
    //
}
```
**Retrieving The Request URL**
```php
// Without Query String...
$url = $request->url();

// With Query String...
$url = $request->fullUrl();
```
**Retrieving The Request Method**
```php
$method = $request->method();

// check
if ($request->isMethod('post')) {
    //
}
```

### Retrieving Input
**Retrieving All Input Data**
```php
$input = $request->all();
```
**Retrieving An Input Value**
```php
$name = $request->input('name');
// It's = to
$name = $request->name;

$name = $request->input('name', 'Sally'); // with default
$name = $request->input('products.0.name'); // array
$names = $request->input('products.*.name'); // array range
$name = $request->input('user.name'); // json
```
**Retrieving Input From The Query String**
```php
$name = $request->query('name');
$name = $request->query('name', 'Helen'); // with default
$query = $request->query(); // all
```
**Retrieving A Portion Of The Input Data**
```php
$input = $request->only(['username', 'password']);
$input = $request->only('username', 'password');

$input = $request->except(['credit_card']);
$input = $request->except('credit_card');
```
**Determining If An Input Value Is Present**
```php
if ($request->has('name')) {
    //
}
if ($request->has(['name', 'email'])) {
    //
}

if ($request->filled('name')) {
    //
}
```

### Old Input
Laravel allows you to keep input from one request during the next request. This feature is particularly useful for re-populating forms after detecting validation errors.

**Flashing Input To The Session**
```php
$request->flash();

$request->flashOnly(['username', 'email']);
$request->flashExcept('password');
```
**Flashing Input Then Redirecting**
```php
return redirect('form')->withInput();

return redirect('form')->withInput(
    $request->except('password')
);
```
**Retrieving Old Input**
```php
$username = $request->old('username');
```
```html
<input type="text" name="username" value="{{ old('username') }}">
```

### Cookies
**Retrieving Cookies From Requests**
```php
$value = $request->cookie('name');
// or
$value = Cookie::get('name');
```
**Attaching Cookies To Responses**
```php
return response('Hello World')->cookie(
    'name', 'value', $minutes
);
// full parameters
return response('Hello World')->cookie(
    'name', 'value', $minutes, $path, $domain, $secure, $httpOnly
);
// or
Cookie::queue(Cookie::make('name', 'value', $minutes));
// or
Cookie::queue('name', 'value', $minutes);
```
**Generating Cookie Instances**
```php
$cookie = cookie('name', 'value', $minutes);

return response('Hello World')->cookie($cookie);
```

### Files
**Retrieving Uploaded Files**
```php
$file = $request->file('photo');
// or
$file = $request->photo;
// Check
if ($request->hasFile('photo')) {
    //
}
```
**Validating Successful Uploads**
```php
if ($request->file('photo')->isValid()) {
    //
}
```
**File Paths & Extensions**
```php
$path = $request->photo->path();

$extension = $request->photo->extension();
```
**Storing Uploaded Files**
```php
// unique ID will automatically be generated
$path = $request->photo->store('images');
$path = $request->photo->store('images', 's3'); // s3 the desk number
// manually add file ID
$path = $request->photo->storeAs('images', 'filename.jpg');
$path = $request->photo->storeAs('images', 'filename.jpg', 's3');
```

### Configuring Trusted Proxies
From <code>App\Http\Middleware\TrustProxies</code>
```php
protected $proxies = [
    '192.168.1.1',
    '192.168.1.2',
];

protected $headers = Request::HEADER_X_FORWARDED_ALL;
```
**Trusting All Proxies**
```php
protected $proxies = '*';
```
