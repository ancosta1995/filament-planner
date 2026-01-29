# Overview

**URL:** https://filamentphp.com/docs/5.x/notifications/overview  
**Section:** notifications  
**Page:** overview  
**Priority:** low  
**AI Context:** User notification system (flash, database, broadcast).

---

## #Introduction

Notifications are sent using aNotificationobject that’s constructed through a fluent API. Calling thesend()method on theNotificationobject will dispatch the notification and display it in your application. As the session is used to flash notifications, they can be sent from anywhere in your code, including JavaScript, not just Livewire components.

```php
<?php

namespace App\Livewire;

use Filament\Notifications\Notification;
use Livewire\Component;

class EditPost extends Component
{
    public function save(): void
    {
        // ...

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}

```

## #Setting a title

The main message of the notification is shown in the title. You can set the title as follows:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->send();

```

The title text can contain basic, safe HTML elements. To generate safe HTML with Markdown, you can use theStr::markdown()helper:title(Str::markdown('Saved **successfully**'))

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .send()

```

## #Setting an icon

Optionally, a notification can have aniconthat’s displayed in front of its content. You may also set a color for the icon, which is gray by default:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->icon('heroicon-o-document-text')
    ->iconColor('success')
    ->send();

```

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .icon('heroicon-o-document-text')
    .iconColor('success')
    .send()

```

Notifications often have a status likesuccess,warning,dangerorinfo. Instead of manually setting the correspondingiconsandcolors, there’s astatus()method which you can pass the status. You may also use the dedicatedsuccess(),warning(),danger()andinfo()methods instead. So, cleaning up the above example would look like this:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->send();

```

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .send()

```

## #Setting a background color

Notifications have no background color by default. You may want to provide additional context to your notification by setting a color as follows:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->color('success')
    ->send();

```

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .color('success')
    .send()

```

## #Setting a duration

By default, notifications are shown for 6 seconds before they’re automatically closed. You may specify a custom duration value in milliseconds as follows:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->duration(5000)
    ->send();

```

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .duration(5000)
    .send()

```

If you prefer setting a duration in seconds instead of milliseconds, you can do so:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->seconds(5)
    ->send();

```

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .seconds(5)
    .send()

```

You might want some notifications to not automatically close and require the user to close them manually. This can be achieved by making the notification persistent:

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->persistent()
    ->send();

```

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .persistent()
    .send()

```

## #Setting body text

Additional notification text can be shown in thebody():

```php
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the post have been saved.')
    ->send();

```

The body text can contain basic, safe HTML elements. To generate safe HTML with Markdown, you can use theStr::markdown()helper:body(Str::markdown('Changes to the **post** have been saved.'))

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .body('Changes to the post have been saved.')
    .send()

```

## #Adding actions to notifications

Notifications supportActions, which are buttons that render below the content of the notification. They can open a URL or dispatch a Livewire event. Actions can be defined as follows:

```php
use Filament\Actions\Action;
use Filament\Notifications\Notification;

Notification::make()
    ->title('Saved successfully')
    ->success()
    ->body('Changes to the post have been saved.')
    ->actions([
        Action::make('view')
            ->button(),
        Action::make('undo')
            ->color('gray'),
    ])
    ->send();

```

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .body('Changes to the post have been saved.')
    .actions([
        new FilamentNotificationAction('view')
            .button(),
        new FilamentNotificationAction('undo')
            .color('gray'),
    ])
    .send()

```

You can learn more about how to style action buttonshere.

### #Opening URLs from notification actions

You can open a URL, optionally in a new tab, when clicking on an action:

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
            ->url(route('posts.show', $post), shouldOpenInNewTab: true),
        Action::make('undo')
            ->color('gray'),
    ])
    ->send();

```

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .body('Changes to the post have been saved.')
    .actions([
        new FilamentNotificationAction('view')
            .button()
            .url('/view')
            .openUrlInNewTab(),
        new FilamentNotificationAction('undo')
            .color('gray'),
    ])
    .send()

```

### #Dispatching Livewire events from notification actions

Sometimes you want to execute additional code when a notification action is clicked. This can be achieved by setting a Livewire event which should be dispatched on clicking the action. You may optionally pass an array of data, which will be available as parameters in the event listener on your Livewire component:

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
            ->url(route('posts.show', $post), shouldOpenInNewTab: true),
        Action::make('undo')
            ->color('gray')
            ->dispatch('undoEditingPost', [$post->id]),
    ])
    ->send();

