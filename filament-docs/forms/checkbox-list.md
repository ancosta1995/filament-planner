# Checkbox list

**URL:** https://filamentphp.com/docs/5.x/forms/checkbox-list  
**Section:** forms  
**Page:** checkbox-list  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The checkbox list component allows you to select multiple values from a list of predefined options:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
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

## #Setting option descriptions

You can optionally provide descriptions to each option using thedescriptions()method. This method accepts an array of plain text strings, or instances ofIlluminate\Support\HtmlStringorIlluminate\Contracts\Support\Htmlable. This allows you to render HTML, or even markdown, in the descriptions:

```php
use Filament\Forms\Components\CheckboxList;
use Illuminate\Support\HtmlString;

CheckboxList::make('technologies')
    ->options([
        'tailwind' => 'Tailwind CSS',
        'alpine' => 'Alpine.js',
        'laravel' => 'Laravel',
        'livewire' => 'Laravel Livewire',
    ])
    ->descriptions([
        'tailwind' => 'A utility-first CSS framework for rapidly building modern websites without ever leaving your HTML.',
        'alpine' => new HtmlString('A rugged, minimal tool for composing behavior <strong>directly in your markup</strong>.'),
        'laravel' => str('A **web application** framework with expressive, elegant syntax.')->inlineMarkdown()->toHtmlString(),
        'livewire' => 'A full-stack framework for Laravel building dynamic interfaces simple, without leaving the comfort of Laravel.',
    ])

```

NOTE

Be sure to use the samekeyin the descriptions array as thekeyin the option array so the right description matches the right option.

## #Splitting options into columns

You may split options into columns by using thecolumns()method:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->columns(2)

```

This method accepts the same options as thecolumns()method of thegrid. This allows you to responsively customize the number of columns at various breakpoints.

### #Setting the grid direction

By default, when you arrange checkboxes into columns, they will be listed in order vertically. If you’d like to list them horizontally, you may use thegridDirection(GridDirection::Row)method:

```php
use Filament\Forms\Components\CheckboxList;
use Filament\Support\Enums\GridDirection;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->columns(2)
    ->gridDirection(GridDirection::Row)

```

## #Searching options

You may enable a search input to allow easier access to many options, using thesearchable()method:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->searchable()

```

Optionally, you may pass a boolean value to control if the options should be searchable or not:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->searchable(FeatureFlag::active())

```

## #Bulk toggling checkboxes

You may allow users to toggle all checkboxes at once using thebulkToggleable()method:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->bulkToggleable()

```

Optionally, you may pass a boolean value to control if the checkboxes should be bulk toggleable or not:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->bulkToggleable(FeatureFlag::active())

```

## #Disabling specific options

You can disable specific options using thedisableOptionWhen()method. It accepts a closure, in which you can check if the option with a specific$valueshould be disabled:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        'tailwind' => 'Tailwind CSS',
        'alpine' => 'Alpine.js',
        'laravel' => 'Laravel',
        'livewire' => 'Laravel Livewire',
    ])
    ->disableOptionWhen(fn (string $value): bool => $value === 'livewire')

```

If you want to retrieve the options that have not been disabled, e.g. for validation purposes, you can do so usinggetEnabledOptions():

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        'tailwind' => 'Tailwind CSS',
        'alpine' => 'Alpine.js',
        'laravel' => 'Laravel',
        'livewire' => 'Laravel Livewire',
        'heroicons' => 'SVG icons',
    ])
    ->disableOptionWhen(fn (string $value): bool => $value === 'heroicons')
    ->in(fn (CheckboxList $component): array => array_keys($component->getEnabledOptions()))

```

For more information about thein()function, please see theValidation documentation.

## #Allowing HTML in the option labels

By default, Filament will escape any HTML in the option labels. If you’d like to allow HTML, you can use theallowHtml()method:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technology')
    ->options([
        'tailwind' => '<span class="text-blue-500">Tailwind</span>',
        'alpine' => '<span class="text-green-500">Alpine</span>',
        'laravel' => '<span class="text-red-500">Laravel</span>',
        'livewire' => '<span class="text-pink-500">Livewire</span>',
    ])
    ->searchable()
    ->allowHtml()

```

NOTE

Be aware that you will need to ensure that the HTML is safe to render, otherwise your application will be vulnerable to XSS attacks.

