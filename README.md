# l5-workbench

> depends on [https://github.com/pingpong-labs/workbench](https://github.com/pingpong-labs/workbench) ,custom directory and update some code

### Installation

You can install the package via composer command line by running this following command.

```
composer require iwanli/workbench
```

After the package installed, add `Iwanli\Workbench\WorkbenchServiceProvider::class` to your `providers` array in `config/app.php` file.

```
'providers' => [

  /*
   * Laravel Framework Service Providers...
   */
     ....

    Iwanli\Workbench\WorkbenchServiceProvider::class,

],
```

And the last, publish the package's configuration by running:

```
php artisan vendor:publish
```

That will publish the `workbench.php` config file to your `config/` folder and you need to set the name and email of package creators on it.

```
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Workbench Author Name
    |--------------------------------------------------------------------------
    |
    | When you create new packages via the Artisan "workbench" command your
    | name is needed to generate the composer.json file for your package.
    | You may specify it now so it is used for all of your workbenches.
    |
    */

    'name' => '',

    /*
    |--------------------------------------------------------------------------
    | Workbench Author E-Mail Address
    |--------------------------------------------------------------------------
    |
    | Like the option above, your e-mail address is used when generating new
    | workbench packages. The e-mail is placed in your composer.json file
    | automatically after the package is created by the workbench tool.
    |
    */

    'email' => '',
    /**
     * basic root directory name
     */
    'root_dir' => 'packages',

    /**
     * create directory in src
     */
    'support_directory' => [
        'config',
        'resources/lang',
        'migrations',
        'resources/views',
        'Facades'
    ]

];
```

## Autoloading Workbench

You can autoload the workbench by adding this following command to your `bootstrap/autoload.php` file. Put this following command at the very bottom of script.

```php
/*
|--------------------------------------------------------------------------
| Register The Workbench Loaders
|--------------------------------------------------------------------------
|
| The Laravel workbench provides a convenient place to develop packages
| when working locally. However we will need to load in the Composer
| auto-load files for the packages so that these can be used here.
|
*/
if (is_dir($workbench = __DIR__.'/../packages'))
{
	Iwanli\Workbench\Starter::start($workbench);
}
```

### Creating A Package

> Before you create a package, you need to update `name` and `email` config value in your `config/workbench.php` file.

Creating a basic package.

```
php artisan workbench vendor/package
```

Creating a package with generating some scaffold resources.

```
php artisan workbench vendor/package --resources
```
