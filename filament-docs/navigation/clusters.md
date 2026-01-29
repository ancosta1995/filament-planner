# Clusters

**URL:** https://filamentphp.com/docs/5.x/navigation/clusters  
**Section:** navigation  
**Page:** clusters  
**Priority:** medium  
**AI Context:** Configure panel navigation and menu structure.

---

## #Introduction

Clusters are a hierarchical structure in panels that allow you to groupresourcesandcustom pagestogether. They are useful for organizing your panel into logical sections, and can help reduce the size of your panel’s sidebar.

When using a cluster, a few things happen:
- A new navigation item is added to the navigation, which is a link to the first resource or page in the cluster.
- The individual navigation items for the resources or pages are no longer visible in the main navigation.
- A new sub-navigation UI is added to each resource or page in the cluster, which contains the navigation items for the resources or pages in the cluster.
- Resources and pages in the cluster get a new URL, prefixed with the name of the cluster. If you are generating URLs toresourcesandpagescorrectly, then this change should be handled for you automatically.
- The cluster’s name is in the breadcrumbs of all resources and pages in the cluster. When clicking it, you are taken to the first resource or page in the cluster.


## #Creating a cluster

Before creating your first cluster, you must tell the panel where cluster classes should be located. Alongside methods likediscoverResources()anddiscoverPages()in theconfiguration, you can usediscoverClusters():

```php
public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
        ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
        ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters');
}

```

Now, you can create a cluster with thephp artisan make:filament-clustercommand:

```php
php artisan make:filament-cluster Settings

```

This will create a new cluster class in theapp/Filament/Clustersdirectory:

```php
<?php

namespace App\Filament\Clusters\Settings;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class SettingsCluster extends Cluster
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedSquares2x2;
}

```

The$navigationIconproperty is defined by default since you will most likely want to customize this immediately. All othernavigation properties and methodsare also available to use, including$navigationLabel,$navigationSortand$navigationGroup. These are used to customize the cluster’s main navigation item, in the same way you would customize the item for a resource or page.

## #Adding resources and pages to a cluster

To add resources and pages to a cluster, you just need to define the$clusterproperty on the resource or page class, and set it to the cluster classyou created:

```php
use App\Filament\Clusters\SettingsCluster;

protected static ?string $cluster = SettingsCluster::class;

```

## #Code structure recommendations for panels using clusters

When using clusters, it is recommended that you move all of your resources and pages into a directory with the same name as the cluster. For example, here is a directory structure for a panel that uses a cluster calledSettings, containing aColorResourceand two custom pages:

```php
Clusters/
└── Settings/
    ├── SettingsCluster.php
    ├── Pages/
    │   ├── ManageBranding.php
    │   └── ManageNotifications.php
    └── Resources/
        └── Colors/
            ├── ColorResource.php
            └── Pages/
                ├── CreateColor.php
                ├── EditColor.php
                └── ListColors.php

```

This is a recommendation, not a requirement. You can structure your panel however you like, as long as the resources and pages in your cluster use the$clusterproperty. This is just a suggestion to help you keep your panel organized.

When a cluster exists in your panel, and you generate new resources or pages with themake:filament-resourceormake:filament-pagecommands, you will be asked if you want to create them inside a cluster directory, according to these guidelines. If you choose to, then Filament will also assign the correct$clusterproperty to the resource or page class for you. If you do not, you will need todefine the$clusterpropertyyourself.

## #Setting the sub-navigation position for all pages in a cluster

The sub-navigation is rendered at the start of each page by default. It can be customized per-page, but you can also customize it for the entire cluster at once by setting the$subNavigationPositionproperty. The value may beSubNavigationPosition::Start,SubNavigationPosition::End, orSubNavigationPosition::Topto render the sub-navigation as tabs:

```php
use Filament\Pages\Enums\SubNavigationPosition;

protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

```

## #Customizing the cluster breadcrumb

The cluster’s name is in the breadcrumbs of all resources and pages in the cluster.

You may customize the breadcrumb name using the$clusterBreadcrumbproperty in the cluster class:

```php
protected static ?string $clusterBreadcrumb = 'cluster';

```

Alternatively, you may use thegetClusterBreadcrumb()to define a dynamic breadcrumb name:

```php
public static function getClusterBreadcrumb(): string
{
    return __('filament/clusters/cluster.name');
}

```

## #Removing the sub navigation from a cluster

By default, all resources and pages in a cluster will show the sub-navigation. If you want to remove the sub-navigation from all resources and pages in a cluster, you can set the$shouldRegisterSubNavigationproperty tofalsein the cluster class:

```php
protected static bool $shouldRegisterSubNavigation = false;

```

Alternatively, you may override theshouldRegisterSubNavigation()method to define dynamic behavior:

```php
public static function shouldRegisterSubNavigation(): bool
{
    return FeatureFlag::active();
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** panel-configuration  
**Keywords:** menu, sidebar, navigation, routing

*Extracted from Filament v5 Documentation - 2026-01-28*
