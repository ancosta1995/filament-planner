# Empty states

**URL:** https://filamentphp.com/docs/5.x/schemas/empty-states  
**Section:** schemas  
**Page:** empty-states  
**Priority:** medium  
**AI Context:** Layout system for building complex UIs with sections, tabs, wizards.

---

## #Introduction

You can display an empty state in your schema to communicate that there is no content to show yet, and to guide the user towards the next action. An empty state requires a heading, but can also have adescription(),icon()andfooter():

```php
use Filament\Actions\Action;
use Filament\Schemas\Components\EmptyState;
use Filament\Support\Icons\Heroicon;

EmptyState::make('No users yet')
    ->description('Get started by creating a new user.')
    ->icon(Heroicon::OutlinedUser)
    ->footer([
        Action::make('createUser')
            ->icon(Heroicon::Plus),
    ])

```

## #Adding an icon to the empty state

You may add aniconto the empty state using theicon()method:

```php
use Filament\Schemas\Components\EmptyState;
use Filament\Support\Icons\Heroicon;

EmptyState::make('No users yet')
    ->description('Get started by creating a new user.')
    ->icon(Heroicon::OutlinedUser)

```

## #Inserting actions and other components in the footer of an empty state

You may insertactionsand any other schema component (usuallyprime components) into the footer of an empty state by passing an array of components to thefooter()method:

```php
use Filament\Actions\Action;
use Filament\Schemas\Components\EmptyState;

EmptyState::make('No users yet')
    ->description('Get started by creating a new user.')
    ->footer([
        Action::make('createUser')
            ->icon(Heroicon::Plus),
    ])

```

## #Removing the empty state container

By default, empty states have a background color, shadow and border. You may remove these styles and just render the content of the empty state without the container usingcontained(false):

```php
use Filament\Schemas\Components\EmptyState;

EmptyState::make('No users yet')
    ->description('Get started by creating a new user.')
    ->contained(false)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, infolists  
**Keywords:** layout, structure, organization, ui

*Extracted from Filament v5 Documentation - 2026-01-28*
