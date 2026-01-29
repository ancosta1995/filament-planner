# Empty state

**URL:** https://filamentphp.com/docs/5.x/tables/empty-state  
**Section:** tables  
**Page:** empty-state  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

The table’s “empty state” is rendered when there are no rows in the table.

## #Setting the empty state heading

To customize the heading of the empty state, use theemptyStateHeading()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->emptyStateHeading('No posts yet');
}

```

## #Setting the empty state description

To customize the description of the empty state, use theemptyStateDescription()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->emptyStateDescription('Once you write your first post, it will appear here.');
}

```

## #Setting the empty state icon

To customize theiconof the empty state, use theemptyStateIcon()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->emptyStateIcon('heroicon-o-bookmark');
}

```

## #Adding empty state actions

You can addActionsto the empty state to prompt users to take action. Pass these to theemptyStateActions()method:

```php
use Filament\Actions\Action;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->emptyStateActions([
            Action::make('create')
                ->label('Create post')
                ->url(route('posts.create'))
                ->icon('heroicon-m-plus')
                ->button(),
        ]);
}

```

## #Using a custom empty state view

You may use a completely custom empty state view by passing it to theemptyState()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->emptyState(view('tables.posts.empty-state'));
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
