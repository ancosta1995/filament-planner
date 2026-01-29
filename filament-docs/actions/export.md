# Export action

**URL:** https://filamentphp.com/docs/5.x/actions/export  
**Section:** actions  
**Page:** export  
**Priority:** high  
**AI Context:** Interactive buttons with modals for user actions.

---

## #Introduction

Filament includes an action that is able to export rows to a CSV or XLSX file. When the trigger button is clicked, a modal asks for the columns that they want to export, and what they should be labeled. This feature usesjob batchesanddatabase notifications, so you need to publish those migrations from Laravel. Also, you need to publish the migrations for tables that Filament uses to store information about exports:

```php
php artisan make:queue-batches-table
php artisan make:notifications-table
php artisan vendor:publish --tag=filament-actions-migrations
php artisan migrate

```

If you’d like to receive export notifications in a panel, you can enable them in thepanel configuration.

NOTE

If you’re using PostgreSQL, make sure that thedatacolumn in the notifications migration is usingjson():$table->json('data').

NOTE

If you’re using UUIDs for yourUsermodel, make sure that yournotifiablecolumn in the notifications migration is usinguuidMorphs():$table->uuidMorphs('notifiable').

You may use theExportActionlike so:

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;

ExportAction::make()
    ->exporter(ProductExporter::class)

```

If you want to add this action to the header of a table, you may do so like this:

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->headerActions([
            ExportAction::make()
                ->exporter(ProductExporter::class),
        ]);
}

```

Or if you want to add it as a table bulk action, so that the user can choose which rows to export, they can useFilament\Actions\ExportBulkAction:

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->toolbarActions([
            ExportBulkAction::make()
                ->exporter(ProductExporter::class),
        ]);
}

```

The“exporter” class needs to be createdto tell Filament how to export each row.

## #Creating an exporter

To create an exporter class for a model, you may use themake:filament-exportercommand, passing the name of a model:

```php
php artisan make:filament-exporter Product

```

This will create a new class in theapp/Filament/Exportsdirectory. You now need to define thecolumnsthat can be exported.

### #Automatically generating exporter columns

If you’d like to save time, Filament can automatically generate thecolumnsfor you, based on your model’s database columns, using--generate:

```php
php artisan make:filament-exporter Product --generate

```

## #Defining exporter columns

To define the columns that can be exported, you need to override thegetColumns()method on your exporter class, returning an array ofExportColumnobjects:

```php
use Filament\Actions\Exports\ExportColumn;

public static function getColumns(): array
{
    return [
        ExportColumn::make('name'),
        ExportColumn::make('sku')
            ->label('SKU'),
        ExportColumn::make('price'),
    ];
}

```

### #Customizing the label of an export column

The label for each column will be generated automatically from its name, but you can override it by calling thelabel()method:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('sku')
    ->label('SKU')

```

### #Configuring the default column selection

By default, all columns will be selected when the user is asked which columns they would like to export. You can customize the default selection state for a column with theenabledByDefault()method:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('description')
    ->enabledByDefault(false)

```

You can use theenableVisibleTableColumnsByDefault()method on theExportActionto enable only the columns that are currently visible in the table by default. Columns that useenabledByDefault(false)will also be disabled by default:

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->enableVisibleTableColumnsByDefault()

```

### #Configuring the column selection form layout

By default, the column selection form uses a single column layout. You can change this using thecolumnMappingColumns()method, passing the number of columns you would like to use for the layout on large screens:

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->columnMappingColumns(3)

```

This will display the column selection checkboxes and label inputs in a 3-column layout, making better use of available space when you have many exportable columns. Note that while there will be three columns in the layout on large screens, the layout will still be responsive and there will be fewer columns displayed on smaller screens.

### #Disabling column selection

By default, user will be asked which columns they would like to export. You can disable this functionality usingcolumnMapping(false):

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->columnMapping(false)

```

### #Calculated export column state

Sometimes you need to calculate the state of a column, instead of directly reading it from a database column.

By passing a callback function to thestate()method, you can customize the returned state for that column based on the$record:

```php
use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('amount_including_vat')
    ->state(function (Order $record): float {
        return $record->amount * (1 + $record->vat_rate);
    })

```

### #Formatting the value of an export column

You may instead pass a custom formatting callback toformatStateUsing(), which accepts the$stateof the cell, and optionally the Eloquent$record:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('status')
    ->formatStateUsing(fn (string $state): string => __("statuses.{$state}"))

