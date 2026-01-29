# Testing tables

**URL:** https://filamentphp.com/docs/5.x/testing/testing-tables  
**Section:** testing  
**Page:** testing-tables  
**Priority:** medium  
**AI Context:** Testing Filament applications with PHPUnit/Pest.

---

## #Testing that a table can render

To ensure a table component renders, use theassertSuccessful()Livewire helper:

```php
use function Pest\Livewire\livewire;

it('can render page', function () {
    livewire(ListPosts::class)
        ->assertSuccessful();
});

```

To test which records are shown, you can useassertCanSeeTableRecords(),assertCanNotSeeTableRecords()andassertCountTableRecords():

```php
use function Pest\Livewire\livewire;

it('cannot display trashed posts by default', function () {
    $posts = Post::factory()->count(4)->create();
    $trashedPosts = Post::factory()->trashed()->count(6)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->assertCanNotSeeTableRecords($trashedPosts)
        ->assertCountTableRecords(4);
});

```
> If your table uses pagination,assertCanSeeTableRecords()will only check for records on the first page. To switch page, callcall('gotoPage', 2).


If your table uses pagination,assertCanSeeTableRecords()will only check for records on the first page. To switch page, callcall('gotoPage', 2).
> If your table usesdeferLoading(), you should callloadTable()beforeassertCanSeeTableRecords().


If your table usesdeferLoading(), you should callloadTable()beforeassertCanSeeTableRecords().

## #Testing columns

To ensure that a certain column is rendered, pass the column name toassertCanRenderTableColumn():

```php
use function Pest\Livewire\livewire;

it('can render post titles', function () {
    Post::factory()->count(10)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanRenderTableColumn('title');
});

```

This helper will get the HTML for this column, and check that it is present in the table.

For testing that a column is not rendered, you can useassertCanNotRenderTableColumn():

```php
use function Pest\Livewire\livewire;

it('can not render post comments', function () {
    Post::factory()->count(10)->create()

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanNotRenderTableColumn('comments');
});

```

This helper will assert that the HTML for this column is not shown by default in the present table.

### #Testing that a column can be searched

To search the table, call thesearchTable()method with your search query.

You can then useassertCanSeeTableRecords()to check your filtered table records, and useassertCanNotSeeTableRecords()to assert that some records are no longer in the table:

```php
use function Pest\Livewire\livewire;

it('can search posts by title', function () {
    $posts = Post::factory()->count(10)->create();

    $title = $posts->first()->title;

    livewire(PostResource\Pages\ListPosts::class)
        ->searchTable($title)
        ->assertCanSeeTableRecords($posts->where('title', $title))
        ->assertCanNotSeeTableRecords($posts->where('title', '!=', $title));
});

```

To search individual columns, you can pass an array of searches tosearchTableColumns():

```php
use function Pest\Livewire\livewire;

it('can search posts by title column', function () {
    $posts = Post::factory()->count(10)->create();

    $title = $posts->first()->title;

    livewire(PostResource\Pages\ListPosts::class)
        ->searchTableColumns(['title' => $title])
        ->assertCanSeeTableRecords($posts->where('title', $title))
        ->assertCanNotSeeTableRecords($posts->where('title', '!=', $title));
});

```

### #Testing that a column can be sorted

To sort table records, you can callsortTable(), passing the name of the column to sort by. You can use'desc'in the second parameter ofsortTable()to reverse the sorting direction.

Once the table is sorted, you can ensure that the table records are rendered in order usingassertCanSeeTableRecords()with theinOrderparameter:

```php
use function Pest\Livewire\livewire;

it('can sort posts by title', function () {
    Post::factory()->count(10)->create();

    $sortedPostsAsc = Post::query()->orderBy('title')->get();
    $sortedPostsDesc = Post::query()->orderBy('title', 'desc')->get();

    livewire(PostResource\Pages\ListPosts::class)
        ->sortTable('title')
        ->assertCanSeeTableRecords($sortedPostsAsc, inOrder: true)
        ->sortTable('title', 'desc')
        ->assertCanSeeTableRecords($sortedPostsDesc, inOrder: true);
});

```

NOTE

Filament tables use a SQLorderstatement to sort records before they are output. Different database drivers can use different sorting strategies, and they can differ from PHP’s own sorting strategy, so you should ensure that test records are sorted usingorderBy()on a database query rather thansortBy()on a collection of models.

### #Testing the state of a column

To assert that a certain column has a state or does not have a state for a record you can useassertTableColumnStateSet()andassertTableColumnStateNotSet():

```php
use function Pest\Livewire\livewire;

it('can get post author names', function () {
    $posts = Post::factory()->count(10)->create();

    $post = $posts->first();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertTableColumnStateSet('author.name', $post->author->name, record: $post)
        ->assertTableColumnStateNotSet('author.name', 'Anonymous', record: $post);
});

```

