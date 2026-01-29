# Builder

**URL:** https://filamentphp.com/docs/5.x/forms/builder  
**Section:** forms  
**Page:** builder  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

Similar to arepeater, the builder component allows you to output a JSON array of repeated form components. Unlike the repeater, which only defines one form schema to repeat, the builder allows you to define different schema “blocks”, which you can repeat in any order. This makes it useful for building more advanced array structures.

The primary use of the builder component is to build web page content using predefined blocks. This could be content for a marketing website, or maybe even fields in an online form. The example below defines multiple blocks for different elements in the page content. On the frontend of your website, you could loop through each block in the JSON and format it how you wish.

```php
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

Builder::make('content')
    ->blocks([
        Block::make('heading')
            ->schema([
                TextInput::make('content')
                    ->label('Heading')
                    ->required(),
                Select::make('level')
                    ->options([
                        'h1' => 'Heading 1',
                        'h2' => 'Heading 2',
                        'h3' => 'Heading 3',
                        'h4' => 'Heading 4',
                        'h5' => 'Heading 5',
                        'h6' => 'Heading 6',
                    ])
                    ->required(),
            ])
            ->columns(2),
        Block::make('paragraph')
            ->schema([
                Textarea::make('content')
                    ->label('Paragraph')
                    ->required(),
            ]),
        Block::make('image')
            ->schema([
                FileUpload::make('url')
                    ->label('Image')
                    ->image()
                    ->required(),
                TextInput::make('alt')
                    ->label('Alt text')
                    ->required(),
            ]),
    ])

```

We recommend that you store builder data with aJSONcolumn in your database. Additionally, if you’re using Eloquent, make sure that column has anarraycast.

As evident in the above example, blocks can be defined within theblocks()method of the component. Blocks areBuilder\Blockobjects, and require a unique name, and a component schema:

```php
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;

Builder::make('content')
    ->blocks([
        Block::make('heading')
            ->schema([
                TextInput::make('content')->required(),
                // ...
            ]),
        // ...
    ])

```

## #Setting a block’s label

By default, the label of the block will be automatically determined based on its name. To override the block’s label, you may use thelabel()method. Customizing the label in this way is useful if you wish to use atranslation string for localization:

```php
use Filament\Forms\Components\Builder\Block;

Block::make('heading')
    ->label(__('blocks.heading'))

```

### #Labelling builder items based on their content

You may add a label for a builder item using the samelabel()method. This method accepts a closure that receives the item’s data in a$statevariable. If$stateis null, you should return the block label that should be displayed in the block picker. Otherwise, you should return a string to be used as the item label:

```php
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;

Block::make('heading')
    ->schema([
        TextInput::make('content')
            ->live(onBlur: true)
            ->required(),
        // ...
    ])
    ->label(function (?array $state): string {
        if ($state === null) {
            return 'Heading';
        }

        return $state['content'] ?? 'Untitled heading';
    })

```

Any fields that you use from$stateshould belive()if you wish to see the item label update live as you use the form.

### #Numbering builder items

By default, items in the builder have a number next to their label. You may disable this using theblockNumbers(false)method:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->blockNumbers(false)

```

## #Setting a block’s icon

Blocks may also have anicon, which is displayed next to the label. You can add an icon by passing its name to theicon()method:

```php
use Filament\Forms\Components\Builder\Block;
use Filament\Support\Icons\Heroicon;

Block::make('paragraph')
    ->icon(Heroicon::Bars3BottomLeft)

```

### #Adding icons to the header of blocks

By default, blocks in the builder don’t have an icon next to the header label, just in the dropdown to add new blocks. You may enable this using theblockIcons()method:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->blockIcons()

```

Optionally, you may pass a boolean value to theblockIcons()method to control if the icons are displayed in the block headers:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->blockIcons(FeatureFlag::active())

```

## #Previewing blocks

If you prefer to render read-only previews in the builder instead of the blocks’ forms, you can use theblockPreviews()method. This will render each block’spreview()instead of the form. Block data will be passed to the preview Blade view in a variable with the same name:

```php
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;

Builder::make('content')
    ->blockPreviews()
    ->blocks([
        Block::make('heading')
            ->schema([
                TextInput::make('text')
                    ->placeholder('Default heading'),
            ])
            ->preview('filament.content.block-previews.heading'),
    ])

