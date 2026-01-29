# Import action

**URL:** https://filamentphp.com/docs/5.x/actions/import  
**Section:** actions  
**Page:** import  
**Priority:** high  
**AI Context:** Interactive buttons with modals for user actions.

---

## #Introduction

Filament includes an action that is able to import rows from a CSV. When the trigger button is clicked, a modal asks the user for a file. Once they upload one, they are able to map each column in the CSV to a real column in the database. If any rows fail validation, they will be compiled into a downloadable CSV for the user to review after the rest of the rows have been imported. Users can also download an example CSV file containing all the columns that can be imported.

This feature usesjob batchesanddatabase notifications, so you need to publish those migrations from Laravel. Also, you need to publish the migrations for tables that Filament uses to store information about imports:

```php
php artisan make:queue-batches-table
php artisan make:notifications-table
php artisan vendor:publish --tag=filament-actions-migrations
php artisan migrate

```

If you’d like to receive import notifications in a panel, you can enable them in thepanel configuration.

NOTE

If you’re using PostgreSQL, make sure that thedatacolumn in the notifications migration is usingjson():$table->json('data').

NOTE

If you’re using UUIDs for yourUsermodel, make sure that yournotifiablecolumn in the notifications migration is usinguuidMorphs():$table->uuidMorphs('notifiable').

You may use theImportActionlike so:

```php
use App\Filament\Imports\ProductImporter;
use Filament\Actions\ImportAction;

ImportAction::make()
    ->importer(ProductImporter::class)

```

If you want to add this action to the header of a table, you may do so like this:

```php
use App\Filament\Imports\ProductImporter;
use Filament\Actions\ImportAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->headerActions([
            ImportAction::make()
                ->importer(ProductImporter::class)
        ]);
}

```

The“importer” class needs to be createdto tell Filament how to import each row of the CSV.

If you have more than oneImportActionin the same place, you should give each a unique name in themake()method:

```php
use Filament\Actions\ImportAction;

ImportAction::make('importProducts')
    ->importer(ProductImporter::class)

ImportAction::make('importBrands')
    ->importer(BrandImporter::class)

```

## #Creating an importer

To create an importer class for a model, you may use themake:filament-importercommand, passing the name of a model:

```php
php artisan make:filament-importer Product

```

This will create a new class in theapp/Filament/Importsdirectory. You now need to define thecolumnsthat can be imported.

### #Automatically generating importer columns

If you’d like to save time, Filament can automatically generate thecolumnsfor you, based on your model’s database columns, using--generate:

```php
php artisan make:filament-importer Product --generate

```

## #Defining importer columns

To define the columns that can be imported, you need to override thegetColumns()method on your importer class, returning an array ofImportColumnobjects:

```php
use Filament\Actions\Imports\ImportColumn;

public static function getColumns(): array
{
    return [
        ImportColumn::make('name')
            ->requiredMapping()
            ->rules(['required', 'max:255']),
        ImportColumn::make('sku')
            ->label('SKU')
            ->requiredMapping()
            ->rules(['required', 'max:32']),
        ImportColumn::make('price')
            ->numeric()
            ->rules(['numeric', 'min:0']),
    ];
}

```

### #Customizing the label of an import column

The label for each column will be generated automatically from its name, but you can override it by calling thelabel()method:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->label('SKU')

```

### #Requiring an importer column to be mapped to a CSV column

You can call therequiredMapping()method to make a column required to be mapped to a column in the CSV. Columns that are required in the database should be required to be mapped:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->requiredMapping()

```

If you require a column in the database, you also need to make sure that it has arules(['required'])validation rule.

If a column is not mapped, it will not be validated since there is no data to validate.

If you allow an import to create records as well asupdate existing ones, but only require a column to be mapped when creating records as it’s a required field, you can use therequiredMappingForNewRecordsOnly()method instead ofrequiredMapping():

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->requiredMappingForNewRecordsOnly()