To assert that a certain column has a formatted state or does not have a formatted state for a record you can useassertTableColumnFormattedStateSet()andassertTableColumnFormattedStateNotSet():

```php
use function Pest\Livewire\livewire;

it('can get post author names', function () {
    $post = Post::factory(['name' => 'John Smith'])->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertTableColumnFormattedStateSet('author.name', 'Smith, John', record: $post)
        ->assertTableColumnFormattedStateNotSet('author.name', $post->author->name, record: $post);
});

```

### #Testing the existence of a column

To ensure that a column exists, you can use theassertTableColumnExists()method:

```php
use function Pest\Livewire\livewire;

it('has an author column', function () {
    livewire(PostResource\Pages\ListPosts::class)
        ->assertTableColumnExists('author');
});

```

You may pass a function as an additional argument to assert that a column passes a given “truth test”. This is useful for asserting that a column has a specific configuration. You can also pass in a record as the third parameter, which is useful if your check is dependent on which table row is being rendered:

```php
use function Pest\Livewire\livewire;
use Filament\Tables\Columns\TextColumn;

it('has an author column', function () {
    $post = Post::factory()->create();
    
    livewire(PostResource\Pages\ListPosts::class)
        ->assertTableColumnExists('author', function (TextColumn $column): bool {
            return $column->getDescriptionBelow() === $post->subtitle;
        }, $post);
});

```

### #Testing the visibility of a column

To ensure that a particular user cannot see a column, you can use theassertTableColumnVisible()andassertTableColumnHidden()methods:

```php
use function Pest\Livewire\livewire;

it('shows the correct columns', function () {
    livewire(PostResource\Pages\ListPosts::class)
        ->assertTableColumnVisible('created_at')
        ->assertTableColumnHidden('author');
});

```

### #Testing the description of a column

To ensure a column has the correct description above or below you can use theassertTableColumnHasDescription()andassertTableColumnDoesNotHaveDescription()methods:

```php
use function Pest\Livewire\livewire;

it('has the correct descriptions above and below author', function () {
    $post = Post::factory()->create();

    livewire(PostsTable::class)
        ->assertTableColumnHasDescription('author', 'Author! ↓↓↓', $post, 'above')
        ->assertTableColumnHasDescription('author', 'Author! ↑↑↑', $post)
        ->assertTableColumnDoesNotHaveDescription('author', 'Author! ↑↑↑', $post, 'above')
        ->assertTableColumnDoesNotHaveDescription('author', 'Author! ↓↓↓', $post);
});

```

### #Testing the extra attributes of a column

To ensure that a column has the correct extra attributes, you can use theassertTableColumnHasExtraAttributes()andassertTableColumnDoesNotHaveExtraAttributes()methods:

```php
use function Pest\Livewire\livewire;

it('displays author in red', function () {
    $post = Post::factory()->create();

    livewire(PostsTable::class)
        ->assertTableColumnHasExtraAttributes('author', ['class' => 'text-danger-500'], $post)
        ->assertTableColumnDoesNotHaveExtraAttributes('author', ['class' => 'text-primary-500'], $post);
});

```

### #Testing the options in aSelectColumn

If you have a select column, you can ensure it has the correct options withassertTableSelectColumnHasOptions()andassertTableSelectColumnDoesNotHaveOptions():

```php
use function Pest\Livewire\livewire;

it('has the correct statuses', function () {
    $post = Post::factory()->create();

    livewire(PostsTable::class)
        ->assertTableSelectColumnHasOptions('status', ['unpublished' => 'Unpublished', 'published' => 'Published'], $post)
        ->assertTableSelectColumnDoesNotHaveOptions('status', ['archived' => 'Archived'], $post);
});

```

## #Testing filters

To filter the table records, you can use thefilterTable()method, along withassertCanSeeTableRecords()andassertCanNotSeeTableRecords():

```php
use function Pest\Livewire\livewire;

it('can filter posts by `is_published`', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->filterTable('is_published')
        ->assertCanSeeTableRecords($posts->where('is_published', true))
        ->assertCanNotSeeTableRecords($posts->where('is_published', false));
});

```

For a simple filter, this will just enable the filter.

If you’d like to set the value of aSelectFilterorTernaryFilter, pass the value as a second argument:

```php
use function Pest\Livewire\livewire;

it('can filter posts by `author_id`', function () {
    $posts = Post::factory()->count(10)->create();

    $authorId = $posts->first()->author_id;

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->filterTable('author_id', $authorId)
        ->assertCanSeeTableRecords($posts->where('author_id', $authorId))
        ->assertCanNotSeeTableRecords($posts->where('author_id', '!=', $authorId));
});

```

