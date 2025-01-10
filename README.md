# Inspector | Code Execution Monitoring tool

[![Latest Stable Version](https://poser.pugx.org/inspector-apm/inspector-codeigniter/v/stable)](https://packagist.org/packages/inspector-apm/inspector-codeigniter)
[![License](https://poser.pugx.org/inspector-apm/inspector-codeigniter/license)](//packagist.org/packages/inspector-apm/inspector-codeigniter)
[![Contributor Covenant](https://img.shields.io/badge/Contributor%20Covenant-2.1-4baaaa.svg)](CODE_OF_CONDUCT.md)

Connect CodeIgniter 4 applications to the Inspector monitoring dashboard.


## Official maintainer

> This library was built in collaboration with [Prysis](http://www.prysis.co.za/) - sales@prysis.co.za


## Installation

Install the latest version using the composer command below:

```
composer require inspector-apm/inspector-codeigniter
```

Run the install command to publish the `Inspector.php` configuration file in your application `app/Config` directory:

```
php spark inspector:install
```


## Configure the Ingestion Key

Add the environment variable below to your `.env` file in order to make your application able to send data to your dashboard.
You can get a new Ingestion Key by creating a new app in your account: https://app.inspector.dev

```dotenv
#--------------------------------------------------------------------
# INSPECTOR
#--------------------------------------------------------------------

inspector.ingestionKey = '974yn8c34ync8xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
```


## Verify And Deploy

Run the command below to check if your system is properly configured. If all checks are green you can deploy 
the update in your production environment. 

```
php spark inspector:test
```


## Helper

The helper provides a shortcut to the inspector instance. It must first be loaded using the `helper()` method:

```php
// Load the helper function
helper('inspector');

// Add custom segments
$json = inspector()->addSegment(function () {
    return file_get_contents('auth.json');
}, 'http', 'READ auth.json');

// Report an exception
inspector()->reportException(new \Exception("Whoops there's an error here."));
```

**We highly recommend** adding the helper in the `Config/Autoload.php` configuration class to make it available 
globally into the application:

```php
class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     * Prototype:
     *   $helpers = [
     *       'form',
     *   ];
     *
     * @var list<string>
     */
    public $helpers = ['inspector'];
}
```


## Official documentation

**[Check out the official documentation](https://docs.inspector.dev/guides/codeigniter)**

## Contributing

We encourage you to contribute to Inspector! Please check out the [Contribution Guidelines](CONTRIBUTING.md) about how to proceed. Join us!

## LICENSE

This package is licensed under the [MIT](LICENSE) license.