```

You can alsodispatchSelfanddispatchTo:

```php
Action::make('undo')
    ->color('gray')
    ->dispatchSelf('undoEditingPost', [$post->id])

Action::make('undo')
    ->color('gray')
    ->dispatchTo('another_component', 'undoEditingPost', [$post->id])

```

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .body('Changes to the post have been saved.')
    .actions([
        new FilamentNotificationAction('view')
            .button()
            .url('/view')
            .openUrlInNewTab(),
        new FilamentNotificationAction('undo')
            .color('gray')
            .dispatch('undoEditingPost'),
    ])
    .send()

```

Similarly,dispatchSelfanddispatchToare also available:

```php
new FilamentNotificationAction('undo')
    .color('gray')
    .dispatchSelf('undoEditingPost')

new FilamentNotificationAction('undo')
    .color('gray')
    .dispatchTo('another_component', 'undoEditingPost')

```

### #Closing notifications from actions

After opening a URL or dispatching an event from your action, you may want to close the notification right away:

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
            ->url(route('posts.show', $post), shouldOpenInNewTab: true),
        Action::make('undo')
            ->color('gray')
            ->dispatch('undoEditingPost', [$post->id])
            ->close(),
    ])
    ->send();

```

Or with JavaScript:

```php
new FilamentNotification()
    .title('Saved successfully')
    .success()
    .body('Changes to the post have been saved.')
    .actions([
        new FilamentNotificationAction('view')
            .button()
            .url('/view')
            .openUrlInNewTab(),
        new FilamentNotificationAction('undo')
            .color('gray')
            .dispatch('undoEditingPost')
            .close(),
    ])
    .send()

```

## #Using the JavaScript objects

The JavaScript objects (FilamentNotificationandFilamentNotificationAction) are assigned towindow.FilamentNotificationandwindow.FilamentNotificationAction, so they are available in on-page scripts.

You may also import them in a bundled JavaScript file:

```php
import { Notification, NotificationAction } from '../../vendor/filament/notifications/dist/index.js'

// ...

```

## #Closing a notification with JavaScript

Once a notification has been sent, you can close it on demand by dispatching a browser event on the window calledclose-notification.

The event needs to contain the ID of the notification you sent. To get the ID, you can use thegetId()method on theNotificationobject:

```php
use Filament\Notifications\Notification;

$notification = Notification::make()
    ->title('Hello')
    ->persistent()
    ->send()

$notificationId = $notification->getId()

```

To close the notification, you can dispatch the event from Livewire:

```php
$this->dispatch('close-notification', id: $notificationId);

```

Or from JavaScript, in this case Alpine.js:

```php
<button x-on:click="$dispatch('close-notification', { id: notificationId })" type="button">
    Close Notification
</button>

```

If you are able to retrieve the notification ID, persist it, and then use it to close the notification, that is the recommended approach, as IDs are generated uniquely, and you will not risk closing the wrong notification. However, if it is not possible to persist the random ID, you can pass in a custom ID when sending the notification:

```php
use Filament\Notifications\Notification;

Notification::make('greeting')
    ->title('Hello')
    ->persistent()
    ->send()

```

In this case, you can close the notification by dispatching the event with the custom ID:

```php
<button x-on:click="$dispatch('close-notification', { id: 'greeting' })" type="button">
    Close Notification
</button>

```

Please be aware that if you send multiple notifications with the same ID, you may experience unexpected side effects, so random IDs are recommended.

## #Positioning notifications

You can configure the alignment of the notifications in a service provider or middleware, by callingNotifications::alignment()andNotifications::verticalAlignment(). You can passAlignment::Start,Alignment::Center,Alignment::End,VerticalAlignment::Start,VerticalAlignment::CenterorVerticalAlignment::End:

```php
use Filament\Notifications\Livewire\Notifications;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\VerticalAlignment;

Notifications::alignment(Alignment::Start);
Notifications::verticalAlignment(VerticalAlignment::End);

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** actions  
**Keywords:** alerts, messages, notifications, feedback

*Extracted from Filament v5 Documentation - 2026-01-28*
