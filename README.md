# Bladestrap = Blade + Bootstrap

[![MIT Licensed](https://img.shields.io/badge/License-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![Tests @GitHub](https://img.shields.io/github/actions/workflow/status/portavice/bladestrap/tests.yml?branch=main&label=Tests)
![Code style check @GitHub](https://img.shields.io/github/actions/workflow/status/portavice/bladestrap/code-style.yml?branch=main&label=Code%20style)

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
    - [Button groups and toolbars](#button-groups-and-toolbars)
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
  - [Navigation](#navigation) 


## Installation
First, install the package via [Composer](https://getcomposer.org/):
```bash
composer require portavice/bladestrap
```

The package will automatically register itself.

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
You may need to adjust the steps above to your custom project configuration (e.g. if you want a [custom build](https://getbootstrap.com/docs/5.3/customize/sass/)).


### Configure Bladestrap
Usually this should not be necessary, but if need to overwrite the default configuration,
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

#### Button groups and toolbars
Buttons can be [grouped](https://getbootstrap.com/docs/5.3/components/button-group/):
```HTML
<x-bs::button.group>
    <x-bs::button>Button 1</x-bs::button>
    <x-bs::button variant="secondary">Button 2</x-bs::button>
</x-bs::button.group>
```

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
If you don't want this, don't pass any content:
```HTML
<x-bs::form.field name="my_field_name" type="text" value="My value"/>
```

The following [types](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#input_types) are supported as values for the `type` attribute:
- `checkbox`, requires `:options`
- `color`
- `date`
- `datetime-local`*
- `email`
- `file`
- `month`*
- `number`
- `password`
- `radio`, requires `:options`
- `range`
- `select` - creates a `<select>` with `<option>`s, requires `:options`
- `tel`
- `text`
- `textarea` - creates a `<textarea>`
- `time`*
- `url`
- `week`*

The types (marked with *) listed above don't have [full browser support](https://caniuse.com/?search=input%20type).

#### Options
Radio buttons, check boxes and selects need a `:options` attribute providing an iterable of value/label pairs, e.g.
- an array, as in `:options="[1 => 'Label 1', 2 => 'Label 2']"`
- an instance of `Illuminate\Support\Collection`, such as `:options="User::query()->pluck('name', 'id')"`
  or `:options="User::query()->pluck('name', 'id')->prepend(__('all'), '')"`

**Radio** buttons:
```HTML
<x-bs::form.field name="my_field_name"
                  type="radio" :options="$options"
                  :value="$value">{{ __('My label') }}</x-bs::form.field>
```

**Multiple** check boxes:
```HTML
<x-bs::form.field id="my_field_name"
                  name="my_field_name[]"
                  type="checkbox" :options="$options"
                  :value="$value">{{ __('My label') }}</x-bs::form.field>
```

**Single** checkbox (just one option):
```HTML
<x-bs::form.field id="my_field_name"
                  name="my_field_name[]"
                  type="checkbox" :options="[1 => 'Option enabled']"
                  :value="$value">{{ __('My label') }}</x-bs::form.field>
```

**Select**:
```HTML
<x-bs::form.field id="my_field_name"
                  type="select" :options="$options"
                  :value="$value">{{ __('My label') }}</x-bs::form.field>
```

#### Disabled, readonly, required
The attributes `:disabled`, `:readonly`, and `:required` accept a boolean value,
e.g. `:disabled=true` or `:required=isset($var)`.

#### Input groups
To add text at the left or the right of a form field, you can use the slots `<x-slot:prependText>` and `<x-slot:appendText>`
which makes an [input group](https://getbootstrap.com/docs/5.3/forms/input-group/):
```HTML
<x-bs::form.field name="my_field_name" type="number" min="0" max="100" step="0.1">
    {{ __('My label') }}
    <x-slot:prependText>≥</x-slot:prependText>
    <x-slot:appendText>€</x-slot:appendText>
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

### Navigation
`<x-bs::nav>` creates a [nav](https://getbootstrap.com/docs/5.3/components/navs-tabs/) container, use `container="ol"` to change the container element from the default `<ul>` to `<ol>`.

Navigation items can be added via `<x-bs::nav.item href="{{ route('route-name') }}">Current Page</x-bs::nav.item>`.
