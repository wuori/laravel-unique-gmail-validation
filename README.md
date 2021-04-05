# Validate uniqueness of Gmail addresses

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wuori/laravel-unique-gmail-validation.svg?style=flat-square)](https://packagist.org/packages/wuori/laravel-unique-gmail-validation)
[![Total Downloads](https://img.shields.io/packagist/dt/wuori/laravel-unique-gmail-validation.svg?style=flat-square)](https://packagist.org/packages/wuori/laravel-unique-gmail-validation)

Gmail [allows two modifiers](https://gmail.googleblog.com/2008/03/2-hidden-ways-to-get-more-from-your.html), `+` and `.` to be added to your email address without affecting delivery. For example, `michaelwuori@gmail.com` and `michael.wuori@gmail.com` both work as valid addresses to the same Gmail account.

The period (`.`) modifier can be placed anywhere within your account name, as in the example above. 

The plus (`+`) modifier can be appended to your account name. Example: `michael.wuori+junk@gmail.com`.

This validation rule removes any period modifiers as well as any `+foo` appendages then compares the address to any existing `@gmail.com` emails that exist in the targeted model.

## Installation

You can install the package via composer:

```bash
composer require wuori/laravel-unique-gmail-validation
```

The package will automatically register itself.

### Using the UniqueGmail Rule

Include the rule:
```php
...
use Illuminate\Http\Request;
use Wuori\UniqueGmail\UniqueGmail;
...
```

Attach the rule to an email field, passing the Model you wish to validate against as the first parameter.

If no Model is passed, the rule will default to `\App\Models\User`.

```php
// default User model
$validator = $request->validate([
    'email' => [new UniqueGmail()]
]);

// custom model
$validator = $request->validate([
    'email' => [new UniqueGmail(\App\Models\Customers::class)]
]);

// combined with other rules
$validator = $request->validate([
    'email' => ['required','email','unique:users,email,NULL,id', new UniqueGmail(\App\Models\User::class)]
]);
```

This validation rule will pass if the id of the logged in user matches the `user_id` on `TestModel` who's it is in the `model_id` key of the request.

### Custom Error Message

By default the `UniqueGmail` rule will use Laravel's built-in `unique` validation message (`trans('validation.unique')`).

You can supply your own message as an entry to the second parameter, `$options`:

```php
$options = [
    'message' => 'An account already exists for :existing_email, a varient of :requested_email.'
];
$validator = $request->validate([
    'email' => [new UniqueGmail(\App\Models\User::class, $options)]
]);
```

The custom message accepts two optional attributes to replace:

`:existing_email` is replaced with the matching (existing) email address.

`:requested_email` is replaced with the email address provided in the request.

### Testing

Coming soon...

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

### Security

If you discover any security related issues, please email michael.wuori@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
