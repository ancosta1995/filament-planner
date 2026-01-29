# Overview

**URL:** https://filamentphp.com/docs/5.x/navigation/overview  
**Section:** navigation  
**Page:** overview  
**Priority:** medium  
**AI Context:** Configure panel navigation and menu structure.

---

## #Introduction

By default, Filament will register navigation items for each of yourresources,custom pages, andclusters. These classes contain static properties and methods that you can override, to configure that navigation item.

If you’re looking to add a second layer of navigation to your app, you can useclusters. These are useful for grouping resources and pages together.

## #Customizing a navigation item’s label

By default, the navigation label is generated from the resource or page’s name. You may customize this using the$navigationLabelproperty:

```php
protected static ?string $navigationLabel = 'Custom Navigation Label';

```

Alternatively, you may override thegetNavigationLabel()method:

```php
public static function getNavigationLabel(): string
{
    return 'Custom Navigation Label';
}

```

## #Customizing a navigation item’s icon

To customize a navigation item’sicon, you may override the$navigationIconproperty on theresourceorpageclass:

```php
use BackedEnum;

protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

```

If you set$navigationIcon = nullon all items within the same navigation group, those items will be joined with a vertical bar below the group label.

### #Switching navigation item icon when it is active

You may assign a navigationiconwhich will only be used for active items using the$activeNavigationIconproperty:

```php
use BackedEnum;

protected static string | BackedEnum | null $activeNavigationIcon = 'heroicon-o-document-text';

```

## #Sorting navigation items

By default, navigation items are sorted alphabetically. You may customize this using the$navigationSortproperty:

```php
protected static ?int $navigationSort = 3;

```

Now, navigation items with a lower sort value will appear before those with a higher sort value - the order is ascending.

## #Adding a badge to a navigation item

To add a badge next to the navigation item, you can use thegetNavigationBadge()method and return the content of the badge:

```php
public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

```

If a badge value is returned bygetNavigationBadge(), it will display using the primary color by default. To style the badge contextually, return eitherdanger,gray,info,primary,successorwarningfrom thegetNavigationBadgeColor()method:

```php
public static function getNavigationBadgeColor(): ?string
{
    return static::getModel()::count() > 10 ? 'warning' : 'primary';
}

```

A custom tooltip for the navigation badge can be set in$navigationBadgeTooltip:

```php
protected static ?string $navigationBadgeTooltip = 'The number of users';

```

Or it can be returned fromgetNavigationBadgeTooltip():

```php
public static function getNavigationBadgeTooltip(): ?string
{
    return 'The number of users';
}

```

## #Grouping navigation items

You may group navigation items by specifying a$navigationGroupproperty on aresourceandcustom page:

```php
use UnitEnum;

protected static string | UnitEnum | null $navigationGroup = 'Settings';

```

All items in the same navigation group will be displayed together under the same group label, “Settings” in this case. Ungrouped items will remain at the start of the navigation.

### #Grouping navigation items under other items

You may group navigation items as children of other items, by passing the label of the parent item as the$navigationParentItem:

```php
use UnitEnum;

protected static ?string $navigationParentItem = 'Notifications';

protected static string | UnitEnum | null $navigationGroup = 'Settings';

```

You may also use thegetNavigationParentItem()method to set a dynamic parent item label:

```php
public static function getNavigationParentItem(): ?string
{
    return __('filament/navigation.groups.settings.items.notifications');
}

```

As seen above, if the parent item has a navigation group, that navigation group must also be defined, so the correct parent item can be identified.

TIP

If you’re reaching for a third level of navigation like this, you should consider usingclustersinstead, which are a logical grouping of resources and custom pages, which can share their own separate navigation.

### #Customizing navigation groups

You may customize navigation groups by callingnavigationGroups()in theconfiguration, and passingNavigationGroupobjects in order:

```php
use Filament\Navigation\NavigationGroup;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->navigationGroups([
            NavigationGroup::make()
                 ->label('Shop')
                 ->icon('heroicon-o-shopping-cart'),
            NavigationGroup::make()
                ->label('Blog')
                ->icon('heroicon-o-pencil'),
            NavigationGroup::make()
                ->label(fn (): string => __('navigation.settings'))
                ->icon('heroicon-o-cog-6-tooth')
                ->collapsed(),
        ]);
}

```

In this example, we pass in a customicon()for the groups, and make onecollapsed()by default.

#### #Ordering navigation groups

By usingnavigationGroups(), you are defining a new order for the navigation groups. If you just want to reorder the groups and not define an entireNavigationGroupobject, you may just pass the labels of the groups in the new order:

```php
$panel
    ->navigationGroups([
        'Shop',
        'Blog',
        'Settings',
    ])

```

#### #Making navigation groups not collapsible

By default, navigation groups are collapsible.

