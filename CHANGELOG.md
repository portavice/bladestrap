# Bladestrap Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## Unreleased


## Version 1.5.0 (2025-03-11)

### Added
- Support Laravel 12
- Support dropdown buttons in [nested button groups](https://getbootstrap.com/docs/5.3/components/button-group/#nesting) 


## Version 1.4.0 (2024-11-22)

### Added
- Support nested form fields
- Mark required fields with a `*`
- Support PHP 8.4

### Changed
- Cast enum and models set as value before comparing whether values are active

### Fixed
- Don't override passed values for form fields if fromQuery is enabled, but there is no query parameter with the value


## Version 1.3.0 (2024-05-10)

### Added
- Support custom casts for form field values via `Closure`
- Prepend/append multiple options at once
- Sort options alphabetically or by user defined function
- Support Laravel 11

### Changed
- Migrate PHPUnit tests to use PHP attributes instead of comments, support PHPUnit 11


## Version 1.2.0 (2024-03-21)

### Added
- Components for [modals](https://getbootstrap.com/docs/5.3/components/modal/) and buttons opening a modal

### Fixed
- Move PHPDoc comments outside of `@props` block
- Display of disabled navigation items (`<x-bs::nav.item :disabled="true">`)
- Set the value of disabled form fields even if the value is not contained in the old values


## Version 1.1.1 (2024-01-05)

### Fixed
- Display of error messages for checkboxes and radios whose name is not an array
- Make `Portavice\Bladestrap\Support\Options` countable to correct `$loop->last` in Blade
  and display of error messages for checkboxes and radios
- Set the correct value of form fields if the value was emptied or unchecked before submission with validation errors
  (don't prefill with the previous value currently in the database, but with the empty one submitted)


## Version 1.1.0 (2023-12-30)

### Added
- Support input groups with without automatic wrapping into a `<label>` container

### Fixed
- Layout of input groups with validation errors
- Avoid any visible output for form fields of `type="hidden"`


## Version 1.0.0 (2023-11-22)

### Added
- Components for [alerts](https://getbootstrap.com/docs/5.3/components/alerts/) with support for variants and dismissible
- Components for [badges](https://getbootstrap.com/docs/5.3/components/badge/) with background variants from Bootstrap 5.3
- Components for [breadcrumb](https://getbootstrap.com/docs/5.3/components/breadcrumb/) containers and items
- Components for [buttons](https://getbootstrap.com/docs/5.3/components/buttons/) and button-like links with support for variants and active/disabled styling,
  button groups and toolbars
- Components for [dropdown](https://getbootstrap.com/docs/5.3/components/dropdowns/#single-button) buttons (with different directions), headers, and items
- Components for [forms](https://getbootstrap.com/docs/5.3/forms/overview/) and their fields
  - Support all types of `<input>`s, `<textarea>`, and `<select>`
  - Set HTML attributes (e.g. classes or data attributes) for options of `<input type="checkbox">`, `<input type="radio">`, and `<select>`
  - Allow HTML for labels of `<input type="checkbox">` and `<input type="radio">`
  - Prefill values with old values or from query parameters
  - Show feedback in case of errors
- Components for [colored links](https://getbootstrap.com/docs/5.3/helpers/colored-links/#link-colors) with support for opacity
- Components for [list groups](https://getbootstrap.com/docs/5.3/components/list-group/) with support for flush style
  and list items with support for variants and active/disabled styling
- Auto-registration within Laravel applications
- Guide how to use Bladestrap in an application with Blade views, but without Laravel