```

If there aremultiple valuesin the column, the function will be called for each value.

#### #Limiting text length

You maylimit()the length of the cell’s value:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('description')
    ->limit(50)

```

#### #Limiting word count

You may limit the number ofwords()displayed in the cell:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('description')
    ->words(10)

```

#### #Adding a prefix or suffix

You may add aprefix()orsuffix()to the cell’s value:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('domain')
    ->prefix('https://')
    ->suffix('.com')

```

### #Exporting multiple values in a cell

By default, if there are multiple values in the column, they will be comma-separated. You may use thelistAsJson()method to list them as a JSON array instead:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('tags')
    ->listAsJson()

```

### #Displaying data from relationships

You may use “dot notation” to access columns within relationships. The name of the relationship comes first, followed by a period, followed by the name of the column to display:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('author.name')

```

### #Counting relationships

If you wish to count the number of related records in a column, you may use thecounts()method:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('users_count')
    ->counts('users')

```

In this example,usersis the name of the relationship to count from. The name of the column must beusers_count, as this is the convention thatLaravel usesfor storing the result.

If you’d like to scope the relationship before counting, you can pass an array to the method, where the key is the relationship name and the value is the function to scope the Eloquent query with:

```php
use Filament\Actions\Exports\ExportColumn;
use Illuminate\Database\Eloquent\Builder;

ExportColumn::make('users_count')
    ->counts([
        'users' => fn (Builder $query) => $query->where('is_active', true),
    ])

```

### #Determining relationship existence

If you simply wish to indicate whether related records exist in a column, you may use theexists()method:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('users_exists')
    ->exists('users')

```

In this example,usersis the name of the relationship to check for existence. The name of the column must beusers_exists, as this is the convention thatLaravel usesfor storing the result.

If you’d like to scope the relationship before checking existence, you can pass an array to the method, where the key is the relationship name and the value is the function to scope the Eloquent query with:

```php
use Filament\Actions\Exports\ExportColumn;
use Illuminate\Database\Eloquent\Builder;

ExportColumn::make('users_exists')
    ->exists([
        'users' => fn (Builder $query) => $query->where('is_active', true),
    ])

```

### #Aggregating relationships

Filament provides several methods for aggregating a relationship field, includingavg(),max(),min()andsum(). For instance, if you wish to show the average of a field on all related records in a column, you may use theavg()method:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('users_avg_age')
    ->avg('users', 'age')

```

In this example,usersis the name of the relationship, whileageis the field that is being averaged. The name of the column must beusers_avg_age, as this is the convention thatLaravel usesfor storing the result.

If you’d like to scope the relationship before aggregating, you can pass an array to the method, where the key is the relationship name and the value is the function to scope the Eloquent query with:

```php
use Filament\Actions\Exports\ExportColumn;
use Illuminate\Database\Eloquent\Builder;

ExportColumn::make('users_avg_age')
    ->avg([
        'users' => fn (Builder $query) => $query->where('is_active', true),
    ], 'age')

```

## #Configuring the export formats

By default, the export action will generate both CSV and XLSX formats and allow user to choose between them in the notification. You can use theExportFormatenum to customize this, by passing an array of formats to theformats()method on the action:

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->formats([
        ExportFormat::Csv,
    ])
    // or
    ->formats([
        ExportFormat::Xlsx,
    ])
    // or
    ->formats([
        ExportFormat::Xlsx,
        ExportFormat::Csv,
    ])

```

Alternatively, you can override thegetFormats()method on the exporter class, which will set the default formats for all actions that use that exporter:

```php
use Filament\Actions\Exports\Enums\ExportFormat;

public function getFormats(): array
{
    return [
        ExportFormat::Csv,
    ];
}

```

## #Modifying the export query

By default, if you are using theExportActionwith a table, the action will use the table’s currently filtered and sorted query to export the data. If you don’t have a table, it will use the model’s default query. To modify the query builder before exporting, you can use themodifyQueryUsing()method on the action:

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true))

```

You may inject the$optionsargument into the function, which is an array ofoptionsfor that export:

```php
use App\Filament\Exports\ProductExporter;
use Illuminate\Database\Eloquent\Builder;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->modifyQueryUsing(fn (Builder $query, array $options) => $query->where('is_active', $options['isActive'] ?? true))

```

Alternatively, you can override themodifyQuery()method on the exporter class, which will modify the query for all actions that use that exporter:

```php
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

