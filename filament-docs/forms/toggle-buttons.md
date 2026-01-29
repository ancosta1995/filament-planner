# Toggle buttons

**URL:** https://filamentphp.com/docs/5.x/forms/toggle-buttons  
**Section:** forms  
**Page:** toggle-buttons  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The toggle buttons input provides a group of buttons for selecting a single value, or multiple values, from a list of predefined options:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published'
    ])

```

## #Changing the color of option buttons

You can change thecolorof the option buttons using thecolors()method. Each key in the array should correspond to an option value:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published'
    ])
    ->colors([
        'draft' => 'info',
        'scheduled' => 'warning',
        'published' => 'success',
    ])

```

If you are using an enum for the options, you can use theHasColorinterfaceto define colors instead.

## #Adding icons to option buttons

You can addiconto the option buttons using theicons()method. Each key in the array should correspond to an option value, and the value may be any validicon:

```php
use Filament\Forms\Components\ToggleButtons;
use Filament\Support\Icons\Heroicon;

ToggleButtons::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published'
    ])
    ->icons([
        'draft' => Heroicon::OutlinedPencil,
        'scheduled' => Heroicon::OutlinedClock,
        'published' => Heroicon::OutlinedCheckCircle,
    ])

```

If you are using an enum for the options, you can use theHasIconinterfaceto define icons instead.

If you want to display only icons, you can usehiddenButtonLabels()to hide the option labels.

## #Adding tooltips to option buttons

You can add different tooltips to each option button using thetooltips()method.

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published',
    ])
    ->tooltips([
        'draft' => 'Set as a draft before publishing.',
        'scheduled' => 'Schedule publishing on a specific date.',
        'published' => 'Publish now',
    ])

```

## #Boolean options

If you want a simple boolean toggle button group, with “Yes” and “No” options, you can use theboolean()method:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean()

```

The options will havecolorsandiconsset up automatically, but you can override these withcolors()oricons().

To customize the “Yes” label, you can use thetrueLabelargument on theboolean()method:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean(trueLabel: 'Absolutely!')

```

To customize the “No” label, you can use thefalseLabelargument on theboolean()method:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean(falseLabel: 'Not at all!')

```

## #Positioning the options inline with each other

You may wish to display the buttonsinline()with each other:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean()
    ->inline()

```

Optionally, you may pass a boolean value to control if the buttons should be inline or not:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean()
    ->inline(FeatureFlag::active())

```

## #Grouping option buttons

You may wish to group option buttons together so they are more compact, using thegrouped()method. This also makes them appear horizontally inline with each other:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean()
    ->grouped()

```

Optionally, you may pass a boolean value to control if the buttons should be grouped or not:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('feedback')
    ->label('Like this post?')
    ->boolean()
    ->grouped(FeatureFlag::active())

```

## #Selecting multiple buttons

Themultiple()method on theToggleButtonscomponent allows you to select multiple values from the list of options:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('technologies')
    ->multiple()
    ->options([
        'tailwind' => 'Tailwind CSS',
        'alpine' => 'Alpine.js',
        'laravel' => 'Laravel',
        'livewire' => 'Laravel Livewire',
    ])

```

These options are returned in JSON format. If you’re saving them using Eloquent, you should be sure to add anarraycastto the model property:

```php
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    { 
        return [
            'technologies' => 'array',
        ];
    }

    // ...
}

```

Optionally, you may pass a boolean value to control if the buttons should allow multiple selections or not:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('technologies')
    ->multiple(FeatureFlag::active())
    ->options([
        'tailwind' => 'Tailwind CSS',
        'alpine' => 'Alpine.js',
        'laravel' => 'Laravel',
        'livewire' => 'Laravel Livewire',
    ])

```

## #Splitting options into columns

You may split options into columns by using thecolumns()method:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('technologies')
    ->options([
        // ...
    ])
    ->columns(2)

```

This method accepts the same options as thecolumns()method of thegrid. This allows you to responsively customize the number of columns at various breakpoints.

### #Setting the grid direction

By default, when you arrange buttons into columns, they will be listed in order vertically. If you’d like to list them horizontally, you may use thegridDirection(GridDirection::Row)method:

```php
use Filament\Forms\Components\ToggleButtons;
use Filament\Support\Enums\GridDirection;

ToggleButtons::make('technologies')
    ->options([
        // ...
    ])
    ->columns(2)
    ->gridDirection(GridDirection::Row)

```

## #Disabling specific options

You can disable specific options using thedisableOptionWhen()method. It accepts a closure, in which you can check if the option with a specific$valueshould be disabled:

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published',
    ])
    ->disableOptionWhen(fn (string $value): bool => $value === 'published')

```

If you want to retrieve the options that have not been disabled, e.g. for validation purposes, you can do so usinggetEnabledOptions():

```php
use Filament\Forms\Components\ToggleButtons;

ToggleButtons::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published',
    ])
    ->disableOptionWhen(fn (string $value): bool => $value === 'published')
    ->in(fn (ToggleButtons $component): array => array_keys($component->getEnabledOptions()))

```

For more information about thein()function, please see theValidation documentation.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
