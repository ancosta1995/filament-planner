# Overview

**URL:** https://filamentphp.com/docs/5.x/tables/filters/overview  
**Section:** tables  
**Page:** overview  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

Filters allow you to define certain constraints on your data, and allow users to scope it to find the information they need. You put them in the$table->filters()method.

Filters may be created using the staticmake()method, passing its unique name. You should then pass a callback toquery()which applies your filter’s scope:

```php
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

public function table(Table $table): Table
{
    return $table
        ->filters([
            Filter::make('is_featured')
                ->query(fn (Builder $query): Builder => $query->where('is_featured', true))
            // ...
        ]);
}

```

## #Available filters

By default, using theFilter::make()method will render a checkbox form component. When the checkbox is on, thequery()will be activated.
- You can alsoreplace the checkbox with a toggle.
- You may use aselect filterto allow users to select from a list of options, and filter using the selection.
- You can use aternary filterto replace the checkbox with a select field to allow users to pick between 3 states - usually “true”, “false” and “blank”. This is useful for filtering boolean columns.
- Thetrashed filteris a pre-built ternary filter that allows you to filter soft-deletable records.
- Using aquery builder, users can create complex sets of filters, with an advanced user interface for combining constraints.
- You may buildcustom filterswith other form fields, to do whatever you want.


## #Setting a label

By default, the label of the filter is generated from the name of the filter. You may customize this using thelabel()method:

```php
use Filament\Tables\Filters\Filter;

Filter::make('is_featured')
    ->label('Featured')

```

Customizing the label in this way is useful if you wish to use atranslation string for localization:

```php
use Filament\Tables\Filters\Filter;

Filter::make('is_featured')
    ->label(__('filters.is_featured'))

```

## #Customizing the filter schema

By default, creating a filter with theFilterclass will render acheckbox form component. When the checkbox is checked, thequery()function will be applied to the table’s query, scoping the records in the table. When the checkbox is unchecked, thequery()function will be removed from the table’s query.

Filters are built entirely on Filament’s form fields. They can render any combination of form fields, which users can then interact with to filter the table.

### #Using a toggle button instead of a checkbox

The simplest example of managing the form field that is used for a filter is to replace thecheckboxwith atoggle button, using thetoggle()method:

```php
use Filament\Tables\Filters\Filter;

Filter::make('is_featured')
    ->toggle()

```

### #Customizing the built-in filter form field

Whether you are using a checkbox, atoggleor aselect, you can customize the built-in form field used for the filter, using themodifyFormFieldUsing()method. The method accepts a function with a$fieldparameter that gives you access to the form field object to customize:

```php
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Filters\Filter;

Filter::make('is_featured')
    ->modifyFormFieldUsing(fn (Checkbox $field) => $field->inline(false))

```

## #Applying the filter by default

You may set a filter to be enabled by default, using thedefault()method:

```php
use Filament\Tables\Filters\Filter;

Filter::make('is_featured')
    ->default()

```

If you’re using aselect filter,visit the “applying select filters by default” section.

## #Persisting filters in the user’s session

To persist the table filters in the user’s session, use thepersistFiltersInSession()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ])
        ->persistFiltersInSession();
}

```

## #Live filters

By default, filter changes are deferred and do not affect the table, until the user clicks an “Apply” button. To disable this and make the filters “live” instead, use thedeferFilters(false)method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ])
        ->deferFilters(false);
}

```

### #Customizing the apply filters action

When deferring filters, you can customize the “Apply” button, using thefiltersApplyAction()method, passing a closure that returns an action. All methods that are available tocustomize action trigger buttonscan be used:

```php
use Filament\Actions\Action;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ])
        ->filtersApplyAction(
            fn (Action $action) => $action
                ->link()
                ->label('Save filters to table'),
        );
}

```

## #Deselecting records when filters change