You may disable this behavior by callingcollapsible(false)on theNavigationGroupobject:

```php
use Filament\Navigation\NavigationGroup;

NavigationGroup::make()
    ->label('Settings')
    ->icon('heroicon-o-cog-6-tooth')
    ->collapsible(false);

```

Or, you can do it globally for all groups in theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->collapsibleNavigationGroups(false);
}

```

#### #Adding extra HTML attributes to navigation groups

You can pass extra HTML attributes to the navigation group, which will be merged onto the outer DOM element. Pass an array of attributes to theextraSidebarAttributes()orextraTopbarAttributes()method, where the key is the attribute name and the value is the attribute value:

```php
NavigationGroup::make()
    ->extraSidebarAttributes(['class' => 'featured-sidebar-group']),
    ->extraTopbarAttributes(['class' => 'featured-topbar-group']),

```

TheextraSidebarAttributes()will be applied to navigation group elements contained in the sidebar, and theextraTopbarAttributes()will only be applied to topbar navigation group dropdowns when usingtop navigation.

### #Registering navigation groups with an enum

You can use an enum class to register navigation groups, which allows you to control their labels, icons, and order in a single place, without needing to register them in theconfiguration.

To do this, you can create an enum class with cases for each group:

```php
enum NavigationGroup
{
    case Shop;
    
    case Blog;
    
    case Settings;
}

```

The order that the cases are defined in will control the order of the navigation groups.

To use an enum navigation group for a resource or custom page, you can set the$navigationGroupproperty to the enum case:

```php
protected static string | UnitEnum | null $navigationGroup = NavigationGroup::Shop;

```

You can also implement theHasLabelinterface on the enum class, to define a custom label for each group:

```php
use Filament\Support\Contracts\HasLabel;

enum NavigationGroup implements HasLabel
{
    case Shop;
    
    case Blog;
    
    case Settings;

    public function getLabel(): string
    {
        return match ($this) {
            self::Shop => __('navigation-groups.shop'),
            self::Blog => __('navigation-groups.blog'),
            self::Settings => __('navigation-groups.settings'),
        };
    }
}

```

You can also implement theHasIconinterface on the enum class, to define a custom icon for each group:

```php
use Filament\Support\Contracts\HasIcon;

enum NavigationGroup implements HasIcon
{
    case Shop;
    
    case Blog;
    
    case Settings;

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Shop => 'heroicon-o-shopping-cart',
            self::Blog => 'heroicon-o-pencil',
            self::Settings => 'heroicon-o-cog-6-tooth',
        };
    }
}

```

## #Collapsible sidebar on desktop

To make the sidebar collapsible on desktop as well as mobile, you can use theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->sidebarCollapsibleOnDesktop();
}

```

By default, when you collapse the sidebar on desktop, the navigation icons still show. You can fully collapse the sidebar using thesidebarFullyCollapsibleOnDesktop()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->sidebarFullyCollapsibleOnDesktop();
}

```

### #Navigation groups in a collapsible sidebar on desktop

NOTE

This section only applies tosidebarCollapsibleOnDesktop(), notsidebarFullyCollapsibleOnDesktop(), since the fully collapsible UI just hides the entire sidebar instead of changing its design.

When using a collapsible sidebar on desktop, you will also often be usingnavigation groups. By default, the labels of each navigation group will be hidden when the sidebar is collapsed, since there is no space to display them. Even if the navigation group itself iscollapsible, all items will still be visible in the collapsed sidebar, since there is no group label to click on to expand the group.

These issues can be solved, to achieve a very minimal sidebar design, bypassing anicon()to the navigation group objects. When an icon is defined, the icon will be displayed in the collapsed sidebar instead of the items at all times. When the icon is clicked, a dropdown will open to the side of the icon, revealing the items in the group.

When passing an icon to a navigation group, even if the items also have icons, the expanded sidebar UI will not show the item icons. This is to keep the navigation hierarchy clear, and the design minimal. However, the icons for the items will be shown in the collapsed sidebar’s dropdowns though, since the hierarchy is already clear from the fact that the dropdown is open.

## #Registering custom navigation items

To register new navigation items, you can use theconfiguration:

```php
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use function Filament\Support\original_request;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->navigationItems([
            NavigationItem::make('Analytics')
                ->url('https://filament.pirsch.io', shouldOpenInNewTab: true)
                ->icon('heroicon-o-presentation-chart-line')
                ->group('Reports')
                ->sort(3),
            NavigationItem::make('dashboard')
                ->label(fn (): string => __('filament-panels::pages/dashboard.title'))
                ->url(fn (): string => Dashboard::getUrl())
                ->isActiveWhen(fn () => original_request()->routeIs('filament.admin.pages.dashboard')),
            // ...
        ]);
}

