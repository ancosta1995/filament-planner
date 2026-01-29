# Modals

**URL:** https://filamentphp.com/docs/5.x/actions/modals  
**Section:** actions  
**Page:** modals  
**Priority:** high  
**AI Context:** Interactive buttons with modals for user actions.

---

## #Introduction

Actions may require additional confirmation or input from the user before they run. You may open a modal before an action is executed to do this.

## #Confirmation modals

You may require confirmation before an action is run using therequiresConfirmation()method. This is useful for particularly destructive actions, such as those that delete records.

```php
use App\Models\Post;
use Filament\Actions\Action;

Action::make('delete')
    ->action(fn (Post $record) => $record->delete())
    ->requiresConfirmation()

```

NOTE

The confirmation modal is not available when aurl()is set instead of anaction(). Instead, you should redirect to the URL within theaction()closure.

## #Controlling modal content

### #Customizing the modal’s heading, description, and submit action label

You may customize the heading, description and label of the submit button in the modal:

```php
use App\Models\Post;
use Filament\Actions\Action;

Action::make('delete')
    ->action(fn (Post $record) => $record->delete())
    ->requiresConfirmation()
    ->modalHeading('Delete post')
    ->modalDescription('Are you sure you\'d like to delete this post? This cannot be undone.')
    ->modalSubmitActionLabel('Yes, delete it')

```

### #Rendering a schema in a modal

Filament allows you to render aschemain a modal, which allows you to render any of the available components to build a UI. Usually, it is useful to build a form in the schema that can collect extra information from the user before the action runs, but any UI can be rendered:

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

