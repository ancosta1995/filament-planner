# Toggle

**URL:** https://filamentphp.com/docs/5.x/forms/toggle  
**Section:** forms  
**Page:** toggle  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The toggle component, similar to acheckbox, allows you to interact a boolean value.

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')

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

## #Adding icons to the toggle button

Toggles may also use aniconto represent the “on” and “off” state of the button. To add an icon to the “on” state, use theonIcon()method. To add an icon to the “off” state, use theoffIcon()method:

```php
use Filament\Forms\Components\Toggle;
use Filament\Support\Icons\Heroicon;

Toggle::make('is_admin')
    ->onIcon(Heroicon::Bolt)
    ->offIcon(Heroicon::User)

```

## #Customizing the color of the toggle button

You may also customize thecolorrepresenting the “on” or “off” state of the toggle. To add a color to the “on” state, use theonColor()method. To add a color to the “off” state, use theoffColor()method:

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')
    ->onColor('success')
    ->offColor('danger')

```

## #Positioning the label above

Toggle fields have two layout modes, inline and stacked. By default, they are inline.

When the toggle is inline, its label is adjacent to it:

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')
    ->inline()

```

When the toggle is stacked, its label is above it:

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_admin')
    ->inline(false)

```

## #Toggle validation

As well as all rules listed on thevalidationpage, there are additional rules that are specific to toggles.

### #Accepted validation

You may ensure that the toggle is “on” using theaccepted()method:

```php
use Filament\Forms\Components\Toggle;

Toggle::make('terms_of_service')
    ->accepted()

```

Optionally, you may pass a boolean value to control if the validation rule should be applied or not:

```php
use Filament\Forms\Components\Toggle;

Toggle::make('terms_of_service')
    ->accepted(FeatureFlag::active())

```

### #Declined validation

You may ensure that the toggle is “off” using thedeclined()method:

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_under_18')
    ->declined()

```

Optionally, you may pass a boolean value to control if the validation rule should be applied or not:

```php
use Filament\Forms\Components\Toggle;

Toggle::make('is_under_18')
    ->declined(FeatureFlag::active())

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
