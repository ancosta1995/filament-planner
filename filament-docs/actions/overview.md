# Overview

**URL:** https://filamentphp.com/docs/5.x/actions/overview  
**Section:** actions  
**Page:** overview  
**Priority:** high  
**AI Context:** Interactive buttons with modals for user actions.

---

## #Introduction

“Action” is a word that is used quite a bit within the Laravel community. Traditionally, action PHP classes handle “doing” something in your application’s business logic. For instance, logging a user in, sending an email, or creating a new user record in the database.

In Filament, actions also handle “doing” something in your app. However, they are a bit different from traditional actions. They are designed to be used in the context of a user interface. For instance, you might have a button to delete a client record, which opens a modal to confirm your decision. When the user clicks the “Delete” button in the modal, the client is deleted. This whole workflow is an “action”.

```php
use Filament\Actions\Action;

Action::make('delete')
    ->requiresConfirmation()
    ->action(fn () => $this->client->delete())

```

Actions can also collect extra information from the user. For instance, you might have a button to email a client. When the user clicks the button, a modal opens to collect the email subject and body. When the user clicks the “Send” button in the modal, the email is sent:

```php
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Mail;

Action::make('sendEmail')
    ->schema([
        TextInput::make('subject')->required(),
        RichEditor::make('body')->required(),
    ])
    ->action(function (array $data) {
        Mail::to($this->client)
            ->send(new GenericEmail(
                subject: $data['subject'],
                body: $data['body'],
            ));
    })

```

Usually, actions get executed without redirecting the user away from the page. This is because we extensively use Livewire. However, actions can be much simpler, and don’t even need a modal. You can pass a URL to an action, and when the user clicks on the button, they are redirected to that page:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))

```

The entire look of the action’s trigger button and the modal is customizable using fluent PHP methods. We provide a sensible and consistent styling for the UI, but all of this is customizable with CSS.

## #Available actions

Filament includes several actions that you can add to your app. Their aim is to simplify the most common Eloquent-related actions:
- Create
- Edit
- View
- Delete
- Replicate
- Force-delete
- Restore
- Import
- Export


You can also create your own actions to do anything, these are just common ones that we include out of the box.

## #Choosing a trigger style

Out of the box, action triggers have 4 styles - “button”, “link”, “icon button”, and “badge”.

“Button” triggers have a background color, label, and optionally anicon. Usually, this is the default button style, but you can use it manually with thebutton()method:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->button()

```

“Link” triggers have no background color. They must have a label and optionally anicon. They look like a link that you might find embedded within text. You can switch to that style with thelink()method:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->link()

```

“Icon button” triggers are circular buttons with aniconand no label. You can switch to that style with theiconButton()method:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->icon('heroicon-m-pencil-square')
    ->iconButton()

```

“Badge” triggers have a background color, label, and optionally anicon. You can use a badge as trigger using thebadge()method:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->badge()

```

### #Using an icon button on mobile devices only

You may want to use a button style with a label on desktop, but remove the label on mobile. This will transform it into an icon button. You can do this with thelabeledFrom()method, passing in the responsivebreakpointat which you want the label to be added to the button:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->icon('heroicon-m-pencil-square')
    ->button()
    ->labeledFrom('md')

```

## #Setting a label

By default, the label of the trigger button is generated from its name. You may customize this using thelabel()method:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->label('Edit post')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))

```

## #Setting a color

Buttons may have acolorto indicate their significance:

```php
use Filament\Actions\Action;

Action::make('delete')
    ->color('danger')

```

## #Setting a size

Buttons come in 3 sizes -Size::Small,Size::MediumorSize::Large. You can change the size of the action’s trigger using thesize()method:

```php
use Filament\Actions\Action;
use Filament\Support\Enums\Size;

Action::make('create')
    ->size(Size::Large)

```

## #Setting an icon

Buttons may have aniconto add more detail to the UI. You can set the icon using theicon()method:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->icon('heroicon-m-pencil-square')

```

You can also change the icon’s position to be after the label instead of before it, using theiconPosition()method:

```php
use Filament\Actions\Action;
use Filament\Support\Enums\IconPosition;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->icon('heroicon-m-pencil-square')
    ->iconPosition(IconPosition::After)

```

## #Authorization

You may conditionally show or hide actions for certain users. To do this, you can use either thevisible()orhidden()methods:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->visible(auth()->user()->can('update', $this->post))

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->hidden(! auth()->user()->can('update', $this->post))

```

This is useful for authorization of certain actions to only users who have permission.

### #Authorization using a policy

You can use a policy to authorize an action. To do this, pass the name of the policy method to theauthorize()method, and Filament will use the current Eloquent model for that action to find the correct policy:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->authorize('update')

```

NOTE

