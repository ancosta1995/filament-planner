# Replicate action

**URL:** https://filamentphp.com/docs/5.x/actions/replicate  
**Section:** actions  
**Page:** replicate  
**Priority:** high  
**AI Context:** Interactive buttons with modals for user actions.

---

## #Introduction

Filament includes an action that is able toreplicateEloquent records. You may use it like so:

```php
use Filament\Actions\ReplicateAction;

ReplicateAction::make()

```

## #Excluding attributes

TheexcludeAttributes()method is used to instruct the action which columns should be excluded from replication:

```php
use Filament\Actions\ReplicateAction;

ReplicateAction::make()
    ->excludeAttributes(['slug'])

```

## #Customizing data before filling the form

You may wish to modify the data from a record before it is filled into the form. To do this, you may use themutateRecordDataUsing()method to modify the$dataarray, and return the modified version before it is filled into the form:

```php
use Filament\Actions\ReplicateAction;

ReplicateAction::make()
    ->mutateRecordDataUsing(function (array $data): array {
        $data['user_id'] = auth()->id();

        return $data;
    })

```

## #Redirecting after replication

You may set up a custom redirect when the form is submitted using thesuccessRedirectUrl()method:

```php
use Filament\Actions\ReplicateAction;

ReplicateAction::make()
    ->successRedirectUrl(route('posts.list'))

```

## #Customizing the replicate notification

When the record is successfully replicated, a notification is dispatched to the user, which indicates the success of their action.

To customize the title of this notification, use thesuccessNotificationTitle()method:

```php
use Filament\Actions\ReplicateAction;

ReplicateAction::make()
    ->successNotificationTitle('Category replicated')

```

You may customize the entire notification using thesuccessNotification()method:

```php
use Filament\Actions\ReplicateAction;
use Filament\Notifications\Notification;

ReplicateAction::make()
    ->successNotification(
       Notification::make()
            ->success()
            ->title('Category replicated')
            ->body('The category has been replicated successfully.'),
    )

```

To disable the notification altogether, use thesuccessNotification(null)method:

```php
use Filament\Actions\RestoreAction;

ReplicateAction::make()
    ->successNotification(null)

```

## #Lifecycle hooks

Hooks may be used to execute code at various points within the action’s lifecycle, like before the replica is saved.

```php
use Filament\Actions\ReplicateAction;
use Illuminate\Database\Eloquent\Model;

ReplicateAction::make()
    ->before(function () {
        // Runs before the record has been replicated.
    })
    ->beforeReplicaSaved(function (Model $replica): void {
        // Runs after the record has been replicated but before it is saved to the database.
    })
    ->after(function (Model $replica): void {
        // Runs after the replica has been saved to the database.
    })

```

## #Halting the replication process

At any time, you may call$action->halt()from inside a lifecycle hook, which will halt the entire replication process:

```php
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\ReplicateAction;
use Filament\Notifications\Notification;

ReplicateAction::make()
    ->before(function (ReplicateAction $action, Post $record) {
        if (! $record->team->subscribed()) {
            Notification::make()
                ->warning()
                ->title('You don\'t have an active subscription!')
                ->body('Choose a plan to continue.')
                ->persistent()
                ->actions([
                    Action::make('subscribe')
                        ->button()
                        ->url(route('subscribe'), shouldOpenInNewTab: true),
                ])
                ->send();
        
            $action->halt();
        }
    })

```

If you’d like the action modal to close too, you can completelycancel()the action instead of halting it:

```php
$action->cancel();

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, tables, forms  
**Keywords:** buttons, modals, interactions, crud operations

*Extracted from Filament v5 Documentation - 2026-01-28*
