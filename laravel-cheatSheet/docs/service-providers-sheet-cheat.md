# Service Providers Sheet Cheat

* [Routing](#routing)
* [Controllers](#controllers)
* [Response](#response)
* [Views](#views)
* [Validation Rules](#validation-rules)
* [Blade Templates](#blade-templates)

### Routing
**Global Constraints**
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
**Explicit Binding**
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

### Controllers
**Localizing Resource URIs**
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

### Response
**Localizing Resource URIs**
```php
public function boot()
{
    Response::macro('caps', function ($value) {
        return Response::make(strtoupper($value));
    });
}

// use it like
return response()->caps('foo');
```

### Views
**Sharing Data With All Views**
```php
public function boot()
{
    View::share('key', 'value');
}
```
**View Composers**
```php
public function boot()
{
    // Using class based composers...
    View::composer(
        'profile', 'App\Http\ViewComposers\ProfileComposer'
    );

    // Using Closure based composers...
    View::composer('dashboard', function ($view) {
        //
    });
}

// In case of the class
class ProfileComposer
{
    protected $users;

    public function __construct(UserRepository $users)
    {
        // Dependencies automatically resolved by service container...
        $this->users = $users;
    }

    public function compose(View $view)
    {
        $view->with('count', $this->users->count());
    }
}
```
**Attaching A Composer To Multiple Views**
```php
public function boot()
{
    View::composer(
        ['profile', 'dashboard'],
        'App\Http\ViewComposers\MyViewComposer'
    );

    View::composer('*', function ($view) {
        //
    });
}
```
**View Creators**
```php
public function boot()
{
    View::creator('profile', 'App\Http\ViewCreators\ProfileCreator');
}
```

### Validation Rules
**Using Extensions**
```php
public function boot()
{
    Validator::extend('foo', function ($attribute, $value, $parameters, $validator) {
        return $value == 'foo';
    });

    // or
    Validator::extend('foo', 'FooValidator@validate');
}
```
To rest the validation error messages from service provider.
```php
public function boot()
{
    Validator::extend(...);

    Validator::replacer('foo', function ($message, $attribute, $rule, $parameters) {
        return str_replace(...);
    });
}
```

### Blade Templates
**HTML Entity Encoding**
```php
public function boot()
{
    Blade::withoutDoubleEncoding();
}
```
**Extending Blade directives**
```php
public function boot()
{
    Blade::directive('datetime', function ($expression) {
        return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
    });
}

// @datetime($var)
```
**Custom If Statements**
```php
public function boot()
{
    Blade::if('env', function ($environment) {
        return app()->environment($environment);
    });
}

// @env('local')
    // The application is in the local environment...
// @elseenv('testing')
    // The application is in the testing environment...
// @else
    // The application is not in the local or testing environment...
// @endenv
```
