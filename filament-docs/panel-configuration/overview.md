# Panel configuration

**URL:** https://filamentphp.com/docs/5.x/panel-configuration  
**Section:** panel-configuration  
**Page:** overview  
**Priority:** medium  
**AI Context:** Global panel settings and configuration.

---

## #Introduction

By default, the configuration file is located atapp/Providers/Filament/AdminPanelProvider.php. Keep reading to learn more aboutpanelsand how each hasits own configuration file.

## #Introducing panels

By default, when you install the package, there is one panel that has been set up for you - and it lives on/admin. All theresources,custom pages, anddashboard widgetsyou create get registered to this panel.

However, you can create as many panels as you want, and each can have its own set of resources, pages and widgets.

For example, you could build a panel where users can log in at/appand access their dashboard, and admins can log in at/adminand manage the app. The/apppanel and the/adminpanel have their own resources, since each group of users has different requirements. Filament allows you to do that by providing you with the ability to create multiple panels.

### #The default admin panel

When you runfilament:install, a new file is created inapp/Providers/Filament-AdminPanelProvider.php. This file contains the configuration for the/adminpanel.

When this documentation refers to the “configuration”, this is the file you need to edit. It allows you to completely customize the app.

### #Creating a new panel

To create a new panel, you can use themake:filament-panelcommand, passing in the unique name of the new panel:

```php
php artisan make:filament-panel app

```

This command will create a new panel called “app”. A configuration file will be created atapp/Providers/Filament/AppPanelProvider.php. You can access this panel at/app, but you cancustomize the pathif you don’t want that.

Since this configuration file is also aLaravel service provider, it needs to be registered inbootstrap/providers.php(Laravel 11 app structure and above) orconfig/app.php(Laravel 10 app structure and below). Filament will attempt to do this for you, but if you get an error while trying to access your panel then this process has probably failed.

## #Changing the path

In a panel configuration file, you can change the path that the app is accessible at using thepath()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->path('app');
}

```

If you want the app to be accessible without any prefix, you can set this to be an empty string:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->path('');
}

```

Make sure yourroutes/web.phpfile doesn’t already define the''or'/'route, as it will take precedence.

## #Render hooks

Render hooksallow you to render Blade content at various points in the framework views. You canregister global render hooksin a service provider or middleware, but it also allows you to register render hooks that are specific to a panel. To do that, you can use therenderHook()method on the panel configuration object. Here’s an example, integratingwire-elements/modalwith Filament:

```php
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->renderHook(
            PanelsRenderHook::BODY_START,
            fn (): string => Blade::render('@livewire(\'livewire-ui-modal\')'),
        );
}

```

A full list of available render hooks can be foundhere.

## #Setting a domain

By default, Filament will respond to requests from all domains. If you’d like to scope it to a specific domain, you can use thedomain()method, similar toRoute::domain()in Laravel:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->domain('admin.example.com');
}

```

## #Customizing the maximum content width

By default, Filament will restrict the width of the content on the page, so it doesn’t become too wide on large screens. To change this, you may use themaxContentWidth()method. Options correspond toTailwind’s max-width scale. The options areExtraSmall,Small,Medium,Large,ExtraLarge,TwoExtraLarge,ThreeExtraLarge,FourExtraLarge,FiveExtraLarge,SixExtraLarge,SevenExtraLarge,Full,MinContent,MaxContent,FitContent,Prose,ScreenSmall,ScreenMedium,ScreenLarge,ScreenExtraLargeandScreenTwoExtraLarge. The default isSevenExtraLarge:

```php
use Filament\Panel;
use Filament\Support\Enums\Width;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->maxContentWidth(Width::Full);
}

```

If you’d like to set the max content width for pages of the typeSimplePage, like login and registration pages, you may do so using thesimplePageMaxContentWidth()method. The default isLarge:

```php
use Filament\Panel;
use Filament\Support\Enums\Width;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->simplePageMaxContentWidth(Width::Small);
}

```

## #Setting the default sub-navigation position

Sub-navigation is rendered at the start of each page by default. It can be customized per-page, per-resource and per-cluster, but you can also customize it for the entire panel at once using thesubNavigationPosition()method. The value may beSubNavigationPosition::Start,SubNavigationPosition::End, orSubNavigationPosition::Topto render the sub-navigation as tabs:

```php
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->subNavigationPosition(SubNavigationPosition::End);
}

