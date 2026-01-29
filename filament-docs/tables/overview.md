# Overview

**URL:** https://filamentphp.com/docs/5.x/tables/overview  
**Section:** tables  
**Page:** overview  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

Tables are a common UI pattern for displaying lists of records in web applications. Filament provides a PHP-based API for defining tables with many features, while also being incredibly customizable.

### #Defining table columns

The basis of any table is rows and columns. Filament uses Eloquent to get the data for rows in the table, and you are responsible for defining the columns that are used in that row.

Filament includes many column types prebuilt for you, and you canview a full list here. You can evencreate your own custom column typesto display data in whatever way you need.

Columns are stored in an array, as objects within the$table->columns()method:

```php
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('title'),
            TextColumn::make('slug'),
            IconColumn::make('is_featured')
                ->boolean(),
        ]);
}

```

In this example, there are 3 columns in the table. The first two displaytext- the title and slug of each row in the table. The third column displays anicon, either a green check or a red cross depending on if the row is featured or not.

#### #Making columns sortable and searchable

You can easily modify columns by chaining methods onto them. For example, you can make a columnsearchableusing thesearchable()method. Now, there will be a search field in the table, and you will be able to filter rows by the value of that column:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('title')
    ->searchable()

```

You can make multiple columns searchable, and Filament will be able to search for matches within any of them, all at once.

You can also make a columnsortableusing thesortable()method. This will add a sort button to the column header, and clicking it will sort the table by that column:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('title')
    ->sortable()

```

#### #Accessing related data from columns

You can also display data in a column that belongs to a relationship. For example, if you have aPostmodel that belongs to aUsermodel (the author of the post), you can display the user’s name in the table:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('author.name')

```

In this case, Filament will search for anauthorrelationship on thePostmodel, and then display thenameattribute of that relationship. We call this “dot notation”, and you can use it to display any attribute of any relationship, even nested relationships. Filament uses this dot notation to eager-load the results of that relationship for you.

For more information about column relationships, visit theRelationships section.

#### #Adding new columns alongside existing columns

While thecolumns()method redefines all columns for a table, you may sometimes want to add columns to an existing configuration without overriding it completely. This is particularly useful when you have global column configurations that should appear across multiple tables.

Filament provides thepushColumns()method for this purpose. Unlikecolumns(), which replaces the entire column configuration,pushColumns()appends new columns to any existing ones.

This is especially powerful when combined withglobal table settingsin theboot()method of a service provider, such asAppServiceProvider:

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

Table::configureUsing(function (Table $table) {
    $table
        ->pushColumns([
            TextColumn::make('created_at')
                ->label('Created')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->label('Updated')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ]);
});

```

### #Defining table filters

As well as making columnssearchable(), which allows the user to filter the table by searching the content of columns, you can also allow the users to filter rows in the table in other ways.Filterscan be defined in the$table->filters()method:

```php
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->filters([
            Filter::make('is_featured')
                ->query(fn (Builder $query) => $query->where('is_featured', true)),
            SelectFilter::make('status')
                ->options([
                    'draft' => 'Draft',
                    'reviewing' => 'Reviewing',
                    'published' => 'Published',
                ]),
        ]);
}

```

In this example, we have defined 2 table filters. On the table, there is now a “filter” icon button in the top corner. Clicking it will open a dropdown with the 2 filters we have defined.

The first filter is rendered as a checkbox. When it’s checked, only featured rows in the table will be displayed. When it’s unchecked, all rows will be displayed.

The second filter is rendered as a select dropdown. When a user selects an option, only rows with that status will be displayed. When no option is selected, all rows will be displayed.

You can use anyschema componentto build the UI for a filter. For example, you could createa custom date range filter.

### #Defining table actions

Filament’s tables can useactions. They are buttons that can be added to theend of any table row, or even in theheaderof a table. For instance, you may want an action to “create” a new record in the header, and then “edit” and “delete” actions on each row.Bulk actionscan be used to execute code when records in the table are selected.

