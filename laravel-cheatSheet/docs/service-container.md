# Service Container

* [Simple Bindings](#simple-bindings)
* [Binding A Singleton](#binding-a-singleton)
* [Binding Instances](#binding-instances)
* [Binding Primitives](#binding-primitives)
* [Binding Interfaces To Implementations](#binding-interfaces-to-implementations)
* [Contextual Binding](#contextual-binding)
* [Tagging](#tagging)
* [Extending Bindings](#extending-bindings)
* [Resolving](#resolving)
* [Container Events](#container-events)

The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection.
```php
/**
* Create a new controller instance.
*
* @param  UserRepository  $users
* @return void
*/
public function __construct(UserRepository $users)
{
   $this->users = $users;
}
```

### Simple Bindings (Factory DP)
```php
$this->app->bind('HelpSpot\API', function ($app) {
    return new HelpSpot\API($app->make('HttpClient'));
});
```
[More here - service providers](./service-providers.md)

### Binding A Singleton (Singleton DP)
```php
$this->app->singleton('HelpSpot\API', function ($app) {
    return new HelpSpot\API($app->make('HttpClient'));
});
```

### Binding Instances
You may also bind an existing object instance into the container.
```php
$api = new HelpSpot\API(new HttpClient);

$this->app->instance('HelpSpot\API', $api);
```

### Binding Primitives
Sometimes you may have a class that receives some injected classes, but also needs an injected primitive value such as an integer.
```php
$this->app->when('App\Http\Controllers\UserController')
          ->needs('$variableName')
          ->give($value);
```

### Binding Interfaces To Implementations
```php
$this->app->bind(
    'App\Contracts\EventPusher',
    'App\Services\RedisEventPusher'
);
```
This statement tells the container that it should inject the <code>RedisEventPusher</code> when a class needs an implementation of <code>EventPusher</code>.

### Contextual Binding
Sometimes you may have two classes that utilize the same interface, but you wish to inject different implementations into each class.
```php
$this->app->when(PhotoController::class)
          ->needs(Filesystem::class)
          ->give(function () {
              return Storage::disk('local');
          });

$this->app->when(VideoController::class)
          ->needs(Filesystem::class)
          ->give(function () {
              return Storage::disk('s3');
          });
```

### Tagging
Occasionally, you may need to resolve all of a certain "category" of binding.
```php
$this->app->bind('SpeedReport', function () {
    //
});

$this->app->bind('MemoryReport', function () {
    //
});

$this->app->tag(['SpeedReport', 'MemoryReport'], 'reports');

// then
$this->app->bind('ReportAggregator', function ($app) {
    return new ReportAggregator($app->tagged('reports'));
})
```

### Extending Bindings
The <code>extend</code> method allows the modification of resolved services.
```php
$this->app->extend(Service::class, function($service) {
    return new DecoratedService($service);
});
```

### Resolving
```php
// inside the provider
$api = $this->app->make('HelpSpot\API');
$api = $this->app->makeWith('HelpSpot\API', ['id' => 1]);
// any other places
$api = resolve('HelpSpot\API');
```

### Container Events
The service container fires an event each time it resolves an object.
```php
$this->app->resolving(function ($object, $app) {
    // Called when container resolves object of any type...
});

$this->app->resolving(HelpSpot\API::class, function ($api, $app) {
    // Called when container resolves objects of type "HelpSpot\API"...
});
```
