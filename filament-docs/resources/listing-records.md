# Listing records

**URL:** https://filamentphp.com/docs/5.x/resources/listing-records  
**Section:** resources  
**Page:** listing-records  
**Priority:** high  
**AI Context:** Core CRUD functionality. Main feature for building admin panels.

---

## #Using tabs to filter the records

You can add tabs above the table, which can be used to filter the records based on some predefined conditions. Each tab can scope the Eloquent query of the table in a different way. To register tabs, add agetTabs()method to the List page class, and return an array ofTabobjects:

```php
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

public function getTabs(): array
{
    return [
        'all' => Tab::make(),
        'active' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('active', true)),
        'inactive' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false)),
    ];
}

```

### #Customizing the filter tab labels

The keys of the array will be used as identifiers for the tabs, so they can be persisted in the URL’s query string. The label of each tab is also generated from the key, but you can override that by passing a label into themake()method of the tab:

```php
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

public function getTabs(): array
{
    return [
        'all' => Tab::make('All customers'),
        'active' => Tab::make('Active customers')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('active', true)),
        'inactive' => Tab::make('Inactive customers')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false)),
    ];
}

```

### #Adding icons to filter tabs

You can add icons to the tabs by passing aniconinto theicon()method of the tab:

```php
use Filament\Schemas\Components\Tabs\Tab;

Tab::make()
    ->icon('heroicon-m-user-group')

```

You can also change the icon’s position to be after the label instead of before it, using theiconPosition()method:

```php
use Filament\Support\Enums\IconPosition;

Tab::make()
    ->icon('heroicon-m-user-group')
    ->iconPosition(IconPosition::After)

```

### #Adding badges to filter tabs

You can add badges to the tabs by passing a string into thebadge()method of the tab:

```php
use Filament\Schemas\Components\Tabs\Tab;

Tab::make()
    ->badge(Customer::query()->where('active', true)->count())

```

#### #Changing the color of filter tab badges

The color of a badge may be changed using thebadgeColor()method:

```php
use Filament\Schemas\Components\Tabs\Tab;

Tab::make()
    ->badge(Customer::query()->where('active', true)->count())
    ->badgeColor('success')

```

### #Adding extra attributes to filter tabs

You may also pass extra HTML attributes to filter tabs usingextraAttributes():

```php
use Filament\Schemas\Components\Tabs\Tab;

Tab::make()
    ->extraAttributes(['data-cy' => 'statement-confirmed-tab'])

```

### #Customizing the default tab

To customize the default tab that is selected when the page is loaded, you can return the array key of the tab from thegetDefaultActiveTab()method:

```php
use Filament\Schemas\Components\Tabs\Tab;

public function getTabs(): array
{
    return [
        'all' => Tab::make(),
        'active' => Tab::make(),
        'inactive' => Tab::make(),
    ];
}

public function getDefaultActiveTab(): string | int | null
{
    return 'active';
}

```

### #Excluding the tab query when resolving records

When a user interacts with a table record (e.g., clicking an action button), Filament resolves that record from the database. By default, the active tab’s query is applied, ensuring users cannot access records outside the current tab’s scope.

However, when a record’s state changes after the user saw it in the table, you may still want the user to interact with it. For example, if you have an “Active” tab and an action sets a record to inactive, subsequent actions in the same modal would fail to resolve that record.

You may mark a tab to be excluded when resolving records using theexcludeQueryWhenResolvingRecord()method:

```php
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

public function getTabs(): array
{
    return [
        'active' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('active', true))
            ->excludeQueryWhenResolvingRecord(),
        'inactive' => Tab::make()
            ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false))
            ->excludeQueryWhenResolvingRecord(),
    ];
}

```

NOTE

Do not useexcludeQueryWhenResolvingRecord()on tabs that enforce authorization rules. For example, if you have a tab that restricts records by tenant or user ownership, those tabs should remain enforced to prevent unauthorized access.

## #Authorization

For authorization, Filament will observe anymodel policiesthat are registered in your app.

Users may access the List page if theviewAny()method of the model policy returnstrue.

Thereorder()method is used to controlreordering a record.

## #Customizing the table Eloquent query

Although you cancustomize the Eloquent query for the entire resource, you may also make specific modifications for the List page table. To do this, use themodifyQueryUsing()method in thetable()method of the resource:

```php
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

public static function table(Table $table): Table
{
    return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes());
}

```

## #Custom page content

Each page in Filament has its ownschema, which defines the overall structure and content. You can override the schema for the page by defining acontent()method on it. Thecontent()method for the List page contains the following components by default:

```php
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Schema;

public function content(Schema $schema): Schema
{
    return $schema
        ->components([
            $this->getTabsContentComponent(), // This method returns a component to display the tabs above a table
            RenderHook::make(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE),
            EmbeddedTable::make(), // This is the component that renders the table that is defined in this resource
            RenderHook::make(PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_AFTER),
        ]);
}

```

Inside thecomponents()array, you can insert anyschema component. You can reorder the components by changing the order of the array or remove any of the components that are not needed.

### #Using a custom Blade view

For further customization opportunities, you can override the static$viewproperty on the page class to a custom view in your app:

```php
protected string $view = 'filament.resources.users.pages.list-users';

```

This assumes that you have created a view atresources/views/filament/resources/users/pages/list-users.blade.php:

```php
<x-filament-panels::page>
    {{ $this->content }} {{-- This will render the content of the page defined in the `content()` method, which can be removed if you want to start from scratch --}}
</x-filament-panels::page>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables, actions  
**Keywords:** crud, database, eloquent, models, admin panel

*Extracted from Filament v5 Documentation - 2026-01-28*
