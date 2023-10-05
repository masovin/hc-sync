# Hc Sync


## Introduction

HC Sync will help you to share human capital data based on event

## Requirements

At this time, Laravel Signature only support:
- Laravel ^8.x.
- PHP ^8.1.0

## Installation

1. run `composer require reksakarya/hc-sync`.

2. add autoload to `composer.json`
```
...
  "autoload": {
    "psr-4": {
      ...
      "HcSync\\": "vendor/reksakarya/hc-sync/src",
      ...
    }
  }
...
```

3. run command ```composer dump-autoload```

4. add provider to `config/app.php 
```
  "providers" => [
    ...
    /*
    * Package Service Providers...
    */
    HcSync\ServiceProvider::class,
    ...
  ]
```

5. To publish signature config into application, run:

```
$ php artisan vendor:publish
```
6. Choose `HcSync\ServiceProvider`

## How to use

to sync Human Capital data, you just need to run `php artisan hc:sync`. you can run command by cron every 2 minutes for daily, but if you integrate your app with HcSync in firt time, you need to run command by nohup.

## List Of Event
| `ENTITY`                            | `EVENT`                    |
|-------------------------------------|----------------------------|
| Organization                        | organizationCreated        |
| Organization                        | organizationUpdated        |
| Employee                            | employeeCreated            |
| Employee                            | employeeUpdated            |
| Employee                            | changeOrganization         |
| Employee                            | changePosition             |
| Employee                            | updatePosition             |
| Employee                            | employeeActivated          |
| Employee                            | employeeDisabled           |
| Teamwork                            | teamworkCreated            |
| Teamwork                            | teamworkUpdated            |
| Teamwork                            | teamworkActivated          |
| Teamwork                            | teamworkDeactivated        |
| Teamwork                            | teamleaderActivated        |
| Teamwork                            | teamleaderDeactivate       |
| Teamwork                            | teammemberActivated        |
| Teamwork                            | teammemberDeactivate       |

