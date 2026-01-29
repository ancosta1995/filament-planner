# Text input

**URL:** https://filamentphp.com/docs/5.x/forms/text-input  
**Section:** forms  
**Page:** text-input  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The text input allows you to interact with a string:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')

```

## #Setting the HTML input type

You may set the type of string using a set of methods. Some, such asemail(), also provide validation:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('text')
    ->email() // or
    ->numeric() // or
    ->integer() // or
    ->password() // or
    ->tel() // or
    ->url()

```

You may instead use thetype()method to pass anotherHTML input type:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('backgroundColor')
    ->type('color')

```

The individual type methods also allow you to pass in a boolean value to control if the field should be that or not:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('text')
    ->email(FeatureFlag::active()) // or
    ->numeric(FeatureFlag::active()) // or
    ->integer(FeatureFlag::active()) // or
    ->password(FeatureFlag::active()) // or
    ->tel(FeatureFlag::active()) // or
    ->url(FeatureFlag::active())

```

## #Setting the HTML input mode

You may set theinputmodeattributeof the input using theinputMode()method:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('text')
    ->numeric()
    ->inputMode('decimal')

```

## #Setting the numeric step

You may set thestepattributeof the input using thestep()method:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('number')
    ->numeric()
    ->step(100)

```

## #Autocompleting text

You may allow the text to beautocompleted by the browserusing theautocomplete()method:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('password')
    ->password()
    ->autocomplete('new-password')

```

As a shortcut forautocomplete="off", you may useautocomplete(false):

```php
use Filament\Forms\Components\TextInput;

TextInput::make('password')
    ->password()
    ->autocomplete(false)

```

For more complex autocomplete options, text inputs also supportdatalists.

### #Autocompleting text with a datalist

You may specifydatalistoptions for a text input using thedatalist()method:

```php
TextInput::make('manufacturer')
    ->datalist([
        'BMW',
        'Ford',
        'Mercedes-Benz',
        'Porsche',
        'Toyota',
        'Volkswagen',
    ])

```

Datalists provide autocomplete options to users when they use a text input. However, these are purely recommendations, and the user is still able to type any value into the input. If you’re looking to strictly limit users to a set of predefined options, check out theselect field.

## #Autocapitalizing text

You may allow the text to beautocapitalized by the browserusing theautocapitalize()method:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->autocapitalize('words')

```

## #Adding affix text aside the field

You may place text before and after the input using theprefix()andsuffix()methods:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('domain')
    ->prefix('https://')
    ->suffix('.com')

```

### #Using icons as affixes

You may place aniconbefore and after the input using theprefixIcon()andsuffixIcon()methods:

```php
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

TextInput::make('domain')
    ->url()
    ->suffixIcon(Heroicon::GlobeAlt)

```

#### #Setting the affix icon’s color

Affix icons are gray by default, but you may set a different color using theprefixIconColor()andsuffixIconColor()methods:

```php
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

TextInput::make('domain')
    ->url()
    ->suffixIcon(Heroicon::CheckCircle)
    ->suffixIconColor('success')

```

## #Revealable password inputs

When usingpassword(), you can also make the inputrevealable(), so that the user can see a plain text version of the password they’re typing by clicking a button:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('password')
    ->password()
    ->revealable()

```

Optionally, you may pass a boolean value to control if the input should be revealable or not:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('password')
    ->password()
    ->revealable(FeatureFlag::active())

```

## #Allowing the text to be copied to the clipboard

You may make the text copyable, such that clicking on a button next to the input copies the text to the clipboard, and optionally specify a custom confirmation message and duration in milliseconds:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('apiKey')
    ->label('API key')
    ->copyable(copyMessage: 'Copied!', copyMessageDuration: 1500)

```

Optionally, you may pass a boolean value to control if the text should be copyable or not:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('apiKey')
    ->label('API key')
    ->copyable(FeatureFlag::active())

```

NOTE

This feature only works when SSL is enabled for the app.

## #Input masking

Input masking is the practice of defining a format that the input value must conform to.

In Filament, you may use themask()method to configure anAlpine.js mask:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('birthday')
    ->mask('99/99/9999')
    ->placeholder('MM/DD/YYYY')

```

To use adynamic mask, wrap the JavaScript in aRawJsobject:

```php
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

TextInput::make('cardNumber')
    ->mask(RawJs::make(<<<'JS'
        $input.startsWith('34') || $input.startsWith('37') ? '9999 999999 99999' : '9999 9999 9999 9999'
    JS))

```

Alpine.js will send the entire masked value to the server, so you may need to strip certain characters from the state before validating the field and saving it. You can do this with thestripCharacters()method, passing in a character or an array of characters to remove from the masked value:

```php
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;

TextInput::make('amount')
    ->mask(RawJs::make('$money($input)'))
    ->stripCharacters(',')
    ->numeric()

```

## #Trimming whitespace

You can automatically trim whitespace from the beginning and end of the input value using thetrim()method:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->trim()

```

You may want to enable trimming globally for all text inputs, similar to Laravel’sTrimStringsmiddleware. You can do this in a service provider using theconfigureUsing()method:

```php
use Filament\Forms\Components\TextInput;

TextInput::configureUsing(function (TextInput $component): void {
    $component->trim();
});

```

## #Making the field read-only

Not to be confused withdisabling the field, you may make the field “read-only” using thereadOnly()method:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->readOnly()

```

There are a few differences, compared todisabled():
- When usingreadOnly(), the field will still be sent to the server when the form is submitted. It can be mutated with the browser console, or via JavaScript. You can usesaved(false)to prevent this.
- There are no styling changes, such as less opacity, when usingreadOnly().
- The field is still focusable when usingreadOnly().


Optionally, you may pass a boolean value to control if the field should be read-only or not:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->readOnly(FeatureFlag::active())

```

## #Text input validation

As well as all rules listed on thevalidationpage, there are additional rules that are specific to text inputs.

### #Length validation

You may limit the length of the input by setting theminLength()andmaxLength()methods. These methods add both frontend and backend validation:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('name')
    ->minLength(2)
    ->maxLength(255)

```

You can also specify the exact length of the input by setting thelength(). This method adds both frontend and backend validation:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('code')
    ->length(8)

```

### #Size validation

You may validate the minimum and maximum value of a numeric input by setting theminValue()andmaxValue()methods:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('number')
    ->numeric()
    ->minValue(1)
    ->maxValue(100)

```

### #Phone number validation

When using atel()field, the value will be validated using:/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/.

If you wish to change that, then you can use thetelRegex()method:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('phone')
    ->tel()
    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')

```

Alternatively, to customize thetelRegex()across all fields, use a service provider:

```php
use Filament\Forms\Components\TextInput;

TextInput::configureUsing(function (TextInput $component): void {
    $component->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/');
});

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
