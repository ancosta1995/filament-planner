# Grouping actions

**URL:** https://filamentphp.com/docs/5.x/actions/grouping-actions  
**Section:** actions  
**Page:** grouping-actions  
**Priority:** high  
**AI Context:** Interactive buttons with modals for user actions.

---

## #Introduction

You may group actions together into a dropdown menu by using anActionGroupobject. Groups may contain many actions, or other groups:

```php
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;

ActionGroup::make([
    Action::make('view'),
    Action::make('edit'),
    Action::make('delete'),
])

```

This page is about customizing the look of the group’s trigger button and dropdown.

## #Customizing the group trigger style

The button which opens the dropdown may be customized in the same way as a normal action.All the methods available for trigger buttonsmay be used to customize the group trigger button:

```php
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\Size;

ActionGroup::make([
    // Array of actions
])
    ->label('More actions')
    ->icon('heroicon-m-ellipsis-vertical')
    ->size(Size::Small)
    ->color('primary')
    ->button()

```

### #Using a grouped button design

Instead of a dropdown, an action group can render itself as a group of buttons. This design works with and without button labels. To use this feature, use thebuttonGroup()method:

```php
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Support\Icons\Heroicon;

ActionGroup::make([
    Action::make('edit')
        ->color('gray')
        ->icon(Heroicon::PencilSquare)
        ->hiddenLabel(),
    Action::make('delete')
        ->color('gray')
        ->icon(Heroicon::Trash)
        ->hiddenLabel(),
])
    ->buttonGroup()

```

## #Setting the placement of the dropdown

The dropdown may be positioned relative to the trigger button by using thedropdownPlacement()method:

```php
use Filament\Actions\ActionGroup;

ActionGroup::make([
    // Array of actions
])
    ->dropdownPlacement('top-start')

```

Alternatively, you may let the dropdown position be automatically determined based on the available space using thedropdownAutoPlacement()method:

```php
use Filament\Actions\ActionGroup;

ActionGroup::make([
    // Array of actions
])
    ->dropdownAutoPlacement()

```

## #Adding dividers between actions

You may add dividers between groups of actions by using nestedActionGroupobjects:

```php
use Filament\Actions\ActionGroup;

ActionGroup::make([
    ActionGroup::make([
        // Array of actions
    ])->dropdown(false),
    // Array of actions
])

```

Thedropdown(false)method puts the actions inside the parent dropdown, instead of a new nested dropdown.

## #Setting the width of the dropdown

The dropdown may be set to a width by using thedropdownWidth()method. Options correspond toTailwind’s max-width scale. The options areExtraSmall,Small,Medium,Large,ExtraLarge,TwoExtraLarge,ThreeExtraLarge,FourExtraLarge,FiveExtraLarge,SixExtraLargeandSevenExtraLarge:

```php
use Filament\Actions\ActionGroup;
use Filament\Support\Enums\Width;

ActionGroup::make([
    // Array of actions
])
    ->dropdownWidth(Width::ExtraSmall)

```

## #Controlling the dropdown offset

You may control the offset of the dropdown using thedropdownOffset()method, by default the offset is set to8.

```php
use Filament\Actions\ActionGroup;

ActionGroup::make([
    // Array of actions
])
    ->dropdownOffset(16)

```

## #Controlling the maximum height of the dropdown

The dropdown content can have a maximum height using themaxHeight()method, so that it scrolls. You can pass aCSS length:

```php
use Filament\Actions\ActionGroup;

ActionGroup::make([
    // Array of actions
])
    ->maxHeight('400px')

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, tables, forms  
**Keywords:** buttons, modals, interactions, crud operations

*Extracted from Filament v5 Documentation - 2026-01-28*
