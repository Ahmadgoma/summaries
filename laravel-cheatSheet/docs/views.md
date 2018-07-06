# Views

* [Creating Views](#creating-views)
    * Determining If A View Exists
    * Creating The First Available View
* [Passing Data To Views](#passing-data-to-views)
    * **Sharing Data With All Views**
* [View Composers](#view-composers)
    * Attaching A Composer To Multiple Views
    * **View Creators**

### Creating Views
```php
Route::get('/', function () {
    return view('greeting', ['name' => 'James']);
});

// From controller
return view('admin.profile', $data);
```
**Determining If A View Exists**
```php
if (View::exists('emails.customer')) {
    //
}
```
**Creating The First Available View**
```php
return view()->first(['custom.admin', 'admin'], $data);

// or
use Illuminate\Support\Facades\View;
return View::first(['custom.admin', 'admin'], $data);
```

### Passing Data To Views
```php
return view('greetings', ['name' => 'Victoria']);

return view('greeting')->with('name', 'Victoria');
```
**Sharing Data With All Views**
From your <code>AppServiceProvider</code>
```php
public function boot()
{
    View::share('key', 'value');
}
```

### View Composers
If you have data that you want to be bound to a view each time that view is rendered, a view composer can help you organize that logic into a single location.

From your service providers class.
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
View::composer(
    ['profile', 'dashboard'],
    'App\Http\ViewComposers\MyViewComposer'
);

View::composer('*', function ($view) {
    //
});
```
**View Creators** <br>
<code>View creators</code> executed immediately after the view is instantiated, It used when you wanna initialize some logic and you may change it later.
```php
View::creator('profile', 'App\Http\ViewCreators\ProfileCreator');
```
