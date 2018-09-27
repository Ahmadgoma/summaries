# Localization

* [Introduction](#introduction)
    * Configuring The Locale
    * Determining The Current Locale
* [Defining Translation Strings](#defining-translation-strings)
    * Using Short Keys
    * **Using Translation Strings As Keys**
* [Retrieving Translation Strings](#retrieving-translation-strings)
    * Replacing Parameters In Translation Strings
    * **Pluralization**

### Introduction
Language strings are stored in files within the <code>resources/lang</code> directory.

<pre>
/resources
    /lang
        /en
            messages.php
        /es
            messages.php
</pre>

All language files return an array of keyed strings.
```php
return [
    'welcome' => 'Welcome to our application'
];
```
**Configuring The Locale**
You can set your local language from <code>config/app.php</code>, or from <code>route</code> file.
```php
Route::get('welcome/{locale}', function ($locale) {
    App::setLocale($locale);

    //
});
```
You may also configure a "fallback language" from <code>config/app.php</code>.

**Determining The Current Locale**
```php
$locale = App::getLocale();

if (App::isLocale('en')) {
    //
}
```

### Defining Translation Strings
**Using Short Keys**
```php
return [
    'welcome' => 'Welcome to our application'
];
```
**Using Translation Strings As Keys** <br>
You can save a file on <code>JSON</code> extension on <code>resources/lang/xx.json</code> with proper translation.
```php
{
    "I love programming.": "Me encanta programar."
}
```

### Retrieving Translation Strings
```php
echo __('messages.welcome');

echo __('I love programming.');
```
In blade
```html
{{ __('messages.welcome') }}

@lang('messages.welcome')
```
**Replacing Parameters In Translation Strings**
```php
echo __('messages.welcome', ['name' => 'dayle']);

'welcome' => 'Welcome, :name', // Welcome, dayle
'welcome' => 'Welcome, :NAME', // Welcome, DAYLE
'goodbye' => 'Goodbye, :Name', // Goodbye, Dayle
```
**Pluralization** <br>
By using a "pipe" character, you may distinguish singular and plural forms of a string
```php
'apples' => 'There is one apple|There are many apples',
```
You may even create more complex pluralization
```php
'apples' => '{0} There are none|[1,19] There are some|[20,*] There are many',

echo trans_choice('messages.apples', 10);
```
You may also define place-holder
```php
'minutes_ago' => '{1} :value minute ago|[2,*] :value minutes ago',

echo trans_choice('time.minutes_ago', 5, ['value' => 5]);
```