public static function modifyQuery(Builder $query): Builder
{
    return $query->with([
        'purchasable' => fn (MorphTo $morphTo) => $morphTo->morphWith([
            ProductPurchase::class => ['product'],
            ServicePurchase::class => ['service'],
            Subscription::class => ['plan'],
        ]),
    ]);
}

```

## #Configuring the export filesystem

### #Customizing the storage disk

By default, exported files will be uploaded to the storage disk defined in theconfiguration file, which ispublicby default. You can set theFILESYSTEM_DISKenvironment variable to change this.

While using thepublicdisk a good default for many parts of Filament, using it for exports would result in exported files being stored in a public location. As such, if the default filesystem disk ispublicand alocaldisk exists in yourconfig/filesystems.php, Filament will use thelocaldisk for exports instead. If you override the disk to bepublicfor anExportActionor inside an exporter class, Filament will use that.

In production, you should use a disk such ass3with a private access policy, to prevent unauthorized access to the exported files.

If you want to use a different disk for a specific export, you can pass the disk name to thedisk()method on the action:

```php
use Filament\Actions\ExportAction;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->fileDisk('s3')

```

You may set the disk for all export actions at once in theboot()method of a service provider such asAppServiceProvider:

```php
use Filament\Actions\ExportAction;

ExportAction::configureUsing(fn (ExportAction $action) => $action->fileDisk('s3'));

```

Alternatively, you can override thegetFileDisk()method on the exporter class, returning the name of the disk:

```php
public function getFileDisk(): string
{
    return 's3';
}

```

Export files that are created are the developer’s responsibility to delete if they wish. Filament does not delete these files in case the exports need to be downloaded again at a later date.

### #Configuring the export file names

By default, exported files are given a name generated based on the export’s ID and type. You can customize the file name by using thefileName()method on the action:

```php
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Models\Export;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->fileName(fn (Export $export): string => "products-{$export->getKey()}")

```

Alternatively, you can override thegetFileName()method on the exporter class and return a custom string:

```php
use Filament\Actions\Exports\Models\Export;

public function getFileName(Export $export): string
{
    return "products-{$export->getKey()}";
}

```

## #Using export options

The export action can render extra form components that the user can interact with when exporting a CSV. This can be useful to allow the user to customize the behavior of the exporter. For instance, you might want a user to be able to choose the format of specific columns when exporting. To do this, you can return options form components from thegetOptionsFormComponents()method on your exporter class:

```php
use Filament\Forms\Components\TextInput;

public static function getOptionsFormComponents(): array
{
    return [
        TextInput::make('descriptionLimit')
            ->label('Limit the length of the description column content')
            ->integer(),
    ];
}

```

Alternatively, you can pass a set of static options to the exporter through theoptions()method on the action:

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->options([
        'descriptionLimit' => 250,
    ])

```

Now, you can access the data from these options inside the exporter class, by injecting the$optionsargument into any closure function. For example, you might want to use it insideformatStateUsing()toformat a column’s value:

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('description')
    ->formatStateUsing(function (string $state, array $options): string {
        return (string) str($state)->limit($options['descriptionLimit'] ?? 100);
    })

```

Alternatively, since the$optionsargument is passed to all closure functions, you can access it insidelimit():

```php
use Filament\Actions\Exports\ExportColumn;

ExportColumn::make('description')
    ->limit(fn (array $options): int => $options['descriptionLimit'] ?? 100)

```

## #Using a custom user model

By default, theexportstable has auser_idcolumn. That column is constrained to theuserstable:

```php
$table->foreignId('user_id')->constrained()->cascadeOnDelete();

```

In theExportmodel, theuser()relationship is defined as aBelongsTorelationship to theApp\Models\Usermodel. If theApp\Models\Usermodel does not exist, or you want to use a different one, you can bind a newAuthenticatablemodel to the container in a service provider’sregister()method:

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

If you want to associate exports with multiple user models, you can use a polymorphicMorphTorelationship instead. To do this, you need to replace theuser_idcolumn in theexportstable:

```php
$table->morphs('user');

```

Then, in a service provider’sboot()method, you should callExport::polymorphicUserRelationship()to swap theuser()relationship on theExportmodel to aMorphTorelationship:

```php
use Filament\Actions\Exports\Models\Export;

Export::polymorphicUserRelationship();

```

## #Limiting the maximum number of rows that can be exported

To prevent server overload, you may wish to limit the maximum number of rows that can be exported from one CSV file. You can do this by calling themaxRows()method on the action:

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->maxRows(100000)

```

