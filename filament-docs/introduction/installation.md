# Installation

**URL:** https://filamentphp.com/docs/5.x/introduction/installation  
**Section:** introduction  
**Page:** installation  
**Priority:** high  
**AI Context:** Fundamental concepts, installation, and setup. Start here for new users.

---

Filament requires the following to run:
- PHP 8.2+
- Laravel v11.28+
- Tailwind CSS v4.1+


Installation comes in two flavors, depending on whether you want to build an app using our panel builder or use the components within your app’s Blade views:

Panel builder

Most people choose this option to build a panel (e.g., admin panel) for their app. The panel builder combines all the individual components into a cohesive framework. You can create as many panels as you like within a Laravel installation, but you only need to install it once.

Individual components

If you are using Blade to build your app from scratch, you can install individual components from Filament to enrich your UI.

## #Installing the panel builder

Install the Filament Panel Builder by running the following commands in your Laravel project directory:

```php
composer require filament/filament:"^5.0"

php artisan filament:install --panels

```

NOTE

When using Windows PowerShell to install Filament, you may need to run the command below, since it ignores^characters in version constraints:

```php
composer require filament/filament:"~5.0"

php artisan filament:install --panels

```

This will create and register a newLaravel service providercalledapp/Providers/Filament/AdminPanelProvider.php.

TIP

If you get an error when accessing your panel, check that the service provider is registered inbootstrap/providers.php. If it’s not registered, you’ll need toadd it manually.

You can create a new user account using the following command:

```php
php artisan make:filament-user

```

Open/adminin your web browser, sign in, andstart building your app!

## #Installing the individual components

Install the Filament components you want to use with Composer:

```php
composer require
    filament/tables:"^5.0"
    filament/schemas:"^5.0"
    filament/forms:"^5.0"
    filament/infolists:"^5.0"
    filament/actions:"^5.0"
    filament/notifications:"^5.0"
    filament/widgets:"^5.0"

```

You can install additional packages later in your project without having to repeat these installation steps.

NOTE

When using Windows PowerShell to install Filament, you may need to run the command below, since it ignores^characters in version constraints:

```php
composer require
    filament/tables:"~5.0"
    filament/schemas:"~5.0"
    filament/forms:"~5.0"
    filament/infolists:"~5.0"
    filament/actions:"~5.0"
    filament/notifications:"~5.0"
    filament/widgets:"~5.0"

```

If you only want to use the set ofBlade UI components, you’ll need to requirefilament/supportat this stage.

New Laravel projects

Get started with Filament components quickly by running a simple command. Note that this will overwrite any modified files in your app, so it’s only suitable for new Laravel projects.

Existing Laravel projects

If you have an existing Laravel project, you can still install Filament, but should do so manually to preserve your existing functionality.

To quickly set up Filament in a new Laravel project, run the following commands to installLivewire,Alpine.js, andTailwind CSS:

NOTE

These commands will overwrite existing files in your application. Only run them in a new Laravel project!

Run the following command to install the Filament frontend assets:

```php
php artisan filament:install --scaffold

npm install

npm run dev

```

During scaffolding, if you have theNotificationspackage installed, Filament will ask if you want to install the required Livewire component into your default layout file. This component is required if you want to send flash notifications to users through Filament.

Run the following command to install the Filament frontend assets:

```php
php artisan filament:install

```

### #Installing Tailwind CSS

Run the following command to install Tailwind CSS and its Vite plugin, if you don’t have those installed already:

```php
npm install tailwindcss @tailwindcss/vite --save-dev

```

### #Configuring styles

To configure Filament’s styles, you need to import the CSS files for the Filament packages you installed, usually inresources/css/app.css.

Depending on which combination of packages you installed, you can import only the necessary CSS files, to keep your app’s CSS bundle size small:

```php
@import 'tailwindcss';

/* Required by all components */
@import '../../vendor/filament/support/resources/css/index.css';

/* Required by actions and tables */
@import '../../vendor/filament/actions/resources/css/index.css';

/* Required by actions, forms and tables */
@import '../../vendor/filament/forms/resources/css/index.css';

/* Required by actions and infolists */
@import '../../vendor/filament/infolists/resources/css/index.css';

/* Required by notifications */
@import '../../vendor/filament/notifications/resources/css/index.css';

/* Required by actions, infolists, forms, schemas and tables */
@import '../../vendor/filament/schemas/resources/css/index.css';

/* Required by tables */
@import '../../vendor/filament/tables/resources/css/index.css';

/* Required by widgets */
@import '../../vendor/filament/widgets/resources/css/index.css';

@variant dark (&:where(.dark, .dark *));

```

### #Configure the Vite plugin

If it isn’t already set up, add the@tailwindcss/viteplugin to your Vite configuration (vite.config.js):

```php
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
})

```

### #Compiling assets

Compile your new CSS and JavaScript assets usingnpm run dev.

### #Configuring your layout

If you don’t have a Blade layout file yet, create it atresources/views/layouts/app.blade.phpby running the following command:

```php
php artisan livewire:layout

```

Add the following code to your new layout file:

```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')
    </head>

    <body class="antialiased">
        {{ $slot }}

        @livewire('notifications') {{-- Only required if you wish to send flash notifications --}}

        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>

```

The important parts of this are the@filamentStylesin the<head>of the layout, and the@filamentScriptsat the end of the<body>. Make sure to also include your app’s compiled CSS and JavaScript files from Vite!

NOTE

The@livewire('notifications')line is required in the layout only if you have theNotificationspackage installed and want to send flash notifications to users through Filament.

## #Publishing configuration

Filament ships with a configuration file that allows you to override defaults shared across all packages. Publish it after installing the panel builder so you can review and customize the settings:

```php
php artisan vendor:publish --tag=filament-config

```

This command createsconfig/filament.php, where you can configure options like the default filesystem disk, file generation flags, and UI defaults. Re-run the publish command any time you want to pull in newly added configuration keys before tweaking them to suit your project.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** getting-started  
**Keywords:** installation, setup, basics, getting started

*Extracted from Filament v5 Documentation - 2026-01-28*