```

In/resources/views/filament/content/block-previews/heading.blade.php, you can access the block data like so:

```php
<h1>
    {{ $text ?? 'Default heading' }}
</h1>

```

Optionally, theblockPreviews()method accepts a boolean value to control if the builder should render block previews or not:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->blockPreviews(FeatureFlag::active())

```

### #Interactive block previews

By default, preview content is not interactive, and clicking it will open the Edit modal for that block to manage its settings. If you have links and buttons that you’d like to remain interactive in the block previews, you can use theareInteractive: trueargument of theblockPreviews()method:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blockPreviews(areInteractive: true)
    ->blocks([
        //
    ])

```

## #Adding items

An action button is displayed below the builder to allow the user to add a new item.

## #Setting the add action button’s label

You may set a label to customize the text that should be displayed in the button for adding a builder item, using theaddActionLabel()method:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->addActionLabel('Add a new block')

```

### #Aligning the add action button

By default, the add action is aligned in the center. You may adjust this using theaddActionAlignment()method, passing anAlignmentoption ofAlignment::StartorAlignment::End:

```php
use Filament\Forms\Components\Builder;
use Filament\Support\Enums\Alignment;

Builder::make('content')
    ->schema([
        // ...
    ])
    ->addActionAlignment(Alignment::Start)

```

### #Preventing the user from adding items

You may prevent the user from adding items to the builder using theaddable(false)method:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->addable(false)

```

## #Deleting items

An action button is displayed on each item to allow the user to delete it.

### #Preventing the user from deleting items

You may prevent the user from deleting items from the builder using thedeletable(false)method:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->deletable(false)

```

## #Reordering items

A button is displayed on each item to allow the user to drag and drop to reorder it in the list.

### #Preventing the user from reordering items

You may prevent the user from reordering items from the builder using thereorderable(false)method:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->reorderable(false)

```

### #Reordering items with buttons

You may use thereorderableWithButtons()method to enable reordering items with buttons to move the item up and down:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->reorderableWithButtons()

```

Optionally, you may pass a boolean value to control if the builder should be ordered with buttons or not:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->reorderableWithButtons(FeatureFlag::active())

```

### #Preventing reordering with drag and drop

You may use thereorderableWithDragAndDrop(false)method to prevent items from being ordered with drag and drop:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->reorderableWithDragAndDrop(false)

```

## #Collapsing items

The builder may becollapsible()to optionally hide content in long forms:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->collapsible()

```

You may also collapse all items by default:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->collapsed()

```

Optionally, thecollapsible()andcollapsed()methods accept a boolean value to control if the builder should be collapsible and collapsed or not:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->collapsible(FeatureFlag::active())
    ->collapsed(FeatureFlag::active())

```

## #Cloning items

You may allow builder items to be duplicated using thecloneable()method:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->cloneable()

```

## #Customizing the block picker

### #Changing the number of columns in the block picker

The block picker has only 1 column. You may customize it by passing a number of columns toblockPickerColumns():

```php
use Filament\Forms\Components\Builder;

Builder::make()
    ->blockPickerColumns(2)
    ->blocks([
        // ...
    ])

```

This method can be used in a couple of different ways:
- You can pass an integer likeblockPickerColumns(2). This integer is the number of columns used on thelgbreakpoint and higher. All smaller devices will have just 1 column.
- You can pass an array, where the key is the breakpoint and the value is the number of columns. For example,blockPickerColumns(['md' => 2, 'xl' => 4])will create a 2 column layout on medium devices, and a 4 column layout on extra large devices. The default breakpoint for smaller devices uses 1 column, unless you use adefaultarray key.


Breakpoints (sm,md,lg,xl,2xl) are defined by Tailwind, and can be found in theTailwind documentation.

### #Increasing the width of the block picker

When youincrease the number of columns, the width of the dropdown should increase incrementally to handle the additional columns. If you’d like more control, you can manually set a maximum width for the dropdown using theblockPickerWidth()method. Options correspond toTailwind’s max-width scale. The options arexs,sm,md,lg,xl,2xl,3xl,4xl,5xl,6xl,7xl:

```php
use Filament\Forms\Components\Builder;

Builder::make()
    ->blockPickerColumns(3)
    ->blockPickerWidth('2xl')
    ->blocks([
        // ...
    ])

```

