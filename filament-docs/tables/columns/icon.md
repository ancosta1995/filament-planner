# Icon column

**URL:** https://filamentphp.com/docs/5.x/tables/columns/icon  
**Section:** tables  
**Page:** icon  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

Icon columns render aniconrepresenting the state of the column:

```php
use Filament\Tables\Columns\IconColumn;
use Filament\Support\Icons\Heroicon;

IconColumn::make('status')
    ->icon(fn (string $state): Heroicon => match ($state) {
        'draft' => Heroicon::OutlinedPencil,
        'reviewing' => Heroicon::OutlinedClock,
        'published' => Heroicon::OutlinedCheckCircle,
    })

```

## #Customizing the color

You may change thecolorof the icon, using thecolor()method:

```php
use Filament\Tables\Columns\IconColumn;

IconColumn::make('status')
    ->color('success')

```

By passing a function tocolor(), you can customize the color based on the state of the column:

```php
use Filament\Tables\Columns\IconColumn;

IconColumn::make('status')
    ->color(fn (string $state): string => match ($state) {
        'draft' => 'info',
        'reviewing' => 'warning',
        'published' => 'success',
        default => 'gray',
    })

```

## #Customizing the size

The default icon size isIconSize::Large, but you may customize the size to be eitherIconSize::ExtraSmall,IconSize::Small,IconSize::Medium,IconSize::ExtraLargeorIconSize::TwoExtraLarge:

```php
use Filament\Tables\Columns\IconColumn;
use Filament\Support\Enums\IconSize;

IconColumn::make('status')
    ->size(IconSize::Medium)

```

## #Handling booleans

Icon columns can display a check or “X” icon based on the state of the column, either true or false, using theboolean()method:

```php
use Filament\Tables\Columns\IconColumn;

IconColumn::make('is_featured')
    ->boolean()

```
> If this attribute in the model class is already cast as aboolorboolean, Filament is able to detect this, and you do not need to useboolean()manually.


If this attribute in the model class is already cast as aboolorboolean, Filament is able to detect this, and you do not need to useboolean()manually.

Optionally, you may pass a boolean value to control if the icon should be boolean or not:

```php
use Filament\Tables\Columns\IconColumn;

IconColumn::make('is_featured')
    ->boolean(FeatureFlag::active())

```

### #Customizing the boolean icons

You may customize theiconrepresenting each state:

```php
use Filament\Tables\Columns\IconColumn;
use Filament\Support\Icons\Heroicon;

IconColumn::make('is_featured')
    ->boolean()
    ->trueIcon(Heroicon::OutlinedCheckBadge)
    ->falseIcon(Heroicon::OutlinedXMark)

```

### #Customizing the boolean colors

You may customize the iconcolorrepresenting each state:

```php
use Filament\Tables\Columns\IconColumn;

IconColumn::make('is_featured')
    ->boolean()
    ->trueColor('info')
    ->falseColor('warning')

```

## #Wrapping multiple icons

When displaying multiple icons, they can be set to wrap if they can’t fit on one line, usingwrap():

```php
use Filament\Tables\Columns\IconColumn;

IconColumn::make('icon')
    ->wrap()

```

TIP

The “width” for wrapping is affected by the column label, so you may need to use a shorter or hidden label to wrap more tightly.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
