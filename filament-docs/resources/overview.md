# Overview

**URL:** https://filamentphp.com/docs/5.x/resources/overview  
**Section:** resources  
**Page:** overview  
**Priority:** high  
**AI Context:** Core CRUD functionality. Main feature for building admin panels.

---

## #Introduction

Resources are static classes that are used to build CRUD interfaces for your Eloquent models. They describe how administrators should be able to interact with data from your app using tables and forms.

## #Creating a resource

To create a resource for theApp\Models\Customermodel:

```php
php artisan make:filament-resource Customer

```

This will create several files in theapp/Filament/Resourcesdirectory:

```php
Customers/
├── CustomerResource.php
├── Pages/
│   ├── CreateCustomer.php
│   ├── EditCustomer.php
│   └── ListCustomers.php
├── Schemas/
│   └── CustomerForm.php
└── Tables/
    └── CustomersTable.php

```

Your new resource class lives inCustomerResource.php.

The classes in thePagesdirectory are used to customize the pages in the app that interact with your resource. They’re all full-pageLivewirecomponents that you can customize in any way you wish.

The classes in theSchemasdirectory are used to define the content of theformsandinfolistsfor your resource. The classes in theTablesdirectory are used to build the table for your resource.

TIP

Have you created a resource, but it’s not appearing in the navigation menu? If you have amodel policy, make sure you returntruefrom theviewAny()method.

### #Simple (modal) resources

Sometimes, your models are simple enough that you only want to manage records on one page, using modals to create, edit and delete records. To generate a simple resource with modals:

```php
php artisan make:filament-resource Customer --simple

```

Your resource will have a “Manage” page, which is a List page with modals added.

Additionally, your simple resource will have nogetRelations()method, asrelation managersare only displayed on the Edit and View pages, which are not present in simple resources. Everything else is the same.

### #Automatically generating forms and tables

If you’d like to save time, Filament can automatically generate theformandtablefor you, based on your model’s database columns, using--generate:

```php
php artisan make:filament-resource Customer --generate

```

### #Handling soft-deletes

By default, you will not be able to interact with deleted records in the app. If you’d like to add functionality to restore, force-delete and filter trashed records in your resource, use the--soft-deletesflag when generating the resource:

```php
php artisan make:filament-resource Customer --soft-deletes

```

You can find out more about soft-deletinghere.

### #Generating a View page

By default, only List, Create and Edit pages are generated for your resource. If you’d also like aView page, use the--viewflag:

```php
php artisan make:filament-resource Customer --view

```

### #Specifying a custom model namespace

By default, Filament will assume that your model exists in theApp\Modelsdirectory. You can pass a different namespace for the model using the--model-namespaceflag:

```php
php artisan make:filament-resource Customer --model-namespace=Custom\\Path\\Models

```

In this example, the model should exist atCustom\Path\Models\Customer. Please note the double backslashes\\in the command that are required.

Now whengenerating the resource, Filament will be able to locate the model and read the database schema.

### #Generating the model, migration and factory at the same time

If you’d like to save time when scaffolding your resources, Filament can also generate the model, migration and factory for the new resource at the same time using the--model,--migrationand--factoryflags in any combination:

```php
php artisan make:filament-resource Customer --model --migration --factory

```

## #Record titles

A$recordTitleAttributemay be set for your resource, which is the name of the column on your model that can be used to identify it from others.

For example, this could be a blog post’stitleor a customer’sname:

```php
protected static ?string $recordTitleAttribute = 'name';

```

This is required for features likeglobal searchto work.

TIP

You may specify the name of anEloquent accessorif just one column is inadequate at identifying a record.

## #Resource forms

Resource classes contain aform()method that is used to build the forms on theCreateandEditpages.

By default, Filament creates a form schema file for you, which is referenced in theform()method. This is to keep your resource class clean and organized, otherwise it can get quite large:

```php
use App\Filament\Resources\Customers\Schemas\CustomerForm;
use Filament\Schemas\Schema;

public static function form(Schema $schema): Schema
{
    return CustomerForm::configure($schema);
}

```

In theCustomerFormclass, you can define the fields and layout of your form:

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

public static function configure(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('name')->required(),
            TextInput::make('email')->email()->required(),
            // ...
        ]);
}

```

Thecomponents()method is used to define the structure of your form. It is an array offieldsandlayout components, in the order they should appear in your form.

Check out the Forms docs for aguideon how to build forms with Filament.

TIP

If you would prefer to define the form directly in the resource class, you can do so and delete the form schema class altogether:

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

public static function form(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('name')->required(),
            TextInput::make('email')->email()->required(),
            // ...
        ]);
}

```

