# Custom filters

**URL:** https://filamentphp.com/docs/5.x/tables/filters/custom  
**Section:** tables  
**Page:** custom  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Custom filter schemas

You may useschema componentsto create custom filters. The data from the custom filter schema is available in the$dataarray of thequery()callback:

```php
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

Filter::make('created_at')
    ->schema([
        DatePicker::make('created_from'),
        DatePicker::make('created_until'),
    ])
    ->query(function (Builder $query, array $data): Builder {
        return $query
            ->when(
                $data['created_from'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
            )
            ->when(
                $data['created_until'],
                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
            );
    })

```

### #Setting default values for custom filter fields

To customize the default value of a field in a custom filter schema, you may use thedefault()method:

```php
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

Filter::make('created_at')
    ->schema([
        DatePicker::make('created_from'),
        DatePicker::make('created_until')
            ->default(now()),
    ])

```

## #Active indicators

When a filter is active, an indicator is displayed above the table content to signal that the table query has been scoped.

By default, the label of the filter is used as the indicator. You can override this using theindicator()method:

```php
use Filament\Tables\Filters\Filter;

Filter::make('is_admin')
    ->label('Administrators only?')
    ->indicator('Administrators')

```

If you are using acustom filter schema, you should useindicateUsing()to display an active indicator.

Please note: if you do not have an indicator for your filter, then the badge-count of how many filters are active in the table will not include that filter.

### #Custom active indicators

Not all indicators are simple, so you may need to useindicateUsing()to customize which indicators should be shown at any time.

For example, if you have a custom date filter, you may create a custom indicator that formats the selected date:

```php
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

Filter::make('created_at')
    ->schema([DatePicker::make('date')])
    // ...
    ->indicateUsing(function (array $data): ?string {
        if (! $data['date']) {
            return null;
        }

        return 'Created at ' . Carbon::parse($data['date'])->toFormattedDateString();
    })

```

### #Multiple active indicators

You may even render multiple indicators at once, by returning an array ofIndicatorobjects. If you have different fields associated with different indicators, you should set the field using theremoveField()method on theIndicatorobject to ensure that the correct field is reset when the filter is removed:

```php
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;

Filter::make('created_at')
    ->schema([
        DatePicker::make('from'),
        DatePicker::make('until'),
    ])
    // ...
    ->indicateUsing(function (array $data): array {
        $indicators = [];

        if ($data['from'] ?? null) {
            $indicators[] = Indicator::make('Created from ' . Carbon::parse($data['from'])->toFormattedDateString())
                ->removeField('from');
        }

        if ($data['until'] ?? null) {
            $indicators[] = Indicator::make('Created until ' . Carbon::parse($data['until'])->toFormattedDateString())
                ->removeField('until');
        }

        return $indicators;
    })

```

### #Preventing indicators from being removed

You can prevent users from removing an indicator usingremovable(false)on anIndicatorobject:

```php
use Carbon\Carbon;
use Filament\Tables\Filters\Indicator;

Indicator::make('Created from ' . Carbon::parse($data['from'])->toFormattedDateString())
    ->removable(false)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