Optionally, you may pass a boolean value to control if the options should allow HTML or not:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technology')
    ->options([
        'tailwind' => '<span class="text-blue-500">Tailwind</span>',
        'alpine' => '<span class="text-green-500">Alpine</span>',
        'laravel' => '<span class="text-red-500">Laravel</span>',
        'livewire' => '<span class="text-pink-500">Livewire</span>',
    ])
    ->searchable()
    ->allowHtml(FeatureFlag::active())

```

## #Integrating with an Eloquent relationship
> If you’re building a form inside your Livewire component, make sure you have set up theform’s model. Otherwise, Filament doesn’t know which model to use to retrieve the relationship from.


If you’re building a form inside your Livewire component, make sure you have set up theform’s model. Otherwise, Filament doesn’t know which model to use to retrieve the relationship from.

You may employ therelationship()method of theCheckboxListto point to aBelongsToManyrelationship. Filament will load the options from the relationship, and save them back to the relationship’s pivot table when the form is submitted. ThetitleAttributeis the name of a column that will be used to generate a label for each option:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->relationship(titleAttribute: 'name')

```

NOTE

When usingdisabled()withrelationship(), ensure thatdisabled()is called beforerelationship(). This ensures that thesaved()call fromdisabled()is not applied after therelationship()configuration:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->disabled()
    ->relationship(titleAttribute: 'name')

```

### #Customizing the relationship query

You may customize the database query that retrieves options using themodifyOptionsQueryUsingparameter of therelationship()method:

```php
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Builder;

CheckboxList::make('technologies')
    ->relationship(
        titleAttribute: 'name',
        modifyQueryUsing: fn (Builder $query) => $query->withTrashed(),
    )

```

### #Customizing the relationship option labels

If you’d like to customize the label of each option, maybe to be more descriptive, or to concatenate a first and last name, you could use a virtual column in your database migration:

```php
$table->string('full_name')->virtualAs('concat(first_name, \' \', last_name)');

```

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('authors')
    ->relationship(titleAttribute: 'full_name')

```

Alternatively, you can use thegetOptionLabelFromRecordUsing()method to transform an option’s Eloquent model into a label:

```php
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

CheckboxList::make('authors')
    ->relationship(
        modifyQueryUsing: fn (Builder $query) => $query->orderBy('first_name')->orderBy('last_name'),
    )
    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->first_name} {$record->last_name}")

```

### #Customizing the relationship option descriptions

If you’d like to customize the description of each option, you can use thegetOptionDescriptionFromRecordUsing()method to transform an option’s Eloquent model into a description:

```php
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

CheckboxList::make('authors')
    ->relationship(
        modifyQueryUsing: fn (Builder $query) => $query->orderBy('first_name')->orderBy('last_name'),
    )
    ->getOptionDescriptionFromRecordUsing(fn (Model $record) => $record->notes)

```

### #Saving pivot data to the relationship

If your pivot table has additional columns, you can use thepivotData()method to specify the data that should be saved in them:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('primaryTechnologies')
    ->relationship(name: 'technologies', titleAttribute: 'name')
    ->pivotData([
        'is_primary' => true,
    ])

```

## #Setting a custom no search results message

When you’re using a searchable checkbox list, you may want to display a custom message when no search results are found. You can do this using thenoSearchResultsMessage()method:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->searchable()
    ->noSearchResultsMessage('No technologies found.')

```

## #Setting a custom search prompt

When you’re using a searchable checkbox list, you may want to tweak the search input’s placeholder when the user has not yet entered a search term. You can do this using thesearchPrompt()method:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->searchable()
    ->searchPrompt('Search for a technology')

```

## #Tweaking the search debounce

By default, Filament will wait 1000 milliseconds (1 second) before searching for options when the user types in a searchable checkbox list. It will also wait 1000 milliseconds between searches if the user is continuously typing into the search input. You can change this using thesearchDebounce()method:

```php
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->searchable()
    ->searchDebounce(500)

```

## #Customizing the checkbox list action objects

This field uses action objects for easy customization of buttons within it. You can customize these buttons by passing a function to an action registration method. The function has access to the$actionobject, which you can use tocustomize it. The following methods are available to customize the actions:
- selectAllAction()
- deselectAllAction()


Here is an example of how you might customize an action:

```php
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;

CheckboxList::make('technologies')
    ->options([
        // ...
    ])
    ->selectAllAction(
        fn (Action $action) => $action->label('Select all technologies'),
    )

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