```

## #Lifecycle hooks

Hooks may be used to execute code during a panel’s lifecycle.bootUsing()is a hook that gets run on every request that takes place within that panel. If you have multiple panels, only the current panel’sbootUsing()will be run. The function gets run from middleware, after all service providers have been booted:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->bootUsing(function (Panel $panel) {
            // ...
        });
}

```

## #SPA mode

SPA mode utilizesLivewire’swire:navigatefeatureto make your server-rendered panel feel like a single-page-application, with less delay between page loads and a loading bar for longer requests. To enable SPA mode on a panel, you can use thespa()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->spa();
}

```

### #Disabling SPA navigation for specific URLs

By default, when enabling SPA mode, any URL that lives on the same domain as the current request will be navigated to using Livewire’swire:navigatefeature. If you want to disable this for specific URLs, you can use thespaUrlExceptions()method:

```php
use App\Filament\Resources\Posts\PostResource;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->spa()
        ->spaUrlExceptions(fn (): array => [
            url('/admin'),
            PostResource::getUrl(),
        ]);
}

```

NOTE

In this example, we are usinggetUrl()on a resource to get the URL to the resource’s index page. This feature requires the panel to already be registered though, and the configuration is too early in the request lifecycle to do that. You can use a function to return the URLs instead, which will be resolved when the panel has been registered.

These URLs need to exactly match the URL that the user is navigating to, including the domain and protocol. If you’d like to use a pattern to match multiple URLs, you can use an asterisk (*) as a wildcard character:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->spa()
        ->spaUrlExceptions([
            '*/admin/posts/*',
        ]);
}

```

### #Enabling SPA prefetching

SPA prefetching enhances the user experience by automatically prefetching pages when users hover over links, making navigation feel even more responsive. This feature utilizesLivewire’swire:navigate.hoverfunctionality.

To enable SPA mode with prefetching, you can pass thehasPrefetching: trueparameter to thespa()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->spa(hasPrefetching: true);
}

```

When prefetching is enabled, all links within your panel will automatically includewire:navigate.hover, which prefetches the page content when users hover over the link. This works seamlessly withURL exceptions- any URLs excluded from SPA mode will also be excluded from prefetching.

NOTE

Prefetching only works when SPA mode is enabled. If you disable SPA mode, prefetching will also be disabled automatically.

NOTE

Prefetching heavy pages can lead to increased bandwidth usage and server load, especially if users hover over many links in quick succession. Use this feature judiciously, particularly if your app has pages with large amounts of data or complex queries.

## #Unsaved changes alerts

You may alert users if they attempt to navigate away from a page without saving their changes. This is applied onCreateandEditpages of a resource, as well as any open action modals. To enable this feature, you can use theunsavedChangesAlerts()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->unsavedChangesAlerts();
}

```

## #Enabling database transactions

By default, Filament does not wrap operations in database transactions, and allows the user to enable this themselves when they have tested to ensure that their operations are safe to be wrapped in a transaction. However, you can enable database transactions at once for all operations by using thedatabaseTransactions()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->databaseTransactions();
}

```

For any actions you do not want to be wrapped in a transaction, you can use thedatabaseTransaction(false)method:

```php
CreateAction::make()
    ->databaseTransaction(false)

```

And for any pages likeCreate resourceandEdit resource, you can define the$hasDatabaseTransactionsproperty tofalseon the page class:

```php
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected ?bool $hasDatabaseTransactions = false;

    // ...
}

```

### #Opting in to database transactions for specific actions and pages

Instead of enabling database transactions everywhere and opting out of them for specific actions and pages, you can opt in to database transactions for specific actions and pages.

For actions, you can use thedatabaseTransaction()method:

```php
CreateAction::make()
    ->databaseTransaction()

```

For pages likeCreate resourceandEdit resource, you can define the$hasDatabaseTransactionsproperty totrueon the page class:

```php
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected ?bool $hasDatabaseTransactions = true;

    // ...
}