If you’re using an action in a panel resource or relation manager, you don’t need to use theauthorize()method, since Filament will automatically read the policy based on the resource model for the built-in actions likeCreateAction,EditActionandDeleteAction. For more information, visit theresource authorizationsection.

If your policy method returns aresponse message, you can disable the action instead of hiding it, and add a tooltip containing the message, using theauthorizationTooltip()method:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->authorize('update')
    ->authorizationTooltip()

```

You may instead allow the action to still be clickable even if the user is not authorized, but send a notification containing the response message, using theauthorizationNotification()method:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->authorize('update')
    ->authorizationNotification()

```

### #Disabling a button

If you want to disable a button instead of hiding it, you can use thedisabled()method:

```php
use Filament\Actions\Action;

Action::make('delete')
    ->disabled()

```

You can conditionally disable a button by passing a boolean to it:

```php
use Filament\Actions\Action;

Action::make('delete')
    ->disabled(! auth()->user()->can('delete', $this->post))

```

## #Registering keybindings

You can attach keyboard shortcuts to trigger buttons. These use the same key codes asMousetrap:

```php
use Filament\Actions\Action;

Action::make('save')
    ->action(fn () => $this->save())
    ->keyBindings(['command+s', 'ctrl+s'])

```

## #Adding a badge to the corner of the button

You can add a badge to the corner of the button, to display whatever you want. It’s useful for displaying a count of something, or a status indicator:

```php
use Filament\Actions\Action;

Action::make('filter')
    ->iconButton()
    ->icon('heroicon-m-funnel')
    ->badge(5)

```

You can also pass acolorto be used for the badge:

```php
use Filament\Actions\Action;

Action::make('filter')
    ->iconButton()
    ->icon('heroicon-m-funnel')
    ->badge(5)
    ->badgeColor('success')

```

## #Outlined button style

When you’re using the “button” trigger style, you might wish to make it less prominent. You could use a differentcolor, but sometimes you might want to make it outlined instead. You can do this with theoutlined()method:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->button()
    ->outlined()

```

Optionally, you may pass a boolean value to control if the label should be hidden or not:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->button()
    ->outlined(FeatureFlag::active())

```

## #Adding extra HTML attributes to an action

You can pass extra HTML attributes to the action via theextraAttributes()method, which will be merged onto its outer HTML element. The attributes should be represented by an array, where the key is the attribute name and the value is the attribute value:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))
    ->extraAttributes([
        'title' => 'Edit this post',
    ])

```

TIP

By default, callingextraAttributes()multiple times will overwrite the previous attributes. If you wish to merge the attributes instead, you can passmerge: trueto the method.

## #Rate limiting actions

You can rate limit actions by using therateLimit()method. This method accepts the number of attempts per minute that a user IP address can make. If the user exceeds this limit, the action will not run and a notification will be shown:

```php
use Filament\Actions\Action;

Action::make('delete')
    ->rateLimit(5)

```

If the action opens a modal, the rate limit will be applied when the modal is submitted.

If an action is opened with arguments or for a specific Eloquent record, the rate limit will apply to each unique combination of arguments or record for each action. The rate limit is also unique to the current Livewire component / page in a panel.

## #Customizing the rate limited notification

When an action is rate limited, a notification is dispatched to the user, which indicates the rate limit.

To customize the title of this notification, use therateLimitedNotificationTitle()method:

```php
use Filament\Actions\DeleteAction;

DeleteAction::make()
    ->rateLimit(5)
    ->rateLimitedNotificationTitle('Slow down!')

```

You may customize the entire notification using therateLimitedNotification()method:

```php
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;

DeleteAction::make()
    ->rateLimit(5)
    ->rateLimitedNotification(
       fn (TooManyRequestsException $exception): Notification => Notification::make()
            ->warning()
            ->title('Slow down!')
            ->body("You can try deleting again in {$exception->secondsUntilAvailable} seconds."),
    )

```

### #Customizing the rate limit behavior

If you wish to customize the rate limit behavior, you can use Laravel’srate limitingfeatures and Filament’sflash notificationstogether in the action.

If you want to rate limit immediately when an action modal is opened, you can do so in themountUsing()method:

```php
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\RateLimiter;

Action::make('delete')
    ->mountUsing(function () {
        if (RateLimiter::tooManyAttempts(
            $rateLimitKey = 'delete:' . auth()->id(),
            maxAttempts: 5,
        )) {
            Notification::make()
                ->title('Too many attempts')
                ->body('Please try again in ' . RateLimiter::availableIn($rateLimitKey) . ' seconds.')
                ->danger()
                ->send();
                
            return;
        }
        
         RateLimiter::hit($rateLimitKey);
    })

```

If you want to rate limit when an action is run, you can do so in theaction()method:

```php
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\RateLimiter;

