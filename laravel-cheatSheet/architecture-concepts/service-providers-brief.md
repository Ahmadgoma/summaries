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
* From `Register` method on provider classes you can bind any object you want.
* From `Boot` method on provider classes you can bootstrap any thing you want (`route`/`view`/`event`).