```

## #Registering assets for a panel

You can registerassetsthat will only be loaded on pages within a specific panel, and not in the rest of the app. To do that, pass an array of assets to theassets()method:

```php
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->assets([
            Css::make('custom-stylesheet', resource_path('css/custom.css')),
            Js::make('custom-script', resource_path('js/custom.js')),
        ]);
}

```

Before theseassetscan be used, you’ll need to runphp artisan filament:assets.

## #Applying middleware

You can apply extra middleware to all routes by passing an array of middleware classes to themiddleware()method in the configuration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->middleware([
            // ...
        ]);
}

```

By default, middleware will be run when the page is first loaded, but not on subsequent Livewire AJAX requests. If you want to run middleware on every request, you can make it persistent by passingtrueas the second argument to themiddleware()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->middleware([
            // ...
        ], isPersistent: true);
}

```

### #Applying middleware to authenticated routes

You can apply middleware to all authenticated routes by passing an array of middleware classes to theauthMiddleware()method in the configuration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->authMiddleware([
            // ...
        ]);
}

```

By default, middleware will be run when the page is first loaded, but not on subsequent Livewire AJAX requests. If you want to run middleware on every request, you can make it persistent by passingtrueas the second argument to theauthMiddleware()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->authMiddleware([
            // ...
        ], isPersistent: true);
}

```

## #Disabling broadcasting

By default, Laravel Echo will automatically connect for every panel, if credentials have been set up in thepublishedconfig/filament.phpconfiguration file. To disable this automatic connection in a panel, you can use thebroadcasting(false)method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->broadcasting(false);
}

```

## #Strict authorization mode

By default, when Filament authorizes the user access to a resource, it will first check if the policy exists for that model, and if it does, it will check if a method exists on the policy to perform the action. If the policy or policy method does not exist, it will grant the user access to the resource, as it assumes you have not set up authorization yet, or you do not require it.

If you would prefer Filament to throw an exception if the policy or policy method does not exist, you can enable strict authorization mode using thestrictAuthorization()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->strictAuthorization();
}

```

## #Configuring error notifications

When Laravel’s debug mode is disabled, Filament will replace Livewire’s full-screen error modals with neater flash notifications. You can disable this behavior using theerrorNotifications(false)method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->errorNotifications(false);
}

```

You may customize the error notification text by passing strings to thetitleandbodyparameters of theregisterErrorNotification()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->registerErrorNotification(
            title: 'An error occurred',
            body: 'Please try again later.',
        );
}

```

You may also register error notification text for a specific HTTP status code, such as404, by passing that status code in thestatusCodeparameter:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->registerErrorNotification(
            title: 'An error occurred',
            body: 'Please try again later.',
        )
        ->registerErrorNotification(
            title: 'Record not found',
            body: 'A record you are looking for does not exist.',
            statusCode: 404,
        );
}

```

You can also enable or disable error notifications for specific pages in a panel by setting the$hasErrorNotificationsproperty on the page class:

```php
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected ?bool $hasErrorNotifications = true;
    
    // or
    
    protected ?bool $hasErrorNotifications = false;

    // ...
}

```

If you would like to run custom code to check if error notifications should be shown, you can use thehasErrorNotifications()method on the page class:

```php
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function hasErrorNotifications(): bool
    {
        return FeatureFlag::active();
    }

    // ...
}

```

You may also register error notification text by calling theregisterErrorNotification()method on the page class from inside thesetUpErrorNotifications()method:

```php
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function setUpErrorNotifications(): void
    {
        $this->registerErrorNotification(
            title: 'An error occurred',
            body: 'Please try again later.',
        );
    }

    // ...
}

```

You can also register error notification text for a specific HTTP status code, such as404, by passing that status code in thestatusCodeparameter:

```php
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected function setUpErrorNotifications(): void
    {
        $this->registerErrorNotification(
            title: 'An error occurred',
            body: 'Please try again later.',
        );
    
        $this->registerErrorNotification(
            title: 'Record not found',
            body: 'A record you are looking for does not exist.',
            statusCode: 404,
        );
    }

    // ...
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** navigation, users  
**Keywords:** config, settings, panel, global

*Extracted from Filament v5 Documentation - 2026-01-28*
