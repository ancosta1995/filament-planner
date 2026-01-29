# Contributing

**URL:** https://filamentphp.com/docs/5.x/introduction/contributing  
**Section:** introduction  
**Page:** contributing  
**Priority:** high  
**AI Context:** Fundamental concepts, installation, and setup. Start here for new users.

---

NOTE

Parts of this guide are adapted fromLaravel’s contribution guide, which served as valuable inspiration.

## #Reporting bugs

If you discover a bug in Filament, please report it by opening an issue on ourGitHub repository. Before opening an issue, search through theexisting issuesto check if the bug has already been reported.

Please include as much information as possible, particularly the version numbers of packages in your application. You can use this Artisan command in your application to open a new issue with all the correct versions automatically pre-filled:

```php
php artisan make:filament-issue

```

When creating an issue, we require a “reproduction repository”.Please do not link to your actual project. Instead, what we need is aminimalreproduction in a fresh project without any unnecessary code. This means it doesn’t matter if your real project is private/confidential since we want a link to a separate, isolated reproduction. This allows us to fix the problem much quicker.Issues will be automatically closed and not reviewed if this is missing, to preserve maintainer time and ensure the process is fair for those who put effort into reporting.If you believe a reproduction repository is not suitable for the issue, which is a very rare case, please@danharrinand explain why. Saying “it’s just a simple issue” is not an excuse for not creating a repository!Need a head start? We have a template Filament project for you.

Remember, bug reports are created in the hope that others with the same problem will be able to collaborate with you on solving it. Do not expect that the bug report will automatically receive attention or that others will jump to fix it. Creating a bug report serves to help yourself and others start on the path of fixing the problem.

## #Development of new features

If you would like to propose a new feature or improvement to Filament, you may use ourdiscussion forumhosted on GitHub. If you intend to implement the feature yourself in a pull request, we advise you to@danharrin(a core maintainer of Filament) in your feature discussion beforehand and ask if it is suitable for the framework to prevent wasting your time.

## #Development of plugins

If you would like to develop a plugin for Filament, please refer to theplugin development sectionin the documentation. OurDiscordserver is also a great place to ask questions and get help with plugin development. You can start a conversation in the#plugin-developers-chatchannel.

You can alsosubmit your plugin to be advertised on the Filament website.

## #Developing with a local copy of Filament

If you want to contribute to the Filament packages, then you may want to test it in a real Laravel project:
- Forkthe GitHub repositoryto your GitHub account.
- Create a Laravel app locally.
- Clone your fork into your Laravel app’s root directory.
- In the/filamentdirectory, create a branch for your fix, e.g.fix/error-message.


Install the packages in your app’scomposer.json:

```php
{
    // ...
    "require": {
        "filament/filament": "*",
    },
    "minimum-stability": "dev",
    "repositories": [
        {
            "type": "path",
            "url": "filament/packages/*"
        }
    ],
    // ...
}

```

Now, runcomposer update.

Once you’ve finished making changes, you can commit them and submit a pull request tothe GitHub repository.

## #Checking for outdated translations

To check for outdated translations, you can use our Translation Tool. Clone the Filament repository, install the dependencies for the commands and then run the command.

```php
# Clone
git clone [email protected]:filamentphp/filament.git

# Install dependencies
composer install

# Run the tool  
./bin/translation-tool.php

```

First select “List outdated translations” as the command and then choose the locale you want to check. This command will show you which translations are missing for the specified locale. You can then submit a pull request with the missing translations tothe GitHub repository.

## #Security vulnerabilities

If you discover a security vulnerability within Filament, pleasereport it through GitHub. All security vulnerabilities will be promptly addressed. Please see ourversion support policyto understand which versions are currently under maintenance.

## #Code of Conduct

Please note that Filament is released with aContributor Code of Conduct. By participating in this project, you agree to abide by its terms.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** getting-started  
**Keywords:** installation, setup, basics, getting started

*Extracted from Filament v5 Documentation - 2026-01-28*
