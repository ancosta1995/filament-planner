# Repeater

**URL:** https://filamentphp.com/docs/5.x/forms/repeater  
**Section:** forms  
**Page:** repeater  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The repeater component allows you to output a JSON array of repeated form components.

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

Repeater::make('members')
    ->schema([
        TextInput::make('name')->required(),
        Select::make('role')
            ->options([
                'member' => 'Member',
                'administrator' => 'Administrator',
                'owner' => 'Owner',
            ])
            ->required(),
    ])
    ->columns(2)

```

We recommend that you store repeater data with aJSONcolumn in your database. Additionally, if you’re using Eloquent, make sure that column has anarraycast.

As evident in the above example, the component schema can be defined within theschema()method of the component:

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

Repeater::make('members')
    ->schema([
        TextInput::make('name')->required(),
        // ...
    ])

```

If you wish to define a repeater with multiple schema blocks that can be repeated in any order, please use thebuilder.

## #Setting empty default items

Repeaters may have a certain number of empty items created by default. The default is only used when a schema is loaded with no data. In a standardpanel resource, defaults are used on the Create page, not the Edit page. To use default items, pass the number of items to thedefaultItems()method:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->defaultItems(3)

```

## #Adding items

An action button is displayed below the repeater to allow the user to add a new item.

## #Setting the add action button’s label

You may set a label to customize the text that should be displayed in the button for adding a repeater item, using theaddActionLabel()method:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->addActionLabel('Add member')

```

### #Aligning the add action button

By default, the add action is aligned in the center. You may adjust this using theaddActionAlignment()method, passing anAlignmentoption ofAlignment::StartorAlignment::End:

```php
use Filament\Forms\Components\Repeater;
use Filament\Support\Enums\Alignment;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->addActionAlignment(Alignment::Start)

```

### #Preventing the user from adding items

You may prevent the user from adding items to the repeater using theaddable(false)method:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->addable(false)

```

## #Deleting items

An action button is displayed on each item to allow the user to delete it.

### #Preventing the user from deleting items

You may prevent the user from deleting items from the repeater using thedeletable(false)method:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->deletable(false)

```

## #Reordering items

A button is displayed on each item to allow the user to drag and drop to reorder it in the list.

### #Preventing the user from reordering items

You may prevent the user from reordering items from the repeater using thereorderable(false)method:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->reorderable(false)

```

### #Reordering items with buttons

You may use thereorderableWithButtons()method to enable reordering items with buttons to move the item up and down:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->reorderableWithButtons()

```

Optionally, you may pass a boolean value to control if the repeater should be ordered with buttons or not:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->reorderableWithButtons(FeatureFlag::active())

```

### #Preventing reordering with drag and drop

You may use thereorderableWithDragAndDrop(false)method to prevent items from being ordered with drag and drop:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->reorderableWithDragAndDrop(false)

```

## #Collapsing items

The repeater may becollapsible()to optionally hide content in long forms:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->schema([
        // ...
    ])
    ->collapsible()

```

You may also collapse all items by default:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->schema([
        // ...
    ])
    ->collapsed()

```

Optionally, thecollapsible()andcollapsed()methods accept a boolean value to control if the repeater should be collapsible and collapsed or not:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->schema([
        // ...
    ])
    ->collapsible(FeatureFlag::active())
    ->collapsed(FeatureFlag::active())

```

## #Cloning items

You may allow repeater items to be duplicated using thecloneable()method:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->schema([
        // ...
    ])
    ->cloneable()

```

Optionally, thecloneable()method accepts a boolean value to control if the repeater should be cloneable or not:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->schema([
        // ...
    ])
    ->cloneable(FeatureFlag::active())

```

## #Integrating with an Eloquent relationship

You may employ therelationship()method of theRepeaterto configure aHasManyrelationship. Filament will load the item data from the relationship, and save it back to the relationship when the form is submitted. If a custom relationship name is not passed torelationship(), Filament will use the field name as the relationship name:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->relationship()
    ->schema([
        // ...
    ])

```

### #Reordering items in a relationship

By default,reorderingrelationship repeater items is disabled. This is because your related model needs asortcolumn to store the order of related records. To enable reordering, you may use theorderColumn()method, passing in a name of the column on your related model to store the order in:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->relationship()
    ->schema([
        // ...
    ])
    ->orderColumn('sort')

```

If you use something likespatie/eloquent-sortablewith an order column such asorder_column, you may pass this in toorderColumn():

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->relationship()
    ->schema([
        // ...
    ])
    ->orderColumn('order_column')

```

