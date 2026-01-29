# Overview

**URL:** https://filamentphp.com/docs/5.x/infolists/overview  
**Section:** infolists  
**Page:** overview  
**Priority:** medium  
**AI Context:** Display read-only data in structured layouts.

---

## #Introduction

Filament’s infolists package lets you display a read-only list of data for a specific entity. It’s integrated into other Filament packages, such as insidepanel resources,relation managers, andaction modals. Understanding how to use the infolist builder will save you time when building custom Livewire applications or working with other Filament features.

This guide covers the fundamentals of building infolists with Filament. If you want to add an infolist to your own Livewire component,start herebefore continuing. If you’re adding an infolist to apanel resource, or using another Filament package, you’re ready to begin!

## #Defining entries

Entry classes can be found in theFilament\Infolists\Componentsnamespace. They reside within the schema array of components. Filament includes a number of entries built-in:
- Text entry
- Icon entry
- Image entry
- Color entry
- Code entry
- Key-value entry
- Repeatable entry


You may alsocreate your own custom entriesto display data however you wish.

Entries may be created using the staticmake()method, passing its unique name. Usually, the name of an entry corresponds to the name of an attribute on an Eloquent model. You may use “dot notation” to access attributes within relationships:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')

TextEntry::make('author.name')

```

## #Entry content (state)

Entries may feel a bit magic at first, but they are designed to be simple to use and optimized to display data from an Eloquent record. Despite this, they are flexible and you can display data from any source, not just an Eloquent record attribute.

The data that an entry displays is called its “state”. When using apanel resource, the infolist is aware of the record it is displaying. This means that the state of the entry is set based on the value of the attribute on the record. For example, if the entry is used in the infolist of aPostResource, then thetitleattribute value of the current post will be displayed.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')

```

If you want to access the value stored in a relationship, you can use “dot notation”. The name of the relationship that you would like to access data from comes first, followed by a dot, and then the name of the attribute:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('author.name')

```

You can also use “dot notation” to access values within a JSON / array column on an Eloquent model. The name of the attribute comes first, followed by a dot, and then the key of the JSON object you want to read from:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('meta.title')

```

### #Setting the state of an entry

You can pass your own state to an entry by using thestate()method:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->state('Hello, world!')

```

### #Setting the default state of an entry

When an entry is empty (its state isnull), you can use thedefault()method to define alternative state to use instead. This method will treat the default state as if it were real, so entries likeimageorcolorwill display the default image or color.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->default('Untitled')

```

#### #Adding placeholder text if an entry is empty

Sometimes you may want to display placeholder text for entries with an empty state, which is styled as a lighter gray text. This differs from thedefault value, as the placeholder is always text and not treated as if it were real state.

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->placeholder('Untitled')

```

## #Setting an entry’s label

By default, the label of the entry, which is displayed in the header of the infolist, is generated from the name of the entry. You may customize this using thelabel()method:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->label('Full name')

```

Customizing the label in this way is useful if you wish to use atranslation string for localization:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->label(__('entries.name'))

```

### #Hiding an entry’s label

TIP

If you’re looking to hide an entry’s label, it might be the case that you are trying to use an entry for arbitrary text or UI. Entries are specifically designed to display data in a structured way, butPrime componentsare simple components that are used to render basic stand-alone static content, such as text, images, and buttons (actions). You may want to consider using a Prime component instead.

It may be tempting to set the label to an empty string to hide it, but this is not recommended. Setting the label to an empty string will not communicate the purpose of the entry to screen readers, even if the purpose is clear visually. Instead, you should use thehiddenLabel()method, so it is hidden visually but still accessible to screen readers:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->hiddenLabel()

```

Optionally, you may pass a boolean value to control if the label should be hidden or not:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->hiddenLabel(FeatureFlag::active())

```

## #Opening a URL when an entry is clicked

When an entry is clicked, you may open a URL. To do this, pass a URL to theurl()method:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->url('/about/titles')

```

