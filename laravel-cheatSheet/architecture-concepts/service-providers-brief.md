# Architecture Concepts Brief

* [Service Container](#service-container.md)
* [Service Providers](#service-providers.md)

### Service Container
The Laravel `service container` is a powerful tool for managing class dependencies and performing dependency injection.

**The mean ways to bind classes to service container :**
* `Simple Bindings` it behave like `Factory DP`.
* `Singleton Bindings` it behave like `Singleton DP`.
* `Instances Bindings` to bind an existing object instance into the container.
* `Primitives Bindings` to bind class with inject primitive value to it.
* `Interfaces To Implementations Bindings` to bind class with inject interface to it.
* `Contextual Bindings` to bind classes those use same interface but with inject different implementations into each class.

You can use `Tagging` to resolve all of a certain "category" of binding. Also you can `Extending` Bindings to modify resolved services.

**There are three methods to resolve classes:**
* `make()` to normal resolve.
* `makeWith()` to pass dependencies they are not resolvable via the container like primitive values.
* `resolve()` it's a helper function act like `make()`.

You can use `Container Events` to do some stuff each time Laravel resolves an object.
Also you may type-hint the `PSR-11` container interface to obtain an instance of the Laravel container.

**Note:** <br>
There is no need to bind classes into the container if they do not depend on any interfaces. The container does not need to be instructed on how to build these objects, since it can automatically resolve these objects using reflection.

### Service Providers
`Service providers` are the central place of all Laravel application bootstrapping.including registering service container `bindings`, `event listeners`, `middleware`, and even `routes`.
* From `Register` method you should only bind things into the service container. You should never attempt to register any `event listeners`, `routes`, or any other piece of functionality within the `Register` method.
* From `Boot` method on provider classes you can bootstrap any thing you want (`route`/`view`/`event`). This method is called after all other service providers have been registered, meaning you have access to all other services that have been registered by the framework.

You may type-hint dependencies for your service provider's `boot` method. Also your provider is only registering bindings in the service container, you may choose to `defer` its registration until one of the registered bindings is actually needed.