```

If theresolveRecord()method returns a model instance that is not saved in the database yet, the column will be required to be mapped, just for that row. If the user does not map the column, and one of the rows in the import does not yet exist in the database, just that row will fail and a message will be added to the failed rows CSV after every row has been analyzed.

### #Validating CSV data

You can call therules()method to add validation rules to a column. These rules will check the data in each row from the CSV before it is saved to the database:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->rules(['required', 'max:32'])

```

Any rows that do not pass validation will not be imported. Instead, they will be compiled into a new CSV of “failed rows”, which the user can download after the import has finished. The user will be shown a list of validation errors for each row that failed.

### #Casting state

Beforevalidation, data from the CSV can be cast. This is useful for converting strings into the correct data type, otherwise validation may fail. For example, if you have apricecolumn in your CSV, you may want to cast it to a float:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('price')
    ->castStateUsing(function (string $state): ?float {
        if (blank($state)) {
            return null;
        }
        
        $state = preg_replace('/[^0-9.]/', '', $state);
        $state = floatval($state);
    
        return round($state, precision: 2);
    })

```

In this example, we pass in a function that is used to cast the$state. This function removes any non-numeric characters from the string, casts it to a float, and rounds it to two decimal places.

NOTE

If a column is notrequired by validation, and it is empty, it will not be cast.

Filament also ships with some built-in casting methods:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('price')
    ->numeric() // Casts the state to a float.

ImportColumn::make('price')
    ->numeric(decimalPlaces: 2) // Casts the state to a float, and rounds it to 2 decimal places.

ImportColumn::make('quantity')
    ->integer() // Casts the state to an integer.

ImportColumn::make('is_visible')
    ->boolean() // Casts the state to a boolean.

```

#### #Mutating the state after it has been cast

If you’re using abuilt-in casting methodorarray cast, you can mutate the state after it has been cast by passing a function to thecastStateUsing()method:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('price')
    ->numeric()
    ->castStateUsing(function (float $state): ?float {
        if (blank($state)) {
            return null;
        }
    
        return round($state * 100);
    })

```

You can even access the original state before it was cast, by defining an$originalStateargument in the function:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('price')
    ->numeric()
    ->castStateUsing(function (float $state, mixed $originalState): ?float {
        // ...
    })

```

### #Handling multiple values in a single column

You may use themultiple()method to cast the values in a column to an array. It accepts a delimiter as its first argument, which is used to split the values in the column into an array. For example, if you have adocumentation_urlscolumn in your CSV, you may want to cast it to an array of URLs:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('documentation_urls')
    ->multiple(',')

```

In this example, we pass in a comma as the delimiter, so the values in the column will be split by commas, and cast to an array.

#### #Casting each item in an array

If you want to cast each item in the array to a different data type, you can chain thebuilt-in casting methods:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('customer_ratings')
    ->multiple(',')
    ->integer() // Casts each item in the array to an integer.

```

#### #Validating each item in an array

If you want to validate each item in the array, you can chain thenestedRecursiveRules()method:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('customer_ratings')
    ->multiple(',')
    ->integer()
    ->rules(['array'])
    ->nestedRecursiveRules(['integer', 'min:1', 'max:5'])

```

### #Importing relationships

You may use therelationship()method to import a relationship. At the moment,BelongsToandBelongsToManyrelationships are supported. For example, if you have acategorycolumn in your CSV, you may want to import the categoryBelongsTorelationship:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('author')
    ->relationship()

```

In this example, theauthorcolumn in the CSV will be mapped to theauthor_idcolumn in the database. The CSV should contain the primary keys of authors, usuallyid.

If the column has a value, but the author cannot be found, the import will fail validation. Filament automatically adds validation to all relationship columns, to ensure that the relationship is not empty when it is required.

If you want to import aBelongsToManyrelationship, make sure that the column is set tomultiple(), with the correct separator between values:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('authors')
    ->relationship()
    ->multiple(',')

```

#### #Customizing the relationship import resolution

If you want to find a related record using a different column, you can pass the column name asresolveUsing:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('author')
    ->relationship(resolveUsing: 'email')

```

You can pass in multiple columns toresolveUsing, and they will be used to find the author, in an “or” fashion. For example, if you pass in['email', 'username'], the record can be found by either their email or username:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('author')
    ->relationship(resolveUsing: ['email', 'username'])