You may pass a function to theurl()method to dynamically calculate the URL. For example, you may want to access the current Eloquent record for the infolist by injecting$recordas an argument:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->url(fn (Post $record): string => route('posts.edit', ['post' => $record]))

```

If you’re using apanel resource, you can generate a link to a page for the record using thegetUrl()method:

```php
use App\Filament\Posts\PostResource;
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))

```

You may also choose to open the URL in a new tab:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))
    ->openUrlInNewTab()

```

Optionally, you may pass a boolean value to control if the URL should open in a new tab or not:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record]))
    ->openUrlInNewTab(FeatureFlag::active())

```

## #Hiding an entry

You may hide an entry:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('role')
    ->hidden()

```

Optionally, you may pass a boolean value to control if the entry should be hidden or not:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('role')
    ->hidden(! FeatureFlag::active())

```

Alternatively, you may use thevisible()method to control if the entry should be hidden or not. In some situations, this may help to make your code more readable:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('role')
    ->visible(FeatureFlag::active())

```

NOTE

If bothhidden()andvisible()are used, they both need to indicate that the entry should be visible for it to be shown.

### #Hiding an entry using JavaScript

If you need to hide an entry based on a user interaction, you can use thehidden()orvisible()methods, passing a function that uses utilities injected to determine whether the entry should be hidden or not:

```php
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\IconEntry;

Select::make('role')
    ->options([
        'user' => 'User',
        'staff' => 'Staff',
    ])
    ->live()

IconEntry::make('is_admin')
    ->boolean()
    ->hidden(fn (Get $get): bool => $get('role') !== 'staff')

```

In this example, therolefield is set tolive(), which means that the schema will reload the schema each time therolefield is changed. This will cause the function that is passed to thehidden()method to be re-evaluated, which will hide theis_adminentry if therolefield is not set tostaff.

However, reloading the schema each time an entry causes a network request to be made, since there is no way to re-run the PHP function from the client-side. This is not ideal for performance.

Alternatively, you can write JavaScript to hide the entry based on the value of a field. This is done by passing a JavaScript expression to thehiddenJs()method:

```php
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\IconEntry;

Select::make('role')
    ->options([
        'user' => 'User',
        'staff' => 'Staff',
    ])

IconEntry::make('is_admin')
    ->boolean()
    ->hiddenJs(<<<'JS'
        $get('role') !== 'staff'
        JS)

```

Although the code passed tohiddenJs()looks very similar to PHP, it is actually JavaScript. Filament provides the$get()utility function to JavaScript that behaves very similar to its PHP equivalent, but without requiring the depended-on entry to belive().

ThevisibleJs()method is also available, which works in the same way ashiddenJs(), but controls if the entry should be visible or not:

```php
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\IconEntry;

Select::make('role')
    ->options([
        'user' => 'User',
        'staff' => 'Staff',
    ])
    
IconEntry::make('is_admin')
    ->boolean()
    ->visibleJs(<<<'JS'
        $get('role') === 'staff'
        JS)

```

NOTE

If bothhiddenJs()andvisibleJs()are used, they both need to indicate that the entry should be visible for it to be shown.

### #Hiding an entry based on the current operation

The “operation” of a schema is the current action being performed on it. Usually, this is eithercreate,editorview, if you are using thepanel resource.

You can hide an entry based on the current operation by passing an operation to thehiddenOn()method:

```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('is_admin')
    ->boolean()
    ->hiddenOn('edit')
    
// is the same as

IconEntry::make('is_admin')
    ->boolean()
    ->hidden(fn (string $operation): bool => $operation === 'edit')

```

You can also pass an array of operations to thehiddenOn()method, and the entry will be hidden if the current operation is any of the operations in the array:

```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('is_admin')
    ->boolean()
    ->hiddenOn(['edit', 'view'])
    
// is the same as

IconEntry::make('is_admin')
    ->boolean()
    ->hidden(fn (string $operation): bool => in_array($operation, ['edit', 'view']))

```

NOTE

