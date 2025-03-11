# Bladestrap = Blade + Bootstrap

[![MIT Licensed](https://img.shields.io/badge/License-MIT-brightgreen.svg)](LICENSE.md)
![PHP](https://img.shields.io/badge/dynamic/json?url=https%3A%2F%2Fraw.githubusercontent.com%2Fportavice%2Fbladestrap%2Fmain%2Fcomposer.json&query=require.php&label=PHP)
[![Tests](https://github.com/portavice/bladestrap/actions/workflows/tests.yml/badge.svg)](https://github.com/portavice/bladestrap/actions/workflows/tests.yml)
[![Code style check](https://github.com/portavice/bladestrap/actions/workflows/code-style.yml/badge.svg)](https://github.com/portavice/bladestrap/actions/workflows/code-style.yml)
[![Latest version](https://img.shields.io/packagist/v/portavice/bladestrap.svg)](https://packagist.org/packages/portavice/bladestrap)
[![Total downloads](https://img.shields.io/packagist/dt/portavice/bladestrap.svg)](https://packagist.org/packages/portavice/bladestrap)

Bladestrap provides [Laravel Blade components](https://laravel.com/docs/10.x/blade#components)
for the [Bootstrap 5](https://getbootstrap.com/docs/) frontend framework.


## Contents
- [Installation](#installation)
  - [Install Bootstrap](#install-bootstrap)
  - [Configure Bladestrap](#configure-bladestrap)
  - [Customize views](#customize-views)
- [Usage](#usage)
  - [Alerts](#alerts)
  - [Badges](#badges)
  - [Breadcrumb](#breadcrumb)
  - [Buttons](#buttons)
    - [Button groups](#button-groups)
    - [Button toolbars](#button-toolbars)
  - [Dropdowns](#dropdowns)
  - [Forms](#forms)
    - [Types of form fields](#types-of-form-fields)
    - [Options](#options)
    - [Disabled, readonly, required](#disabled-readonly-required)
    - [Input groups](#input-groups)
    - [Hints](#hints)
    - [Prefill values from query parameters](#prefill-values-from-query-parameters)
    - [Error messages](#error-messages)
  - [Links](#links)
  - [List groups](#list-groups)
  - [Modals](#modals)
  - [Navigation](#navigation) 
- [Usage without Laravel](#usage-without-laravel)


## Installation
First, install the package via [Composer](https://getcomposer.org/):
```bash
composer require portavice/bladestrap
```

Within a Laravel application, the package will automatically register itself.

> [!NOTE]
> If you only use parts of the Laravel framework (such as `illuminate/view`),
> make sure to follow the instructions in the section on [usage without Laravel](#usage-without-laravel).

### Install Bootstrap
Note that you need to [include the Bootstrap files](https://github.com/twbs/bootstrap#quick-start) on your own.
1. If you haven't added Bootstrap as one of your dependencies, you can do so via [npm](https://www.npmjs.com/):
    ```bash
    npm install bootstrap
    ```
2. Add the following to your `webpack.mix.js` to copy the required Bootstrap files to your `public` directory:
    ```javascript
    let bootstrapFiles = [
        'node_modules/bootstrap/dist/css/bootstrap.min.css',
        'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
    ];
    mix.copy(bootstrapFiles, 'public/lib');
    ```
3. Include CSS and JavaScript in `resources/views/layouts/app.blade.php`:
    ```HTML
    <link rel="stylesheet" href="{{ mix('lib/bootstrap.min.css') }}">
    <script src="{{ mix('lib/bootstrap.bundle.min.js') }}"></script>
    ```
You may need to adjust the steps above to your custom project configuration.
If you have a [custom Bootstrap build](https://getbootstrap.com/docs/5.3/customize/sass/) you are responsible to include the necessary parts of Bootstrap yourself.


### Configure Bladestrap
Usually this should not be necessary, but if you need to overwrite the default configuration,
create and edit `config/bladestrap.php`:
```bash
php artisan vendor:publish --tag="bladestrap-config"
```

### Customize views
If you want to customize the views, publish them to `resources\views\vendor\bladestrap\components`
and edit them to meet your requirements:
```bash
php artisan vendor:publish --tag="bladestrap-views"
```
You may want to delete the views you haven't changed to benefit from package updates automatically.


## Usage
The components are placed in the `bs` namespace, such that they can be used via:
```HTML
<x-bs::component-name> <!-- Replace component-name with one of the component names described below -->
```
Components can be enhanced with additional classes from Bootstrap or your own CSS.

Specifically handled attributes are documented with type annotations in the `@props`
in the respective Blade template under `resources/views/components`.


### Alerts
[Alerts](https://getbootstrap.com/docs/5.3/components/alerts/) are of variant `alert-info` by default
and can be dismissible (with a close button).
```HTML
<x-bs::alert>My info alert</x-bs::alert>
<x-bs::alert variant="primary">My primary alert</x-bs::alert>
<x-bs::alert variant="secondary" :dismissible="true">My dismissible secondary alert</x-bs::alert>
```

### Badges
[Badges](https://getbootstrap.com/docs/5.3/components/badge/) are of variant `badge-primary` default:
```HTML
<x-bs::badge>My primary badge</x-bs::badge>
<x-bs::badge variant="secondary">My secondary badge</x-bs::badge>
```

### Breadcrumb
The [breadcrumb](https://getbootstrap.com/docs/5.3/components/breadcrumb/) container is a `<x-bs::breadcrumb>` (typically placed within your `layouts/app.blade.php`):
```HTML
@hasSection('breadcrumbs')
    <x-bs::breadcrumb container-class="mt-3" class="bg-light">
        <x-bs::breadcrumb.item href="{{ route('dashboard') }}">{{ __('Dashboard') }}</x-bs::breadcrumb.item>
        @yield('breadcrumbs')
    </x-bs::breadcrumb>
@endif
```

Items can be added via `<x-bs::breadcrumb.item :href="route('route-name')">Title</x-bs::breadcrumb.item>`.

### Buttons
To create [buttons](https://getbootstrap.com/docs/5.3/components/buttons/) 
or button-like links with Bootstrap's `btn-*` classes you can use 
- `<x-bs::button>` (becomes a `<button>`)
- and `<x-bs::button.link>` (becomes an `<a>`).
Per default `btn-primary` is used, you can change that with the variant. 
```HTML
<x-bs::button href="{{ route('my-route') }}" variant="danger">{{ __('Delete') }}</x-bs::button>
<x-bs::button.link href="{{ route('my-route') }}">{{ __('My title') }}</x-bs::button.link>
```

To disable a button or link, just add `disabled="true"` which automatically adds the corresponding class 
and `aria-disabled="true"` as recommended by the Bootstrap documentation.

#### Button groups
Buttons can be [grouped](https://getbootstrap.com/docs/5.3/components/button-group/):
```HTML
<x-bs::button.group>
    <x-bs::button>Button 1</x-bs::button>
    <x-bs::button variant="secondary">Button 2</x-bs::button>
</x-bs::button.group>
```

#### Button toolbars
Button groups can be grouped into a [toolbar](https://getbootstrap.com/docs/5.3/components/button-group/#button-toolbar):
```HTML
<x-bs:toolbar aria-label="Toolbar with two groups">
    <x-bs::button.group aria-label="First group">
        <x-bs::button>Button 1</x-bs::button>
        <x-bs::button>Button 2</x-bs::button>
    </x-bs::button.group>
    <x-bs::button.group aria-label="Second group">
        <x-bs::button variant="secondary">Button 3</x-bs::button>
        <x-bs::button variant="secondary">Button 4</x-bs::button>
    </x-bs::button.group>
</x-bs:toolbar>
```

### Dropdowns
[Dropdown buttons](https://getbootstrap.com/docs/5.3/components/dropdowns/#single-button) can be added as follows:

```HTML
<x-bs::dropdown.button direction="end" variant="secondary">
    My button
    <x-slot:dropdown>
        <x-bs::dropdown.item href="#">Item 1</x-bs::dropdown.item>
        <x-bs::dropdown.item href="#">Item 2</x-bs::dropdown.item>
    </x-slot:dropdown>
</x-bs::dropdown.button>
```

The `direction` attribute can be used to set the direction of the dropdown overlay. It defaults to `down`.
`variant` (default `primary`) is inherited from the [button component](#buttons).

Within the `<x-slot:dropdown>` you may place [headers](https://getbootstrap.com/docs/5.3/components/dropdowns/#headers) 
and [items](https://getbootstrap.com/docs/5.3/components/dropdowns/#menu-items):
```HTML
<x-bs::dropdown.header>My header</x-bs::dropdown.header>
<x-bs::dropdown.item href="#">Item</x-bs::dropdown.item>
```

Note that Bootstrap's dropdowns require Popper, which needs to be included separately if you don't use Bootstrap's `bootstrap.bundle.min.js`.

Dropdown buttons within a button group require a nested button group and `:nested-in-group="true"` on the dropdown button:
```HTML
<x-bs::button.group>
    <x-bs::button.group>
        <x-bs::dropdown.button variant="primary" :nested-in-group="true">
            Primary dropdown in group
            <x-slot:dropdown>
                <x-bs::dropdown.item href="#">Item 1.1</x-bs::dropdown.item>
                <x-bs::dropdown.item href="#">Item 1.2</x-bs::dropdown.item>
            </x-slot:dropdown>
        </x-bs::dropdown.button>
        <x-bs::dropdown.button variant="secondary" :nested-in-group="true">
            Secondary dropdown in group
            <x-slot:dropdown>
                <x-bs::dropdown.item href="#">Item 2.1</x-bs::dropdown.item>
                <x-bs::dropdown.item href="#">Item 2.2</x-bs::dropdown.item>
            </x-slot:dropdown>
        </x-bs::dropdown.button>
    </x-bs::button.group>
    <x-bs::button.link href="#">Normal button in group</x-bs::button.link>
</x-bs::button.group>
```

### Forms
Use `<x-bs::form>` to create [forms](https://getbootstrap.com/docs/5.3/forms/overview/) (method defaults to `POST`),
any additional attributes passed to the form component will be outputted as well:
```HTML
<x-bs::form method="PUT" action="{{ route('my-route.update') }}" class="my-3">
    <!-- TODO: add form fields and buttons -->
</x-bs::form>
```

Bladestrap will inject an [CSRF token field](https://laravel.com/docs/10.x/blade#csrf-field) for all methods except `GET` automatically.
Bladestrap will also configure [method spoofing](https://laravel.com/docs/10.x/blade#method-field) for `PUT`, `PATCH` and `DELETE` forms.

### Types of form fields
Bladestrap has wide support for Bootstrap's [form fields](https://getbootstrap.com/docs/5.3/forms/form-control/).
```HTML
<x-bs::form.field name="my_field_name" type="text" value="My value">{{ __('My label') }}</x-bs::form.field>
```

Note that the content of the form field becomes the label. This allows to include icons etc.
If you don't want to add a label, don't pass any content:
```HTML
<x-bs::form.field name="my_field_name" type="text" value="My value"/>
```

All attributes will be passed to the `<input>`, `<select>`, `<textarea>` - except
- the attributes which start with `container-` (those will be applied to the container for the label and input)
- and the attributes which start with `label-` (those will be applied to the label).

The following [types](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#input_types) are supported as values for the `type` attribute:
- `checkbox` - creates a [normal checkbox](https://getbootstrap.com/docs/5.3/forms/checks-radios/#checks), requires `:options`
- `color`
- `date`
- `datetime-local`*
- `email`
- `file`
- `hidden` - ignores slots for label, hint and input group
- `month`*
- `number`
- `password`
- `radio` - creates a [radio](https://getbootstrap.com/docs/5.3/forms/checks-radios/#radios), requires `:options`
- `range`
- `select` - creates a [dropdown](https://getbootstrap.com/docs/5.3/forms/select/) (`<select>` with `<option>`s), requires `:options`
- `switch` - creates a [toggle switch](https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches), requires `:options`
- `tel`
- `text`
- `textarea` - creates a `<textarea>`
- `time`*
- `url`
- `week`*

The types (marked with *) listed above don't have [full browser support](https://caniuse.com/?search=input%20type).

#### Options
Radio buttons, checkboxes and selects need a `:options` attribute providing an iterable of value/label pairs, e.g.
- an `array`, as in `:options="[1 => 'Label 1', 2 => 'Label 2']"`
- an `Illuminate\Support\Collection`, such as 
  - `:options="User::query()->pluck('name', 'id')"`
  - or `:options="User::query()->pluck('name', 'id')->prepend(__('all'), '')"`
- a `Portavice\Bladestrap\Support\Options` which allows to set custom attributes for each option.
  For checkboxes, radios and switches, custom attributes prefixed with `check-container-` or `check-label-` are applied to the `.form-check` or `.form-check-label` respectively.
  If labels contain HTML, set `:allow-html="true"`.

An `Portavice\Bladestrap\Support\Options` can be used to easily create an iterable based on
- an `array`
  ```PHP
  use Portavice\Bladestrap\Support\Options;

  // Array with custom attributes
  Options::fromArray(
        [
            1 => 'One',
            2 => 'Two',
        ],
        static fn ($optionValue, $label) => [
            'data-value' => $optionValue + 2,
        ]
    );
  ```
- an `enum` implementing the [BackedEnum interface](https://www.php.net/manual/de/class.backedenum.php)
  ```PHP
  use Portavice\Bladestrap\Support\Options;

  // All enum cases with labels based on the value
  Options::fromEnum(MyEnum::class);

  // ... with labels based on the name
  Options::fromEnum(MyEnum::class, 'name');

  // ... with labels based on the result of the myMethod function
  Options::fromEnum(MyEnum::class, 'myMethod');

  // Only a subset of enum cases
  Options::fromEnum([MyEnum::Case1, MyEnum::Case2]);
  ```
- an `array` or `Illuminate\Database\Eloquent\Collection` of Eloquent models 
  (the primary key becomes the value, label must be defined)
  ```PHP
  use Portavice\Bladestrap\Support\Options;

  // Array of models with labels based on a column or accessor
  Options::fromModels([$user1, $user2, ...$moreUsers], 'name');

  // Collection of models with labels based on a column or accessor
  Options::fromModels(User::query()->get(), 'name');

  // ... with labels based on a Closure
  Options::fromModels(
      User::query()->get(),
      static fn (User $user) => sprintf('%s (%s)', $user->name, $user->id)
  );

  // ... with custom attributes for <option>s using a \Closure defining an ComponentAttributeBag
  Options::fromModels(User::query()->get(), 'name', static function (User $user) {
      return (new ComponentAttributeBag([]))->class([
          'user-option',
          'inactive' => $user->isInactive(),
      ]);
  });

  // ... with custom attributes for <option>s using a \Closure defining an array of attributes
  Options::fromModels(User::query()->get(), 'name', fn (User $user) => [
      'data-title' => $user->title,
  ]);
  ```

Additional options can be prepended/appended to an `Options`:

```PHP
use Portavice\Bladestrap\Support\Options;

$options = Options::fromModels(User::query()->get(), 'name')
    ->sortAlphabetically() // call sort for current options
    ->prepend('all', '') // adds an option with an empty value before first option
    ->append('label for last option', 'value') // adds an option after the last option
    ->prependMany([ // adds options before the first option (value => label)
        'value-1' => 'first prepended option',
        'value-2' => 'second prepended option',
    ]);
```

**Radio** buttons (allows to select one of multiple values):
```HTML
<x-bs::form.field name="my_field_name" type="radio" :options="$options"
                  :value="$value">{{ __('My label') }}</x-bs::form.field>
```

**Multiple** checkboxes (allows to select multiple values):
```HTML
<x-bs::form.field id="my_field_name" name="my_field_name[]" type="checkbox" :options="$options"
                  :value="$value">{{ __('My label') }}</x-bs::form.field>
```

**Single** checkbox (just one option):
```HTML
<x-bs::form.field id="my_field_name" type="checkbox" :options="[1 => 'Option enabled']"
                  :value="$value">{{ __('My label') }}</x-bs::form.field>
<x-bs::form.field id="my_field_name" type="checkbox" :allow-html="true"
                  :options="Options::one('Option <strong>with HTML</strong> enabled')"
                  :value="$value">{{ __('My label') }}</x-bs::form.field>
```

**Select** (allows to select one of multiple values):
```HTML
<x-bs::form.field name="my_field_name" type="select" :options="$options"
                  :value="$value">{{ __('My label') }}</x-bs::form.field>
```

**Multi-Select** (allows to select multiple values):
```HTML
<x-bs::form.field id="my_field_name" name="my_field_name[]" type="select" multi :options="$options"
                  :value="$value">{{ __('My label') }}</x-bs::form.field>
```

#### Disabled, readonly, required
The attributes `:disabled`, `:readonly`, and `:required` accept a boolean value,
e.g. `:disabled="true"` or `:required="isset($var)"`.

Per default fields with `:required="true"` are marked with a `*` after the label.
This behavior can be disabled via configuration (for all fields) or with `:mark-as-required="false"` (for a single field).

#### Input groups
To add text at the left or the right of a form field (except checkboxes and radio buttons),
you can use the slots `<x-slot:prependText>` and `<x-slot:appendText>`
which makes an [input group](https://getbootstrap.com/docs/5.3/forms/input-group/):
```HTML
<x-bs::form.field name="my_field_name" type="number" min="0" max="100" step="0.1">
    {{ __('My label') }}
    <x-slot:prependText>≥</x-slot:prependText>
    <x-slot:appendText>€</x-slot:appendText>
</x-bs::form.field>
```

By default, the appended/prepended text is wrapped within a `<label> class="input-group-text"` associated with the field.
To avoid this, set `:container="false"` attribute on the slot which allows to define to add buttons for example:
```HTML
<x-bs::form.field name="file" type="file">
    File
    <x-slot:appendText>
        <x-bs::button.link variant="primary" href="test.pdf">Download current file</x-bs::button.link>
    </x-slot:appendText>
</x-bs::form.field>'
```

Alternatively, an `appendText` slot can include a `<x-bs::form.field:nested-in-group="true">`:
```HTML
<x-bs::form.field name="price_from" type="number" min="1" step="1">
    <x-slot:prependText>from</x-slot:prependText>
    Price
    <x-slot:appendText :container="false">
        <x-bs::form.field name="price_until" type="number" min="1" step="1" :nested-in-group="true">
            <x-slot:prependText>until</x-slot:prependText>
            <x-slot:appendText>€</x-slot:appendText>
        </x-bs::form.field>
    </x-slot:appendText>
</x-bs::form.field>
```

#### Hints
`<x-slot:hint>` can be used to add a [text](https://getbootstrap.com/docs/5.3/forms/form-control/#form-text) with custom hints (`.form-text`) below the field,
which will be automatically referenced via `aria-describedby` by the input:
```HTML
<x-bs::form.field name="my_field_name" type="text">
    {{ __('My label') }}
    <x-slot:hint>Hint</x-slot:hint>
</x-bs::form.field>
```

#### Prefill values from query parameters
Setting `:from-query="true"` will extract values from the query parameters of the current route.
```HTML
<x-bs::form.field id="name" name="filter[name]" type="text" :from-query="true">{{ __('Name') }}</x-bs::form.field>
```
A form with the example field above on a page `/my-page?filter[name]=Test` will set "Test" as the prefilled value of the field,
while `/my-page` will have an empty value.

To pass default filters applied if no query parameters are set, use `ValueHelper::setDefaults`:
```PHP
use Portavice\Bladestrap\Support\ValueHelper;

ValueHelper::setDefaults([
    'filter.name' => 'default',
])
```

#### Error messages
All form fields show corresponding error messages automatically if present 
([server-side validation](https://getbootstrap.com/docs/5.3/forms/validation/#server-side)).
If you want to show them independent of a form field, you can use the component directly:
```HTML
<x-bs::form.feedback name="{{ $name }}"/>
```
Both `<x-bs::form.feedback>` and `<x-bs::form.field>` support to use another than the default error bag via the `:errors` attribute.

### Links
[Colored links](https://getbootstrap.com/docs/5.3/helpers/colored-links/#link-colors) can be placed via `<x-bs::link>`, 
the attributes `opacity` and `opacityHover` define [opacity](https://getbootstrap.com/docs/5.3/utilities/link/#link-opacity). 
```HTML
<x-bs::link href="{{ route('my-route') }}">Link text</x-bs::link>
<x-bs::link href="{{ route('my-route') }}" variant="danger">Link text</x-bs::link>
<x-bs::link href="{{ route('my-route') }}" opacity="25">Link text</x-bs::link>
```

### List groups
`<x-bs::list>` is a [list group](https://getbootstrap.com/docs/5.3/components/list-group/), a container for multiple `<x-bs::list.item>`.
`:flush="true"` enables [flush behavior](https://getbootstrap.com/docs/5.3/components/list-group/#flush),
`:horizontal="true` changes the layout from vertical to [horizontal](https://getbootstrap.com/docs/5.3/components/list-group/#horizontal).

Items can be added via `<x-bs::list.item>`:
```HTML
<x-bs::list>
    <x-bs::list.item>Item 1</x-bs::list.item>
    <x-bs::list.item :active="true">Item 2</x-bs::list.item>
</x-bs::list>
```
`:active="true"` highlights the [active item](https://getbootstrap.com/docs/5.3/components/list-group/#active-items), 
`:disabled="true"` makes it appear [disabled](https://getbootstrap.com/docs/5.3/components/list-group/#disabled-items).

### Modals
[Modals](https://getbootstrap.com/docs/5.3/components/modal/) can be created via `<x-bs::modal>` with optional slots for title and footer.
Both slots accept additional classes and other attributes.
If you don't want a `<h1>` container for the title, change it via `container="h2"` etc.
```HTML
<x-bs::modal.button modal="my-modal">Open modal</x-bs::modal.button>
<x-bs::modal id="my-modal">
    <x-slot:title>My modal title</x-slot:title>
    <x-slot:footer>
        <x-bs::button>Test</x-bs::button>
    </x-slot:footer>
</x-bs::modal>
```
`<x-bs::modal>` supports the following optional attributes:
- `centered` to center the modal vertically (defaults to `false`)
- `fade` for the fade effect when opening the modal (defaults to `true`)
- `fullScreen` to force fullscreen (defaults to `false`, pass `true` to always enforce full screen or `sm` to enforce for sizes below the sm breakpoint etc.), 
- `scrollable` to enable a vertical scrollbar for long dialog content (defaults to `false`)
- `staticBackdrop`' to enforce that clicking outside of it does not close the modal (defaults to `false`)
- `closeButton` sets the variant of the close button in the modal footer (defaults to `secondary`, `false` to disable the close button),
- `closeButtonTitle` for the title of the close button (defaults to "Close")

A `<x-bs::modal.button modal="my-modal">` opens the modal with the ID `my-modal`.
You may pass any additional attributes as known from [`<x-bs::button>`](#buttons).

### Navigation
`<x-bs::nav>` creates a [nav](https://getbootstrap.com/docs/5.3/components/navs-tabs/) container, use `container="ol"` to change the container element from the default `<ul>` to `<ol>`.

Navigation items can be added via `<x-bs::nav.item href="{{ route('route-name') }}">Current Page</x-bs::nav.item>`.

A navigation item may open a [dropdown](#dropdowns) if you enabled this by adding a dropdown slot:
```HTML
<x-bs::nav.item id="navbarUserDropdown">
    Dropdown link text
    <x-slot:dropdown class="dropdown-menu-end">
        <!-- dropdown content-->
    </x-slot:dropdown>
</x-bs::list.item>
```


## Usage without Laravel
Bladestrap uses `config()` and `request()` helpers.
If you want to use Bladestrap without Laravel, you need to define the two helpers in your application,
for example (may need to be adapted to the framework you use):
```PHP
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

$configFile = [
    'bladestrap' => require __DIR__ . '/../vendor/portavice/bladestrap/config/bladestrap.php',
];
function config(array|string|null $key, mixed $default = null): mixed
{
    global $configFile;
    return Arr::get($configFile, $key, $default);
}

$request = Request::capture();
function request(array|string|null $key = null, mixed $default = null): mixed
{
    global $request;
    return $key === null ? $request : $request->input($key, $default);
}
```

In addition, you have to do the registrations of the `BladestrapServiceProvider` yourself:
```PHP
use Illuminate\View\Factory;
use Portavice\Bladestrap\Macros\ComponentAttributeBagExtension;

// Register macros as BladestrapServiceProvider would do.
ComponentAttributeBagExtension::registerMacros();

/* @var Factory $viewFactory */
// Add components in bs namespace to your views.
$viewFactory->addNamespace('bs', __DIR__ . '/../vendor/portavice/bladestrap/resources/views');
```
