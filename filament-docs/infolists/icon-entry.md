# Icon entry

**URL:** https://filamentphp.com/docs/5.x/infolists/icon-entry  
**Section:** infolists  
**Page:** icon-entry  
**Priority:** medium  
**AI Context:** Display read-only data in structured layouts.

---

## #Introduction

Icon entries render aniconrepresenting the state of the entry:

```php
use Filament\Infolists\Components\IconEntry;
use Filament\Support\Icons\Heroicon;

IconEntry::make('status')
    ->icon(fn (string $state): Heroicon => match ($state) {
        'draft' => Heroicon::OutlinedPencil,
        'reviewing' => Heroicon::OutlinedClock,
        'published' => Heroicon::OutlinedCheckCircle,
    })

```

## #Customizing the color

You may change thecolorof the icon, using thecolor()method:

```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('status')
    ->color('success')

```

By passing a function tocolor(), you can customize the color based on the state of the entry:

```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('status')
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
use Filament\Infolists\Components\IconEntry;
use Filament\Support\Enums\IconSize;

IconEntry::make('status')
    ->size(IconSize::Medium)

```

## #Handling booleans

Icon entries can display a check or “X” icon based on the state of the entry, either true or false, using theboolean()method:

```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('is_featured')
    ->boolean()

```
> If this attribute in the model class is already cast as aboolorboolean, Filament is able to detect this, and you do not need to useboolean()manually.


If this attribute in the model class is already cast as aboolorboolean, Filament is able to detect this, and you do not need to useboolean()manually.

Optionally, you may pass a boolean value to control if the icon should be boolean or not:

```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('is_featured')
    ->boolean(FeatureFlag::active())

```

### #Customizing the boolean icons

You may customize theiconrepresenting each state:

```php
use Filament\Infolists\Components\IconEntry;
use Filament\Support\Icons\Heroicon;

IconEntry::make('is_featured')
    ->boolean()
    ->trueIcon(Heroicon::OutlinedCheckBadge)
    ->falseIcon(Heroicon::OutlinedXMark)

```

### #Customizing the boolean colors

You may customize the iconcolorrepresenting each state:

```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('is_featured')
    ->boolean()
    ->trueColor('info')
    ->falseColor('warning')

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources  
**Keywords:** read-only, display, view, presentation

*Extracted from Filament v5 Documentation - 2026-01-28*