Action::make('delete')
    ->action(function () {
        if (RateLimiter::tooManyAttempts(
            $rateLimitKey = 'delete:' . auth()->id(),
            maxAttempts: 5,
        )) {
            Notification::make()
                ->title('Too many attempts')
                ->body('Please try again in ' . RateLimiter::availableIn($rateLimitKey) . ' seconds.')
                ->danger()
                ->send();
                
            return;
        }
        
         RateLimiter::hit($rateLimitKey);
        
        // ...
    })

```

## #Using actions in schemas

Action objects can be inserted anywhere in aschema, such as inform field slots,section headers and footers, or alongsideprime components. When an action is used in a schema, it has access to the schema’s state viautility injection- you can use$schemaGetand$schemaSetin closures to read and modify form field values.

```php
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

TextInput::make('title')
    ->afterContent(
        Action::make('generateSlug')
            ->action(function (Get $schemaGet, Set $schemaSet) {
                $schemaSet('slug', str($schemaGet('title'))->slug());
            })
    )

TextInput::make('slug')

```

### #Running JavaScript when an action is clicked

If you need a simple action that runs JavaScript directly in the browser without making a network request, you can use theactionJs()method. This is useful for simple interactions like updating form field values instantly:

```php
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

TextInput::make('title')
    ->live(onBlur: true)
    ->afterContent(
        Action::make('generateSlug')
            ->actionJs(<<<'JS'
                $set('slug', $get('title').toLowerCase().replaceAll(' ', '-'))
                JS)
    )

TextInput::make('slug')

```

The JavaScript string has access to$get()and$set()utilities, which allow you to read and modify the state of form fields in the schema.

NOTE

When usingactionJs(), the action cannot open a modal or perform any server-side processing. It is intended for simple client-side interactions only. If you need to run PHP code, use theaction()method instead.

NOTE

Any JavaScript string passed to theactionJs()method will be executed in the browser, so you should never add user input directly into the string, as it could lead to cross-site scripting (XSS) vulnerabilities. User input from$get()should never be evaluated as JavaScript code, but is safe to use as a string value.

## #Action utility injection

The vast majority of methods used to configure actions accept functions as parameters instead of hardcoded values:

```php
use Filament\Actions\Action;

Action::make('edit')
    ->label('Edit post')
    ->url(fn (): string => route('posts.edit', ['post' => $this->post]))

```

This alone unlocks many customization possibilities.

The package is also able to inject many utilities to use inside these functions, as parameters. All customization methods that accept functions as arguments can inject utilities.

These injected utilities require specific parameter names to be used. Otherwise, Filament doesn’t know what to inject.

### #Injecting the current modal form data

If you wish to access the currentmodal form data, define a$dataparameter:

```php
function (array $data) {
    // ...
}

```

Be aware that this will be empty if the modal has not been submitted yet.

### #Injecting the Eloquent record

If your action is associated with an Eloquent record, for example if it is on a table row, you can inject the record using a$recordparameter:

```php
use Illuminate\Database\Eloquent\Model;

function (Model $record) {
    // ...
}

```

### #Injecting the current arguments

If you wish to access thecurrent argumentsthat have been passed to the action, define an$argumentsparameter:

```php
function (array $arguments) {
    // ...
}

```

### #Injecting utilities from a schema

You can access various additional utilities if your action is defined in a schema:
- $schema- The schema instance that the action belongs to.
- $schemaComponent- The schema component instance that the action belongs to.
- $schemaComponentState- The current value of the schema component.
- $schemaState- The current value of the schema that this action belongs to, like the current repeater item.
- $schemaGet- A function for retrieving values from the schema data. Validation is not run on form fields.
- $schemaSet- A function for setting values in the schema data.
- $schemaOperation- The current operation being performed by the schema. Usuallycreate,edit, orview.


For more information, please visit theSchemas section.

### #Injecting the current Livewire component instance

If you wish to access the current Livewire component instance that the action belongs to, define a$livewireparameter:

```php
use Livewire\Component;

function (Component $livewire) {
    // ...
}

```

### #Injecting the current action instance

If you wish to access the current action instance, define a$actionparameter:

```php
function (Action $action) {
    // ...
}

```

### #Injecting multiple utilities

The parameters are injected dynamically using reflection, so you are able to combine multiple parameters in any order:

```php
use Livewire\Component;

function (array $arguments, Component $livewire) {
    // ...
}

```

### #Injecting dependencies from Laravel’s container

You may inject anything from Laravel’s container like normal, alongside utilities:

```php
use Illuminate\Http\Request;

function (Request $request, array $arguments) {
    // ...
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, tables, forms  
**Keywords:** buttons, modals, interactions, crud operations

*Extracted from Filament v5 Documentation - 2026-01-28*
