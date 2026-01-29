# Repeatable entry

**URL:** https://filamentphp.com/docs/5.x/infolists/repeatable-entry  
**Section:** infolists  
**Page:** repeatable-entry  
**Priority:** medium  
**AI Context:** Display read-only data in structured layouts.

---

## #Introduction

The repeatable entry allows you to repeat a set of entries and layout components for items in an array or relationship.

```php
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;

RepeatableEntry::make('comments')
    ->schema([
        TextEntry::make('author.name'),
        TextEntry::make('title'),
        TextEntry::make('content')
            ->columnSpan(2),
    ])
    ->columns(2)

```

As you can see, the repeatable entry has an embeddedschema()which gets repeated for each item.

For example, the state of this entry might be represented as:

```php
[
    [
        'author' => ['name' => 'Jane Doe'],
        'title' => 'Wow!',
        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisl eget aliquam ultricies, nunc nisl aliquet nunc, quis aliquam nisl.',
    ],
    [
        'author' => ['name' => 'John Doe'],
        'title' => 'This isn\'t working. Help!',
        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisl eget aliquam ultricies, nunc nisl aliquet nunc, quis aliquam nisl.',
    ],
]

```

Alternatively,commentsandauthorcould be Eloquent relationships,titleandcontentcould be attributes on the comment model, andnamecould be an attribute on the author model. Filament will automatically handle the relationship loading and display the data in the same way.

## #Grid layout

You may organize repeatable items into columns by using thegrid()method:

```php
use Filament\Infolists\Components\RepeatableEntry;

RepeatableEntry::make('comments')
    ->schema([
        // ...
    ])
    ->grid(2)

```

This method accepts the same options as thecolumns()method of thegrid. This allows you to responsively customize the number of grid columns at various breakpoints.

## #Removing the styled container

By default, each item in a repeatable entry is wrapped in a container styled as a card. You may remove the styled container usingcontained():

```php
use Filament\Infolists\Components\RepeatableEntry;

RepeatableEntry::make('comments')
    ->schema([
        // ...
    ])
    ->contained(false)

```

## #Table repeatable layout

You can present repeatable items in a table format using thetable()method, which accepts an array ofTableColumnobjects. These objects represent the columns of the table, which correspond to any components in the schema of the entry:

```php
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;

RepeatableEntry::make('comments')
    ->table([
        TableColumn::make('Author'),
        TableColumn::make('Title'),
        TableColumn::make('Published'),
    ])
    ->schema([
        TextEntry::make('author.name'),
        TextEntry::make('title'),
        IconEntry::make('is_published')
            ->boolean(),
    ])

```

The labels displayed in the header of the table are passed to theTableColumn::make()method. If you want to provide an accessible label for a column but do not wish to display it, you can use thehiddenHeaderLabel()method:

```php
use Filament\Infolists\Components\RepeatableEntry\TableColumn;

TableColumn::make('Name')
    ->hiddenHeaderLabel()

```

You can enable wrapping of the column header using thewrapHeader()method:

```php
use Filament\Infolists\Components\RepeatableEntry\TableColumn;

TableColumn::make('Name')
    ->wrapHeader()

```

You can also adjust the alignment of the column header using thealignment()method, passing anAlignmentoption ofAlignment::Start,Alignment::Center, orAlignment::End:

```php
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Support\Enums\Alignment;

TableColumn::make('Name')
    ->alignment(Alignment::Center)

```

You can set a fixed column width using thewidth()method, passing a string value that represents the width of the column. This value is passed directly to thestyleattribute of the column header:

```php
use Filament\Infolists\Components\RepeatableEntry\TableColumn;

TableColumn::make('Name')
    ->width('200px')

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources  
**Keywords:** read-only, display, view, presentation

*Extracted from Filament v5 Documentation - 2026-01-28*
