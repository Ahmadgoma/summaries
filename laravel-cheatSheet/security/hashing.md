# Hashing

* [Hash A Value](#hash-a-value)
    * Adjusting The Bcrypt Work Factor
    * Adjusting The Argon2 Work Factor
    * Verifying A Password Against A Hash
    * Checking If A Password Needs To Be Rehashed

### Hash A Value
```php
public function update(Request $request)
{
    // Validate the new password length...

    $request->user()->fill([
        'password' => Hash::make($request->newPassword)
    ])->save();
}
```

**Adjusting The Bcrypt Work Factor**
```php
$hashed = Hash::make('password', [
    'rounds' => 12
]);
```

**Adjusting The Argon2 Work Factor**
```php
$hashed = Hash::make('password', [
    'memory' => 1024,
    'time' => 2,
    'threads' => 2,
]);
```

**Verifying A Password Against A Hash**
```php
if (Hash::check('plain-text', $hashedPassword)) {
    // The passwords match...
}
```

**Checking If A Password Needs To Be Rehashed**
```php
if (Hash::needsRehash($hashed)) {
    $hashed = Hash::make('plain-text');
}
```
