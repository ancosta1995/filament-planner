# Radio

**URL:** https://filamentphp.com/docs/5.x/forms/radio  
**Section:** forms  
**Page:** radio  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The radio input provides a radio button group for selecting a single value from a list of predefined options:

```php
use Filament\Forms\Components\Radio;

Radio::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published'
    ])

```

## #Setting option descriptions

You can optionally provide descriptions to each option using thedescriptions()method:

```php
use Filament\Forms\Components\Radio;

Radio::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published'
    ])
    ->descriptions([
        'draft' => 'Is not visible.',
        'scheduled' => 'Will be visible.',
        'published' => 'Is visible.'
    ])

```

NOTE

Be sure to use the samekeyin the descriptions array as thekeyin the option array so the right description matches the right option.

## #Positioning the options inline with each other

You may wish to display the optionsinline()with each other:

```php
use Filament\Forms\Components\Radio;

Radio::make('feedback')
    ->label('Like this post?')
    ->boolean()
    ->inline()

```

Optionally, you may pass a boolean value to control if the options should be inline or not:

```php
use Filament\Forms\Components\Radio;

Radio::make('feedback')
    ->label('Like this post?')
    ->boolean()
    ->inline(FeatureFlag::active())

```

## #Disabling specific options

You can disable specific options using thedisableOptionWhen()method. It accepts a closure, in which you can check if the option with a specific$valueshould be disabled:

```php
use Filament\Forms\Components\Radio;

Radio::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published',
    ])
    ->disableOptionWhen(fn (string $value): bool => $value === 'published')

```

If you want to retrieve the options that have not been disabled, e.g. for validation purposes, you can do so usinggetEnabledOptions():

```php
use Filament\Forms\Components\Radio;

Radio::make('status')
    ->options([
        'draft' => 'Draft',
        'scheduled' => 'Scheduled',
        'published' => 'Published',
    ])
    ->disableOptionWhen(fn (string $value): bool => $value === 'published')
    ->in(fn (Radio $component): array => array_keys($component->getEnabledOptions()))

```

For more information about thein()function, please see theValidation documentation.

## #Boolean options

If you want a simple boolean radio button group, with “Yes” and “No” options, you can use theboolean()method:

```php
use Filament\Forms\Components\Radio;

Radio::make('feedback')
    ->label('Like this post?')
    ->boolean()

```

To customize the “Yes” label, you can use thetrueLabelargument on theboolean()method:

```php
use Filament\Forms\Components\Radio;

Radio::make('feedback')
    ->label('Like this post?')
    ->boolean(trueLabel: 'Absolutely!')

```

To customize the “No” label, you can use thefalseLabelargument on theboolean()method:

```php
use Filament\Forms\Components\Radio;

Radio::make('feedback')
    ->label('Like this post?')
    ->boolean(falseLabel: 'Not at all!')

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