Action::make('viewUser')
    ->schema([
        Grid::make(2)
            ->schema([
                Section::make('Details')
                    ->schema([
                        TextInput::make('name'),
                        Select::make('position')
                            ->options([
                                'developer' => 'Developer',
                                'designer' => 'Designer',
                            ]),
                        Checkbox::make('is_admin'),
                    ]),
                Section::make('Auditing')
                    ->schema([
                        TextEntry::make('created_at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->dateTime(),
                    ]),
            ]),
    ])

```

#### #Rendering a form in a modal

You may useform fieldto create action modal forms. The data from the form is available in the$dataarray of theaction()closure:

```php
use App\Models\Post;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;

Action::make('updateAuthor')
    ->schema([
        Select::make('authorId')
            ->label('Author')
            ->options(User::query()->pluck('name', 'id'))
            ->required(),
    ])
    ->action(function (array $data, Post $record): void {
        $record->author()->associate($data['authorId']);
        $record->save();
    })

```

##### #Filling the form with existing data

You may fill the form with existing data, using thefillForm()method:

```php
use App\Models\Post;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;

Action::make('updateAuthor')
    ->fillForm(fn (Post $record): array => [
        'authorId' => $record->author->id,
    ])
    ->schema([
        Select::make('authorId')
            ->label('Author')
            ->options(User::query()->pluck('name', 'id'))
            ->required(),
    ])
    ->action(function (array $data, Post $record): void {
        $record->author()->associate($data['authorId']);
        $record->save();
    })

```

##### #Disabling all form fields

You may wish to disable all form fields in the modal, ensuring the user cannot edit them. You may do so using thedisabledForm()method:

```php
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

Action::make('approvePost')
    ->schema([
        TextInput::make('title'),
        Textarea::make('content'),
    ])
    ->disabledForm()
    ->action(function (Post $record): void {
        $record->approve();
    })

```

#### #Rendering a wizard in a modal

You may create amultistep form wizardinside a modal. Instead of using aschema(), define asteps()array and pass yourStepobjects:

```php
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard\Step;

Action::make('create')
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

### #Adding an icon inside the modal

You may add aniconinside the modal using themodalIcon()method:

```php
use App\Models\Post;
use Filament\Actions\Action;

Action::make('delete')
    ->action(fn (Post $record) => $record->delete())
    ->requiresConfirmation()
    ->modalIcon('heroicon-o-trash')

```

By default, the icon will inherit the color of the action button. You may customize the color of the icon using themodalIconColor()method:

```php
use App\Models\Post;
use Filament\Actions\Action;

Action::make('delete')
    ->action(fn (Post $record) => $record->delete())
    ->requiresConfirmation()
    ->color('danger')
    ->modalIcon('heroicon-o-trash')
    ->modalIconColor('warning')

```

### #Customizing the alignment of modal content

By default, modal content will be aligned to the start, or centered if the modal isxsorsminwidth. If you wish to change the alignment of content in a modal, you can use themodalAlignment()method and pass itAlignment::StartorAlignment::Center:

```php
use Filament\Actions\Action;
use Filament\Support\Enums\Alignment;

Action::make('updateAuthor')
    ->schema([
        // ...
    ])
    ->action(function (array $data): void {
        // ...
    })
    ->modalAlignment(Alignment::Center)

```

### #Making the modal header sticky

The header of a modal scrolls out of view with the modal content when it overflows the modal size. However, slide-overs have a sticky header that’s always visible. You may control this behavior usingstickyModalHeader():

```php
use Filament\Actions\Action;

Action::make('updateAuthor')
    ->schema([
        // ...
    ])
    ->action(function (array $data): void {
        // ...
    })
    ->stickyModalHeader()

```

### #Making the modal footer sticky

The footer of a modal is rendered inline after the content by default. Slide-overs, however, have a sticky footer that always shows when scrolling the content. You may enable this for a modal too usingstickyModalFooter():

```php
use Filament\Actions\Action;

Action::make('updateAuthor')
    ->schema([
        // ...
    ])
    ->action(function (array $data): void {
        // ...
    })
    ->stickyModalFooter()

```

### #Custom modal content

You may define custom content to be rendered inside your modal, which you can specify by passing a Blade view into themodalContent()method:

```php
use App\Models\Post;
use Filament\Actions\Action;

Action::make('advance')
    ->action(fn (Post $record) => $record->advance())
    ->modalContent(view('filament.pages.actions.advance'))

```

#### #Passing data to the custom modal content

You can pass data to the view by returning it from a function. For example, if the$recordof an action is set, you can pass that through to the view:

```php
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;

Action::make('advance')
    ->action(fn (Contract $record) => $record->advance())
    ->modalContent(fn (Contract $record): View => view(
        'filament.pages.actions.advance',
        ['record' => $record],
    ))

```

#### #Adding custom modal content below the form

By default, the custom content is displayed above the modal form if there is one, but you can add content below usingmodalContentFooter()if you wish:

```php
use App\Models\Post;
use Filament\Actions\Action;

Action::make('advance')
    ->action(fn (Post $record) => $record->advance())
    ->modalContentFooter(view('filament.pages.actions.advance'))

```

#### #Adding an action to custom modal content

You can add an action button to your custom modal content, which is useful if you want to add a button that performs an action other than the main action. You can do this by registering an action with theregisterModalActions()method, and then passing it to the view:

```php
use App\Models\Post;
use Filament\Actions\Action;
use Illuminate\Contracts\View\View;

Action::make('advance')
    ->registerModalActions([
        Action::make('report')
            ->requiresConfirmation()
            ->action(fn (Post $record) => $record->report()),
    ])
    ->action(fn (Post $record) => $record->advance())
    ->modalContent(fn (Action $action): View => view(
        'filament.pages.actions.advance',
        ['action' => $action],
    ))

```

Now, in the view file, you can render the action button by callinggetModalAction():

```php
<div>
    {{ $action->getModalAction('report') }}
</div>

```

## #Using a slide-over instead of a modal

You can open a “slide-over” dialog instead of a modal by using theslideOver()method:

```php
use Filament\Actions\Action;

Action::make('updateAuthor')
    ->schema([
        // ...
    ])
    ->action(function (array $data): void {
        // ...
    })
    ->slideOver()

```

Instead of opening in the center of the screen, the modal content will now slide in from the right and consume the entire height of the browser.

## #Changing the modal width

You can change the width of the modal by using themodalWidth()method. Options correspond toTailwind’s max-width scale. The options areExtraSmall,Small,Medium,Large,ExtraLarge,TwoExtraLarge,ThreeExtraLarge,FourExtraLarge,FiveExtraLarge,SixExtraLarge,SevenExtraLarge, andScreen:

```php
use Filament\Actions\Action;
use Filament\Support\Enums\Width;

Action::make('updateAuthor')
    ->schema([
        // ...
    ])
    ->action(function (array $data): void {
        // ...
    })
    ->modalWidth(Width::FiveExtraLarge)

```

## #Executing code when the modal opens

You may execute code within a closure when the modal opens, by passing it to themountUsing()method:

```php
use Filament\Actions\Action;
use Filament\Schemas\Schema;

Action::make('create')
    ->mountUsing(function (Schema $form) {
        $form->fill();

        // ...
    })

```
> ThemountUsing()method, by default, is used by Filament to initialize theform. If you override this method, you will need to call$form->fill()to ensure the form is initialized correctly. If you wish to populate the form with data, you can do so by passing an array to thefill()method, instead ofusingfillForm()on the action itself.


ThemountUsing()method, by default, is used by Filament to initialize theform. If you override this method, you will need to call$form->fill()to ensure the form is initialized correctly. If you wish to populate the form with data, you can do so by passing an array to thefill()method, instead ofusingfillForm()on the action itself.

## #Customizing the action buttons in the footer of the modal

By default, there are two actions in the footer of a modal. The first is a button to submit, which executes theaction(). The second button closes the modal and cancels the action.

### #Modifying the default modal footer action button

To modify the action instance that is used to render one of the default action buttons, you may pass a closure to themodalSubmitAction()andmodalCancelAction()methods:

```php
use Filament\Actions\Action;

Action::make('help')
    ->modalContent(view('actions.help'))
    ->modalCancelAction(fn (Action $action) => $action->label('Close'))

```

Themethods available to customize trigger buttonswill work to modify the$actioninstance inside the closure.

TIP

To customize the button labels in your modal, themodalSubmitActionLabel()andmodalCancelActionLabel()methods can be used instead of passing a function tomodalSubmitAction()andmodalCancelAction(), if you don’t require any further customizations.

### #Removing a default modal footer action button

To remove a default action, you may passfalseto eithermodalSubmitAction()ormodalCancelAction():

```php
use Filament\Actions\Action;

Action::make('help')
    ->modalContent(view('actions.help'))
    ->modalSubmitAction(false)

```

### #Adding an extra modal action button to the footer

You may pass an array of extra actions to be rendered, between the default actions, in the footer of the modal using theextraModalFooterActions()method:

```php
use Filament\Actions\Action;

Action::make('create')
    ->schema([
        // ...
    ])
    // ...
    ->extraModalFooterActions(fn (Action $action): array => [
        $action->makeModalSubmitAction('createAnother', arguments: ['another' => true]),
    ])

```

$action->makeModalSubmitAction()returns an action instance that can be customized using themethods available to customize trigger buttons.

The second parameter ofmakeModalSubmitAction()allows you to pass an array of arguments that will be accessible inside the action’saction()closure as$arguments. These could be useful as flags to indicate that the action should behave differently based on the user’s decision:

```php
use Filament\Actions\Action;

Action::make('create')
    ->schema([
        // ...
    ])
    // ...
    ->extraModalFooterActions(fn (Action $action): array => [
        $action->makeModalSubmitAction('createAnother', arguments: ['another' => true]),
    ])
    ->action(function (array $data, array $arguments): void {
        // Create

        if ($arguments['another'] ?? false) {
            // Reset the form and don't close the modal
        }
    })

```

#### #Opening another modal from an extra footer action

You can nest actions within each other, allowing you to open a new modal from an extra footer action:

```php
use Filament\Actions\Action;

Action::make('edit')
    // ...
    ->extraModalFooterActions([
        Action::make('delete')
            ->requiresConfirmation()
            ->action(function () {
                // ...
            }),
    ])

```

Now, the edit modal will have a “Delete” button in the footer, which will open a confirmation modal when clicked. This action is completely independent of theeditaction, and will not run theeditaction when it is clicked.

In this example though, you probably want to cancel theeditaction if thedeleteaction is run. You can do this using thecancelParentActions()method:

```php
use Filament\Actions\Action;

Action::make('delete')
    ->requiresConfirmation()
    ->action(function () {
        // ...
    })
    ->cancelParentActions()

```

If you have deep nesting with multiple parent actions, but you don’t want to cancel all of them, you can pass the name of the parent action you want to cancel, including its children, tocancelParentActions():

```php
use Filament\Actions\Action;

Action::make('first')
    ->requiresConfirmation()
    ->action(function () {
        // ...
    })
    ->extraModalFooterActions([
        Action::make('second')
            ->requiresConfirmation()
            ->action(function () {
                // ...
            })
            ->extraModalFooterActions([
                Action::make('third')
                    ->requiresConfirmation()
                    ->action(function () {
                        // ...
                    })
                    ->extraModalFooterActions([
                        Action::make('fourth')
                            ->requiresConfirmation()
                            ->action(function () {
                                // ...
                            })
                            ->cancelParentActions('second'),
                    ]),
            ]),
    ])

```

In this example, if thefourthaction is run, thesecondaction is canceled, but so is thethirdaction since it is a child ofsecond. Thefirstaction is not canceled, however, since it is the parent ofsecond. Thefirstaction’s modal will remain open.

## #Accessing information about parent actions from a child

You can access the instances of parent actions and their raw data and arguments by injecting the$mountedActionsarray in a function used by your nested action. For example, to get the top-most parent action currently active on the page, you can use$mountedActions[0]. From there, you can get the raw data for that action by calling$mountedActions[0]->getRawData(). Please be aware that raw data is not validated since the action has not been submitted yet:

```php
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

Action::make('first')
    ->schema([
        TextInput::make('foo'),
    ])
    ->action(function () {
        // ...
    })
    ->extraModalFooterActions([
        Action::make('second')
            ->requiresConfirmation()
            ->action(function (array $mountedActions) {
                dd($mountedActions[0]->getRawData());
            
                // ...
            }),
    ])

```

You can do similar with the current arguments for a parent action, with the$mountedActions[0]->getArguments()method.

Even if you have multiple layers of nesting, the$mountedActionsarray will contain every action that is currently active, so you can access information about them:

```php
use Filament\Actions\Action;

Action::make('first')
    ->schema([
        TextInput::make('foo'),
    ])
    ->action(function () {
        // ...
    })
    ->extraModalFooterActions([
        Action::make('second')
            ->schema([
                TextInput::make('bar'),
            ])
            ->arguments(['number' => 2])
            ->action(function () {
                // ...
            })
            ->extraModalFooterActions([
                Action::make('third')
                    ->schema([
                        TextInput::make('baz'),
                    ])
                    ->arguments(['number' => 3])
                    ->action(function () {
                        // ...
                    })
                    ->extraModalFooterActions([
                        Action::make('fourth')
                            ->requiresConfirmation()
                            ->action(function (array $mountedActions) {
                                dd(
                                    $mountedActions[0]->getRawData(),
                                    $mountedActions[0]->getArguments(),
                                    $mountedActions[1]->getRawData(),
                                    $mountedActions[1]->getArguments(),
                                    $mountedActions[2]->getRawData(),
                                    $mountedActions[2]->getArguments(),
                                );
                                // ...
                            }),
                    ]),
            ]),
    ])

```

## #Closing the modal

### #Closing the modal by clicking away

By default, when you click away from a modal, it will close itself. If you wish to disable this behavior for a specific action, you can use thecloseModalByClickingAway(false)method:

```php
use Filament\Actions\Action;

Action::make('updateAuthor')
    ->schema([
        // ...
    ])
    ->action(function (array $data): void {
        // ...
    })
    ->closeModalByClickingAway(false)

```

If you’d like to change the behavior for all modals in the application, you can do so by callingModalComponent::closedByClickingAway()inside a service provider or middleware:

```php
use Filament\Support\View\Components\ModalComponent;

ModalComponent::closedByClickingAway(false);

```

### #Closing the modal by escaping

By default, when you press escape on a modal, it will close itself. If you wish to disable this behavior for a specific action, you can use thecloseModalByEscaping(false)method:

```php
use Filament\Actions\Action;

Action::make('updateAuthor')
    ->schema([
        // ...
    ])
    ->action(function (array $data): void {
        // ...
    })
    ->closeModalByEscaping(false)

```

If you’d like to change the behavior for all modals in the application, you can do so by callingModalComponent::closedByEscaping()inside a service provider or middleware:

```php
use Filament\Support\View\Components\ModalComponent;

ModalComponent::closedByEscaping(false);

```

### #Hiding the modal close button

By default, modals have a close button in the top right corner. If you wish to hide the close button, you can use themodalCloseButton(false)method:

```php
use Filament\Actions\Action;

Action::make('updateAuthor')
    ->schema([
        // ...
    ])
    ->action(function (array $data): void {
        // ...
    })
    ->modalCloseButton(false)

```

If you’d like to hide the close button for all modals in the application, you can do so by callingModalComponent::closeButton(false)inside a service provider or middleware:

```php
use Filament\Support\View\Components\ModalComponent;

ModalComponent::closeButton(false);

```

## #Preventing the modal from autofocusing

By default, modals will autofocus on the first focusable element when opened. If you wish to disable this behavior, you can use themodalAutofocus(false)method:

```php
use Filament\Actions\Action;

Action::make('updateAuthor')
    ->schema([
        // ...
    ])
    ->action(function (array $data): void {
        // ...
    })
    ->modalAutofocus(false)

```

If you’d like to disable autofocus for all modals in the application, you can do so by callingModalComponent::autofocus(false)inside a service provider or middleware:

```php
use Filament\Support\View\Components\ModalComponent;

ModalComponent::autofocus(false);

```

## #Optimizing modal configuration methods

When you use database queries or other heavy operations inside modal configuration methods likemodalHeading(), they can be executed more than once. This is because Filament uses these methods to decide whether to render the modal or not, and also to render the modal’s content.

To skip the check that Filament does to decide whether to render the modal, you can use themodal()method, which will inform Filament that the modal exists for this action, and it does not need to check again:

```php
use Filament\Actions\Action;

Action::make('updateAuthor')
    ->modal()

```

## #Conditionally hiding the modal

You may have a need to conditionally show a modal for confirmation reasons while falling back to the default action. This can be achieved usingmodalHidden():

```php
use Filament\Actions\Action;

Action::make('create')
    ->action(function (array $data): void {
        // ...
    })
    ->modalHidden($this->role !== 'admin')
    ->modalContent(view('filament.pages.actions.create'))

```

## #Adding extra attributes to the modal window

You can pass extra HTML attributes to the modal window via theextraModalWindowAttributes()method, which will be merged onto its outer HTML element. The attributes should be represented by an array, where the key is the attribute name and the value is the attribute value:

```php
use Filament\Actions\Action;

Action::make('updateAuthor')
    ->extraModalWindowAttributes(['class' => 'update-author-modal'])

```

TIP

By default, callingextraModalWindowAttributes()multiple times will overwrite the previous attributes. If you wish to merge the attributes instead, you can passmerge: trueto the method.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, tables, forms  
**Keywords:** buttons, modals, interactions, crud operations

*Extracted from Filament v5 Documentation - 2026-01-28*
