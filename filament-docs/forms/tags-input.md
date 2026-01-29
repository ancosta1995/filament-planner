# Tags input

**URL:** https://filamentphp.com/docs/5.x/forms/tags-input  
**Section:** forms  
**Page:** tags-input  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The tags input component allows you to interact with a list of tags.

By default, tags are stored in JSON:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')

```

If you’re saving the JSON tags using Eloquent, you should be sure to add anarraycastto the model property:

```php
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
        ];
    }

    // ...
}

```

TIP

Filament also supportsspatie/laravel-tags. See ourplugin documentationfor more information.

## #Comma-separated tags

You may allow the tags to be stored in a separated string, instead of JSON. To set this up, pass the separating character to theseparator()method:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->separator(',')

```

## #Autocompleting tag suggestions

Tags inputs may have autocomplete suggestions. To enable this, pass an array of suggestions to thesuggestions()method:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->suggestions([
        'tailwindcss',
        'alpinejs',
        'laravel',
        'livewire',
    ])

```

## #Defining split keys

Split keys allow you to map specific buttons on your user’s keyboard to create a new tag. By default, when the user presses “Enter”, a new tag is created in the input. You may also define other keys to create new tags, such as “Tab” or ” ”. To do this, pass an array of keys to thesplitKeys()method:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->splitKeys(['Tab', ' '])

```

You canread more about possible options for keys.

## #Adding a prefix and suffix to individual tags

You can add prefix and suffix to tags without modifying the real state of the field. This can be useful if you need to show presentational formatting to users without saving it. This is done with thetagPrefix()ortagSuffix()method:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('percentages')
    ->tagSuffix('%')

```

## #Reordering tags

You can allow the user to reorder tags within the field using thereorderable()method:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->reorderable()

```

Optionally, you may pass a boolean value to control if the tags should be reorderable or not:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->reorderable(FeatureFlag::active())

```

## #Changing the color of tags

You can change the color of the tags by passing acolorto thecolor()method:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->color('danger')

```

## #Trimming whitespace

You can automatically trim whitespace from the beginning and end of each tag using thetrim()method:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->trim()

```

You may want to enable trimming globally for all tags inputs, similar to Laravel’sTrimStringsmiddleware. You can do this in a service provider using theconfigureUsing()method:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::configureUsing(function (TagsInput $component): void {
    $component->trim();
});

```

## #Tags validation

You may add validation rules for each tag by passing an array of rules to thenestedRecursiveRules()method:

```php
use Filament\Forms\Components\TagsInput;

TagsInput::make('tags')
    ->nestedRecursiveRules([
        'min:3',
        'max:255',
    ])

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