```

You can also customize the resolution process, by passing in a function toresolveUsing, which should return a record to associate with the relationship:

```php
use App\Models\Author;
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('author')
    ->relationship(resolveUsing: function (string $state): ?Author {
        return Author::query()
            ->where('email', $state)
            ->orWhere('username', $state)
            ->first();
    })

```

If you are using aBelongsToManyrelationship, the$statewill be an array, and you should return a collection of records that you have resolved:

```php
use App\Models\Author;
use Filament\Actions\Imports\ImportColumn;
use Illuminate\Database\Eloquent\Collection;

ImportColumn::make('authors')
    ->relationship(resolveUsing: function (array $state): Collection {
        return Author::query()
            ->whereIn('email', $state)
            ->orWhereIn('username', $state)
            ->get();
    })

```

You could even use this function to dynamically determine which columns to use to resolve the record:

```php
use App\Models\Author;
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('author')
    ->relationship(resolveUsing: function (string $state): ?Author {
        if (filter_var($state, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
    
        return 'username';
    })

```

### #Marking column data as sensitive

When import rows fail validation, they are logged to the database, ready for export when the import completes. You may want to exclude certain columns from this logging to avoid storing sensitive data in plain text. To achieve this, you can use thesensitive()method on theImportColumnto prevent its data from being logged:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('ssn')
    ->label('Social security number')
    ->sensitive()
    ->rules(['required', 'digits:9'])

```

### #Customizing how a column is filled into a record

If you want to customize how column state is filled into a record, you can pass a function to thefillRecordUsing()method:

```php
use App\Models\Product;
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->fillRecordUsing(function (Product $record, string $state): void {
        $record->sku = strtoupper($state);
    })

```

### #Adding helper text below the import column

Sometimes, you may wish to provide extra information for the user before validation. You can do this by addinghelperText()to a column, which gets displayed below the mapping select:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('skus')
    ->multiple(',')
    ->helperText('A comma-separated list of SKUs.')

```

## #Updating existing records when importing

When generating an importer class, you will see thisresolveRecord()method:

```php
use App\Models\Product;

public function resolveRecord(): ?Product
{
    // return Product::firstOrNew([
    //     // Update existing records, matching them by `$this->data['column_name']`
    //     'email' => $this->data['email'],
    // ]);

    return new Product();
}

```

This method is called for each row in the CSV, and is responsible for returning a model instance that will be filled with the data from the CSV, and saved to the database. By default, it will create a new record for each row. However, you can customize this behavior to update existing records instead. For example, you might want to update a product if it already exists, and create a new one if it doesn’t. To do this, you can uncomment thefirstOrNew()line, and pass the column name that you want to match on. For a product, we might want to match on theskucolumn:

```php
use App\Models\Product;

public function resolveRecord(): ?Product
{
    return Product::firstOrNew([
        'sku' => $this->data['sku'],
    ]);
}

```

### #Updating existing records when importing only

If you want to write an importer that only updates existing records, and does not create new ones, you can returnnullif no record is found:

```php
use App\Models\Product;

public function resolveRecord(): ?Product
{
    return Product::query()
        ->where('sku', $this->data['sku'])
        ->first();
}

```

If you’d like to fail the import row if no record is found, you can throw aRowImportFailedExceptionwith a message:

```php
use App\Models\Product;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;

public function resolveRecord(): ?Product
{
    $product = Product::query()
        ->where('sku', $this->data['sku'])
        ->first();

    if (! $product) {
        throw new RowImportFailedException("No product found with SKU [{$this->data['sku']}].");
    }

    return $product;
}

```

When the import is completed, the user will be able to download a CSV of failed rows, which will contain the error messages.

### #Ignoring blank state for an import column

By default, if a column in the CSV is blank, and mapped by the user, and it’s not required by validation, the column will be imported asnullin the database. If you’d like to ignore blank state, and use the existing value in the database instead, you can call theignoreBlankState()method:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('price')
    ->ignoreBlankState()

```

## #Using import options

The import action can render extra form components that the user can interact with when importing a CSV. This can be useful to allow the user to customize the behavior of the importer. For instance, you might want a user to be able to choose whether to update existing records when importing, or only create new ones. To do this, you can return options form components from thegetOptionsFormComponents()method on your importer class:

```php
use Filament\Forms\Components\Checkbox;

public static function getOptionsFormComponents(): array
{
    return [
        Checkbox::make('updateExisting')
            ->label('Update existing records'),
    ];
}

```

Alternatively, you can pass a set of static options to the importer through theoptions()method on the action:

```php
use App\Filament\Imports\ProductImporter;
use Filament\Actions\ImportAction;

ImportAction::make()
    ->importer(ProductImporter::class)
    ->options([
        'updateExisting' => true,
    ])

```

Now, you can access the data from these options inside the importer class, by calling$this->options. For example, you might want to use it insideresolveRecord()toupdate an existing product:

```php
use App\Models\Product;

public function resolveRecord(): ?Product
{
    if ($this->options['updateExisting'] ?? false) {
        return Product::firstOrNew([
            'sku' => $this->data['sku'],
        ]);
    }

    return new Product();
}

```

## #Improving import column mapping guesses

By default, Filament will attempt to “guess” which columns in the CSV match which columns in the database, to save the user time. It does this by attempting to find different combinations of the column name, with spaces,-,_, all cases insensitively. However, if you’d like to improve the guesses, you can call theguess()method with more examples of the column name that could be present in the CSV:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->guess(['id', 'number', 'stock-keeping unit'])

```

## #Providing example CSV data

Before the user uploads a CSV, they have an option to download an example CSV file, containing all the available columns that can be imported. This is useful, as it allows the user to import this file directly into their spreadsheet software, and fill it out.

You can also add an example row to the CSV, to show the user what the data should look like. To fill in this example row, you can pass in an example column value to theexample()method:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->example('ABC123')

```

Or if you want to add more than one example row, you can pass an array to theexamples()method:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->examples(['ABC123', 'DEF456'])

```

By default, the name of the column is used in the header of the example CSV. You can customize the header per-column usingexampleHeader():

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('sku')
    ->exampleHeader('SKU')

```

## #Using a custom user model

By default, theimportstable has auser_idcolumn. That column is constrained to theuserstable:

```php
$table->foreignId('user_id')->constrained()->cascadeOnDelete();

```

In theImportmodel, theuser()relationship is defined as aBelongsTorelationship to theApp\Models\Usermodel. If theApp\Models\Usermodel does not exist, or you want to use a different one, you can bind a newAuthenticatablemodel to the container in a service provider’sregister()method:

```php
use App\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

$this->app->bind(Authenticatable::class, Admin::class);

```

If your authenticatable model uses a different table tousers, you should pass that table name toconstrained():

```php
$table->foreignId('user_id')->constrained('admins')->cascadeOnDelete();

```

### #Using a polymorphic user relationship

If you want to associate imports with multiple user models, you can use a polymorphicMorphTorelationship instead. To do this, you need to replace theuser_idcolumn in theimportstable:

```php
$table->morphs('user');

```

Then, in a service provider’sboot()method, you should callImport::polymorphicUserRelationship()to swap theuser()relationship on theImportmodel to aMorphTorelationship:

```php
use Filament\Actions\Imports\Models\Import;

Import::polymorphicUserRelationship();

```

## #Limiting the maximum number of rows that can be imported

To prevent server overload, you may wish to limit the maximum number of rows that can be imported from one CSV file. You can do this by calling themaxRows()method on the action:

```php
use App\Filament\Imports\ProductImporter;
use Filament\Actions\ImportAction;

ImportAction::make()
    ->importer(ProductImporter::class)
    ->maxRows(100000)

```

## #Changing the import chunk size

Filament will chunk the CSV, and process each chunk in a different queued job. By default, chunks are 100 rows at a time. You can change this by calling thechunkSize()method on the action:

```php
use App\Filament\Imports\ProductImporter;
use Filament\Actions\ImportAction;

ImportAction::make()
    ->importer(ProductImporter::class)
    ->chunkSize(250)

```

TIP

If you are encountering memory or timeout issues when importing large CSV files, you may wish to reduce the chunk size.

## #Changing the CSV delimiter

The default delimiter for CSVs is the comma (,). If your import uses a different delimiter, you may call thecsvDelimiter()method on the action, passing a new one:

```php
use App\Filament\Imports\ProductImporter;
use Filament\Actions\ImportAction;

ImportAction::make()
    ->importer(ProductImporter::class)
    ->csvDelimiter(';')

```

You can only specify a single character, otherwise an exception will be thrown.

## #Changing the column header offset

If your column headers are not on the first row of the CSV, you can call theheaderOffset()method on the action, passing the number of rows to skip:

```php
use App\Filament\Imports\ProductImporter;
use Filament\Actions\ImportAction;

ImportAction::make()
    ->importer(ProductImporter::class)
    ->headerOffset(5)

```

## #Customizing the import job

The default job for processing imports isFilament\Actions\Imports\Jobs\ImportCsv. If you want to extend this class and override any of its methods, you may replace the original class in theregister()method of a service provider:

```php
use App\Jobs\ImportCsv;
use Filament\Actions\Imports\Jobs\ImportCsv as BaseImportCsv;

$this->app->bind(BaseImportCsv::class, ImportCsv::class);

```

Or, you can pass the new job class to thejob()method on the action, to customize the job for a specific import:

```php
use App\Filament\Imports\ProductImporter;
use App\Jobs\ImportCsv;
use Filament\Actions\ImportAction;

ImportAction::make()
    ->importer(ProductImporter::class)
    ->job(ImportCsv::class)

```

### #Customizing the import queue and connection

By default, the import system will use the default queue and connection. If you’d like to customize the queue used for jobs of a certain importer, you may override thegetJobQueue()method in your importer class:

```php
public function getJobQueue(): ?string
{
    return 'imports';
}

```

You can also customize the connection used for jobs of a certain importer, by overriding thegetJobConnection()method in your importer class:

```php
public function getJobConnection(): ?string
{
    return 'sqs';
}

```

### #Customizing the import job middleware

By default, the import system will only process one job at a time from each import. This is to prevent the server from being overloaded, and other jobs from being delayed by large imports. That functionality is defined in theWithoutOverlappingmiddleware on the importer class:

```php
use Illuminate\Queue\Middleware\WithoutOverlapping;

public function getJobMiddleware(): array
{
    return [
        (new WithoutOverlapping("import{$this->import->getKey()}"))->expireAfter(600),
    ];
}

```

If you’d like to customize the middleware that is applied to jobs of a certain importer, you may override this method in your importer class. You can read more about job middleware in theLaravel docs.

### #Customizing the import job retries

By default, the import system will retry a job for 24 hours, or until it fails 5 times with unhandled exceptions, whichever happens first. This is to allow for temporary issues, such as the database being unavailable, to be resolved. You may change the time period for the job to retry, which is defined in thegetJobRetryUntil()method on the exporter class:

```php
use Carbon\CarbonInterface;

public function getJobRetryUntil(): ?CarbonInterface
{
    return now()->addHours(12);
}

```

You can read more about job retries in theLaravel docs.

#### #Customizing the import job backoff strategy

By default, the import system will wait 1 minute, then 2 minutes, then 5 minutes, then 10 minutes thereafter, before retrying a job. This is to prevent the server from being overloaded by a job that is failing repeatedly. That functionality is defined in thegetJobBackoff()method on the importer class:

```php
/**
* @return int | array<int> | null
 */
public function getJobBackoff(): int | array | null
{
    return [60, 120, 300, 600];
}

```

You can read more about job backoff, including how to configure exponential backoffs, in theLaravel docs.

### #Customizing the import job tags

By default, the import system will tag each job with the ID of the import. This is to allow you to easily find all jobs related to a certain import. That functionality is defined in thegetJobTags()method on the importer class:

```php
public function getJobTags(): array
{
    return ["import{$this->import->getKey()}"];
}

```

If you’d like to customize the tags that are applied to jobs of a certain importer, you may override this method in your importer class.

### #Customizing the import job batch name

By default, the import system doesn’t define any name for the job batches. If you’d like to customize the name that is applied to job batches of a certain importer, you may override thegetJobBatchName()method in your importer class:

```php
public function getJobBatchName(): ?string
{
    return 'product-import';
}

```

## #Customizing import validation messages

The import system will automatically validate the CSV file before it is imported. If there are any errors, the user will be shown a list of them, and the import will not be processed. If you’d like to override any default validation messages, you may do so by overriding thegetValidationMessages()method on your importer class:

```php
public function getValidationMessages(): array
{
    return [
        'name.required' => 'The name column must not be empty.',
    ];
}

```

To learn more about customizing validation messages, read theLaravel docs.

### #Customizing import validation attributes

When columns fail validation, their label is used in the error message. To customize the label used in field error messages, use thevalidationAttribute()method:

```php
use Filament\Actions\Imports\ImportColumn;

ImportColumn::make('name')
    ->validationAttribute('full name')

```

## #Customizing import file validation

You can add newLaravel validation rulesfor the import file using thefileRules()method:

```php
use Filament\Actions\ImportAction;
use Illuminate\Validation\Rules\File;

ImportAction::make()
    ->importer(ProductImporter::class)
    ->fileRules([
        'max:1024',
        // or
        File::types(['csv', 'txt'])->max(1024),
    ]),

```

## #Lifecycle hooks

Hooks may be used to execute code at various points within an importer’s lifecycle, like before a record is saved. To set up a hook, create a protected method on the importer class with the name of the hook:

```php
protected function beforeSave(): void
{
    // ...
}

```

In this example, the code in thebeforeSave()method will be called before the validated data from the CSV is saved to the database.

There are several available hooks for importers:

```php
use Filament\Actions\Imports\Importer;

class ProductImporter extends Importer
{
    // ...

    protected function beforeValidate(): void
    {
        // Runs before the CSV data for a row is validated.
    }

    protected function afterValidate(): void
    {
        // Runs after the CSV data for a row is validated.
    }

    protected function beforeFill(): void
    {
        // Runs before the validated CSV data for a row is filled into a model instance.
    }

    protected function afterFill(): void
    {
        // Runs after the validated CSV data for a row is filled into a model instance.
    }

    protected function beforeSave(): void
    {
        // Runs before a record is saved to the database.
    }

    protected function beforeCreate(): void
    {
        // Similar to `beforeSave()`, but only runs when creating a new record.
    }

    protected function beforeUpdate(): void
    {
        // Similar to `beforeSave()`, but only runs when updating an existing record.
    }

    protected function afterSave(): void
    {
        // Runs after a record is saved to the database.
    }
    
    protected function afterCreate(): void
    {
        // Similar to `afterSave()`, but only runs when creating a new record.
    }
    
    protected function afterUpdate(): void
    {
        // Similar to `afterSave()`, but only runs when updating an existing record.
    }
}

```

Inside these hooks, you can access the current row’s data using$this->data. You can also access the original row of data from the CSV, before it wascastor mapped, using$this->originalData.

The current record (if it exists yet) is accessible in$this->record, and theimport form optionsusing$this->options.

## #Authorization

By default, only the user who started the import may access the failure CSV file that gets generated if part of an import fails. If you’d like to customize the authorization logic, you may create anImportPolicyclass, andregister it in yourAuthServiceProvider:

```php
use App\Policies\ImportPolicy;
use Filament\Actions\Imports\Models\Import;

protected $policies = [
    Import::class => ImportPolicy::class,
];

```

Theview()method of the policy will be used to authorize access to the failure CSV file.

Please note that if you define a policy, the existing logic of ensuring only the user who started the import can access the failure CSV file will be removed. You will need to add that logic to your policy if you want to keep it:

```php
use App\Models\User;
use Filament\Actions\Imports\Models\Import;

public function view(User $user, Import $import): bool
{
    return $import->user()->is($user);
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, tables, forms  
**Keywords:** buttons, modals, interactions, crud operations

*Extracted from Filament v5 Documentation - 2026-01-28*
