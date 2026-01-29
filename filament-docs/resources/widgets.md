# Using widgets on resource pages

**URL:** https://filamentphp.com/docs/5.x/resources/widgets  
**Section:** resources  
**Page:** widgets  
**Priority:** high  
**AI Context:** Core CRUD functionality. Main feature for building admin panels.

---

## #Introduction

Filament allows you to display widgets inside pages, below the header and above the footer.

You can use an existingdashboard widget, or create one specifically for the resource.

## #Creating a resource widget

To get started building a resource widget:

```php
php artisan make:filament-widget CustomerOverview --resource=CustomerResource

```

This command will create two files - a widget class in theapp/Filament/Resources/Customers/Widgetsdirectory, and a view in theresources/views/filament/resources/customers/widgetsdirectory.

You must register the new widget in your resource’sgetWidgets()method:

```php
use App\Filament\Resources\Customers\Widgets\CustomerOverview;

public static function getWidgets(): array
{
    return [
        CustomerOverview::class,
    ];
}

```

If you’d like to learn how to build and customize widgets, check out theDashboarddocumentation section.

## #Displaying a widget on a resource page

To display a widget on a resource page, use thegetHeaderWidgets()orgetFooterWidgets()methods for that page:

```php
<?php

namespace App\Filament\Resources\Customers\Pages;

use App\Filament\Resources\Customers\CustomerResource;

class ListCustomers extends ListRecords
{
    public static string $resource = CustomerResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerResource\Widgets\CustomerOverview::class,
        ];
    }
}

```

getHeaderWidgets()returns an array of widgets to display above the page content, whereasgetFooterWidgets()are displayed below.

If you’d like to customize the number of grid columns used to arrange widgets, check out thePages documentation.

## #Accessing the current record in the widget

If you’re using a widget on anEditorViewpage, you may access the current record by defining a$recordproperty on the widget class:

```php
use Illuminate\Database\Eloquent\Model;

public ?Model $record = null;

```

## #Accessing page table data in the widget

If you’re using a widget on aListpage, you may access the table data by first adding theExposesTableToWidgetstrait to the page class:

```php
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    use ExposesTableToWidgets;

    // ...
}

```

Now, on the widget class, you must add theInteractsWithPageTabletrait, and return the name of the page class from thegetTablePage()method:

```php
use App\Filament\Resources\Products\Pages\ListProducts;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\Widget;

class ProductStats extends Widget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListProducts::class;
    }

    // ...
}

```

In the widget class, you can now access the Eloquent query builder instance for the table data using the$this->getPageTableQuery()method:

```php
use Filament\Widgets\StatsOverviewWidget\Stat;

Stat::make('Total Products', $this->getPageTableQuery()->count()),

```

Alternatively, you can access a collection of the records on the current page using the$this->getPageTableRecords()method:

```php
use Filament\Widgets\StatsOverviewWidget\Stat;

Stat::make('Total Products', $this->getPageTableRecords()->count()),

```

## #Accessing the total table records count

If you need the total count of all records for the table query without executing an additional count query, you can use the$tableRecordsCountproperty:

```php
use Filament\Widgets\StatsOverviewWidget\Stat;

Stat::make('Total Products', $this->tableRecordsCount),

```

## #Passing properties to widgets on resource pages

When registering a widget on a resource page, you can use themake()method to pass an array ofLivewire propertiesto it:

```php
protected function getHeaderWidgets(): array
{
    return [
        CustomerResource\Widgets\CustomerOverview::make([
            'status' => 'active',
        ]),
    ];
}

```

This array of properties gets mapped topublic Livewire propertieson the widget class:

```php
use Filament\Widgets\Widget;

class CustomerOverview extends Widget
{
    public string $status;

    // ...
}

```

Now, you can access thestatusin the widget class using$this->status.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables, actions  
**Keywords:** crud, database, eloquent, models, admin panel

*Extracted from Filament v5 Documentation - 2026-01-28*