### #Resetting filters in a test

To reset all filters to their original state, callresetTableFilters():

```php
use function Pest\Livewire\livewire;

it('can reset table filters', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->resetTableFilters();
});

```

### #Removing filters in a test

To remove a single filter you can useremoveTableFilter():

```php
use function Pest\Livewire\livewire;

it('filters list by published', function () {
    $posts = Post::factory()->count(10)->create();

    $unpublishedPosts = $posts->where('is_published', false)->get();

    livewire(PostsTable::class)
        ->filterTable('is_published')
        ->assertCanNotSeeTableRecords($unpublishedPosts)
        ->removeTableFilter('is_published')
        ->assertCanSeeTableRecords($posts);
});

```

To remove all filters you can useremoveTableFilters():

```php
use function Pest\Livewire\livewire;

it('can remove all table filters', function () {
    $posts = Post::factory()->count(10)->forAuthor()->create();

    $unpublishedPosts = $posts
        ->where('is_published', false)
        ->where('author_id', $posts->first()->author->getKey());

    livewire(PostsTable::class)
        ->filterTable('is_published')
        ->filterTable('author', $author)
        ->assertCanNotSeeTableRecords($unpublishedPosts)
        ->removeTableFilters()
        ->assertCanSeeTableRecords($posts);
});

```

### #Testing the visibility of a filter

To ensure that a particular user cannot see a filter, you can use theassertTableFilterVisible()andassertTableFilterHidden()methods:

```php
use function Pest\Livewire\livewire;

it('shows the correct filters', function () {
    livewire(PostsTable::class)
        ->assertTableFilterVisible('created_at')
        ->assertTableFilterHidden('author');
});

```

### #Testing the existence of a filter

To ensure that a filter exists, you can use theassertTableFilterExists()method:

```php
use function Pest\Livewire\livewire;

it('has an author filter', function () {
    livewire(PostResource\Pages\ListPosts::class)
        ->assertTableFilterExists('author');
});

```

You may pass a function as an additional argument to assert that a filter passes a given “truth test”. This is useful for asserting that a filter has a specific configuration:

```php
use function Pest\Livewire\livewire;
use Filament\Tables\Filters\SelectFilter;

it('has an author filter', function () {
    livewire(PostResource\Pages\ListPosts::class)
        ->assertTableFilterExists('author', function (SelectFilter $column): bool {
            return $column->getLabel() === 'Select author';
        });
});

```

## #Testing summaries

To test that a summary calculation is working, you may use theassertTableColumnSummarySet()method:

```php
use function Pest\Livewire\livewire;

it('can average values in a column', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->assertTableColumnSummarySet('rating', 'average', $posts->avg('rating'));
});

```

The first argument is the column name, the second is the summarizer ID, and the third is the expected value.

Note that the expected and actual values are normalized, such that123.12is considered the same as"123.12", and['Fred', 'Jim']is the same as['Jim', 'Fred'].

You may set a summarizer ID by passing it to themake()method:

```php
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('rating')
    ->summarize(Average::make('average'))

```

The ID should be unique between summarizers in that column.

### #Testing summaries on only one pagination page

To calculate the average for only one pagination page, use theisCurrentPaginationPageOnlyargument:

```php
use function Pest\Livewire\livewire;

it('can average values in a column', function () {
    $posts = Post::factory()->count(20)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanSeeTableRecords($posts->take(10))
        ->assertTableColumnSummarySet('rating', 'average', $posts->take(10)->avg('rating'), isCurrentPaginationPageOnly: true);
});

```

### #Testing a range summarizer

To test a range, pass the minimum and maximum value into a tuple-style[$minimum, $maximum]array:

```php
use function Pest\Livewire\livewire;

it('can average values in a column', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->assertTableColumnSummarySet('rating', 'range', [$posts->min('rating'), $posts->max('rating')]);
});

```

## #Testing toggleable columns

By default, only columns that are toggled on by default in the table will be rendered and testable. You can toggle all columns in the table on usingtoggleAllTableColumns():

```php
use function Pest\Livewire\livewire;

it('can toggle all columns', function () {
    livewire(PostResource\Pages\ListPosts::class)
        ->toggleAllTableColumns();
});

```

You can also toggle all columns off usingtoggleAllTableColumns(false):

```php
use function Pest\Livewire\livewire;

it('can toggle all columns off', function () {
    livewire(PostResource\Pages\ListPosts::class)
        ->toggleAllTableColumns(false);
});

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, forms, tables  
**Keywords:** tests, phpunit, pest, quality

*Extracted from Filament v5 Documentation - 2026-01-28*