### #Hiding components based on the current operation

ThehiddenOn()method of form components allows you to dynamically hide fields based on the current page or action.

In this example, we hide thepasswordfield on theeditpage:

```php
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Operation;

TextInput::make('password')
    ->password()
    ->required()
    ->hiddenOn(Operation::Edit),

```

Alternatively, we have avisibleOn()shortcut method for only showing a field on one page or action:

```php
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\Operation;

TextInput::make('password')
    ->password()
    ->required()
    ->visibleOn(Operation::Create),

```

## #Resource tables

Resource classes contain atable()method that is used to build the table on theList page.

By default, Filament creates a table file for you, which is referenced in thetable()method. This is to keep your resource class clean and organized, otherwise it can get quite large:

```php
use App\Filament\Resources\Customers\Tables\CustomersTable;
use Filament\Tables\Table;

public static function table(Table $table): Table
{
    return CustomersTable::configure($table);
}

```

In theCustomersTableclass, you can define the columns, filters and actions of the table:

```php
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

public static function configure(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name'),
            TextColumn::make('email'),
            // ...
        ])
        ->filters([
            Filter::make('verified')
                ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
            // ...
        ])
        ->recordActions([
            EditAction::make(),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
}

```

Check out thetablesdocs to find out how to add table columns, filters, actions and more.

TIP

If you would prefer to define the table directly in the resource class, you can do so and delete the table class altogether:

```php
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name'),
            TextColumn::make('email'),
            // ...
        ])
        ->filters([
            Filter::make('verified')
                ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
            // ...
        ])
        ->recordActions([
            EditAction::make(),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
}

```

## #Customizing the model label

Each resource has a “model label” which is automatically generated from the model name. For example, anApp\Models\Customermodel will have acustomerlabel.

The label is used in several parts of the UI, and you may customize it using the$modelLabelproperty:

```php
protected static ?string $modelLabel = 'cliente';

```

Alternatively, you may use thegetModelLabel()to define a dynamic label:

```php
public static function getModelLabel(): string
{
    return __('filament/resources/customer.label');
}

```

### #Customizing the plural model label

Resources also have a “plural model label” which is automatically generated from the model label. For example, acustomerlabel will be pluralized intocustomers.

You may customize the plural version of the label using the$pluralModelLabelproperty:

```php
protected static ?string $pluralModelLabel = 'clientes';

```

Alternatively, you may set a dynamic plural label in thegetPluralModelLabel()method:

```php
public static function getPluralModelLabel(): string
{
    return __('filament/resources/customer.plural_label');
}

```

### #Automatic model label capitalization

By default, Filament will automatically capitalize each word in the model label, for some parts of the UI. For example, in page titles, the navigation menu, and the breadcrumbs.

If you want to disable this behavior for a resource, you can set$hasTitleCaseModelLabelin the resource:

```php
protected static bool $hasTitleCaseModelLabel = false;

```

## #Resource navigation items

Filament will automatically generate a navigation menu item for your resource using theplural label.

If you’d like to customize the navigation item label, you may use the$navigationLabelproperty:

```php
protected static ?string $navigationLabel = 'Mis Clientes';

```

Alternatively, you may set a dynamic navigation label in thegetNavigationLabel()method:

```php
public static function getNavigationLabel(): string
{
    return __('filament/resources/customer.navigation_label');
}

```

### #Setting a resource navigation icon

The$navigationIconproperty supports the name of any Blade component. By default,Heroiconsare installed. However, you may create your own custom icon components or install an alternative library if you wish.

```php
use BackedEnum;

protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

```

Alternatively, you may set a dynamic navigation icon in thegetNavigationIcon()method:

```php
use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;

public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
{
    return 'heroicon-o-user-group';
}

```

### #Sorting resource navigation items

The$navigationSortproperty allows you to specify the order in which navigation items are listed:

```php
protected static ?int $navigationSort = 2;

```

Alternatively, you may set a dynamic navigation item order in thegetNavigationSort()method:

```php
public static function getNavigationSort(): ?int
{
    return 2;
}

```

### #Grouping resource navigation items

You may group navigation items by specifying a$navigationGroupproperty:

```php
use UnitEnum;

protected static string | UnitEnum | null $navigationGroup = 'Shop';

```

Alternatively, you may use thegetNavigationGroup()method to set a dynamic group label:

```php
public static function getNavigationGroup(): ?string
{
    return __('filament/navigation.groups.shop');
}

```

#### #Grouping resource navigation items under other items

