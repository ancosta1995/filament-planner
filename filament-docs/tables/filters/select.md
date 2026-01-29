# Select filters

**URL:** https://filamentphp.com/docs/5.x/tables/filters/select  
**Section:** tables  
**Page:** select  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

Often, you will want to use aselect fieldinstead of a checkbox. This is especially true when you want to filter a column based on a set of pre-defined options that the user can choose from. To do this, you can create a filter using theSelectFilterclass:

```php
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('status')
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])

```

Theoptions()that are passed to the filter are the same as those that are passed to theselect field.

## #Customizing the column used by a select filter

Select filters do not require a customquery()method. The column name used to scope the query is the name of the filter. To customize this, you may use theattribute()method:

```php
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('status')
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])
    ->attribute('status_id')

```

## #Multi-select filters

These allow the user toselect multiple optionsto apply the filter to their table. For example, a status filter may present the user with a few status options to pick from and filter the table using. When the user selects multiple options, the table will be filtered to show records that match any of the selected options. You can enable this behavior using themultiple()method:

```php
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('status')
    ->multiple()
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])

```

## #Relationship select filters

Select filters are also able to automatically populate themselves based on a relationship. For example, if your table has aauthorrelationship with anamecolumn, you may userelationship()to filter the records belonging to an author:

```php
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('author')
    ->relationship('author', 'name')

```

### #Preloading the select filter relationship options

If you’d like to populate the searchable options from the database when the page is loaded, instead of when the user searches, you can use thepreload()method:

```php
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('author')
    ->relationship('author', 'name')
    ->searchable()
    ->preload()

```

### #Filtering empty relationships

By default, upon selecting an option, all records that have an empty relationship will be excluded from the results. If you want to introduce an additional “None” option for the user to select, which will include all records that do not have a relationship, you can use thehasEmptyOption()argument of therelationship()method:

```php
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('author')
    ->relationship('author', 'name', hasEmptyOption: true)

```

You can rename the “None” option using theemptyRelationshipOptionLabel()method:

```php
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('author')
    ->relationship('author', 'name', hasEmptyOption: true)
    ->emptyRelationshipOptionLabel('No author')

```

### #Customizing the select filter relationship query

You may customize the database query that retrieves options using the third parameter of therelationship()method:

```php
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

SelectFilter::make('author')
    ->relationship('author', 'name', fn (Builder $query) => $query->withTrashed())

```

### #Searching select filter options

You may enable a search input to allow easier access to many options, using thesearchable()method:

```php
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('author')
    ->relationship('author', 'name')
    ->searchable()

```

## #Disable placeholder selection

You can remove the placeholder (null option), which disables the filter so all options are applied, using theselectablePlaceholder()method:

```php
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('status')
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])
    ->default('draft')
    ->selectablePlaceholder(false)

```

## #Applying select filters by default

You may set a select filter to be enabled by default, using thedefault()method. If using a single select filter, thedefault()method accepts a single option value. If using amultiple()select filter, thedefault()method accepts an array of option values:

```php
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('status')
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])
    ->default('draft')

SelectFilter::make('status')
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])
    ->multiple()
    ->default(['draft', 'reviewing'])

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