```

## #Conditionally hiding navigation items

You can also conditionally hide a navigation item by using thevisible()orhidden()methods, passing in a condition to check:

```php
use Filament\Navigation\NavigationItem;

NavigationItem::make('Analytics')
    ->visible(fn(): bool => auth()->user()->can('view-analytics'))
    // or
    ->hidden(fn(): bool => ! auth()->user()->can('view-analytics')),

```

## #Disabling resource or page navigation items

To prevent resources or pages from showing up in navigation, you may use:

```php
protected static bool $shouldRegisterNavigation = false;

```

Or, you may override theshouldRegisterNavigation()method:

```php
public static function shouldRegisterNavigation(): bool
{
    return false;
}

```

Please note that these methods do not control direct access to the resource or page. They only control whether the resource or page will show up in the navigation. If you want to also control access, then you should useresource authorizationorpage authorization.

## #Using top navigation

By default, Filament will use a sidebar navigation. You may use a top navigation instead by using theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->topNavigation();
}

```

## #Customizing the width of the sidebar

You can customize the width of the sidebar by passing it to thesidebarWidth()method in theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->sidebarWidth('40rem');
}

```

Additionally, if you are using thesidebarCollapsibleOnDesktop()method, you can customize width of the collapsed icons by using thecollapsedSidebarWidth()method in theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->sidebarCollapsibleOnDesktop()
        ->collapsedSidebarWidth('9rem');
}

```

## #Advanced navigation customization

Thenavigation()method can be called from theconfiguration. It allows you to build a custom navigation that overrides Filament’s automatically generated items. This API is designed to give you complete control over the navigation.

### #Registering custom navigation items

To register navigation items, call theitems()method:

```php
use App\Filament\Pages\Settings;
use App\Filament\Resources\Users\UserResource;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use function Filament\Support\original_request;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            return $builder->items([
                NavigationItem::make('Dashboard')
                    ->icon('heroicon-o-home')
                    ->isActiveWhen(fn (): bool => original_request()->routeIs('filament.admin.pages.dashboard'))
                    ->url(fn (): string => Dashboard::getUrl()),
                ...UserResource::getNavigationItems(),
                ...Settings::getNavigationItems(),
            ]);
        });
}

```

### #Registering custom navigation groups

If you want to register groups, you can call thegroups()method:

```php
use App\Filament\Pages\HomePageSettings;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Pages\PageResource;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
            return $builder->groups([
                NavigationGroup::make('Website')
                    ->items([
                        ...PageResource::getNavigationItems(),
                        ...CategoryResource::getNavigationItems(),
                        ...HomePageSettings::getNavigationItems(),
                    ]),
            ]);
        });
}

```

### #Disabling navigation

You may disable navigation entirely by passingfalseto thenavigation()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->navigation(false);
}

```

### #Disabling the topbar

You may disable topbar entirely by passingfalseto thetopbar()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->topbar(false);
}

```

### #Replacing the sidebar and topbar Livewire components

You may completely replace the Livewire components that are used to render the sidebar and topbar, passing your own Livewire component class name into thesidebarLivewireComponent()ortopbarLivewireComponent()method:

```php
use App\Livewire\Sidebar;
use App\Livewire\Topbar;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->sidebarLivewireComponent(Sidebar::class)
        ->topbarLivewireComponent(Topbar::class);
}

```

## #Disabling breadcrumbs

The default layout will show breadcrumbs to indicate the location of the current page within the hierarchy of the app.

You may disable breadcrumbs in yourconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->breadcrumbs(false);
}

```

## #Reloading the sidebar and topbar

Once a page in the panel is loaded, the sidebar and topbar are not reloaded until you navigate away from the page, or until a menu item is clicked to trigger an action. You can manually reload these components to update them by dispatching arefresh-sidebarorrefresh-topbarbrowser event.

To dispatch an event from PHP, you can call the$this->dispatch()method from any Livewire component, such as a page class, relation manager class, or widget class:

```php
$this->dispatch('refresh-sidebar');

```

When your code does not live inside a Livewire component, such as when you have a custom action class, you can inject the$livewireargument into a closure function, and calldispatch()on that:

```php
use Filament\Actions\Action;
use Livewire\Component;

Action::make('create')
    ->action(function (Component $livewire) {
        // ...
    
        $livewire->dispatch('refresh-sidebar');
    })

```

Alternatively, you can dispatch an event from JavaScript using the$dispatch()Alpine.js helper method, or the native browserwindow.dispatchEvent()method:

```php
<button x-on:click="$dispatch('refresh-sidebar')" type="button">
    Refresh Sidebar
</button>

```

```php
window.dispatchEvent(new CustomEvent('refresh-sidebar'));

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** panel-configuration  
**Keywords:** menu, sidebar, navigation, routing

*Extracted from Filament v5 Documentation - 2026-01-28*
