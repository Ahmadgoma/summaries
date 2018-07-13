# Encryption

* [Encrypting A Value](#encrypting-a-value)
* [Decrypting A Value](#decrypting-a-value)
* [Encrypting Without Serialization](#encrypting-without-serialization)

### Encrypting A Value
```php
public function storeSecret(Request $request, $id)
{
    $user = User::findOrFail($id);

    $user->fill([
        'secret' => encrypt($request->secret)
    ])->save();
}
```

### Decrypting A Value
```php
use Illuminate\Contracts\Encryption\DecryptException;

try {
    $decrypted = decrypt($encryptedValue);
} catch (DecryptException $e) {
    //
}
```

### Encrypting Without Serialization
```php
use Illuminate\Support\Facades\Crypt;

$encrypted = Crypt::encryptString('Hello world.');

$decrypted = Crypt::decryptString($encrypted);
```
