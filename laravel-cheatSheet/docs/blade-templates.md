# Blade Templates

* [Template Inheritance](#template-inheritance)
    * Defining A Layout
    * Extending A Layout
* [Components and Slots](#components-and-slots)
    * Passing Additional Data To Components
    * Aliasing Components
* [Displaying Data](#displaying-data)
    * Displaying Unescaped Data
    * **Rendering JSON**
    * HTML Entity Encoding
    * Blade & JavaScript Frameworks
* [Control Structures](#control-structures)
    * If Statements
        * Check if isset / empty
        * **Authentication Directives**
        * **Section Directives**
    * Switch Statements
    * Loops
        * foreach in details
    * **The Loop Variable**
    * Comments
    * PHP
* [Including Sub-Views](#including-sub-views)
    * **include If view exists**
    * include When condition is true
    * include First view exists
    * Rendering Views For Collections
* [Stacks](#stacks)
* [Service Injection](#service-injection)
* [Extending Blade](#extending-blade)
    * **Custom If Statements**


### Template Inheritance
**Defining A Layout**
```html
<!-- Stored in resources/views/layouts/app.blade.php -->

<html>
    <head>
        <title>App Name - @yield('title')</title>
    </head>
    <body>
        @section('sidebar')
            This is the master sidebar.
        @show <!-- End the section and also use yield -->

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
```
**Extending A Layout**
```html
<!-- Stored in resources/views/child.blade.php -->

@extends('layouts.app')

@section('title', 'Page Title')

@section('sidebar')
    @parent <!-- To append parent content -->

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <p>This is my body content.</p>
@endsection
```

### Components and Slots
```html
<!-- /resources/views/alert.blade.php -->

<div class="alert alert-danger">
    <div class="alert-title">{{ $title }}</div>

    {{ $slot }}
</div>
```
```html
<!-- /resources/views/index.blade.php -->

@component('alert')
    @slot('title')
        Forbidden
    @endslot

    You are not allowed to access this resource!
@endcomponent
```
**Passing Additional Data To Components**
```html
@component('alert', ['foo' => 'bar'])
    ...
@endcomponent
```
**Aliasing Components** <br>
From your <code>AppServiceProvider</code>
```php
use Illuminate\Support\Facades\Blade;

Blade::component('components.alert', 'alert');
```
Then
```html
@alert
    You are not allowed to access this resource!
@endalert

<!-- Or -->
@alert(['type' => 'danger'])
    You are not allowed to access this resource!
@endalert
```

### Displaying Data
```php
Route::get('greeting', function () {
    return view('welcome', ['name' => 'Samantha']);
});
```
```html
Hello, {{ $name }}.
```
Or we can use any php functions here.
```html
The current UNIX timestamp is {{ time() }}.
```
**Displaying Unescaped Data**
```html
Hello, {!! $name !!}.
```
**Rendering JSON**
```html
<script>
    var app = @json($array);
</script>
```
**HTML Entity Encoding** <br>
By default, Blade (and the Laravel <code>e</code> helper) will double encode HTML entities. If you would like to disable double encoding.
```php
public function boot()
{
    Blade::withoutDoubleEncoding();
}
```
**Blade & JavaScript Frameworks** <br>
To let blade template know that <code>{{  }}</code> is for js Frameworks.
```html
<h1>Laravel</h1>

Hello, @{{ name }}.
```
If you are displaying JavaScript variables in a large portion of your template.
```html
@verbatim
    <div class="container">
        Hello, {{ name }}.
    </div>
@endverbatim
```

### Control Structures
**If Statements**
```html
@if (count($records) === 1)
    I have one record!
@elseif (count($records) > 1)
    I have multiple records!
@else
    I don't have any records!
@endif

@unless (Auth::check())
    You are not signed in.
@endunless
```
**Check if isset / empty**
```html
@isset($records)
    // $records is defined and is not null...
@endisset

@empty($records)
    // $records is "empty"...
@endempty
```
**Authentication Directives**
```html
@auth
    // The user is authenticated...
@endauth

@guest
    // The user is not authenticated...
@endguest

<!-- or when use auth guard-->
@auth('admin')
    // The user is authenticated...
@endauth

@guest('admin')
    // The user is not authenticated...
@endguest
```
**Section Directives** <br>
check if a section has content.
```html
@hasSection('navigation')
    <div class="pull-right">
        @yield('navigation')
    </div>

    <div class="clearfix"></div>
@endif
```

**Switch Statements**
```html
@switch($i)
    @case(1)
        First case...
        @break

    @case(2)
        Second case...
        @break

    @default
        Default case...
@endswitch
```

**Loops**
```html
@for ($i = 0; $i < 10; $i++)
    The current value is {{ $i }}
@endfor

@foreach ($users as $user)
    <p>This is user {{ $user->id }}</p>
@endforeach

@forelse ($users as $user)
    <li>{{ $user->name }}</li>
@empty
    <p>No users</p>
@endforelse

@while (true)
    <p>I'm looping forever.</p>
@endwhile
```
**foreach in details** <br>
continue / break
```html
@foreach ($users as $user)
    @if ($user->type == 1)
        @continue
    @endif
    <!-- or -->
    @continue($user->type == 1)

    <li>{{ $user->name }}</li>

    @if ($user->number == 5)
        @break
    @endif
    <!-- or -->
    @break($user->number == 5)
@endforeach
```

**The Loop Variable**
```html
@foreach ($users as $user)
    @if ($loop->first)
        This is the first iteration.
    @endif

    @if ($loop->last)
        This is the last iteration.
    @endif

    <p>This is user {{ $user->id }}</p>
@endforeach
```
To access parent loop's variable.
```html
@foreach ($users as $user)
    @foreach ($user->posts as $post)
        @if ($loop->parent->first)
            This is first iteration of the parent loop.
        @endif
    @endforeach
@endforeach
```

Property | Description
--- | ---
<code>$loop->index</code> | The index of the current loop iteration (starts at 0).
<code>$loop->iteration</code> | The current loop iteration (starts at 1).
<code>$loop->remaining</code> | The iterations remaining in the loop.
<code>$loop->count</code> | The total number of items in the array being iterated.
<code>$loop->first</code> | Whether this is the first iteration through the loop.
<code>$loop->last</code> | Whether this is the last iteration through the loop.
<code>$loop->depth</code> | The nesting level of the current loop.
<code>$loop->parent</code> | When in a nested loop, the parent's loop variable.

**Comments**
```html
{{-- This comment will not be present in the rendered HTML --}}
```

**PHP**
```html
@php
    //
@endphp
```

### Including Sub-Views
```html
<div>
    @include('shared.errors')
    <!-- or -->
    @include('view.name', ['some' => 'data'])

    <form>
        <!-- Form Contents -->
    </form>
</div>
```
**include If view exists**
```html
@includeIf('view.name', ['some' => 'data'])
```
**include When condition is true**
```html
@includeWhen($boolean, 'view.name', ['some' => 'data'])
```
**include First view exists**
```html
@includeFirst(['custom.admin', 'admin'], ['some' => 'data'])
```

**Rendering Views For Collections** <br>
You may combine <code>loops</code> and <code>includes</code> into one line with Blade's <code>@each</code> directive.
```html
@each('view.name', $jobs, 'job')

<!-- or if var empty-->
@each('view.name', $jobs, 'job', 'view.empty')
```
> Views rendered via <code>@each</code> do not inherit the variables from the parent view, otherwise use <code>foreach</code> and <code>include</code>.

### Stacks
Blade allows you to push to named stacks which can be rendered somewhere else in another view or layout.
```html
<head>
    <!-- Head Contents -->

    @stack('scripts')
</head>
```
Then push to it.
```html
@push('scripts')
    <script src="/example.js"></script>
@endpush
```
If you would like to prepend content onto the beginning of a stack.
```html
@push('scripts')
    This will be second...
@endpush

// Later...

@prepend('scripts')
    This will be first...
@endprepend
```

### Service Injection
```html
@inject('metrics', 'App\Services\MetricsService')

<div>
    Monthly Revenue: {{ $metrics->monthlyRevenue() }}.
</div>
```

### Extending Blade
Blade allows you to define your own custom <code>directives</code> using the directive method.
```php
public function boot()
{
    Blade::directive('datetime', function ($expression) {
        return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
    });
}

// @datetime($var)
```
**Custom If Statements**
```php
public function boot()
{
    Blade::if('env', function ($environment) {
        return app()->environment($environment);
    });
}

// @env('local')
    // The application is in the local environment...
// @elseenv('testing')
    // The application is in the testing environment...
// @else
    // The application is not in the local or testing environment...
// @endenv
```
