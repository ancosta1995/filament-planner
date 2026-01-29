# Checkbox

**URL:** https://filamentphp.com/docs/5.x/forms/checkbox  
**Section:** forms  
**Page:** checkbox  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The checkbox component, similar to atoggle, allows you to interact a boolean value.

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('is_admin')

```

If you’re saving the boolean value using Eloquent, you should be sure to add abooleancastto the model property:

```php
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
        ];
    }

    // ...
}

```

## #Positioning the label above

Checkbox fields have two layout modes, inline and stacked. By default, they are inline.

When the checkbox is inline, its label is adjacent to it:

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('is_admin')
    ->inline()

```

When the checkbox is stacked, its label is above it:

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('is_admin')
    ->inline(false)

```

## #Checkbox validation

As well as all rules listed on thevalidationpage, there are additional rules that are specific to checkboxes.

### #Accepted validation

You may ensure that the checkbox is checked using theaccepted()method:

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('terms_of_service')
    ->accepted()

```

Optionally, you may pass a boolean value to control if the validation rule should be applied or not:

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('terms_of_service')
    ->accepted(FeatureFlag::active())

```

### #Declined validation

You may ensure that the checkbox is not checked using thedeclined()method:

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('is_under_18')
    ->declined()

```

Optionally, you may pass a boolean value to control if the validation rule should be applied or not:

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('is_under_18')
    ->declined(FeatureFlag::active())

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
