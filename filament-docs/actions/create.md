# Create action

**URL:** https://filamentphp.com/docs/5.x/actions/create  
**Section:** actions  
**Page:** create  
**Priority:** high  
**AI Context:** Interactive buttons with modals for user actions.

---

## #Introduction

Filament includes an action that is able to create Eloquent records. When the trigger button is clicked, a modal will open with a form inside. The user fills the form, and that data is validated and saved into the database. You may use it like so:

```php
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;

CreateAction::make()
    ->schema([
        TextInput::make('title')
            ->required()
            ->maxLength(255),
        // ...
    ])

```

## #Customizing data before saving

Sometimes, you may wish to modify form data before it is finally saved to the database. To do this, you may use themutateDataUsing()method, which has access to the$dataas an array, and returns the modified version:

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->mutateDataUsing(function (array $data): array {
        $data['user_id'] = auth()->id();

        return $data;
    })

```

## #Customizing the creation process

You can tweak how the record is created with theusing()method:

```php
use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;

CreateAction::make()
    ->using(function (array $data, string $model): Model {
        return $model::create($data);
    })

```

$modelis the class name of the model, but you can replace this with your own hard-coded class if you wish.

## #Redirecting after creation

You may set up a custom redirect when the form is submitted using thesuccessRedirectUrl()method:

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->successRedirectUrl(route('posts.list'))

```

If you want to redirect using the created record, use the$recordparameter:

```php
use Filament\Actions\CreateAction;
use Illuminate\Database\Eloquent\Model;

CreateAction::make()
    ->successRedirectUrl(fn (Model $record): string => route('posts.edit', [
        'post' => $record,
    ]))

```

## #Customizing the save notification

When the record is successfully created, a notification is dispatched to the user, which indicates the success of their action.

To customize the title of this notification, use thesuccessNotificationTitle()method:

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->successNotificationTitle('User registered')

```

You may customize the entire notification using thesuccessNotification()method:

```php
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;

CreateAction::make()
    ->successNotification(
       Notification::make()
            ->success()
            ->title('User registered')
            ->body('The user has been created successfully.'),
    )

```

To disable the notification altogether, use thesuccessNotification(null)method:

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->successNotification(null)

```

## #Lifecycle hooks

Hooks may be used to execute code at various points within the action’s lifecycle, like before a form is saved.

There are several available hooks:

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->beforeFormFilled(function () {
        // Runs before the form fields are populated with their default values.
    })
    ->afterFormFilled(function () {
        // Runs after the form fields are populated with their default values.
    })
    ->beforeFormValidated(function () {
        // Runs before the form fields are validated when the form is submitted.
    })
    ->afterFormValidated(function () {
        // Runs after the form fields are validated when the form is submitted.
    })
    ->before(function () {
        // Runs before the form fields are saved to the database.
    })
    ->after(function () {
        // Runs after the form fields are saved to the database.
    })

```

## #Halting the creation process

At any time, you may call$action->halt()from inside a lifecycle hook or mutation method, which will halt the entire creation process:

```php
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;

CreateAction::make()
    ->before(function (CreateAction $action, Post $record) {
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

## #Using a wizard

You may easily transform the creation process into a multistep wizard. Instead of using aschema(), define asteps()array and pass yourStepobjects:

```php
use Filament\Actions\CreateAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard\Step;

CreateAction::make()
    ->steps([
        Step::make('Name')
            ->description('Give the category a unique name')
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->disabled()
                    ->required()
                    ->unique(Category::class, 'slug'),
            ])
            ->columns(2),
        Step::make('Description')
            ->description('Add some extra details')
            ->schema([
                MarkdownEditor::make('description'),
            ]),
        Step::make('Visibility')
            ->description('Control who can view it')
            ->schema([
                Toggle::make('is_visible')
                    ->label('Visible to customers.')
                    ->default(true),
            ]),
    ])

```

Now, create a new record to see your wizard in action! Edit will still use the form defined within the resource class.

If you’d like to allow free navigation, so all the steps are skippable, use theskippableSteps()method:

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->steps([
        // ...
    ])
    ->skippableSteps()

```

## #Creating another record

### #Modifying the create another action

If you’d like to modify the “create another” action, you may use thecreateAnotherAction()method, passing a function that returns an action. All methods that are available tocustomize action trigger buttonscan be used:

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->createAnotherAction(fn (Action $action): Action => $action->label('Custom create another label'))

```

### #Disabling create another

If you’d like to remove the “create another” button from the modal, you can use thecreateAnother(false)method:

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->createAnother(false)

```

### #Preserving data when creating another

By default, when the user uses the “create and create another” feature, all the form data is cleared so the user can start fresh. If you’d like to preserve some of the data in the form, you may use thepreserveFormDataWhenCreatingAnother()method, passing an array of fields to preserve:

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->preserveFormDataWhenCreatingAnother(['is_admin', 'organization'])

```

Alternatively, you can define a function that returns an array of the$datato preserve:

```php
use Filament\Actions\CreateAction;
use Illuminate\Support\Arr;

CreateAction::make()
    ->preserveFormDataWhenCreatingAnother(fn (array $data): array => Arr::only($data, ['is_admin', 'organization']))

```

To preserve all the data, return the entire$dataarray:

```php
use Filament\Actions\CreateAction;

CreateAction::make()
    ->preserveFormDataWhenCreatingAnother(fn (array $data): array => $data)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, tables, forms  
**Keywords:** buttons, modals, interactions, crud operations

*Extracted from Filament v5 Documentation - 2026-01-28*
