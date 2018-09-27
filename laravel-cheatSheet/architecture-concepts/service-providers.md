# Service Providers

* [Simple Bindings](#simple-bindings)
* [Boot Method](#boot-method)
* [Registering Providers](#registering-providers)
* [Deferred Providers](#deferred-providers)

Service providers are the central place of all Laravel application bootstrapping.

```bash
php artisan make:provider BlaServiceProvider
```

### Simple Bindings
If your service provider registers many simple bindings, you may wish to use the <code>bindings</code> and <code>singletons</code> properties instead of manually registering each container binding.
```php
/**
 * All of the container bindings that should be registered.
 *
 * @var array
 */
public $bindings = [
    ServerProvider::class => DigitalOceanServerProvider::class,
];

/**
 * All of the container singletons that should be registered.
 *
 * @var array
 */
public $singletons = [
    DowntimeNotifier::class => PingdomDowntimeNotifier::class,
];
```
[More here - service container](./service-container.md/#simple-bindings-factory-dp)

### Boot Method
What if we need to register a view composer within our service provider? This should be done within the `boot` method. <br>
**This method is called after all other service providers have been registered.**
```php
public function boot()
{
    view()->composer('view', function () {
        //
    });
}
```
You may <code>type-hint</code> dependencies for your service provider's <code>boot</code> method.
```php
public function boot(ResponseFactory $response)
{
    $response->macro('caps', function ($value) {
        //
    });
}
```

### Registering Providers
All service providers are registered in the <code>config/app.php</code> configuration file.
```php
'providers' => [
    // Other Service Providers

    App\Providers\ComposerServiceProvider::class,
],
```

### Deferred Providers
If your provider is only registering bindings in the service container, you may choose to defer its registration until one of the registered bindings is actually needed.
```php
protected $defer = true;

public function register()
{
    $this->app->singleton(Connection::class, function ($app) {
        return new Connection($app['config']['riak']);
    });
}

// what will return when call happens
public function provides()
{
    return [Connection::class];
}
```
To defer the loading of a provider, set the `defer` property to `true` and define a `provides` method. The `provides` method should return the service container bindings registered by the provider.
