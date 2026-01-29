# Enum tricks

**URL:** https://filamentphp.com/docs/5.x/advanced/enums  
**Section:** advanced  
**Page:** enums  
**Priority:** low  
**AI Context:** Advanced features like render hooks and modular architecture.

---

## #Introduction

Enums are special PHP classes that represent a fixed set of constants. They are useful for modeling concepts that have a limited number of possible values, like days of the week, months in a year, or the suits in a deck of cards.

Since enum “cases” are instances of the enum class, adding interfaces to enums proves to be very useful. Filament provides a collection of interfaces that you can add to enums, which enhance your experience when working with them.

NOTE

When using an enum with an attribute on your Eloquent model, pleaseensure that it is cast correctly.

## #Enum labels

TheHasLabelinterface transforms an enum instance into a textual label. This is useful for displaying human-readable enum values in your UI.

```php
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Status: string implements HasLabel
{
    case Draft = 'draft';
    case Reviewing = 'reviewing';
    case Published = 'published';
    case Rejected = 'rejected';
    
    public function getLabel(): string | Htmlable | null
    {
        return $this->name;
        
        // or
    
        return match ($this) {
            self::Draft => 'Draft',
            self::Reviewing => 'Reviewing',
            self::Published => 'Published',
            self::Rejected => 'Rejected',
        };
    }
}

```

### #Using the enum label with form field options

TheHasLabelinterface can be used to generate an array of options from an enum, where the enum’s value is the key and the enum’s label is the value. This applies to form fields likeSelectandCheckboxList, as well as the Table Builder’sSelectColumnandSelectFilter:

```php
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;

Select::make('status')
    ->options(Status::class)

CheckboxList::make('status')
    ->options(Status::class)

Radio::make('status')
    ->options(Status::class)

SelectColumn::make('status')
    ->options(Status::class)

SelectFilter::make('status')
    ->options(Status::class)

```

In these examples,Status::classis the enum class which implementsHasLabel, and the options are generated from that:

```php
[
    'draft' => 'Draft',
    'reviewing' => 'Reviewing',
    'published' => 'Published',
    'rejected' => 'Rejected',
]

```

### #Using the enum label with a text column in your table

If you use aTextColumnwith the Table Builder, and it is cast to an enum in your Eloquent model, Filament will automatically use theHasLabelinterface to display the enum’s label instead of its raw value.

### #Using the enum label as a group title in your table

If you use agroupingwith the Table Builder, and it is cast to an enum in your Eloquent model, Filament will automatically use theHasLabelinterface to display the enum’s label instead of its raw value. The label will be displayed as thetitle of each group.

### #Using the enum label with a text entry in your infolist

If you use aTextEntryin an infolist, and it is cast to an enum in your Eloquent model, Filament will automatically use theHasLabelinterface to display the enum’s label instead of its raw value.

## #Enum colors

TheHasColorinterface transforms an enum instance into acolor. This is useful for displaying colored enum values in your UI.

```php
use Filament\Support\Contracts\HasColor;

enum Status: string implements HasColor
{
    case Draft = 'draft';
    case Reviewing = 'reviewing';
    case Published = 'published';
    case Rejected = 'rejected';
    
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Reviewing => 'warning',
            self::Published => 'success',
            self::Rejected => 'danger',
        };
    }
}

```

### #Using the enum color with a text column in your table

If you use aTextColumnwith the Table Builder, and it is cast to an enum in your Eloquent model, Filament will automatically use theHasColorinterface to display the enum label in its color. This works best if you use thebadge()method on the column.

### #Using the enum color with a text entry in your infolist

If you use aTextEntryin an infolist, and it is cast to an enum in your Eloquent model, Filament will automatically use theHasColorinterface to display the enum label in its color. This works best if you use thebadge()method on the entry.

### #Using the enum color with a toggle buttons field in your form

If you use aToggleButtonsform field, and it is set to use an enum for its options, Filament will automatically use theHasColorinterface to display the enum label in its color.

## #Enum icons

TheHasIconinterface transforms an enum instance into anicon. This is useful for displaying icons alongside enum values in your UI.

```php
use BackedEnum;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum Status: string implements HasIcon
{
    case Draft = 'draft';
    case Reviewing = 'reviewing';
    case Published = 'published';
    case Rejected = 'rejected';

    public function getIcon(): string | BackedEnum | Htmlable | null
    {
        return match ($this) {
            self::Draft => Heroicon::Pencil,
            self::Reviewing => Heroicon::Eye,
            self::Published => Heroicon::Check,
            self::Rejected => Heroicon::XMark,
        };
    }
}

```

### #Using the enum icon with a text column in your table

If you use aTextColumnwith the Table Builder, and it is cast to an enum in your Eloquent model, Filament will automatically use theHasIconinterface to display the enum’s icon aside its label. This works best if you use thebadge()method on the column.

### #Using the enum icon with a text entry in your infolist

If you use aTextEntryin an infolist, and it is cast to an enum in your Eloquent model, Filament will automatically use theHasIconinterface to display the enum’s icon aside its label. This works best if you use thebadge()method on the entry.

### #Using the enum icon with a toggle buttons field in your form

If you use aToggleButtonsform field, and it is set to use an enum for its options, Filament will automatically use theHasIconinterface to display the enum’s icon aside its label.

## #Enum descriptions

TheHasDescriptioninterface transforms an enum instance into a textual description, often displayed under itslabel. This is useful for displaying human-friendly descriptions in your UI.

```php
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum Status: string implements HasLabel, HasDescription
{
    case Draft = 'draft';
    case Reviewing = 'reviewing';
    case Published = 'published';
    case Rejected = 'rejected';
    
    public function getLabel(): string | Htmlable | null
    {
        return $this->name;
    }
    
    public function getDescription(): string | Htmlable | null
    {
        return match ($this) {
            self::Draft => 'This has not finished being written yet.',
            self::Reviewing => 'This is ready for a staff member to read.',
            self::Published => 'This has been approved by a staff member and is public on the website.',
            self::Rejected => 'A staff member has decided this is not appropriate for the website.',
        };
    }
}

```

### #Using the enum description with form field descriptions

TheHasDescriptioninterface can be used to generate an array of descriptions from an enum, where the enum’s value is the key and the enum’s description is the value. This applies to form fields likeRadioandCheckboxList:

```php
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;

Radio::make('status')
    ->options(Status::class)

CheckboxList::make('status')
    ->options(Status::class)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:**   
**Keywords:** advanced, hooks, ddd, architecture

*Extracted from Filament v5 Documentation - 2026-01-28*
