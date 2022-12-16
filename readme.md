# Backpack CRUD Menu Manager

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![The Whole Fruit Manifesto](https://img.shields.io/badge/writing%20standard-the%20whole%20fruit-brightgreen)](https://github.com/the-whole-fruit/manifesto)

This package Menu CRUD Manager functionality for projects that use the [Backpack for Laravel](https://backpackforlaravel.com/) administration panel. 

## Screenshots

![screencapture-127-0-0-1-8000-admin-menu-2022-12-16-14_52_12](https://user-images.githubusercontent.com/569999/208031897-22ccb019-751d-4dd9-be7c-340a56f9e61d.png)
![screencapture-127-0-0-1-8000-admin-menu-3-edit-2022-12-16-14_52_37](https://user-images.githubusercontent.com/569999/208031908-9e8867ce-dbc2-4ab0-89ea-9784d24250d6.png)


## Installation

Composer:

``` bash
composer require tjslash/backpack-menu-manager
```

Put link for administration sidebar:

``` bash
php artisan backpack:add-sidebar-content "<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menu') }}'><i class='nav-icon la la-list'></i> {{ __('tjslash::backpack-menu-manager.menu_items') }}</a></li>"
```

## Usage

> Menu CRUD Manager done! Use it simple.

Open Menu CRUD Manager at the administration panel: 

[http://127.0.0.1:8000/admin/menu](http://127.0.0.1:8000/admin/menu)

## Change log

Changes are documented here on Github. Please see the [Releases tab](https://github.com/tjslash/backpack-menu-manager/releases).

## Testing

``` bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for a todolist and howtos.

## Security

If you discover any security related issues, please email vakylenkox@gmail.com instead of using the issue tracker.

## Credits

- [Artem Vakylenko][link-author]
- [All Contributors][link-contributors]

## License

This project was released under MIT, so you can install it on top of any Backpack & Laravel project. Please see the [license file](license.md) for more information. 

However, please note that you do need Backpack installed, so you need to also abide by its [YUMMY License](https://github.com/Laravel-Backpack/CRUD/blob/master/LICENSE.md). That means in production you'll need a Backpack license code. You can get a free one for non-commercial use (or a paid one for commercial use) on [backpackforlaravel.com](https://backpackforlaravel.com).


[ico-version]: https://img.shields.io/packagist/v/tjslash/backpack-menu-manager.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tjslash/backpack-menu-manager.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tjslash/backpack-menu-manager
[link-downloads]: https://packagist.org/packages/tjslash/backpack-menu-manager
[link-author]: https://github.com/tj
[link-contributors]: ../../contributors
