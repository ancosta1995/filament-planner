# Upgrade guide

**URL:** https://filamentphp.com/docs/5.x/upgrade-guide  
**Section:** upgrade-guide  
**Page:** overview  
**Priority:** low  
**AI Context:** Migration guide from previous versions.

---

NOTE

If you see anything missing from this guide, please don’t hesitate tomake a pull requestto our repository! Any help is appreciated!

## #New requirements
- PHP 8.2+
- Laravel v11.28+
- Livewire v4.0+
- Tailwind CSS v4.0+


## #Running the automated upgrade script

NOTE

Some plugins you’re using may not be available in v5 just yet. You could temporarily remove them from yourcomposer.jsonfile until they’ve been upgraded, replace them with a similar plugins that are v5-compatible, wait for the plugins to be upgraded before upgrading your app, or even write PRs to help the authors upgrade them.

The first step to upgrade your Filament app is to run the automated upgrade script. This script will automatically upgrade your application to the latest version of Filament and make changes to your code, which handles breaking changes:

```php
composer require filament/upgrade:"^5.0" -W --dev

vendor/bin/filament-v5

# Run the commands output by the upgrade script, they are unique to your app
composer require filament/filament:"^5.0" -W --no-update
composer update

```

NOTE

When using Windows PowerShell to install Filament, you may need to run the command below, since it ignores^characters in version constraints:

```php
composer require filament/upgrade:"~5.0" -W --dev

vendor/bin/filament-v5

# Run the commands output by the upgrade script, they are unique to your app
composer require filament/filament:"~5.0" -W --no-update
composer update

```

Make sure to carefully follow the instructions, and review the changes made by the script. You may need to make some manual changes to your code afterwards, but the script should handle most of the repetitive work for you.

You can nowcomposer remove filament/upgrade --devas you don’t need it anymore.

## #Upgrading Livewire

Filament v5 requires Livewire v4.0+. You can upgrade your Livewire code by following theLivewire upgrade guide.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:**   
**Keywords:** upgrade, migration, breaking changes

*Extracted from Filament v5 Documentation - 2026-01-28*