ThehiddenOn()method will overwrite any previous calls to thehidden()method, and vice versa.

Alternatively, you may use thevisibleOn()method to control if the entry should be hidden or not. In some situations, this may help to make your code more readable:

```php
use Filament\Infolists\Components\IconEntry;

IconEntry::make('is_admin')
    ->boolean()
    ->visibleOn('create')

IconEntry::make('is_admin')
    ->boolean()
    ->visibleOn(['create', 'edit'])

```

NOTE

ThevisibleOn()method will overwrite any previous calls to thevisible()method, and vice versa.

## #Inline labels

Entries may have their labels displayed inline with the entry, rather than above it. This is useful for infolists with many entries, where vertical space is at a premium. To display an entry’s label inline, use theinlineLabel()method:

```php
use Filament\Infolists\Components\TextEntry;

TextInput::make('name')
    ->inlineLabel()

```

Optionally, you may pass a boolean value to control if the label should be displayed inline or not:

```php
use Filament\Infolists\Components\TextInput;

TextInput::make('name')
    ->inlineLabel(FeatureFlag::active())

```

### #Using inline labels in multiple places at once

If you wish to display all labels inline in alayout componentlike asectionortab, you can use theinlineLabel()on the component itself, and all entries within it will have their labels displayed inline:

```php
use Filament\Infolists\Components\TextInput;
use Filament\Schemas\Components\Section;

Section::make('Details')
    ->inlineLabel()
    ->schema([
        TextInput::make('name'),
        TextInput::make('email')
            ->label('Email address'),
        TextInput::make('phone')
            ->label('Phone number'),
    ])

```

You can also useinlineLabel()on the entire schema to display all labels inline:

```php
use Filament\Schemas\Schema;

public function infolist(Schema $schema): Schema
{
    return $schema
        ->inlineLabel()
        ->components([
            // ...
        ]);
}

```

When usinginlineLabel()on a layout component or schema, you can still opt-out of inline labels for individual entries by using theinlineLabel(false)method on the entry:

```php
use Filament\Infolists\Components\TextInput;
use Filament\Schemas\Components\Section;

Section::make('Details')
    ->inlineLabel()
    ->schema([
        TextInput::make('name'),
        TextInput::make('email')
            ->label('Email address'),
        TextInput::make('phone')
            ->label('Phone number')
            ->inlineLabel(false),
    ])

```

## #Adding a tooltip to an entry

You may specify a tooltip to display when you hover over an entry:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->tooltip('Shown at the top of the page')

```

## #Aligning entry content

You may align the content of an entry to the start (left in left-to-right interfaces, right in right-to-left interfaces), center, or end (right in left-to-right interfaces, left in right-to-left interfaces) using thealignStart(),alignCenter()oralignEnd()methods:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')
    ->alignStart() // This is the default alignment.

TextEntry::make('title')
    ->alignCenter()

TextEntry::make('title')
    ->alignEnd()

```

Alternatively, you may pass anAlignmentenum to thealignment()method:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\Alignment;

TextEntry::make('title')
    ->alignment(Alignment::Center)

```

## #Adding extra content to an entry

Entries contain many “slots” where content can be inserted in a child schema. Slots can accept text,any schema component,actionsandaction groups. Usually,prime componentsare used for content.

The following slots are available for all entries:
- aboveLabel()
- beforeLabel()
- afterLabel()
- belowLabel()
- aboveContent()
- beforeContent()
- afterContent()
- belowContent()


To insert plain text, you can pass a string to these methods:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->belowContent('This is the user\'s full name.')

```

To insert a schema component, often aprime component, you can pass the component to the method:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;

TextEntry::make('name')
    ->belowContent(Text::make('This is the user\'s full name.')->weight(FontWeight::Bold))

```

To insert anactionoraction group, you can pass the action or action group to the method:

```php
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->belowContent(Action::make('generate'))

```

You can insert any combination of content into the slots by passing an array of content to the method:

```php
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->belowContent([
        Icon::make(Heroicon::InformationCircle),
        'This is the user\'s full name.',
        Action::make('generate'),
    ])

