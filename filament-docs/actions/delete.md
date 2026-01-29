# Delete action

**URL:** https://filamentphp.com/docs/5.x/actions/delete  
**Section:** actions  
**Page:** delete  
**Priority:** high  
**AI Context:** Interactive buttons with modals for user actions.

---

## #Introduction

Filament includes an action that is able to delete Eloquent records. When the trigger button is clicked, a modal asks the user for confirmation. You may use it like so:

```php
use Filament\Actions\DeleteAction;

DeleteAction::make()

```

Or if you want to add it as a table bulk action, so that the user can choose which rows to delete, useFilament\Actions\DeleteBulkAction:

```php
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->toolbarActions([
            DeleteBulkAction::make(),
        ]);
}

```

## #Redirecting after deleting

You may set up a custom redirect when the record is deleted using thesuccessRedirectUrl()method:

```php
use Filament\Actions\DeleteAction;

DeleteAction::make()
    ->successRedirectUrl(route('posts.list'))

```

## #Customizing the delete notification

When the record is successfully deleted, a notification is dispatched to the user, which indicates the success of their action.

To customize the title of this notification, use thesuccessNotificationTitle()method:

```php
use Filament\Actions\DeleteAction;

DeleteAction::make()
    ->successNotificationTitle('User deleted')

```

You may customize the entire notification using thesuccessNotification()method:

```php
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;

DeleteAction::make()
    ->successNotification(
       Notification::make()
            ->success()
            ->title('User deleted')
            ->body('The user has been deleted successfully.'),
    )

```

To disable the notification altogether, use thesuccessNotification(null)method:

```php
use Filament\Actions\DeleteAction;

DeleteAction::make()
    ->successNotification(null)

```

## #Lifecycle hooks

You can use thebefore()andafter()methods to execute code before and after a record is deleted:

```php
use Filament\Actions\DeleteAction;

DeleteAction::make()
    ->before(function () {
        // ...
    })
    ->after(function () {
        // ...
    })

```

## #Improving the performance of delete bulk actions

By default, theDeleteBulkActionwill load all Eloquent records into memory, before looping over them and deleting them one by one.

If you are deleting a large number of records, you may want to use thechunkSelectedRecords()method to fetch a smaller number of records at a time. This will reduce the memory usage of your application:

```php
use Filament\Actions\DeleteBulkAction;

DeleteBulkAction::make()
    ->chunkSelectedRecords(250)

```

Filament loads Eloquent records into memory before deleting them for two reasons:
- To allow individual records in the collection to be authorized with a model policy before deletion (usingauthorizeIndividualRecords('delete'), for example).
- To ensure that model events are run when deleting records, such as thedeletinganddeletedevents in a model observer.


If you do not require individual record policy authorization and model events, you can use thefetchSelectedRecords(false)method, which will not fetch the records into memory before deleting them, and instead will delete them in a single query:

```php
use Filament\Actions\DeleteBulkAction;

DeleteBulkAction::make()
    ->fetchSelectedRecords(false)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, tables, forms  
**Keywords:** buttons, modals, interactions, crud operations

*Extracted from Filament v5 Documentation - 2026-01-28*