### #Integrating with aBelongsToManyEloquent relationship

There is a common misconception that using aBelongsToManyrelationship with a repeater is as simple as using aHasManyrelationship. This is not the case, as aBelongsToManyrelationship requires a pivot table to store the relationship data. The repeater saves its data to the related model, not the pivot table. Therefore, if you want to map each repeater item to a row in the pivot table, you must use aHasManyrelationship with a pivot model to use a repeater with aBelongsToManyrelationship.

Imagine you have a form to create a newOrdermodel. Each order belongs to manyProductmodels, and each product belongs to many orders. You have aorder_productpivot table to store the relationship data. Instead of using theproductsrelationship with the repeater, you should create a new relationship calledorderProductson theOrdermodel, and use that with the repeater:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

public function orderProducts(): HasMany
{
    return $this->hasMany(OrderProduct::class);
}

```

If you don’t already have anOrderProductpivot model, you should create that, with inverse relationships toOrderandProduct:

```php
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    public $incrementing = true;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

```

NOTE

Please ensure that your pivot model has a primary key column, likeid, to allow Filament to keep track of which repeater items have been created, updated and deleted. To make sure that Filament keeps track of the primary key, the pivot model needs to have the$incrementingproperty set totrue.

Now you can use theorderProductsrelationship with the repeater, and it will save the data to theorder_productpivot table:

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

Repeater::make('orderProducts')
    ->relationship()
    ->schema([
        Select::make('product_id')
            ->relationship('product', 'name')
            ->required(),
        // ...
    ])

```

### #Mutating related item data before filling the field

You may mutate the data for a related item before it is filled into the field using themutateRelationshipDataBeforeFillUsing()method. This method accepts a closure that receives the current item’s data in a$datavariable. You must return the modified array of data:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->relationship()
    ->schema([
        // ...
    ])
    ->mutateRelationshipDataBeforeFillUsing(function (array $data): array {
        $data['user_id'] = auth()->id();

        return $data;
    })

```

### #Mutating related item data before creating

You may mutate the data for a new related item before it is created in the database using themutateRelationshipDataBeforeCreateUsing()method. This method accepts a closure that receives the current item’s data in a$datavariable. You can choose to return either the modified array of data, ornullto prevent the item from being created:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->relationship()
    ->schema([
        // ...
    ])
    ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
        $data['user_id'] = auth()->id();

        return $data;
    })

```

### #Mutating related item data before saving

You may mutate the data for an existing related item before it is saved in the database using themutateRelationshipDataBeforeSaveUsing()method. This method accepts a closure that receives the current item’s data in a$datavariable. You can choose to return either the modified array of data, ornullto prevent the item from being saved:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->relationship()
    ->schema([
        // ...
    ])
    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
        $data['user_id'] = auth()->id();

        return $data;
    })

```

### #Modifying related records after retrieval

You may filter or modify the related records of a repeater after they are retrieved from the database using themodifyRecordsUsingargument. This method accepts a function that receives aCollectionof related records. You should return the modified collection.

This can be particularly useful to restrict records to a specific group or category without doing this in the database query itself, which would trigger an extra query if the records are already eager loaded:

```php
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Collection;

Repeater::make('startItems')
    ->relationship(name: 'items', modifyRecordsUsing: fn (Collection $records): Collection => $records->where('group', 'start')),
Repeater::make('endItems')
    ->relationship(name: 'items', modifyRecordsUsing: fn (Collection $records): Collection => $records->where('group', 'end')),

```

## #Grid layout

You may organize repeater items into columns by using thegrid()method:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('qualifications')
    ->schema([
        // ...
    ])
    ->grid(2)

```

This method accepts the same options as thecolumns()method of thegrid. This allows you to responsively customize the number of grid columns at various breakpoints.

## #Adding a label to repeater items based on their content

You may add a label for repeater items using theitemLabel()method. This method accepts a closure that receives the current item’s data in a$statevariable. You must return a string to be used as the item label:

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

Repeater::make('members')
    ->schema([
        TextInput::make('name')
            ->required()
            ->live(onBlur: true),
        Select::make('role')
            ->options([
                'member' => 'Member',
                'administrator' => 'Administrator',
                'owner' => 'Owner',
            ])
            ->required(),
    ])
    ->columns(2)
    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),

```

TIP

Any fields that you use from$stateshould belive()if you wish to see the item label update live as you use the form.

## #Numbering repeater items

You can add the repeater item’s number next to its label using theitemNumbers()method:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->itemNumbers()

```

## #Simple repeaters with one field

