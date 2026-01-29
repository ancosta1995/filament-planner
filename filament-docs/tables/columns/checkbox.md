# Checkbox column

**URL:** https://filamentphp.com/docs/5.x/tables/columns/checkbox  
**Section:** tables  
**Page:** checkbox  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

The checkbox column allows you to render a checkbox inside the table, which can be used to update that database record without needing to open a new page or a modal:

```php
use Filament\Tables\Columns\CheckboxColumn;

CheckboxColumn::make('is_admin')

```

## #Lifecycle hooks

Hooks may be used to execute code at various points within the checkbox’s lifecycle:

```php
CheckboxColumn::make()
    ->beforeStateUpdated(function ($record, $state) {
        // Runs before the state is saved to the database.
    })
    ->afterStateUpdated(function ($record, $state) {
        // Runs after the state is saved to the database.
    })

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
