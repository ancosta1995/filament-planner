# Restore action

**URL:** https://filamentphp.com/docs/5.x/actions/restore  
**Section:** actions  
**Page:** restore  
**Priority:** high  
**AI Context:** Interactive buttons with modals for user actions.

---

## #Introduction

Filament includes an action that is able to restoresoft-deletedEloquent records. When the trigger button is clicked, a modal asks the user for confirmation. You may use it like so:

```php
use Filament\Actions\RestoreAction;

RestoreAction::make()

```

Or if you want to add it as a table bulk action, so that the user can choose which rows to restore, useFilament\Actions\RestoreBulkAction:

```php
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->toolbarActions([
            RestoreBulkAction::make(),
        ]);
}

```

## #Redirecting after restoring

You may set up a custom redirect when the form is submitted using thesuccessRedirectUrl()method:

```php
use Filament\Actions\RestoreAction;

RestoreAction::make()
    ->successRedirectUrl(route('posts.list'))

```

## #Customizing the restore notification

When the record is successfully restored, a notification is dispatched to the user, which indicates the success of their action.

To customize the title of this notification, use thesuccessNotificationTitle()method:

```php
use Filament\Actions\RestoreAction;

RestoreAction::make()
    ->successNotificationTitle('User restored')

```

You may customize the entire notification using thesuccessNotification()method:

```php
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;

RestoreAction::make()
    ->successNotification(
       Notification::make()
            ->success()
            ->title('User restored')
            ->body('The user has been restored successfully.'),
    )

```

To disable the notification altogether, use thesuccessNotification(null)method:

```php
use Filament\Actions\RestoreAction;

RestoreAction::make()
    ->successNotification(null)

```

## #Lifecycle hooks

You can use thebefore()andafter()methods to execute code before and after a record is restored:

```php
use Filament\Actions\RestoreAction;

RestoreAction::make()
    ->before(function () {
        // ...
    })
    ->after(function () {
        // ...
    })

```

## #Improving the performance of restore bulk actions

By default, theRestoreBulkActionwill load all Eloquent records into memory, before looping over them and restoring them one by one.

If you are restoring a large number of records, you may want to use thechunkSelectedRecords()method to fetch a smaller number of records at a time. This will reduce the memory usage of your application:

```php
use Filament\Actions\RestoreBulkAction;

RestoreBulkAction::make()
    ->chunkSelectedRecords(250)

```

Filament loads Eloquent records into memory before restoring them for two reasons:
- To allow individual records in the collection to be authorized with a model policy before restoration (usingauthorizeIndividualRecords('restore'), for example).
- To ensure that model events are run when restoring records, such as therestoringandrestoredevents in a model observer.


If you do not require individual record policy authorization and model events, you can use thefetchSelectedRecords(false)method, which will not fetch the records into memory before restoring them, and instead will restore them in a single query:

```php
use Filament\Actions\RestoreBulkAction;

RestoreBulkAction::make()
    ->fetchSelectedRecords(false)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, tables, forms  
**Keywords:** buttons, modals, interactions, crud operations

*Extracted from Filament v5 Documentation - 2026-01-28*
