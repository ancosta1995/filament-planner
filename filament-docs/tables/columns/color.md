# Color column

**URL:** https://filamentphp.com/docs/5.x/tables/columns/color  
**Section:** tables  
**Page:** color  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

The color column allows you to show the color preview from a CSS color definition, typically entered using thecolor picker field, in one of the supported formats (HEX, HSL, RGB, RGBA).

```php
use Filament\Tables\Columns\ColorColumn;

ColorColumn::make('color')

```

## #Allowing the color to be copied to the clipboard

You may make the color copyable, such that clicking on the preview copies the CSS value to the clipboard, and optionally specify a custom confirmation message and duration in milliseconds. This feature only works when SSL is enabled for the app.

```php
use Filament\Tables\Columns\ColorColumn;

ColorColumn::make('color')
    ->copyable()
    ->copyMessage('Copied!')
    ->copyMessageDuration(1500)

```

Optionally, you may pass a boolean value to control if the text should be copyable or not:

```php
use Filament\Tables\Columns\ColorColumn;

ColorColumn::make('color')
    ->copyable(FeatureFlag::active())

```

## #Wrapping multiple color blocks

Color blocks can be set to wrap if they can’t fit on one line, by settingwrap():

```php
use Filament\Tables\Columns\ColorColumn;

ColorColumn::make('color')
    ->wrap()

```

TIP

The “width” for wrapping is affected by the column label, so you may need to use a shorter or hidden label to wrap more tightly.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
