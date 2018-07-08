# Validation Rules

* [Request object validation](#request-object-validation)
    * Stopping On First Validation Failure
    * A Note On Nested Attributes
    * A Note On Optional Fields
    * Displaying The Validation Errors
* [Form Request Validation](#form-request-validation)
    * Creating Form Requests
    * Adding After Hooks To Form Requests
    * **Authorizing Form Requests**
    * **Customizing The Error Messages**
* [Manually Creating Validators](#manually-creating-validators)
    * **Automatic Redirection**
    * Named Error Bags
    * After Validation Hook
* [Working With Error Messages](#working-with-error-messages)
    * Retrieving The First Error Message For A Field
    * Retrieving All Error Messages For A Field
    * Retrieving All Error Messages For All Fields
    * Determining If Messages Exist For A Field
    * Custom Error Messages
    * Specifying A Custom Message For A Given Attribute
    * Specifying Custom Messages In Language Files
    * Specifying Custom Attributes In Language Files
* Available Validation Rules [**Please read the docs**]
* [Conditionally Adding Rules](#conditionally-adding-rules)
    * Validating When Present
    * **Complex Conditional Validation**
* [**Validating Arrays**](#validating-arrays)
* [Custom Validation Rules](#custom-validation-rules)
    * **Using Rule Objects**
    * Using Closures
    * Using Extensions
        * Defining The Error Message
    * Implicit Extensions

### Request object validation
The simplest way to do that is use <code>Request</code> object inside your controller method.
```php
public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ]);

    // The blog post is valid...
}
```
**Stopping On First Validation Failure** <br>
by using <code>bail</code> rule.
```php
public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'bail|required|unique:posts|max:255',
        'body' => 'required',
    ]);

    // The blog post is valid...
}
```
**A Note On Nested Attributes**
```php
$request->validate([
    'title' => 'required|unique:posts|max:255',
    'author.name' => 'required',
    'author.description' => 'required',
]);
```
**A Note On Optional Fields** <br>
by using <code>nullable</code> rule.
```php
$request->validate([
    'title' => 'required|unique:posts|max:255',
    'body' => 'required',
    'publish_at' => 'nullable|date',
]);
```
**Displaying The Validation Errors**
```php
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

### Form Request Validation
**Creating Form Requests**
```bash
php artisan make:request StoreBlogPost
```
In side your <code>rules</code> method put your validation.
```php
public function rules()
{
    return [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ];
}
```
Then in your <code>controller method</code> retrieve your <code>Form request</code> class.
```php
public function store(StoreBlogPost $request)
{
    // The incoming request is valid...

    // Retrieve the validated input data...
    $validated = $request->validated();
}
```
**Adding After Hooks To Form Requests**
```php
public function withValidator($validator)
{
    $validator->after(function ($validator) {
        if ($this->somethingElseIsInvalid()) {
            $validator->errors()->add('field', 'Something is wrong with this field!');
        }
    });
}
```
**Authorizing Form Requests** <br>
In <code>authorize</code> method you may determine if a user actually owns a blog comment they are attempting to update.
```php
public function authorize()
{
    $comment = Comment::find($this->route('comment')); // route('comment') will return the comment id

    return $comment && $this->user()->can('update', $comment); // user() to access the currently authenticated user
}
```
If you plan to have authorization logic in another part of your application, return true from the <code>authorize</code> method.

**Customizing The Error Messages**
```php
public function messages()
{
    return [
        'title.required' => 'A title is required',
        'body.required'  => 'A message is required',
    ];
}
```

### Manually Creating Validators
From your controller method.
```php
use Validator;

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ]);

    if ($validator->fails()) {
        return redirect('post/create')
                    ->withErrors($validator) // pass MessageBag object or array
                    ->withInput();
    }

    // Store the blog post...
}
```
**Automatic Redirection** <br>
You can replace all <code>if fails</code> block with <code>validate</code> method.
```php
Validator::make($request->all(), [
    'title' => 'required|unique:posts|max:255',
    'body' => 'required',
])->validate();
```
**Named Error Bags** <br>
If you have multiple forms on a single page, you may wish to retrieve the error messages for a specific form.
```php
return redirect('register')
            ->withErrors($validator, 'login');

// in blade
{{ $errors->login->first('email') }}
```
**After Validation Hook**
```php
$validator = Validator::make(...);

$validator->after(function ($validator) {
    if ($this->somethingElseIsInvalid()) {
        $validator->errors()->add('field', 'Something is wrong with this field!');
    }
});

if ($validator->fails()) {
    //
}
```

### Working With Error Messages
**Retrieving The First Error Message For A Field**
```php
$errors = $validator->errors();

echo $errors->first('email');
```
**Retrieving All Error Messages For A Field**
```php
foreach ($errors->get('email') as $message) {
    //
}

// or use RE
foreach ($errors->get('attachments.*') as $message) {
    //
}
```
**Retrieving All Error Messages For All Fields**
```php
foreach ($errors->all() as $message) {
    //
}
```
**Determining If Messages Exist For A Field**
```php
if ($errors->has('email')) {
    //
}
```
**Custom Error Messages**
```php
$messages = [
    'same'    => 'The :attribute and :other must match.',
    'size'    => 'The :attribute must be exactly :size.',
    'between' => 'The :attribute value :input is not between :min - :max.',
    'in'      => 'The :attribute must be one of the following types: :values',
];

$validator = Validator::make($input, $rules, $messages);
```
**Specifying A Custom Message For A Given Attribute**
```php
$messages = [
    'email.required' => 'We need to know your e-mail address!',
];
```
**Specifying Custom Messages In Language Files** <br>
From <code>resources/lang/xx/validation.php</code>
```php
'custom' => [
    'email' => [
        'required' => 'We need to know your e-mail address!',
    ],
]
```
**Specifying Custom Attributes In Language Files** <br>
From <code>resources/lang/xx/validation.php</code>
```php
'attributes' => [
    'email' => 'email address',
],
```

### Conditionally Adding Rules
**Validating When Present**
```php
$v = Validator::make($data, [
    'email' => 'sometimes|required|email',
]);
```
**Complex Conditional Validation**
```php
$v = Validator::make($data, [
    'email' => 'required|email',
    'games' => 'required|numeric',
]);

// when a game input >= 100, active the rules on reason input
$v->sometimes('reason', 'required|max:500', function ($input) {
    return $input->games >= 100;
});

// or
$v->sometimes(['reason', 'cost'], 'required', function ($input) {
    return $input->games >= 100;
});
```

### Validating Arrays
```php
// check item in array
$validator = Validator::make($request->all(), [
    'photos.profile' => 'required|image',
]);

// check every item in array
$validator = Validator::make($request->all(), [
    'person.*.email' => 'email|unique:users',
    'person.*.first_name' => 'required_with:person.*.last_name',
]);

// if you wanna change message for every item
'custom' => [
    'person.*.email' => [
        'unique' => 'Each person must have a unique e-mail address',
    ]
],
```

### Custom Validation Rules
**Using Rule Objects**
```bash
php artisan make:rule Uppercase
```

```php
public function passes($attribute, $value)
{
    return strtoupper($value) === $value;
}

public function message()
{
    return 'The :attribute must be uppercase.';

    // or
    return trans('validation.uppercase');
}
```
then use it like
```php
use App\Rules\Uppercase;

$request->validate([
    'name' => ['required', 'string', new Uppercase],
]);
```
**Using Closures**
```php
$validator = Validator::make($request->all(), [
    'title' => [
        'required',
        'max:255',
        function($attribute, $value, $fail) {
            if ($value === 'foo') {
                return $fail($attribute.' is invalid.');
            }
        },
    ],
]);
```
**Using Extensions** <br>
Another method of registering custom validation rules is using the extend method on the <code>Validator facade</code>.
```php
public function boot()
{
    Validator::extend('foo', function ($attribute, $value, $parameters, $validator) {
        return $value == 'foo';
    });

    // or
    Validator::extend('foo', 'FooValidator@validate');
}
```
**Defining The Error Message** <br>
You can do so either using an <coded>inline custom message</coded> array or by adding an entry in the <code>validation language file</code>.
```php
"foo" => "Your input was invalid!",
"accepted" => "The :attribute must be accepted.",
// The rest of the validation error messages...
```
Or from <code>service provider</code>
```php
public function boot()
{
    Validator::extend(...);

    Validator::replacer('foo', function ($message, $attribute, $rule, $parameters) {
        return str_replace(...);
    });
}
```
**Implicit Extensions:** <br>
when an attribute is not present or contains an empty value, normal validation rules, including custom extensions, are not run.
```php
$rules = ['name' => 'unique'];

$input = ['name' => null];

Validator::make($input, $rules)->passes(); // true
```
For a rule to run even when an attribute is empty, the rule must imply that the attribute is required.
```php
Validator::extendImplicit('foo', function ($attribute, $value, $parameters, $validator) {
    return $value == 'foo';
}, 'Error message!');
```
**An "implicit" extension only implies that the attribute is required.**
