Yii2 Billing (In development)
=============================

A Yii2 extension to handle billing.

## Installation

Include the package as dependency under the composer.json file.

To install, either run

```bash
$ php composer.phar require jlorente/yii2-billing "*"
```

or add

```json
...
    "require": {
        // ... other configurations ...
        "jlorente/yii2-billing": "*"
    }
```

to the ```require``` section of your `composer.json` file and run the following 
commands from your project directory.
```bash
$ composer update
```

### Migration
Apply the package migration
```bash
$ ./yii migrate --migrationPath=@vendor/jlorente/yii2-billing/src/migrations
```
or extend this migration in your project and apply it as usually.

###Module setup
You must add this module to the module section and the bootstrap section of the 
application config file in order to make it work.
  
../your_app/config/main.php
```php
return [
    //Other configurations
    'modules' => [
        //Other modules
        'billing' => 'jlorente\billing\Module'
    ],
    'bootstrap' => [
        //Other bootstrapped modules
        , 'billing'
    ]
]
```

## Usage

In development...

## License 
Copyright &copy; 2017 José Lorente Martín <jose.lorente.martin@gmail.com>.

Licensed under the MIT license. See LICENSE.txt for details.
