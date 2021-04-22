# Ldap

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This package is designed to offer a quick authentication process via LDAP.
The example below describes a setup that authenticates using a ```username``` and ```password`` combination.

## Installation

Via Composer

``` bash
$ composer require tyler36/ldap
```

## Usage
1. Publish the config file

```php
$ php artisan vendor:publish --provider="Tyler36\Ldap\LdapServiceProvider"
```


2. Update ```.ENV``` with server settings

```
LDAP_HOST=
LDAP_USERNAME=
LDAP_USERNAME_PREFIX=
LDAP_FILTER=
LDAP_DOMAIN_COMP=
LDAP_COMMON_NAME=
LDAP_BASE_DN=
```

3. Add a username column to your user migration.

```
$table->string('username')->unique();
```

4. Update login view by replacing ```email``` with ```username```. Remember to remove the ```type="email"```

```
<input id="username"
    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline{{ $errors->has('username') ? ' border-red-500' : '' }}"
    name="username" value="{{ old('username') }}" required autofocus>
```

5. Update your ```LoginController``` file. Remember to import ```Tyler36\Ldap\LdapAuthenticator;```

```php
/**
  * Get the login username to be used by the controller. ['email']
  *
  * @return string
  */
public function username()
{
    return 'username';
}

/**
 * Attempt to log the user into the application.
 *
 * @param \Illuminate\Http\Request $request
 *
 * @return mixed
 */
protected function attemptLogin(Request $request)
{
    // This is where authentication happens. It SHOULD return an array containing the user
    $ldap     = new LdapAuthenticator($request);
    $ldapUser = $ldap->authenticate();
    if (!$ldapUser || 'array' !== gettype($ldapUser)) {
        return $ldapUser;
    }

    // Un-comment the following to see details of the user array
    // dd($ldapUser)

    // Update or create a new user based on the username. The second array determines how to populate new users.
    $user   = User::updateOrCreate(
        [$this->username() => $request->get('username')],
        [
            $this->username() => $request->get('username'),
            'name'            => optional($ldapUser)['displayname'][0],
            'email'           => optional($ldapUser)['mail'][0],
        ]
    );

    auth()->login($user);
}
```


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/tyler36/ldap.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tyler36/ldap.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/tyler36/ldap/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/tyler36/ldap
[link-downloads]: https://packagist.org/packages/tyler36/ldap
[link-travis]: https://travis-ci.org/tyler36/ldap
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/tyler36
[link-contributors]: ../../contributors