You may group navigation items as children of other items, by passing the label of the parent item as the$navigationParentItem:

```php
use UnitEnum;

protected static ?string $navigationParentItem = 'Products';

protected static string | UnitEnum | null $navigationGroup = 'Shop';

```

As seen above, if the parent item has a navigation group, that navigation group must also be defined, so the correct parent item can be identified.

You may also use thegetNavigationParentItem()method to set a dynamic parent item label:

```php
public static function getNavigationParentItem(): ?string
{
    return __('filament/navigation.groups.shop.items.products');
}

```

TIP

If you’re reaching for a third level of navigation like this, you should consider usingclustersinstead, which are a logical grouping of resources andcustom pages, which can share their own separate navigation.

## #Generating URLs to resource pages

Filament provides agetUrl()static method on resource classes to generate URLs to resources and specific pages within them. Traditionally, you would need to construct the URL by hand or by using Laravel’sroute()helper, but these methods depend on knowledge of the resource’s slug or route naming conventions.

ThegetUrl()method, without any arguments, will generate a URL to the resource’sList page:

```php
use App\Filament\Resources\Customers\CustomerResource;

CustomerResource::getUrl(); // /admin/customers

```

You may also generate URLs to specific pages within the resource. The name of each page is the array key in thegetPages()array of the resource. For example, to generate a URL to theCreate page:

```php
use App\Filament\Resources\Customers\CustomerResource;

CustomerResource::getUrl('create'); // /admin/customers/create

```

Some pages in thegetPages()method use URL parameters likerecord. To generate a URL to these pages and pass in a record, you should use the second argument:

```php
use App\Filament\Resources\Customers\CustomerResource;

CustomerResource::getUrl('edit', ['record' => $customer]); // /admin/customers/edit/1

```

In this example,$customercan be an Eloquent model object, or an ID.

### #Generating URLs to resource modals

This can be especially useful if you are usingsimple resourceswith only one page.

To generate a URL for an action in the resource’s table, you should pass thetableActionandtableActionRecordas URL parameters:

```php
use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\EditAction;

CustomerResource::getUrl(parameters: [
    'tableAction' => EditAction::getDefaultName(),
    'tableActionRecord' => $customer,
]); // /admin/customers?tableAction=edit&tableActionRecord=1

```

Or if you want to generate a URL for an action on the page like aCreateActionin the header, you can pass it in to theactionparameter:

```php
use App\Filament\Resources\Customers\CustomerResource;
use Filament\Actions\CreateAction;

CustomerResource::getUrl(parameters: [
    'action' => CreateAction::getDefaultName(),
]); // /admin/customers?action=create

```

### #Generating URLs to resources in other panels

If you have multiple panels in your app,getUrl()will generate a URL within the current panel. You can also indicate which panel the resource is associated with, by passing the panel ID to thepanelargument:

```php
use App\Filament\Resources\Customers\CustomerResource;

CustomerResource::getUrl(panel: 'marketing');

```

## #Customizing the resource Eloquent query

Within Filament, every query to your resource model will start with thegetEloquentQuery()method.

Because of this, it’s very easy to apply your own query constraints ormodel scopesthat affect the entire resource:

```php
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->where('is_active', true);
}

```

### #Disabling global scopes

By default, Filament will observe all global scopes that are registered to your model. However, this may not be ideal if you wish to access, for example, soft-deleted records.

To overcome this, you may override thegetEloquentQuery()method that Filament uses:

```php
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->withoutGlobalScopes();
}

```

Alternatively, you may remove specific global scopes:

```php
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->withoutGlobalScopes([ActiveScope::class]);
}

```

More information about removing global scopes may be found in theLaravel documentation.

## #Customizing the resource URL

By default, Filament will generate a URL based on the name of the resource. You can customize this by setting the$slugproperty on the resource:

```php
protected static ?string $slug = 'pending-orders';

```

## #Resource sub-navigation

Sub-navigation allows the user to navigate between different pages within a resource. Typically, all pages in the sub-navigation will be related to the same record in the resource. For example, in a Customer resource, you may have a sub-navigation with the following pages:
- View customer, aViewRecordpagethat provides a read-only view of the customer’s details.
- Edit customer, anEditRecordpagethat allows the user to edit the customer’s details.
- Edit customer contact, anEditRecordpagethat allows the user to edit the customer’s contact details. You canlearn how to create more than one Edit page.
- Manage addresses, aManageRelatedRecordspagethat allows the user to manage the customer’s addresses.
- Manage payments, aManageRelatedRecordspagethat allows the user to manage the customer’s payments.


