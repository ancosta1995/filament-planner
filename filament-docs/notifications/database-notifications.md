# Database notifications

**URL:** https://filamentphp.com/docs/5.x/notifications/database-notifications  
**Section:** notifications  
**Page:** database-notifications  
**Priority:** low  
**AI Context:** User notification system (flash, database, broadcast).

---

## #Setting up the notifications database table

Before we start, make sure that theLaravel notifications tableis added to your database:

```php
php artisan make:notifications-table

```
> If you’re using PostgreSQL, make sure that thedatacolumn in the migration is usingjson():$table->json('data').


If you’re using PostgreSQL, make sure that thedatacolumn in the migration is usingjson():$table->json('data').
> If you’re using UUIDs for yourUsermodel, make sure that yournotifiablecolumn is usinguuidMorphs():$table->uuidMorphs('notifiable').


If you’re using UUIDs for yourUsermodel, make sure that yournotifiablecolumn is usinguuidMorphs():$table->uuidMorphs('notifiable').

## #Enabling database notifications in a panel

If you’d like to receive database notifications in a panel, you can enable them in theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->databaseNotifications();
}

```

## #Sending database notifications

There are several ways to send database notifications, depending on which one suits you best.

You may use our fluent API:

```php
use Filament\Notifications\Notification;

$recipient = auth()->user();

Notification::make()
    ->title('Saved successfully')
    ->sendToDatabase($recipient);

```

Or, use thenotify()method:

```php
use Filament\Notifications\Notification;

$recipient = auth()->user();

$recipient->notify(
    Notification::make()
        ->title('Saved successfully')
        ->toDatabase(),
);

```
> Laravel sends database notifications using the queue. Ensure your queue is running in order to receive the notifications.


Laravel sends database notifications using the queue. Ensure your queue is running in order to receive the notifications.

Alternatively, use a traditionalLaravel notification classby returning the notification from thetoDatabase()method:

```php
use App\Models\User;
use Filament\Notifications\Notification;

public function toDatabase(User $notifiable): array
{
    return Notification::make()
        ->title('Saved successfully')
        ->getDatabaseMessage();
}

```

## #Moving the database notifications trigger to the panel sidebar

By default, the database notifications trigger is positioned in the topbar. If the topbar is disabled, it is added to the sidebar.

You can choose to always move it to the sidebar by passing apositionargument to thedatabaseNotifications()method in theconfiguration:

```php
use Filament\Enums\DatabaseNotificationsPosition;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->databaseNotifications(position: DatabaseNotificationsPosition::Sidebar);
}

```

## #Receiving database notifications

Without any setup, new database notifications will only be received when the page is first loaded.

### #Polling for new database notifications

Polling is the practice of periodically making a request to the server to check for new notifications. This is a good approach as the setup is simple, but some may say that it is not a scalable solution as it increases server load.

By default, Livewire polls for new notifications every 30 seconds:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->databaseNotifications()
        ->databaseNotificationsPolling('30s');
}

```

You may completely disable polling if you wish:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->databaseNotifications()
        ->databaseNotificationsPolling(null);
}

```

### #Using Echo to receive new database notifications with websockets

Websockets are a more efficient way to receive new notifications in real-time. To set up websockets, you mustconfigure itin the panel first.

Once websockets are set up, you can automatically dispatch aDatabaseNotificationsSentevent by setting theisEventDispatchedparameter totruewhen sending the notification. This will trigger the immediate fetching of new notifications for the user:

```php
use Filament\Notifications\Notification;

$recipient = auth()->user();

Notification::make()
    ->title('Saved successfully')
    ->sendToDatabase($recipient, isEventDispatched: true);

```

## #Marking database notifications as read

There is a button at the top of the modal to mark all notifications as read at once. You may also addActionsto notifications, which you can use to mark individual notifications as read. To do this, use themarkAsRead()method on the action:

```php
use Filament\Actions\Action;
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the post have been saved.')
    ->actions([
        Action::make('view')
            ->button()
            ->markAsRead(),
    ])
    ->send();

```

Alternatively, you may use themarkAsUnread()method to mark a notification as unread:

```php
use Filament\Actions\Action;
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the post have been saved.')
    ->actions([
        Action::make('markAsUnread')
            ->button()
            ->markAsUnread(),
    ])
    ->send();

```

## #Opening the database notifications modal

You can open the database notifications modal from anywhere by dispatching anopen-modalbrowser event:

```php
<button
    x-data="{}"
    x-on:click="$dispatch('open-modal', { id: 'database-notifications' })"
    type="button"
>
    Notifications
</button>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** actions  
**Keywords:** alerts, messages, notifications, feedback

*Extracted from Filament v5 Documentation - 2026-01-28*