You can use thesimple()method to create a repeater with a single field, using a minimal design

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

Repeater::make('invitations')
    ->simple(
        TextInput::make('email')
            ->email()
            ->required(),
    )

```

Instead of using a nested array to store data, simple repeaters use a flat array of values. This means that the data structure for the above example could look like this:

```php
[
    'invitations' => [
        '[email protected]',
        '[email protected]',
    ],
],

```

## #Using$get()to access parent field values

All form components are able touse$get()and$set()to access another field’s value. However, you might experience unexpected behavior when using this inside the repeater’s schema.

This is because$get()and$set(), by default, are scoped to the current repeater item. This means that you are able to interact with another field inside that repeater item easily without knowing which repeater item the current form component belongs to.

The consequence of this is that you may be confused when you are unable to interact with a field outside the repeater. We use../syntax to solve this problem -$get('../parent_field_name').

Consider your form has this data structure:

```php
[
    'client_id' => 1,

    'repeater' => [
        'item1' => [
            'service_id' => 2,
        ],
    ],
]

```

You are trying to retrieve the value ofclient_idfrom inside the repeater item.

$get()is relative to the current repeater item, so$get('client_id')is looking for$get('repeater.item1.client_id').

You can use../to go up a level in the data structure, so$get('../client_id')is$get('repeater.client_id')and$get('../../client_id')is$get('client_id').

The special case of$get()with no arguments, or$get('')or$get('./'), will always return the full data array for the current repeater item.

## #Table repeaters

You can present repeater items in a table format using thetable()method, which accepts an array ofTableColumnobjects. These objects represent the columns of the table, which correspond to any components in the schema of the repeater:

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

Repeater::make('members')
    ->table([
        TableColumn::make('Name'),
        TableColumn::make('Role'),
    ])
    ->schema([
        TextInput::make('name')
            ->required(),
        Select::make('role')
            ->options([
                'member' => 'Member',
                'administrator' => 'Administrator',
                'owner' => 'Owner',
            ])
            ->required(),
    ])

```

The labels displayed in the header of the table are passed to theTableColumn::make()method. If you want to provide an accessible label for a column but do not wish to display it, you can use thehiddenHeaderLabel()method:

```php
use Filament\Forms\Components\Repeater\TableColumn;

TableColumn::make('Name')
    ->hiddenHeaderLabel()

```

If you would like to mark a column as “required” with a red asterisk, you can use themarkAsRequired()method:

```php
use Filament\Forms\Components\Repeater\TableColumn;

TableColumn::make('Name')
    ->markAsRequired()

```

You can enable wrapping of the column header using thewrapHeader()method:

```php
use Filament\Forms\Components\Repeater\TableColumn;

TableColumn::make('Name')
    ->wrapHeader()

```

You can also adjust the alignment of the column header using thealignment()method, passing anAlignmentoption ofAlignment::Start,Alignment::Center, orAlignment::End:

```php
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Support\Enums\Alignment;

TableColumn::make('Name')
    ->alignment(Alignment::Start)

```

You can set a fixed column width using thewidth()method, passing a string value that represents the width of the column. This value is passed directly to thestyleattribute of the column header:

```php
use Filament\Forms\Components\Repeater\TableColumn;

TableColumn::make('Name')
    ->width('200px')

```

### #Compact table repeaters

You can make table repeaters more compact by using thecompact()method, to fit more data in a smaller space:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->table([
        // ...
    ])
    ->compact()
    ->schema([
        // ...
    ])

```

Optionally, you may pass a boolean value to control if the table repeater should be compact or not:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->table([
        // ...
    ])
    ->compact(FeatureFlag::active())
    ->schema([
        // ...
    ])

```

## #Repeater validation

As well as all rules listed on thevalidationpage, there are additional rules that are specific to repeaters.

### #Number of items validation

You can validate the minimum and maximum number of items that you can have in a repeater by setting theminItems()andmaxItems()methods:

```php
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->minItems(2)
    ->maxItems(5)

```

### #Distinct state validation

In many cases, you will want to ensure some sort of uniqueness between repeater items. A couple of common examples could be:
- Ensuring that only onecheckboxortoggleis activated at once across items in the repeater.
- Ensuring that an option may only be selected once acrossselect,radio,checkbox list, ortoggle buttonsfields in a repeater.


You can use thedistinct()method to validate that the state of a field is unique across all items in the repeater:

```php
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;

Repeater::make('answers')
    ->schema([
        // ...
        Checkbox::make('is_correct')
            ->distinct(),
    ])

```

