# Color entry

**URL:** https://filamentphp.com/docs/5.x/infolists/color-entry  
**Section:** infolists  
**Page:** color-entry  
**Priority:** medium  
**AI Context:** Display read-only data in structured layouts.

---

## #Introduction

The color entry allows you to show the color preview from a CSS color definition, typically entered using thecolor picker field, in one of the supported formats (HEX, HSL, RGB, RGBA).

```php
use Filament\Infolists\Components\ColorEntry;

ColorEntry::make('color')

```

## #Allowing the color to be copied to the clipboard

You may make the color copyable, such that clicking on the preview copies the CSS value to the clipboard, and optionally specify a custom confirmation message and duration in milliseconds. This feature only works when SSL is enabled for the app.

```php
use Filament\Infolists\Components\ColorEntry;

ColorEntry::make('color')
    ->copyable()
    ->copyMessage('Copied!')
    ->copyMessageDuration(1500)

```

Optionally, you may pass a boolean value to control if the color should be copyable or not:

```php
use Filament\Infolists\Components\ColorEntry;

ColorEntry::make('color')
    ->copyable(FeatureFlag::active())

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources  
**Keywords:** read-only, display, view, presentation

*Extracted from Filament v5 Documentation - 2026-01-28*