```php
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->recordActions([
            Action::make('feature')
                ->action(function (Post $record) {
                    $record->is_featured = true;
                    $record->save();
                })
                ->hidden(fn (Post $record): bool => $record->is_featured),
            Action::make('unfeature')
                ->action(function (Post $record) {
                    $record->is_featured = false;
                    $record->save();
                })
                ->visible(fn (Post $record): bool => $record->is_featured),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
}

```

In this example, we define 2 actions for table rows. The first action is a “feature” action. When clicked, it will set theis_featuredattribute on the record totrue- which is written within theaction()method. Using thehidden()method, the action will be hidden if the record is already featured. The second action is an “unfeature” action. When clicked, it will set theis_featuredattribute on the record tofalse. Using thevisible()method, the action will be hidden if the record is not featured.

We also define a bulk action. When bulk actions are defined, each row in the table will have a checkbox. This bulk action isbuilt-in to Filament, and it will delete all selected records. However, you canwrite your own custom bulk actionseasily too.

Actions can also open modals to request confirmation from the user, as well as render forms inside to collect extra data. It’s a good idea to read theActions documentationto learn more about their extensive capabilities throughout Filament.

## #Pagination

By default, Filament tables will be paginated. The user can choose between 5, 10, 25, and 50 records per page. If there are more records than the selected number, the user can navigate between pages using the pagination buttons.

### #Customizing the pagination options

You may customize the options for the paginated records per page select by passing them to thepaginated()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->paginated([10, 25, 50, 100, 'all']);
}

```

NOTE

Be aware when using very high numbers andallas large number of records can cause performance issues.

### #Customizing the default pagination page option

To customize the default number of records shown use thedefaultPaginationPageOption()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->defaultPaginationPageOption(25);
}

```

NOTE

Make sure that the default pagination page option is included in thepagination options.

### #Displaying links to the first and the last pagination page

To add “extreme” links to the first and the last page using theextremePaginationLinks()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->extremePaginationLinks();
}

```

### #Using simple pagination

You may use simple pagination by using thepaginationMode(PaginationMode::Simple)method:

```php
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->paginationMode(PaginationMode::Simple);
}

```

### #Using cursor pagination

You may use cursor pagination by using thepaginationMode(PaginationMode::Cursor)method:

```php
use Filament\Tables\Enums\PaginationMode;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->paginationMode(PaginationMode::Cursor);
}

```

### #Preventing query string conflicts with the pagination page

By default, Livewire stores the pagination state in apageparameter of the URL query string. If you have multiple tables on the same page, this will mean that the pagination state of one table may be overwritten by the state of another table.

To fix this, you may define a$table->queryStringIdentifier(), to return a unique query string identifier for that table:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->queryStringIdentifier('users');
}

```

### #Disabling pagination

By default, tables will be paginated. To disable this, you should use the$table->paginated(false)method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->paginated(false);
}

```

## #Record URLs (clickable rows)

You may allow table rows to be completely clickable by using the$table->recordUrl()method:

```php
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

public function table(Table $table): Table
{
    return $table
        ->recordUrl(
            fn (Model $record): string => route('posts.edit', ['record' => $record]),
        );
}

```

When using aresourcetable, the URL for each row is usually already set up for you, but this method can be called to override the default URL for each row.

TIP

You can alsooverride the URLfor a specific column, ortrigger an actionwhen a column is clicked.

You may also open the URL in a new tab:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->openRecordUrlInNewTab();
}

```

## #Reordering records

To allow the user to reorder records using drag and drop in your table, you can use the$table->reorderable()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->reorderable('sort');
}

```

Thesortdatabase column in this example will be used to store the order of records in the table. Whenever you order a database query using that column, they will be returned in the defined order. If you’re using mass assignment protection on your model, you will also need to add thesortattribute to the$fillablearray there.

When making the table reorderable, a new button will be available on the table to toggle reordering.

Thereorderable()method accepts the name of a column to store the record order in. If you use something likespatie/eloquent-sortablewith an order column such asorder_column, you may use this instead:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->reorderable('order_column');
}

```

