# Textarea

**URL:** https://filamentphp.com/docs/5.x/forms/textarea  
**Section:** forms  
**Page:** textarea  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The textarea allows you to interact with a multi-line string:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')

```

## #Resizing the textarea

You may change the size of the textarea by defining therows()andcols()methods:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->rows(10)
    ->cols(20)

```

### #Autosizing the textarea

You may allow the textarea to automatically resize to fit its content by setting theautosize()method:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->autosize()

```

Optionally, you may pass a boolean value to control if the textarea should be autosizeable or not:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->autosize(FeatureFlag::active())

```

## #Making the field read-only

Not to be confused withdisabling the field, you may make the field “read-only” using thereadOnly()method:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->readOnly()

```

There are a few differences, compared todisabled():
- When usingreadOnly(), the field will still be sent to the server when the form is submitted. It can be mutated with the browser console, or via JavaScript. You can usesaved(false)to prevent this.
- There are no styling changes, such as less opacity, when usingreadOnly().
- The field is still focusable when usingreadOnly().


Optionally, you may pass a boolean value to control if the field should be read-only or not:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->readOnly(FeatureFlag::active())

```

## #Disabling Grammarly checks

If the user has Grammarly installed and you would like to prevent it from analyzing the contents of the textarea, you can use thedisableGrammarly()method:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->disableGrammarly()

```

Optionally, you may pass a boolean value to control if the field should disable Grammarly checks or not:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->disableGrammarly(FeatureFlag::active())

```

## #Trimming whitespace

You can automatically trim whitespace from the beginning and end of the textarea value using thetrim()method:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->trim()

```

You may want to enable trimming globally for all textareas, similar to Laravel’sTrimStringsmiddleware. You can do this in a service provider using theconfigureUsing()method:

```php
use Filament\Forms\Components\Textarea;

Textarea::configureUsing(function (Textarea $component): void {
    $component->trim();
});

```

## #Textarea validation

As well as all rules listed on thevalidationpage, there are additional rules that are specific to textareas.

### #Length validation

You may limit the length of the textarea by setting theminLength()andmaxLength()methods. These methods add both frontend and backend validation:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('description')
    ->minLength(2)
    ->maxLength(1024)

```

You can also specify the exact length of the textarea by setting thelength(). This method adds both frontend and backend validation:

```php
use Filament\Forms\Components\Textarea;

Textarea::make('question')
    ->length(100)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
