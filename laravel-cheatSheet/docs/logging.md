# Logging

* [Configuration](#configuration)
    * Available Channel Drivers
    * **Building Log Stacks**
    * **Log Levels**
* [Writing Log Messages](#writing-log-messages)
    * **Contextual Information**
    * Writing To Specific Channels
* Advanced Monolog Channel Customization [**Please read the docs**]

### Configuration
All configuration you can find them on <code>config/logging.php</code>

**Available Channel Drivers** <br>
Name | Description
--- | ---
<code>stack</code> | A wrapper to facilitate creating "multi-channel" channels
<code>single</code> | A single file or path based logger channel (StreamHandler)
<code>daily</code> | A RotatingFileHandler based Monolog driver which rotates daily
<code>slack</code> | A SlackWebhookHandler based Monolog driver
<code>syslog</code> | A SyslogHandler based Monolog driver
<code>errorlog</code> | A ErrorLogHandler based Monolog driver
<code>monolog</code> | A Monolog factory driver that may use any supported Monolog handler
<code>custom</code> | A driver that calls a specified factory to create a channel

**Building Log Stacks** <br>
```php
'stack' => [
    'driver' => 'stack',
    'channels' => ['syslog', 'slack'],
],
```

**Log Levels** <br>
emergency, alert, critical, error, warning, notice, info, and debug.

### Writing Log Messages
```php
Log::emergency($message);
Log::alert($message);
Log::critical($message);
Log::error($message);
Log::warning($message);
Log::notice($message);
Log::info($message);
Log::debug($message);
```
**Contextual Information**
```php
Log::info('User failed to login.', ['id' => $user->id]);
```
**Writing To Specific Channels**
```php
Log::channel('slack')->info('Something happened!');

Log::stack(['single', 'slack'])->info('Something happened!');
```
