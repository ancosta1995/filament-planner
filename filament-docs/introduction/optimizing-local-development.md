# Optimizing local development

**URL:** https://filamentphp.com/docs/5.x/introduction/optimizing-local-development  
**Section:** introduction  
**Page:** optimizing-local-development  
**Priority:** high  
**AI Context:** Fundamental concepts, installation, and setup. Start here for new users.

---

This section includes optional tips to optimize performance when running your Filament app locally.

If you’re looking for production-specific optimizations, check outDeploying to production.

## #Enabling OPcache

OPcacheimproves PHP performance by storing precompiled script bytecode in shared memory, thereby removing the need for PHP to load and parse scripts on each request. This can significantly speed up your local development environment, especially for larger applications.

### #Checking OPcache status

To check ifOPcacheis enabled, run:

```php
php -r "echo 'opcache.enable => ' . ini_get('opcache.enable') . PHP_EOL;"

```

You should seeopcache.enable => 1. If not, enable it by adding the following line to yourphp.ini:

```php
opcache.enable=1 # Enable OPcache

```

TIP

To locate yourphp.inifile, run:php --ini

### #Configuring OPcache settings

If you’re experiencing slow response times or suspect that OPcache is running out of space, you can adjust these parameters in yourphp.inifile:

```php
opcache.memory_consumption=128
opcache.max_accelerated_files=10000

```

TIP

To locate yourphp.inifile, run:php --ini
- opcache.memory_consumption: defines how much memory (in megabytes) OPcache can use to store precompiled PHP code. You can try setting this to128and adjust based on your project’s needs.
- opcache.max_accelerated_files: sets the maximum number of PHP files that OPcache can cache. You can try10000as a starting point and increase if your application includes a large number of files.


These settings are optional but useful if you’re troubleshooting performance or working on a large Laravel app.

## #Exclude your project folder from antivirus scanning

Issues with the performance of Filament, particularly on Windows, often involveMicrosoft Defender.

Security software, such as realtime file scanners or antivirus tools, can slow down your development environment by scanning files every time they’re accessed. This can affect PHP execution, view rendering, and performance in general.

If you’re noticing slowness, consider excluding your local project folder from realtime scanning.

Tools likeMicrosoft Defender, or similar antivirus solutions, can be configured to skip specific directories. Check your antivirus or security software documentation for instructions on how to exclude specific folders from realtime scanning.

NOTE

Only exclude folders from scanning if you fully trust the project and understand the risks.

## #Disabling debugging tools

Debugging tools can be very useful for local development, but they can significantly slow down your application when you aren’t actively using them. Temporarily disabling these tools when you need maximum performance can make a noticeable difference in your development experience.

### #Disabling view debugging in Laravel Herd

Laravel Herdincludes a view debugging tool formacOSandWindows. It shows which views were rendered and what data was passed to them during a request.

While helpful for debugging, this feature can significantly slow down your app. If you’re not actively using it, it’s best to turn it off.

To disable view debugging in Herd:
- Open Herd > Dumps.
- Click Settings.
- Uncheck the “Views” option.


### #Disabling Debugbar

While useful for debugging,Laravel Debugbarcan slow down your application, especially on complex pages, because it collects and renders a large amount of data on each request.

If you’re noticing slowness, try disabling it by adding the following line to your.envfile:

```php
DEBUGBAR_ENABLED=false

```

If you still need Debugbar for development, consider disabling specific collectors you’re not using.
Refer to theDebugbar documentationfor details.

### #Disabling Xdebug

Xdebugis a powerful tool for debugging, but it can significantly slow down performance. If you notice performance issues, check ifXdebugis installed and consider disabling it.

IfXdebugis installed but not disabled, it will still be enabled by default. If you have it installed, make sure it is explicitly disabled in yourphp.inifile:

```php
xdebug.mode=off # Disable Xdebug

```

TIP

To locate yourphp.inifile, run:php --ini

## #Caching Blade icons

CachingBlade iconscan help improve performance during local development, especially in views that render many icons.

To enable caching, run:

```php
php artisan icons:cache

```

Make sure that when you install new Blade icon packages, you run the command again to discover the new icons.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** getting-started  
**Keywords:** installation, setup, basics, getting started

*Extracted from Filament v5 Documentation - 2026-01-28*
