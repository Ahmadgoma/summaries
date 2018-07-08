# Error Handling

* [Configuration](#configuration)
* [The Exception Handler](#the-exception-handler)
    * The Report Method
    * The Render Method
    * Ignoring Exceptions By Type
    * **Reportable & Renderable Exceptions class**
* [HTTP Exceptions](#http-exceptions)
    * Custom HTTP Error Pages

### Configuration
The debug option in your <code>config/app.php</code> configuration file determines how much information about an error is actually displayed to the user. Or from <code>APP_DEBUG</code> environment variable, which is stored in your <code>.env</code> file.

### The Exception Handler
**The Report Method** <br>
From a <code>App\Exceptions\Handler</code> class,The report method is used to log exceptions or send them to an external service like <code>Bugsnag</code> or <code>Sentry</code>.
```php
public function report(Exception $exception)
{
    if ($exception instanceof CustomException) {
        //
    }

    return parent::report($exception);
}
```
The <code>report</code> Helper
```php
public function isValid($value)
{
    try {
        // Validate the value...
    } catch (Exception $e) {
        report($e);

        return false;
    }
}
```
**The Render Method** <br>
The <code>render</code> method is responsible for converting a given exception into an HTTP response that should be sent back to the browser.
```php
public function render($request, Exception $exception)
{
    if ($exception instanceof CustomException) {
        return response()->view('errors.custom', [], 500);
    }

    return parent::render($request, $exception);
}
```
**Ignoring Exceptions By Type** <br>
The <code>$dontReport</code> property of the exception handler contains an array of exception types that will not be logged.

**Reportable & Renderable Exceptions**
Instead of type-checking exceptions in the exception handler's <code>report</code> and <code>render</code> methods, you may define <code>report</code> and <code>render</code> methods directly on your custom exception.
```php
namespace App\Exceptions;
use Exception;

class RenderException extends Exception
{
    public function report()
    {
        //
    }

    public function render($request)
    {
        return response(...);
    }
}
```

### HTTP Exceptions
```php
abort(404);

abort(403, 'Unauthorized action.');
```
**Custom HTTP Error Pages**
From <code>resources/views/errors/</code> you can declare your error exceptions.
```php
// 404.blade.php

<h2>{{ $exception->getMessage() }}</h2>
```