```

You can also align the content in the slots by passing the array of content to eitherSchema::start()(default),Schema::end()orSchema::between():

```php
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->belowContent(Schema::end([
        Icon::make(Heroicon::InformationCircle),
        'This is the user\'s full name.',
        Action::make('generate'),
    ]))

TextEntry::make('name')
    ->belowContent(Schema::between([
        Icon::make(Heroicon::InformationCircle),
        'This is the user\'s full name.',
        Action::make('generate'),
    ]))

TextEntry::make('name')
    ->belowContent(Schema::between([
        Flex::make([
            Icon::make(Heroicon::InformationCircle)
                ->grow(false),
            'This is the user\'s full name.',
        ]),
        Action::make('generate'),
    ]))

```

TIP

As you can see in the above example forSchema::between(), aFlexcomponentis used to group the icon and text together so they do not have space between them. The icon usesgrow(false)to prevent it from taking up half of the horizontal space, allowing the text to consume the remaining space.

### #Adding extra content above an entry’s label

You can insert extra content above an entry’s label using theaboveLabel()method. You canpass any contentto this method, like text, a schema component, an action, or an action group:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->aboveLabel([
        Icon::make(Heroicon::Star),
        'This is the content above the entry\'s label'
    ])

```

### #Adding extra content before an entry’s label

You can insert extra content before an entry’s label using thebeforeLabel()method. You canpass any contentto this method, like text, a schema component, an action, or an action group:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->beforeLabel(Icon::make(Heroicon::Star))

```

### #Adding extra content after an entry’s label

You can insert extra content after an entry’s label using theafterLabel()method. You canpass any contentto this method, like text, a schema component, an action, or an action group:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->afterLabel([
        Icon::make(Heroicon::Star),
        'This is the content after the entry\'s label'
    ])

```

By default, the content in theafterLabel()schema is aligned to the end of the container. If you wish to align it to the start of the container, you should pass aSchema::start()object containing the content:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->afterLabel(Schema::start([
        Icon::make(Heroicon::Star),
        'This is the content after the entry\'s label'
    ]))

```

### #Adding extra content below an entry’s label

You can insert extra content below an entry’s label using thebelowLabel()method. You canpass any contentto this method, like text, a schema component, an action, or an action group:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->belowLabel([
        Icon::make(Heroicon::Star),
        'This is the content below the entry\'s label'
    ])

```

NOTE

This may seem like the same as theaboveContent()method. However, when usinginline labels, theaboveContent()method will place the content above the entry, not below the label, since the label is displayed in a separate column to the entry content.

### #Adding extra content above an entry’s content

You can insert extra content above an entry’s content using theaboveContent()method. You canpass any contentto this method, like text, a schema component, an action, or an action group:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->aboveContent([
        Icon::make(Heroicon::Star),
        'This is the content above the entry\'s content'
    ])

```

NOTE

This may seem like the same as thebelowLabel()method. However, when usinginline labels, thebelowLabel()method will place the content below the label, not above the entry’s content, since the label is displayed in a separate column to the entry content.

### #Adding extra content before an entry’s content

You can insert extra content before an entry’s content using thebeforeContent()method. You canpass any contentto this method, like text, a schema component, an action, or an action group:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->beforeContent(Icon::make(Heroicon::Star))

```

### #Adding extra content after an entry’s content

You can insert extra content after an entry’s content using theafterContent()method. You canpass any contentto this method, like text, a schema component, an action, or an action group:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

TextEntry::make('name')
    ->afterContent(Icon::make(Heroicon::Star))

```

## #Adding extra HTML attributes to an entry

You can pass extra HTML attributes to the entry via theextraAttributes()method, which will be merged onto its outer HTML element. The attributes should be represented by an array, where the key is the attribute name and the value is the attribute value:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('slug')
    ->extraAttributes(['class' => 'bg-gray-200'])

```