## #Changing the export chunk size

Filament will chunk the CSV, and process each chunk in a different queued job. By default, chunks are 100 rows at a time. You can change this by calling thechunkSize()method on the action:

```php
use App\Filament\Exports\ProductExporter;
use Filament\Actions\ExportAction;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->chunkSize(250)

```

TIP

If you are encountering memory or timeout issues when importing large CSV files, you may wish to reduce the chunk size.

## #Changing the CSV delimiter

The default delimiter for CSVs is the comma (,). If you want to export using a different delimiter, you may override thegetCsvDelimiter()method on the exporter class, returning a new one:

```php
public static function getCsvDelimiter(): string
{
    return ';';
}

```

You can only specify a single character, otherwise an exception will be thrown.

## #Customizing XLSX files

### #Styling XLSX rows

If you want to style the cells of the XLSX file, you may override thegetXlsxCellStyle()method on the exporter class, returning anOpenSpoutStyleobject:

```php
use OpenSpout\Common\Entity\Style\Style;

public function getXlsxCellStyle(): ?Style
{
    return (new Style())
        ->setFontSize(12)
        ->setFontName('Consolas');
}

```

If you want to use a different style for the header cells of the XLSX file only, you may override thegetXlsxHeaderCellStyle()method on the exporter class, returning anOpenSpoutStyleobject:

```php
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;

public function getXlsxHeaderCellStyle(): ?Style
{
    return (new Style())
        ->setFontBold()
        ->setFontItalic()
        ->setFontSize(14)
        ->setFontName('Consolas')
        ->setFontColor(Color::rgb(255, 255, 77))
        ->setBackgroundColor(Color::rgb(0, 0, 0))
        ->setCellAlignment(CellAlignment::CENTER)
        ->setCellVerticalAlignment(CellVerticalAlignment::CENTER);
}

```

### #Styling XLSX columns

ThemakeXlsxRow()andmakeXlsxHeaderRow()methods on the exporter class allow you to customize the styling of individual cells within a row. By default, the methods are implemented like this:

```php
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;

/**
 * @param array<mixed> $values
 */
public function makeXlsxRow(array $values, ?Style $style = null): Row
{
    return Row::fromValues($values, $style);
}

```

When a user exports, they can choose which columns to export. As such, the$this->columnMapproperty may be used to determine which columns are being exported and in which order. You can replaceRow::fromValues()with an array ofCellobjects, which allow you to style them individually usingOpenSpoutStyleobjects. AStyleMergercan be used to merge the default style with the custom style for a cell, allowing you to apply additional styles on top of the default ones:

```php
use OpenSpout\Common\Entity\Cell;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\Common\Manager\Style\StyleMerger;

/**
 * @param array<mixed> $values
 */
public function makeXlsxRow(array $values, ?Style $style = null): Row
{
    $styleMerger = new StyleMerger();

    $cells = [];
    
    foreach (array_keys($this->columnMap) as $columnIndex => $column) {
        $cells[] = match ($column) {
            'name' => Cell::fromValue(
                $values[$columnIndex],
                $styleMerger->merge(
                    (new Style())->setFontUnderline(),
                    $style,
                ),
            ),
            'price' => Cell::fromValue(
                $values[$columnIndex],
                (new Style())->setFontSize(12),
            ),
            default => Cell::fromValue($values[$columnIndex]),
        },
    }
    
    return new Row($cells, $style);
}

```

### #Customizing the XLSX writer

If you want to pass “options” to theOpenSpout XLSXWriter, you can return anOpenSpout\Writer\XLSX\Optionsinstance from thegetXlsxWriterOptions()method of the exporter class:

```php
use OpenSpout\Writer\XLSX\Options;

public function getXlsxWriterOptions(): ?Options
{
    $options = new Options();
    $options->setColumnWidth(10, 1);
    $options->setColumnWidthForRange(12, 2, 3);
    
    return $options;
}

```

If you want to customize the XLSX writer before it is closed, you can override theconfigureXlsxWriterBeforeClosing()method on the exporter class. This method receives theWriterinstance as a parameter, and you can modify it before it is closed:

