# Deploying to production

**URL:** https://filamentphp.com/docs/5.x/deployment  
**Section:** deployment  
**Page:** overview  
**Priority:** low  
**AI Context:** Production deployment best practices.

---

## #Introduction

Deploying a Laravel app using Filament to production is similar to deploying any other Laravel app. However, there are a few additional steps you should take to ensure that your Filament panel is optimized for performance and security.

For tips focused on local development performance, seeOptimizing local development.

## #Ensure that users are authorized to access panels

When Filament detects that your app’sAPP_ENVis notlocal, it will require you to set up authorization for your users. This is to ensure that only authorized users can access your Filament panel in production, while keeping the local environment easy to get started with.

To authorize users to access a panel, you should follow theguide in the users section.

NOTE

If you do not follow these steps and your user model does not implement theFilamentUserinterface, no users will be able to sign in to your panel in production.

## #Improving Filament panel performance

### #Optimizing Filament for production

To optimize Filament for production, you should run the following command in your deployment script:

```php
php artisan filament:optimize

```

This command willcache the Filament componentsand additionally theBlade Icons, which can significantly improve the performance of your Filament panels. This command is a shorthand for the commandsphp artisan filament:cache-componentsandphp artisan icons:cache.

To clear the caches at once, you can run:

```php
php artisan filament:optimize-clear

```

#### #Caching Filament components

If you’re not using thefilament:optimizecommand, you may wish to consider runningphp artisan filament:cache-componentsin your deployment script, especially if you have large numbers of components (resources, pages, widgets, relation managers, custom Livewire components, etc.). This will create cache files in thebootstrap/cache/filamentdirectory of your application, which contain indexes for each type of component. This can significantly improve the performance of Filament in some apps, as it reduces the number of files that need to be scanned and auto-discovered for components.

However, if you are actively developing your app locally, you should avoid using this command, as it will prevent any new components from being discovered until the cache is cleared or rebuilt.

You can clear the cache at any time without rebuilding it by runningphp artisan filament:clear-cached-components.

#### #Caching Blade Icons

If you’re not using thefilament:optimizecommand, you may wish to consider runningphp artisan icons:cachelocally, and also in your deployment script. This is because Filament uses theBlade Iconspackage, which can be much more performant when cached.

### #Enabling OPcache on your server

To check ifOPcacheis enabled, run:

```php
php -r "echo 'opcache.enable => ' . ini_get('opcache.enable') . PHP_EOL;"

```

You should seeopcache.enable => 1. If not, enable it by adding the following line to yourphp.ini:

```php
opcache.enable=1 # Enable OPcache

```

From theLaravel Forge documentation:

TIP

Optimizing the PHP OPcache for production will configure OPcache to store your compiled PHP code in memory to greatly improve performance.

Please use a search engine to find the relevant OPcache setup instructions for your environment.

### #Optimizing your Laravel app

You should also consider optimizing your Laravel app for production by runningphp artisan optimizein your deployment script. This will cache the configuration files and routes.

## #Ensuring assets are up to date

During the Filament installation process, Filament adds thephp artisan filament:upgradecommand to thecomposer.jsonfile, in thepost-autoload-dumpscript. This command will ensure that your assets are up to date whenever you download the package.

We strongly suggest that this script remains in yourcomposer.jsonfile, otherwise you may run into issues with missing or outdated assets in your production environment. However, if you must remove it, make sure that the command is run manually in your deployment process.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:**   
**Keywords:** deploy, production, optimization

*Extracted from Filament v5 Documentation - 2026-01-28*
