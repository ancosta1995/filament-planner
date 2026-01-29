# Global search

**URL:** https://filamentphp.com/docs/5.x/resources/global-search  
**Section:** resources  
**Page:** global-search  
**Priority:** high  
**AI Context:** Core CRUD functionality. Main feature for building admin panels.

---

## #Introduction

Global search allows you to search across all of your resource records, from anywhere in the app.

## #Setting global search result titles

To enable global search on your model, you mustset a title attributefor your resource:

```php
protected static ?string $recordTitleAttribute = 'title';

```

This attribute is used to retrieve the search result title for that record.

NOTE

Your resource needs to have an Edit or View page to allow the global search results to link to a URL, otherwise no results will be returned for this resource.

You may customize the title further by overridinggetGlobalSearchResultTitle()method. It may return a plain text string, or an instance ofIlluminate\Support\HtmlStringorIlluminate\Contracts\Support\Htmlable. This allows you to render HTML, or even Markdown, in the search result title:

```php
use Illuminate\Contracts\Support\Htmlable;

public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
{
    return $record->name;
}

```

## #Globally searching across multiple columns

If you would like to search across multiple columns of your resource, you may override thegetGloballySearchableAttributes()method. “Dot notation” allows you to search inside relationships:

```php
public static function getGloballySearchableAttributes(): array
{
    return ['title', 'slug', 'author.name', 'category.name'];
}

```

## #Adding extra details to global search results

Search results can display “details” below their title, which gives the user more information about the record. To enable this feature, you must override thegetGlobalSearchResultDetails()method:

```php
public static function getGlobalSearchResultDetails(Model $record): array
{
    return [
        'Author' => $record->author->name,
        'Category' => $record->category->name,
    ];
}

```

In this example, the category and author of the record will be displayed below its title in the search result. However, thecategoryandauthorrelationships will be lazy-loaded, which will result in poor results performance. Toeager-loadthese relationships, we must override thegetGlobalSearchEloquentQuery()method:

```php
public static function getGlobalSearchEloquentQuery(): Builder
{
    return parent::getGlobalSearchEloquentQuery()->with(['author', 'category']);
}

```

## #Customizing global search result URLs

Global search results will link to theEdit pageof your resource, or theView pageif the user does not haveedit permissions. To customize this, you may override thegetGlobalSearchResultUrl()method and return a route of your choice:

```php
public static function getGlobalSearchResultUrl(Model $record): string
{
    return UserResource::getUrl('edit', ['record' => $record]);
}

```

## #Adding actions to global search results

Global search supports actions, which are buttons that render below each search result. They can open a URL or dispatch a Livewire event.

Actions can be defined as follows:

```php
use Filament\Actions\Action;

public static function getGlobalSearchResultActions(Model $record): array
{
    return [
        Action::make('edit')
            ->url(static::getUrl('edit', ['record' => $record])),
    ];
}

```

You can learn more about how to style action buttonshere.

### #Opening URLs from global search actions

You can open a URL, optionally in a new tab, when clicking on an action:

```php
use Filament\Actions\Action;

Action::make('view')
    ->url(static::getUrl('view', ['record' => $record]), shouldOpenInNewTab: true)

```

### #Dispatching Livewire events from global search actions

Sometimes you want to execute additional code when a global search result action is clicked. This can be achieved by setting a Livewire event which should be dispatched on clicking the action. You may optionally pass an array of data, which will be available as parameters in the event listener on your Livewire component:

```php
use Filament\Actions\Action;

Action::make('quickView')
    ->dispatch('quickView', [$record->id])

```

## #Limiting the number of global search results

By default, global search will return up to 50 results per resource. You can customize this on the resource label by overriding the$globalSearchResultsLimitproperty:

```php
protected static int $globalSearchResultsLimit = 20;

```

## #Moving the global search to the sidebar

By default, the global search field is positioned in the topbar. If the topbar is disabled, it is added to the sidebar.

You can choose to always move it to the sidebar by passing apositionargument to theglobalSearch()method in theconfiguration:

```php
use Filament\Enums\GlobalSearchPosition;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->globalSearch(position: GlobalSearchPosition::Sidebar);
}

```

## #Sorting global search results

By default, global search results are ordered alphabetically by resource name. You can customize this order by setting the$globalSearchSortproperty on your resource:

```php
protected static ?int $globalSearchSort = 3;

```

Now, navigation items with a lower sort value will appear before those with a higher sort value - the order is ascending.

## #Disabling global search

Asexplained above, global search is automatically enabled once you set a title attribute for your resource. Sometimes you may want to specify the title attribute while not enabling global search.

This can be achieved by disabling global search in theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->globalSearch(false);
}

```

## #Registering global search key bindings

The global search field can be opened using keyboard shortcuts. To configure these, pass theglobalSearchKeyBindings()method to theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->globalSearchKeyBindings(['command+k', 'ctrl+k']);
}

```

## #Configuring the global search debounce

Global search has a default debounce time of 500ms, to limit the number of requests that are made while the user is typing. You can alter this by using theglobalSearchDebounce()method in theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->globalSearchDebounce('750ms');
}

```

## #Configuring the global search field suffix

Global search field by default doesn’t include any suffix. You may customize it using theglobalSearchFieldSuffix()method in theconfiguration.

If you want to display the currently configuredglobal search key bindingsin the suffix, you can use theglobalSearchFieldKeyBindingSuffix()method, which will display the first registered key binding as the suffix of the global search field:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->globalSearchFieldKeyBindingSuffix();
}

```

To customize the suffix yourself, you can pass a string or function to theglobalSearchFieldSuffix()method. For example, to provide a custom key binding suffix for each platform manually:

```php
use Filament\Panel;
use Filament\Support\Enums\Platform;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->globalSearchFieldSuffix(fn (): ?string => match (Platform::detect()) {
            Platform::Windows, Platform::Linux => 'CTRL+K',
            Platform::Mac => '⌘K',
            default => null,
        });
}

```

## #Disabling search term splitting

By default, the global search will split the search term into individual words and search for each word separately. This allows for more flexible search queries. However, it can have a negative impact on performance when large datasets are involved. You can disable this behavior by setting the$shouldSplitGlobalSearchTermsproperty tofalseon the resource:

```php
protected static ?bool $shouldSplitGlobalSearchTerms = false;

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables, actions  
**Keywords:** crud, database, eloquent, models, admin panel

*Extracted from Filament v5 Documentation - 2026-01-28*
