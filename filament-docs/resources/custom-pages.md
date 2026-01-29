# Custom resource pages

**URL:** https://filamentphp.com/docs/5.x/resources/custom-pages  
**Section:** resources  
**Page:** custom-pages  
**Priority:** high  
**AI Context:** Core CRUD functionality. Main feature for building admin panels.

---

## #Introduction

Filament allows you to create completely custom pages for resources. To create a new page, you can use:

```php
php artisan make:filament-page SortUsers --resource=UserResource --type=custom

```

This command will create two files - a page class in the/Pagesdirectory of your resource directory, and a view in the/pagesdirectory of the resource views directory.

You must register custom pages to a route in the staticgetPages()method of your resource:

```php
public static function getPages(): array
{
    return [
        // ...
        'sort' => Pages\SortUsers::route('/sort'),
    ];
}

```

NOTE

The order of pages registered in this method matters - any wildcard route segments that are defined before hard-coded ones will be matched by Laravel’s router first.

Anyparametersdefined in the route’s path will be available to the page class, in an identical way toLivewire.

## #Using a resource record

If you’d like to create a page that uses a record similar to theEditorViewpages, you can use theInteractsWithRecordtrait:

```php
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ManageUser extends Page
{
    use InteractsWithRecord;
    
    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    // ...
}

```

Themount()method should resolve the record from the URL and store it in$this->record. You can access the record at any time using$this->getRecord()in the class or view.

To add the record to the route as a parameter, you must define{record}ingetPages():

```php
public static function getPages(): array
{
    return [
        // ...
        'manage' => Pages\ManageUser::route('/{record}/manage'),
    ];
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables, actions  
**Keywords:** crud, database, eloquent, models, admin panel

*Extracted from Filament v5 Documentation - 2026-01-28*
