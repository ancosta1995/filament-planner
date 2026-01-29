# Tabs

**URL:** https://filamentphp.com/docs/5.x/schemas/tabs  
**Section:** schemas  
**Page:** tabs  
**Priority:** medium  
**AI Context:** Layout system for building complex UIs with sections, tabs, wizards.

---

## #Introduction

Some schemas can be long and complex. You may want to use tabs to reduce the number of components that are visible at once:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Tab 1')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 2')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 3')
            ->schema([
                // ...
            ]),
    ])

```

## #Setting the default active tab

The first tab will be open by default. You can change the default open tab using theactiveTab()method:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Tab 1')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 2')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 3')
            ->schema([
                // ...
            ]),
    ])
    ->activeTab(2)

```

## #Setting a tab icon

Tabs may have anicon, which you can set using theicon()method:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Notifications')
            ->icon(Heroicon::Bell)
            ->schema([
                // ...
            ]),
        // ...
    ])

```

### #Setting the tab icon position

The icon of the tab may be positioned before or after the label using theiconPosition()method:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Notifications')
            ->icon(Heroicon::Bell)
            ->iconPosition(IconPosition::After)
            ->schema([
                // ...
            ]),
        // ...
    ])

```

## #Setting a tab badge

Tabs may have a badge, which you can set using thebadge()method:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Notifications')
            ->badge(5)
            ->schema([
                // ...
            ]),
        // ...
    ])

```

If you’d like to change thecolorfor a badge, you can use thebadgeColor()method:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Notifications')
            ->badge(5)
            ->badgeColor('info')
            ->schema([
                // ...
            ]),
        // ...
    ])

```

## #Using grid columns within a tab

You may use thecolumns()method to customize thegridwithin the tab:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Tab 1')
            ->schema([
                // ...
            ])
            ->columns(3),
        // ...
    ])

```

## #Disabling scrollable tabs

Tabs are rendered horizontally by default, and are scrollable when they exceed the available width.

You may disable scrolling using thescrollable(false)method:

```php
use Filament\Schemas\Components\Tabs;

Tabs::make('Tabs')
    ->tabs([
        // ...
    ])
    ->scrollable(false)

```

When tabs are not scrollable, the component automatically detects the available width. If not all tabs can fit, a dropdown button will appear. Any tabs that exceed the available width will be grouped inside this dropdown automatically.

## #Using vertical tabs

You can render the tabs vertically by using thevertical()method:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Tab 1')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 2')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 3')
            ->schema([
                // ...
            ]),
    ])
    ->vertical()

```

Optionally, you can pass a boolean value to thevertical()method to control if the tabs should be rendered vertically or not:

```php
use Filament\Schemas\Components\Tabs;

Tabs::make('Tabs')
    ->tabs([
        // ...
    ])
    ->vertical(FeatureFlag::active())

```

## #Removing the styled container

By default, tabs and their content are wrapped in a container styled as a card. You may remove the styled container usingcontained():

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Tab 1')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 2')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 3')
            ->schema([
                // ...
            ]),
    ])
    ->contained(false)

```

## #Persisting the current tab in the user’s session

By default, the current tab is not persisted in the browser’s local storage. You can change this behavior using thepersistTab()method. You must also pass in a uniqueid()for the tabs component, to distinguish it from all other sets of tabs in the app. This ID will be used as the key in the local storage to store the current tab:

```php
use Filament\Schemas\Components\Tabs;

Tabs::make('Tabs')
    ->tabs([
        // ...
    ])
    ->persistTab()
    ->id('order-tabs')

```

Optionally, thepersistTab()method accepts a boolean value to control if the active tab should persist or not:

```php
use Filament\Schemas\Components\Tabs;

Tabs::make('Tabs')
    ->tabs([
        // ...
    ])
    ->persistTab(FeatureFlag::active())
    ->id('order-tabs')

```

### #Persisting the current tab in the URL’s query string

By default, the current tab is not persisted in the URL’s query string. You can change this behavior using thepersistTabInQueryString()method:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Tab 1')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 2')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 3')
            ->schema([
                // ...
            ]),
    ])
    ->persistTabInQueryString()

```

When enabled, the current tab is persisted in the URL’s query string using thetabkey. You can change this key by passing it to thepersistTabInQueryString()method:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

Tabs::make('Tabs')
    ->tabs([
        Tab::make('Tab 1')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 2')
            ->schema([
                // ...
            ]),
        Tab::make('Tab 3')
            ->schema([
                // ...
            ]),
    ])
    ->persistTabInQueryString('settings-tab')

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, infolists  
**Keywords:** layout, structure, organization, ui

*Extracted from Filament v5 Documentation - 2026-01-28*
