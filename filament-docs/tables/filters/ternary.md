# Ternary filters

**URL:** https://filamentphp.com/docs/5.x/tables/filters/ternary  
**Section:** tables  
**Page:** ternary  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

Ternary filters allow you to easily create a select filter which has three states - usually true, false and blank. To filter a column namedis_adminto betrueorfalse, you may use the ternary filter:

```php
use Filament\Tables\Filters\TernaryFilter;

TernaryFilter::make('is_admin')

```

## #Using a ternary filter with a nullable column

Another common pattern is to use a nullable column. For example, when filtering verified and unverified users using theemail_verified_atcolumn, unverified users have a null timestamp in this column. To apply that logic, you may use thenullable()method:

```php
use Filament\Tables\Filters\TernaryFilter;

TernaryFilter::make('email_verified_at')
    ->nullable()

```

## #Customizing the column used by a ternary filter

The column name used to scope the query is the name of the filter. To customize this, you may use theattribute()method:

```php
use Filament\Tables\Filters\TernaryFilter;

TernaryFilter::make('verified')
    ->nullable()
    ->attribute('status_id')

```

## #Customizing the ternary filter option labels

You may customize the labels used for each state of the ternary filter. The true option label can be customized using thetrueLabel()method. The false option label can be customized using thefalseLabel()method. The blank (default) option label can be customized using theplaceholder()method:

```php
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;

TernaryFilter::make('email_verified_at')
    ->label('Email verification')
    ->nullable()
    ->placeholder('All users')
    ->trueLabel('Verified users')
    ->falseLabel('Not verified users')

```

## #Customizing how a ternary filter modifies the query

You may customize how the query changes for each state of the ternary filter, use thequeries()method:

```php
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;

TernaryFilter::make('email_verified_at')
    ->label('Email verification')
    ->placeholder('All users')
    ->trueLabel('Verified users')
    ->falseLabel('Not verified users')
    ->queries(
        true: fn (Builder $query) => $query->whereNotNull('email_verified_at'),
        false: fn (Builder $query) => $query->whereNull('email_verified_at'),
        blank: fn (Builder $query) => $query, // In this example, we do not want to filter the query when it is blank.
    )

```

## #Filtering soft-deletable records

TheTrashedFiltercan be used to filter soft-deleted records. It is a type of ternary filter that is built-in to Filament. You can use it like so:

```php
use Filament\Tables\Filters\TrashedFilter;

TrashedFilter::make()

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