By default, callingextraAttributes()multiple times will overwrite the previous attributes. If you wish to merge the attributes instead, you can passmerge: trueto the method.

### #Adding extra HTML attributes to the entry wrapper

You can also pass extra HTML attributes to the very outer element of the “entry wrapper” which surrounds the label and content of the entry. This is useful if you want to style the label or spacing of the entry via CSS, since you could target elements as children of the wrapper:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('slug')
    ->extraEntryWrapperAttributes(['class' => 'components-locked'])

```

By default, callingextraEntryWrapperAttributes()multiple times will overwrite the previous attributes. If you wish to merge the attributes instead, you can passmerge: trueto the method.

## #Entry utility injection

The vast majority of methods used to configure entries accept functions as parameters instead of hardcoded values:

```php
use App\Models\User;
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->label(fn (string $state): string => str_contains($state, ' ') ? 'Full name' : 'Name')

TextEntry::make('currentUserEmail')
    ->state(fn (): string => auth()->user()->email)

TextEntry::make('role')
    ->hidden(fn (User $record): bool => $record->role === 'admin')

```

This alone unlocks many customization possibilities.

The package is also able to inject many utilities to use inside these functions, as parameters. All customization methods that accept functions as arguments can inject utilities.

These injected utilities require specific parameter names to be used. Otherwise, Filament doesn’t know what to inject.

### #Injecting the current state of the entry

If you wish to access the currentvalue (state)of the entry, define a$stateparameter:

```php
function ($state) {
    // ...
}

```

### #Injecting the state of another entry or form field

You may also retrieve the state (value) of another entry or form field from within a callback, using a$getparameter:

```php
use Filament\Schemas\Components\Utilities\Get;

function (Get $get) {
    $email = $get('email'); // Store the value of the `email` entry in the `$email` variable.
    //...
}

```

TIP

Unless a form field isreactive, the schema will not refresh when the value of the field changes, only when the next user interaction occurs that makes a request to the server. If you need to react to changes in a field’s value, it should belive().

### #Injecting the current Eloquent record

You may retrieve the Eloquent record for the current schema using a$recordparameter:

```php
use Illuminate\Database\Eloquent\Model;

function (?Model $record) {
    // ...
}

```

### #Injecting the current operation

If you’re writing a schema for a panel resource or relation manager, and you wish to check if a schema iscreate,editorview, use the$operationparameter:

```php
function (string $operation) {
    // ...
}

```

NOTE

You can manually set a schema’s operation using the$schema->operation()method.

### #Injecting the current Livewire component instance

If you wish to access the current Livewire component instance, define a$livewireparameter:

```php
use Livewire\Component;

function (Component $livewire) {
    // ...
}

```

### #Injecting the current entry instance

If you wish to access the current component instance, define a$componentparameter:

```php
use Filament\Infolists\Components\Entry;

function (Entry $component) {
    // ...
}

```

### #Injecting multiple utilities

The parameters are injected dynamically using reflection, so you are able to combine multiple parameters in any order:

```php
use App\Models\User;
use Filament\Schemas\Components\Utilities\Get;
use Livewire\Component as Livewire;

function (Livewire $livewire, Get $get, User $record) {
    // ...
}

```

### #Injecting dependencies from Laravel’s container

You may inject anything from Laravel’s container like normal, alongside utilities:

```php
use App\Models\User;
use Illuminate\Http\Request;

function (Request $request, User $record) {
    // ...
}

```

## #Global settings

If you wish to change the default behavior of all entries globally, then you can call the staticconfigureUsing()method inside a service provider’sboot()method, to which you pass a Closure to modify the entries using. For example, if you wish to make allTextEntrycomponentswords(10), you can do it like so:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::configureUsing(function (TextEntry $entry): void {
    $entry->words(10);
});

```

Of course, you are still able to overwrite this on each entry individually:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('name')
    ->words(null)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources  
**Keywords:** read-only, display, view, presentation

*Extracted from Filament v5 Documentation - 2026-01-28*
