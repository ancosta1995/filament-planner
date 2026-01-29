# Code quality tips

**URL:** https://filamentphp.com/docs/5.x/resources/code-quality-tips  
**Section:** resources  
**Page:** code-quality-tips  
**Priority:** high  
**AI Context:** Core CRUD functionality. Main feature for building admin panels.

---

## #Using schema and table classes

Since many Filament methods define both the UI and the functionality of the app in just one method, it can be easy to end up with giant methods and files. These can be difficult to read, even if your code has a clean and consistent style.

Filament tries to mitigate some of this by providing dedicated schema and table classes when you generate a resource. These classes have aconfigure()method that accepts a$schemaor$table. You can then call theconfigure()method from anywhere you want to define a schema or table.

For example, if you have the followingapp/Filament/Resources/Customers/Schemas/CustomerForm.phpfile:

```php
namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                // ...
            ]);
    }
}

```

You can use this in theform()method of the resource:

```php
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use Filament\Schemas\Schema;

public static function form(Schema $schema): Schema
{
    return CustomerForm::configure($schema);
}

```

You could do the same for thetable():

```php
use App\Filament\Resources\Customers\Schemas\CustomersTable;
use Filament\Tables\Table;

public static function table(Table $table): Table
{
    return CustomersTable::configure($table);
}

```

Or theinfolist():

```php
use App\Filament\Resources\Customers\Schemas\CustomerInfolist;
use Filament\Schemas\Schema;

public static function infolist(Schema $schema): Schema
{
    return CustomerInfolist::configure($schema);
}

```

These schema and table classes deliberately don’t have a parent class or interface by default. If Filament were to enforce a method signature for theconfigure()method, you would not be able to pass your own configuration variables to the method, which could be useful if you wanted to reuse the same class in multiple places but with slight tweaks.

## #Using component classes

Even if you are usingschema and table classesto keep the schema and table definitions in their own files, you can still end up with a very longconfigure()method. This is especially true if you are using a lot of components in your schema or table, or if the components require a lot of configuration.

You can mitigate this by creating dedicated classes for each component. For example, if you have aTextInputcomponent that requires a lot of configuration, you can create a dedicated class for it:

```php
namespace App\Filament\Resources\Customers\Schemas\Components;

use Filament\Forms\Components\TextInput;

class CustomerNameInput
{
    public static function make(): TextInput
    {
        return TextInput::make('name')
            ->label('Full name')
            ->required()
            ->maxLength(255)
            ->placeholder('Enter your full name')
            ->belowContent('This is the name that will be displayed on your profile.');
    }
}

```

You can then use this class in your schema or table:

```php
use App\Filament\Resources\Customers\Schemas\Components\CustomerNameInput;
use Filament\Schemas\Schema;

public static function configure(Schema $schema): Schema
{
    return $schema
        ->components([
            CustomerNameInput::make(),
            // ...
        ]);
}

```

You could do this with a number of different types of component. There are no enforced rules as to how these components should be named or where they should be stored. However, here are some ideas:
- Schema components: These could live in theSchemas/Componentsdirectory of the resource. They could be named after the component they are wrapping, for exampleCustomerNameInputorCustomerCountrySelect.
- Table columns: These could live in theTables/Columnsdirectory of the resource. They could be named after the column followed byColumn, for exampleCustomerNameColumnorCustomerCountryColumn.
- Table filters: These could live in theTables/Filtersdirectory of the resource. They could be named after the filter followed byFilter, for exampleCustomerCountryFilterorCustomerStatusFilter.
- Actions: These could live in theActionsdirectory of the resource. They could be named after the action followed byActionorBulkAction, for exampleEmailCustomerActionorUpdateCustomerCountryBulkAction.


As a further example, here is a potentialEmailCustomerActionclass:

```php
namespace App\Filament\Resources\Customers\Actions;

use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Icons\Heroicon;

class EmailCustomerAction
{
    public static function make(): Action
    {
        return Action::make('email')
            ->label('Send email')
            ->icon(Heroicon::Envelope)
            ->schema([
                TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Textarea::make('body')
                    ->autosize()
                    ->required(),
            ])
            ->action(function (Customer $customer, array $data) {
                // ...
            });
    }
}

```

And you could use it in thegetHeaderActions()of a page:

```php
use App\Filament\Resources\Customers\Actions\EmailCustomerAction;

protected function getHeaderActions(): array
{
    return [
        EmailCustomerAction::make(),
    ];
}

```

Or you could use it on a table row:

```php
use App\Filament\Resources\Customers\Actions\EmailCustomerAction;
use Filament\Tables\Table;

public static function configure(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->recordActions([
            EmailCustomerAction::make(),
        ]);
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables, actions  
**Keywords:** crud, database, eloquent, models, admin panel

*Extracted from Filament v5 Documentation - 2026-01-28*
