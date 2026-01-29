# Deleting records

**URL:** https://filamentphp.com/docs/5.x/resources/deleting-records  
**Section:** resources  
**Page:** deleting-records  
**Priority:** high  
**AI Context:** Core CRUD functionality. Main feature for building admin panels.

---

## #Handling soft-deletes

## #Creating a resource with soft-delete

By default, you will not be able to interact with deleted records in the app. If you’d like to add functionality to restore, force-delete and filter trashed records in your resource, use the--soft-deletesflag when generating the resource:

```php
php artisan make:filament-resource Customer --soft-deletes

```

## #Adding soft-deletes to an existing resource

Alternatively, you may add soft-deleting functionality to an existing resource.

Firstly, you must update the resource:

```php
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->filters([
            TrashedFilter::make(),
            // ...
        ])
        ->recordActions([
            // You may add these actions to your table if you're using a simple
            // resource, or you just want to be able to delete records without
            // leaving the table.
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            // ...
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
                // ...
            ]),
        ]);
}

public static function getRecordRouteBindingEloquentQuery(): Builder
{
    return parent::getRecordRouteBindingEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
}

```

Now, update the Edit page class if you have one:

```php
use Filament\Actions;

protected function getHeaderActions(): array
{
    return [
        Actions\DeleteAction::make(),
        Actions\ForceDeleteAction::make(),
        Actions\RestoreAction::make(),
        // ...
    ];
}

```

## #Deleting records on the List page

By default, you can bulk-delete records in your table. You may also wish to delete single records, using aDeleteAction:

```php
use Filament\Actions\DeleteAction;
use Filament\Tables\Table;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->recordActions([
            // ...
            DeleteAction::make(),
        ]);
}

```

## #Authorization

For authorization, Filament will observe anymodel policiesthat are registered in your app.

Users may delete records if thedelete()method of the model policy returnstrue.

They also have the ability to bulk-delete records if thedeleteAny()method of the policy returnstrue. Filament uses thedeleteAny()method because iterating through multiple records and checking thedelete()policy is not very performant.

You can use theauthorizeIndividualRecords()method on theBulkDeleteActionto check thedelete()policy for each record individually.

### #Authorizing soft-deletes

TheforceDelete()policy method is used to prevent a single soft-deleted record from being force-deleted.forceDeleteAny()is used to prevent records from being bulk force-deleted. Filament uses theforceDeleteAny()method because iterating through multiple records and checking theforceDelete()policy is not very performant.

Therestore()policy method is used to prevent a single soft-deleted record from being restored.restoreAny()is used to prevent records from being bulk restored. Filament uses therestoreAny()method because iterating through multiple records and checking therestore()policy is not very performant.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables, actions  
**Keywords:** crud, database, eloquent, models, admin panel

*Extracted from Filament v5 Documentation - 2026-01-28*