By default, all records will be deselected when the filters change. Using thedeselectAllRecordsWhenFiltered(false)method, you can disable this behavior:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ])
        ->deselectAllRecordsWhenFiltered(false);
}

```

## #Modifying the base query

By default, modifications to the Eloquent query performed in thequery()method will be applied inside a scopedwhere()clause. This is to ensure that the query does not clash with any other filters that may be applied, especially those that useorWhere().

However, the downside of this is that thequery()method cannot be used to modify the query in other ways, such as removing global scopes, since the base query needs to be modified directly, not the scoped query.

To modify the base query directly, you may use thebaseQuery()method, passing a closure that receives the base query:

```php
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TernaryFilter;

TernaryFilter::make('trashed')
    // ...
    ->baseQuery(fn (Builder $query) => $query->withoutGlobalScopes([
        SoftDeletingScope::class,
    ]))

```

## #Excluding filters when resolving records

When a user interacts with a table record (e.g., clicking an action button), Filament resolves that record from the database. By default, all active filter conditions are applied, ensuring users cannot access records outside their filter scope.

However, some filters likeTrashedFiltermodify global scopes rather than restricting access. When a record’s state changes after the user saw it in the table, you may still want the user to interact with it.

You may mark a filter to be excluded when resolving records using theexcludeWhenResolvingRecord()method:

```php
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

Filter::make('trashed')
    ->query(fn (Builder $query) => $query->onlyTrashed())
    ->baseQuery(fn (Builder $query) => $query->withoutGlobalScopes([
        SoftDeletingScope::class,
    ]))
    ->excludeWhenResolvingRecord()

```

WhenexcludeWhenResolvingRecord()is used:
- The filter’squery()callback is not applied when resolving records
- The filter’sbaseQuery()callback is still applied when resolving records


NOTE

Do not useexcludeWhenResolvingRecord()on filters that enforce authorization rules. For example, if you have a filter that restricts records by tenant or user ownership, those filters should remain enforced to prevent unauthorized access.

## #Customizing the filters trigger action

To customize the filters trigger buttons, you may use thefiltersTriggerAction()method, passing a closure that returns an action. All methods that are available tocustomize action trigger buttonscan be used:

```php
use Filament\Actions\Action;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ])
        ->filtersTriggerAction(
            fn (Action $action) => $action
                ->button()
                ->label('Filter'),
        );
}

```

## #Filter utility injection

The vast majority of methods used to configure filters accept functions as parameters instead of hardcoded values:

```php
use App\Models\Author;
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('author')
    ->options(fn (): array => Author::query()->pluck('name', 'id')->all())

```

This alone unlocks many customization possibilities.

The package is also able to inject many utilities to use inside these functions, as parameters. All customization methods that accept functions as arguments can inject utilities.

These injected utilities require specific parameter names to be used. Otherwise, Filament doesn’t know what to inject.

### #Injecting the current filter instance

If you wish to access the current filter instance, define a$filterparameter:

```php
use Filament\Tables\Filters\BaseFilter;

function (BaseFilter $filter) {
    // ...
}

```

### #Injecting the current Livewire component instance

If you wish to access the current Livewire component instance that the table belongs to, define a$livewireparameter:

```php
use Filament\Tables\Contracts\HasTable;

function (HasTable $livewire) {
    // ...
}

```

### #Injecting the current table instance

If you wish to access the current table configuration instance that the filter belongs to, define a$tableparameter:

```php
use Filament\Tables\Table;

function (Table $table) {
    // ...
}

```

### #Injecting multiple utilities

The parameters are injected dynamically using reflection, so you are able to combine multiple parameters in any order:

```php
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

function (HasTable $livewire, Table $table) {
    // ...
}

```

### #Injecting dependencies from Laravel’s container

You may inject anything from Laravel’s container like normal, alongside utilities:

```php
use Filament\Tables\Table;
use Illuminate\Http\Request;

function (Request $request, Table $table) {
    // ...
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