Thereorderable()method also accepts a boolean condition as its second parameter, allowing you to conditionally enable reordering:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->reorderable('sort', auth()->user()->isAdmin());
}

```

You can pass adirectionparameter asdescto reorder the records in descending order instead of ascending:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->reorderable('sort', direction: 'desc');
}

```

### #Enabling pagination while reordering

Pagination will be disabled in reorder mode to allow you to move records between pages. It is generally a bad experience to have pagination while reordering, but if would like to override this use$table->paginatedWhileReordering():

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->paginatedWhileReordering();
}

```

### #Customizing the reordering trigger action

To customize the reordering trigger button, you may use thereorderRecordsTriggerAction()method, passing a closure that returns an action. All methods that are available tocustomize action trigger buttonscan be used:

```php
use Filament\Actions\Action;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->reorderRecordsTriggerAction(
            fn (Action $action, bool $isReordering) => $action
                ->button()
                ->label($isReordering ? 'Disable reordering' : 'Enable reordering'),
        );
}

```

### #Running code before and after reordering

You may run code before or after a record is reordered using thebeforeReordering()andafterReordering()methods. Both methods accept a function that receives the new$orderarray of record keys:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->reorderable('sort')
        ->beforeReordering(function (array $order): void {
            // Runs before records are reordered in the database.
        })
        ->afterReordering(function (array $order): void {
            // Runs after records are reordered in the database.
        });
}

```

## #Customizing the table header

You can add a heading to a table using the$table->heading()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->heading('Clients')
        ->columns([
            // ...
        ]);

```

You can also add a description below the heading using the$table->description()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->heading('Clients')
        ->description('Manage your clients here.')
        ->columns([
            // ...
        ]);

```

You can pass a view to the$table->header()method to customize the entire header HTML:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->header(view('tables.header', [
            'heading' => 'Clients',
        ]))
        ->columns([
            // ...
        ]);

```

## #Polling table content

You may poll table content so that it refreshes at a set interval, using the$table->poll()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->poll('10s');
}

```

## #Deferring loading

Tables with lots of data might take a while to load, in which case you can load the table data asynchronously using thedeferLoading()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->deferLoading();
}

```

## #Searching records with Laravel Scout

While Filament doesn’t provide a direct integration withLaravel Scout, you may use thesearchUsing()method with awhereKey()clause to filter the query for Scout results:

```php
use App\Models\Post;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

public function table(Table $table): Table
{
    return $table
        ->searchUsing(fn (Builder $query, string $search) => $query->whereKey(Post::search($search)->keys()));

```

Under normal circumstances Scout uses thewhereKey()(whereIn()) method to retrieve results internally, so there is no performance penalty for using it.

For the global search input to show, at least one column in the table needs to besearchable(). Alternatively, if you are using Scout to control which columns are searchable already, you can simply passsearchable()to the entire table instead:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->searchable();
}

```

## #Styling table rows

### #Striped table rows

To enable striped table rows, you can use thestriped()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->striped();
}

```

### #Custom row classes

You may want to conditionally style rows based on the record data. This can be achieved by specifying a string or array of CSS classes to be applied to the row using the$table->recordClasses()method:

```php
use App\Models\Post;
use Closure;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

public function table(Table $table): Table
{
    return $table
        ->recordClasses(fn (Post $record) => match ($record->status) {
            'draft' => 'draft-post-table-row',
            'reviewing' => 'reviewing-post-table-row',
            'published' => 'published-post-table-row',
            default => null,
        });
}

```

## #Global settings

To customize the default configuration used for all tables, you can call the staticconfigureUsing()method from theboot()method of a service provider. The function will be run for each table that gets created:

```php
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

Table::configureUsing(function (Table $table): void {
    $table
        ->reorderableColumns()
        ->filtersLayout(FiltersLayout::AboveContentCollapsible)
        ->paginationPageOptions([10, 25, 50]);
});

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
