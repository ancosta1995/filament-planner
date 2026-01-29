# Text input column

**URL:** https://filamentphp.com/docs/5.x/tables/columns/text-input  
**Section:** tables  
**Page:** text-input  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

The text input column allows you to render a text input inside the table, which can be used to update that database record without needing to open a new page or a modal:

```php
use Filament\Tables\Columns\TextInputColumn;

TextInputColumn::make('email')

```

## #Validation

You can validate the input by passing anyLaravel validation rulesin an array:

```php
use Filament\Tables\Columns\TextInputColumn;

TextInputColumn::make('name')
    ->rules(['required', 'max:255'])

```

## #Customizing the HTML input type

You may use thetype()method to pass a customHTML input type:

```php
use Filament\Tables\Columns\TextInputColumn;

TextInputColumn::make('background_color')->type('color')

```

## #Lifecycle hooks

Hooks may be used to execute code at various points within the input’s lifecycle:

```php
TextInputColumn::make()
    ->beforeStateUpdated(function ($record, $state) {
        // Runs before the state is saved to the database.
    })
    ->afterStateUpdated(function ($record, $state) {
        // Runs after the state is saved to the database.
    })

```

## #Adding affix text aside the field

You may place text before and after the input using theprefix()andsuffix()methods:

```php
use Filament\Tables\Columns\TextInputColumn;

TextInputColumn::make('domain')
    ->prefix('https://')
    ->suffix('.com')

```

### #Using icons as affixes

You may place aniconbefore and after the input using theprefixIcon()andsuffixIcon()methods:

```php
use Filament\Tables\Columns\TextInputColumn;
use Filament\Support\Icons\Heroicon;

TextInputColumn::make('domain')
    ->prefixIcon(Heroicon::GlobeAlt)
    ->suffixIcon(Heroicon::CheckCircle)

```

#### #Setting the affix icon’s color

Affix icons are gray by default, but you may set a different color using theprefixIconColor()andsuffixIconColor()methods:

```php
use Filament\Tables\Columns\TextInputColumn;
use Filament\Support\Icons\Heroicon;

TextInputColumn::make('status')
    ->suffixIcon(Heroicon::CheckCircle)
    ->suffixIconColor('success')

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