To add a sub-navigation to each “singular record” page in the resource, you can add thegetRecordSubNavigation()method to the resource class:

```php
use Filament\Resources\Pages\Page;

public static function getRecordSubNavigation(Page $page): array
{
    return $page->generateNavigationItems([
        ViewCustomer::class,
        EditCustomer::class,
        EditCustomerContact::class,
        ManageCustomerAddresses::class,
        ManageCustomerPayments::class,
    ]);
}

```

Each item in the sub-navigation can be customized using thesame navigation methods as normal pages.

TIP

If you’re looking to add sub-navigation to switchbetweenentire resources andcustom pages, you might be looking forclusters, which are used to group these together. ThegetRecordSubNavigation()method is intended to construct a navigation between pages that relate to a particular recordinsidea resource.

### #Setting the sub-navigation position for a resource

The sub-navigation is rendered at the start of the page by default. You may change the position for all pages in a resource by setting the$subNavigationPositionproperty on the resource. The value may beSubNavigationPosition::Start,SubNavigationPosition::End, orSubNavigationPosition::Topto render the sub-navigation as tabs:

```php
use Filament\Pages\Enums\SubNavigationPosition;

protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

```

## #Deleting resource pages

If you’d like to delete a page from your resource, you can just delete the page file from thePagesdirectory of your resource, and its entry in thegetPages()method.

For example, you may have a resource with records that may not be created by anyone. Delete theCreatepage file, and then remove it fromgetPages():

```php
public static function getPages(): array
{
    return [
        'index' => ListCustomers::route('/'),
        'edit' => EditCustomer::route('/{record}/edit'),
    ];
}

```

Deleting a page will not delete any actions that link to that page. Any actions will open a modal instead of sending the user to the non-existent page. For instance, theCreateActionon the List page, theEditActionon the table or View page, or theViewActionon the table or Edit page. If you want to remove those buttons, you must delete the actions as well.

## #Security

## #Authorization

For authorization, Filament will observe anymodel policiesthat are registered in your app. The following methods are used:
- viewAny()is used to completely hide resources from the navigation menu, and prevents the user from accessing any pages.
- create()is used to controlcreating new records.
- update()is used to controlediting a record.
- view()is used to controlviewing a record.
- delete()is used to prevent a single record from being deleted.deleteAny()is used to prevent records from being bulk deleted. Filament uses thedeleteAny()method because iterating through multiple records and checking thedelete()policy is not very performant. When using aDeleteBulkAction, if you want to call thedelete()method for each record anyway, you should use theDeleteBulkAction::make()->authorizeIndividualRecords()method. Any records that fail the authorization check will not be processed.
- forceDelete()is used to prevent a single soft-deleted record from being force-deleted.forceDeleteAny()is used to prevent records from being bulk force-deleted. Filament uses theforceDeleteAny()method because iterating through multiple records and checking theforceDelete()policy is not very performant. When using aForceDeleteBulkAction, if you want to call theforceDelete()method for each record anyway, you should use theForceDeleteBulkAction::make()->authorizeIndividualRecords()method. Any records that fail the authorization check will not be processed.
- restore()is used to prevent a single soft-deleted record from being restored.restoreAny()is used to prevent records from being bulk restored. Filament uses therestoreAny()method because iterating through multiple records and checking therestore()policy is not very performant. When using aRestoreBulkAction, if you want to call therestore()method for each record anyway, you should use theRestoreBulkAction::make()->authorizeIndividualRecords()method. Any records that fail the authorization check will not be processed.
- reorder()is used to controlreordering records in a table.


### #Skipping authorization

If you’d like to skip authorization for a resource, you may set the$shouldSkipAuthorizationproperty totrue:

```php
protected static bool $shouldSkipAuthorization = true;

```

### #Protecting model attributes

Filament will expose all model attributes to JavaScript, except if they are$hiddenon your model. This is Livewire’s behavior for model binding. We preserve this functionality to facilitate the dynamic addition and removal of form fields after they are initially loaded, while preserving the data they may need.

NOTE

While attributes may be visible in JavaScript, only those with a form field are actually editable by the user. This is not an issue with mass assignment.

To remove certain attributes from JavaScript on the Edit and View pages, you may overridethemutateFormDataBeforeFill()method:

```php
protected function mutateFormDataBeforeFill(array $data): array
{
    unset($data['is_admin']);

    return $data;
}

```

In this example, we remove theis_adminattribute from JavaScript, as it’s not being used by the form.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables, actions  
**Keywords:** crud, database, eloquent, models, admin panel

*Extracted from Filament v5 Documentation - 2026-01-28*
