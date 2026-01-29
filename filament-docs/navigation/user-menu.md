# User menu

**URL:** https://filamentphp.com/docs/5.x/navigation/user-menu  
**Section:** navigation  
**Page:** user-menu  
**Priority:** medium  
**AI Context:** Configure panel navigation and menu structure.

---

## #Introduction

The user menu is featured in the top right corner of the admin layout. It’s fully customizable.

Each menu item is represented by anaction, and can be customized in the same way. To register new items, you can pass the actions to theuserMenuItems()method of theconfiguration:

```php
use App\Filament\Pages\Settings;
use Filament\Actions\Action;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->userMenuItems([
            Action::make('settings')
                ->url(fn (): string => Settings::getUrl())
                ->icon('heroicon-o-cog-6-tooth'),
            // ...
        ]);
}

```

## #Moving the user menu to the sidebar

By default, the user menu is positioned in the topbar. If the topbar is disabled, it is added to the sidebar.

You can choose to always move it to the sidebar by passing apositionargument to theuserMenu()method in theconfiguration:

```php
use Filament\Enums\UserMenuPosition;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->userMenu(position: UserMenuPosition::Sidebar);
}

```

## #Customizing the profile link

To customize the user profile link at the start of the user menu, register a new item with theprofilearray key, and pass a function thatcustomizes the actionobject:

```php
use Filament\Actions\Action;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->userMenuItems([
            'profile' => fn (Action $action) => $action->label('Edit profile'),
            // ...
        ]);
}

```

For more information on creating a profile page, check out theauthentication features documentation.

## #Customizing the logout link

To customize the user logout link at the end of the user menu, register a new item with thelogoutarray key, and pass a function thatcustomizes the actionobject:

```php
use Filament\Actions\Action;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->userMenuItems([
            'logout' => fn (Action $action) => $action->label('Log out'),
            // ...
        ]);
}

```

## #Conditionally hiding user menu items

You can also conditionally hide a user menu item by using thevisible()orhidden()methods, passing in a condition to check. Passing a function will defer condition evaluation until the menu is actually being rendered:

```php
use App\Models\Payment;
use Filament\Actions\Action;

Action::make('payments')
    ->visible(fn (): bool => auth()->user()->can('viewAny', Payment::class))
    // or
    ->hidden(fn (): bool => ! auth()->user()->can('viewAny', Payment::class))

```

## #Sending aPOSTHTTP request from a user menu item

You can send aPOSTHTTP request from a user menu item by passing a URL to theurl()method, and also usingpostToUrl():

```php
use Filament\Actions\Action;

Action::make('lockSession')
    ->url(fn (): string => route('lock-session'))
    ->postToUrl()

```

## #Disabling the user menu

You may disable the user menu entirely by passingfalseto theuserMenu()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->userMenu(false);
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** panel-configuration  
**Keywords:** menu, sidebar, navigation, routing

*Extracted from Filament v5 Documentation - 2026-01-28*