The behavior of thedistinct()validation depends on the data type that the field handles
- If the field returns a boolean, like acheckboxortoggle, the validation will ensure that only one item has a value oftrue. There may be many fields in the repeater that have a value offalse.
- Otherwise, for fields like aselect,radio,checkbox list, ortoggle buttons, the validation will ensure that each option may only be selected once across all items in the repeater.


Optionally, you may pass a boolean value to thedistinct()method to control if the field should be distinct or not:

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('is_correct')
    ->distinct(FeatureFlag::active())

```

#### #Automatically fixing indistinct state

If you’d like to automatically fix indistinct state, you can use thefixIndistinctState()method:

```php
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;

Repeater::make('answers')
    ->schema([
        // ...
        Checkbox::make('is_correct')
            ->fixIndistinctState(),
    ])

```

This method will automatically enable thedistinct()andlive()methods on the field.

Depending on the data type that the field handles, the behavior of thefixIndistinctState()adapts:
- If the field returns a boolean, like acheckboxortoggle, and one of the fields is enabled, Filament will automatically disable all other enabled fields on behalf of the user.
- Otherwise, for fields like aselect,radio,checkbox list, ortoggle buttons, when a user selects an option, Filament will automatically deselect all other usages of that option on behalf of the user.


Optionally, you may pass a boolean value to thefixIndistinctState()method to control if the field should fix indistinct state or not:

```php
use Filament\Forms\Components\Checkbox;

Checkbox::make('is_correct')
    ->fixIndistinctState(FeatureFlag::active())

```

#### #Disabling options when they are already selected in another item

If you’d like to disable options in aselect,radio,checkbox list, ortoggle buttonswhen they are already selected in another item, you can use thedisableOptionsWhenSelectedInSiblingRepeaterItems()method:

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

Repeater::make('members')
    ->schema([
        Select::make('role')
            ->options([
                // ...
            ])
            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
    ])

```

This method will automatically enable thedistinct()andlive()methods on the field.

NOTE

In case you want to add another condition todisable optionswith, you can chaindisableOptionWhen()with themerge: trueargument:

```php
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

Repeater::make('members')
    ->schema([
        Select::make('role')
            ->options([
                // ...
            ])
            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
            ->disableOptionWhen(fn (string $value): bool => $value === 'super_admin', merge: true),
    ])

```

## #Customizing the repeater item actions

This field uses action objects for easy customization of buttons within it. You can customize these buttons by passing a function to an action registration method. The function has access to the$actionobject, which you can use tocustomize it. The following methods are available to customize the actions:
- addAction()
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
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->collapseAllAction(
        fn (Action $action) => $action->label('Collapse all members'),
    )

```

### #Confirming repeater actions with a modal

You can confirm actions with a modal by using therequiresConfirmation()method on the action object. You may use anymodal customization methodto change its content and behavior:

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;

Repeater::make('members')
    ->schema([
        // ...
    ])
    ->deleteAction(
        fn (Action $action) => $action->requiresConfirmation(),
    )

```

NOTE

ThecollapseAction(),collapseAllAction(),expandAction(),expandAllAction()andreorderAction()methods do not support confirmation modals, as clicking their buttons does not make the network request that is required to show the modal.

### #Adding extra item actions to a repeater

You may add newaction buttonsto the header of each repeater item by passingActionobjects intoextraItemActions():

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Mail;

Repeater::make('members')
    ->schema([
        TextInput::make('email')
            ->label('Email address')
            ->email(),
        // ...
    ])
    ->extraItemActions([
        Action::make('sendEmail')
            ->icon(Heroicon::Envelope)
            ->action(function (array $arguments, Repeater $component): void {
                $itemData = $component->getItemState($arguments['item']);

                Mail::to($itemData['email'])
                    ->send(
                        // ...
                    );
            }),
    ])

```

In this example,$arguments['item']gives you the ID of the current repeater item. You can validate the data in that repeater item using thegetItemState()method on the repeater component. This method returns the validated data for the item. If the item is not valid, it will cancel the action and show an error message for that item in the form.

If you want to get the raw data from the current item without validating it, you can use$component->getRawItemState($arguments['item'])instead.

If you want to manipulate the raw data for the entire repeater, for example, to add, remove or modify items, you can use$component->getState()to get the data, and$component->state($state)to set it again:

```php
use Illuminate\Support\Str;

// Get the raw data for the entire repeater
$state = $component->getState();

// Add an item, with a random UUID as the key
$state[Str::uuid()] = [
    'email' => auth()->user()->email,
];

// Set the new data for the repeater
$component->state($state);

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