## #Limiting the number of times a block can be used

By default, each block can be used in the builder an unlimited number of times. You may limit this using themaxItems()method on a block:

```php
use Filament\Forms\Components\Builder\Block;

Block::make('heading')
    ->schema([
        // ...
    ])
    ->maxItems(1)

```

## #Using$get()to access parent field values

All form components are able touse$get()and$set()to access another field’s value. However, you might experience unexpected behavior when using this inside the builder’s schema.

This is because$get()and$set(), by default, are scoped to the current builder item. This means that you are able to interact with another field inside that builder item easily without knowing which builder item the current form component belongs to.

The consequence of this is that you may be confused when you are unable to interact with a field outside the builder. We use../syntax to solve this problem -$get('../parent_field_name').

Consider your form has this data structure:

```php
[
    'client_id' => 1,

    'builder' => [
        'item1' => [
            'service_id' => 2,
        ],
    ],
]

```

You are trying to retrieve the value ofclient_idfrom inside the builder item.

$get()is relative to the current builder item, so$get('client_id')is looking for$get('builder.item1.client_id').

You can use../to go up a level in the data structure, so$get('../client_id')is$get('builder.client_id')and$get('../client_id')is$get('client_id').

The special case of$get()with no arguments, or$get('')or$get('./'), will always return the full data array for the current builder item.

## #Builder validation

As well as all rules listed on thevalidationpage, there are additional rules that are specific to builders.

### #Number of items validation

You can validate the minimum and maximum number of items that you can have in a builder by setting theminItems()andmaxItems()methods:

```php
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->minItems(1)
    ->maxItems(5)

```

## #Customizing the builder item actions

This field uses action objects for easy customization of buttons within it. You can customize these buttons by passing a function to an action registration method. The function has access to the$actionobject, which you can use tocustomize it. The following methods are available to customize the actions:
- addAction()
- addBetweenAction()
- cloneAction()
- collapseAction()
- collapseAllAction()
- deleteAction()
- expandAction()
- expandAllAction()
- moveDownAction()
- moveUpAction()
- reorderAction()


Here is an example of how you might customize an action:

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->collapseAllAction(
        fn (Action $action) => $action->label('Collapse all content'),
    )

```

### #Confirming builder actions with a modal

You can confirm actions with a modal by using therequiresConfirmation()method on the action object. You may use anymodal customization methodto change its content and behavior:

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Builder;

Builder::make('content')
    ->blocks([
        // ...
    ])
    ->deleteAction(
        fn (Action $action) => $action->requiresConfirmation(),
    )

```

NOTE

TheaddAction(),addBetweenAction(),collapseAction(),collapseAllAction(),expandAction(),expandAllAction()andreorderAction()methods do not support confirmation modals, as clicking their buttons does not make the network request that is required to show the modal.

### #Adding extra item actions to a builder

You may add newaction buttonsto the header of each builder item by passingActionobjects intoextraItemActions():

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Mail;

Builder::make('content')
    ->blocks([
        Block::make('contactDetails')
            ->schema([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                // ...
            ]),
        // ...
    ])
    ->extraItemActions([
        Action::make('sendEmail')
            ->icon(Heroicon::Square2Stack)
            ->action(function (array $arguments, Builder $component): void {
                $itemData = $component->getItemState($arguments['item']);
                
                Mail::to($itemData['email'])
                    ->send(
                        // ...
                    );
            }),
    ])

```

In this example,$arguments['item']gives you the ID of the current builder item. You can validate the data in that builder item using thegetItemState()method on the builder component. This method returns the validated data for the item. If the item is not valid, it will cancel the action and show an error message for that item in the form.

If you want to get the raw data from the current item without validating it, you can use$component->getRawItemState($arguments['item'])instead.

If you want to manipulate the raw data for the entire builder, for example, to add, remove or modify items, you can use$component->getState()to get the data, and$component->state($state)to set it again:

```php
use Illuminate\Support\Str;

// Get the raw data for the entire builder
$state = $component->getState();

// Add an item, with a random UUID as the key
$state[Str::uuid()] = [
    'type' => 'contactDetails',
    'data' => [
        'email' => auth()->user()->email,
    ],
];

// Set the new data for the builder
$component->state($state);

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
