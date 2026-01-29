# Sections

**URL:** https://filamentphp.com/docs/5.x/schemas/sections  
**Section:** schemas  
**Page:** sections  
**Priority:** medium  
**AI Context:** Layout system for building complex UIs with sections, tabs, wizards.

---

## #Introduction

You may want to separate your fields into sections, each with a heading and description. To do this, you can use a section component:

```php
use Filament\Schemas\Components\Section;

Section::make('Rate limiting')
    ->description('Prevent abuse by limiting the number of requests per period')
    ->schema([
        // ...
    ])

```

You can also use a section without a header, which just wraps the components in a simple card:

```php
use Filament\Schemas\Components\Section;

Section::make()
    ->schema([
        // ...
    ])

```

## #Adding an icon to the section’s header

You may add aniconto the section’s header using theicon()method:

```php
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;

Section::make('Cart')
    ->description('The items you have selected for purchase')
    ->icon(Heroicon::ShoppingBag)
    ->schema([
        // ...
    ])

```

## #Positioning the heading and description aside

You may use theaside()to align heading & description on the left, and the components inside a card on the right:

```php
use Filament\Schemas\Components\Section;

Section::make('Rate limiting')
    ->description('Prevent abuse by limiting the number of requests per period')
    ->aside()
    ->schema([
        // ...
    ])

```

Optionally, you may pass a boolean value to control if the section should be aside or not:

```php
use Filament\Schemas\Components\Section;

Section::make('Rate limiting')
    ->description('Prevent abuse by limiting the number of requests per period')
    ->aside(FeatureFlag::active())
    ->schema([
        // ...
    ])

```

## #Collapsing sections

Sections may becollapsible()to optionally hide long content:

```php
use Filament\Schemas\Components\Section;

Section::make('Cart')
    ->description('The items you have selected for purchase')
    ->schema([
        // ...
    ])
    ->collapsible()

```

Your sections may becollapsed()by default:

```php
use Filament\Schemas\Components\Section;

Section::make('Cart')
    ->description('The items you have selected for purchase')
    ->schema([
        // ...
    ])
    ->collapsed()

```

Optionally, thecollapsible()andcollapsed()methods accept a boolean value to control if the section should be collapsible and collapsed or not:

```php
use Filament\Schemas\Components\Section;

Section::make('Cart')
    ->description('The items you have selected for purchase')
    ->schema([
        // ...
    ])
    ->collapsible(FeatureFlag::active())
    ->collapsed(FeatureFlag::active())

```

### #Persisting collapsed sections in the user’s session

You can persist whether a section is collapsed in local storage using thepersistCollapsed()method, so it will remain collapsed when the user refreshes the page:

```php
use Filament\Schemas\Components\Section;

Section::make('Cart')
    ->description('The items you have selected for purchase')
    ->schema([
        // ...
    ])
    ->collapsible()
    ->persistCollapsed()

```

To persist the collapse state, the local storage needs a unique ID to store the state. This ID is generated based on the heading of the section. If your section does not have a heading, or if you have multiple sections with the same heading that you do not want to collapse together, you can manually specify theid()of that section to prevent an ID conflict:

```php
use Filament\Schemas\Components\Section;

Section::make('Cart')
    ->description('The items you have selected for purchase')
    ->schema([
        // ...
    ])
    ->collapsible()
    ->persistCollapsed()
    ->id('order-cart')

```

Optionally, thepersistCollapsed()method accepts a boolean value to control if the section should persist its collapsed state or not:

```php
use Filament\Schemas\Components\Section;

Section::make('Cart')
    ->description('The items you have selected for purchase')
    ->schema([
        // ...
    ])
    ->collapsible()
    ->persistCollapsed(FeatureFlag::active())

```

## #Compact section styling

When nesting sections, you can use a more compact styling:

```php
use Filament\Schemas\Components\Section;

Section::make('Rate limiting')
    ->description('Prevent abuse by limiting the number of requests per period')
    ->schema([
        // ...
    ])
    ->compact()

```

Optionally, thecompact()method accepts a boolean value to control if the section should be compact or not:

```php
use Filament\Schemas\Components\Section;

Section::make('Rate limiting')
    ->description('Prevent abuse by limiting the number of requests per period')
    ->schema([
        // ...
    ])
    ->compact(FeatureFlag::active())

```

## #Secondary section styling

By default, sections have a contrasting background color, which makes them stand out against a gray background. Secondary styling gives the section a less contrasting background, so it is usually slightly darker. This is a better styling when the background color behind the section is the same color as the default section background color, for example when a section is nested inside another section. Secondary sections can be created using thesecondary()method:

```php
use Filament\Schemas\Components\Section;

Section::make('Notes')
    ->schema([
        // ...
    ])
    ->secondary()
    ->compact()

```

Optionally, thesecondary()method accepts a boolean value to control if the section should be secondary or not:

```php
use Filament\Schemas\Components\Section;

Section::make('Notes')
    ->schema([
        // ...
    ])
    ->secondary(FeatureFlag::active())

```

## #Inserting actions and other components in the header of a section

You may insertactionsand any other schema component (usuallyprime components) into the header of a section by passing an array of components to theafterHeader()method:

```php
use Filament\Schemas\Components\Section;

Section::make('Rate limiting')
    ->description('Prevent abuse by limiting the number of requests per period')
    ->afterHeader([
        Action::make('test'),
    ])
    ->schema([
        // ...
    ])

```

## #Inserting actions and other components in the footer of a section

You may insertactionsand any other schema component (usuallyprime components) into the footer of a section by passing an array of components to thefooter()method:

```php
use Filament\Schemas\Components\Section;

Section::make('Rate limiting')
    ->description('Prevent abuse by limiting the number of requests per period')
    ->schema([
        // ...
    ])
    ->footer([
        Action::make('test'),
    ])

```

## #Using grid columns within a section

You may use thecolumns()method to easily create agridwithin the section:

```php
use Filament\Schemas\Components\Section;

Section::make('Heading')
    ->schema([
        // ...
    ])
    ->columns(2)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, infolists  
**Keywords:** layout, structure, organization, ui

*Extracted from Filament v5 Documentation - 2026-01-28*