```php
use OpenSpout\Writer\XLSX\Entity\SheetView;
use OpenSpout\Writer\XLSX\Writer;

public function configureXlsxWriterBeforeClose(Writer $writer): Writer
{
    $sheetView = new SheetView();
    $sheetView->setFreezeRow(2);
    $sheetView->setFreezeColumn('B');
    
    $sheet = $writer->getCurrentSheet();
    $sheet->setSheetView($sheetView);
    $sheet->setName('export');
    
    return $writer;
}

```

## #Customizing the export job

The default job for processing exports isFilament\Actions\Exports\Jobs\PrepareCsvExport. If you want to extend this class and override any of its methods, you may replace the original class in theregister()method of a service provider:

```php
use App\Jobs\PrepareCsvExport;
use Filament\Actions\Exports\Jobs\PrepareCsvExport as BasePrepareCsvExport;

$this->app->bind(BasePrepareCsvExport::class, PrepareCsvExport::class);

```

Or, you can pass the new job class to thejob()method on the action, to customize the job for a specific export:

```php
use App\Filament\Exports\ProductExporter;
use App\Jobs\PrepareCsvExport;
use Filament\Actions\ExportAction;

ExportAction::make()
    ->exporter(ProductExporter::class)
    ->job(PrepareCsvExport::class)

```

### #Customizing the export queue and connection

By default, the export system will use the default queue and connection. If you’d like to customize the queue used for jobs of a certain exporter, you may override thegetJobQueue()method in your exporter class:

```php
public function getJobQueue(): ?string
{
    return 'exports';
}

```

You can also customize the connection used for jobs of a certain exporter, by overriding thegetJobConnection()method in your exporter class:

```php
public function getJobConnection(): ?string
{
    return 'sqs';
}

```

### #Customizing the export job middleware

By default, the export system will only process one job at a time from each export. This is to prevent the server from being overloaded, and other jobs from being delayed by large exports. That functionality is defined in theWithoutOverlappingmiddleware on the exporter class:

```php
public function getJobMiddleware(): array
{
    return [
        (new WithoutOverlapping("export{$this->export->getKey()}"))->expireAfter(600),
    ];
}

```

If you’d like to customize the middleware that is applied to jobs of a certain exporter, you may override this method in your exporter class. You can read more about job middleware in theLaravel docs.

### #Customizing the export job retries

By default, the export system will retry a job for 24 hours, or until it fails with 5 unhandled exceptions, whichever happens first. This is to allow for temporary issues, such as the database being unavailable, to be resolved. You may change the time period for the job to retry, which is defined in thegetJobRetryUntil()method on the exporter class:

```php
use Carbon\CarbonInterface;

public function getJobRetryUntil(): ?CarbonInterface
{
    return now()->addHours(12);
}

```

You can read more about job retries in theLaravel docs.

#### #Customizing the export job backoff strategy

By default, the export system will wait 1 minute, then 2 minutes, then 5 minutes, then 10 minutes thereafter before retrying a job. This is to prevent the server from being overloaded by a job that is failing repeatedly. That functionality is defined in thegetJobBackoff()method on the exporter class:

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

### #Customizing the export job tags

By default, the export system will tag each job with the ID of the export. This is to allow you to easily find all jobs related to a certain export. That functionality is defined in thegetJobTags()method on the exporter class:

```php
public function getJobTags(): array
{
    return ["export{$this->export->getKey()}"];
}

```

If you’d like to customize the tags that are applied to jobs of a certain exporter, you may override this method in your exporter class.

### #Customizing the export job batch name

By default, the export system doesn’t define any name for the job batches. If you’d like to customize the name that is applied to job batches of a certain exporter, you may override thegetJobBatchName()method in your exporter class:

```php
public function getJobBatchName(): ?string
{
    return 'product-export';
}

```

## #Authorization

By default, only the user who started the export may download files that get generated. If you’d like to customize the authorization logic, you may create anExportPolicyclass, andregister it in yourAuthServiceProvider:

```php
use App\Policies\ExportPolicy;
use Filament\Actions\Exports\Models\Export;

protected $policies = [
    Export::class => ExportPolicy::class,
];

```

Theview()method of the policy will be used to authorize access to the downloads.

Please note that if you define a policy, the existing logic of ensuring only the user who started the export can access it will be removed. You will need to add that logic to your policy if you want to keep it:

```php
use App\Models\User;
use Filament\Actions\Exports\Models\Export;

public function view(User $user, Export $export): bool
{
    return $export->user()->is($user);
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, tables, forms  
**Keywords:** buttons, modals, interactions, crud operations

*Extracted from Filament v5 Documentation - 2026-01-28*
