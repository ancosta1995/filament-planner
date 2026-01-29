# Filter layout

**URL:** https://filamentphp.com/docs/5.x/tables/filters/layout  
**Section:** tables  
**Page:** layout  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Positioning filters into grid columns

To change the number of columns that filters may occupy, you may use thefiltersFormColumns()method:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ])
        ->filtersFormColumns(3);
}

```

## #Controlling the width of the filters dropdown

To customize the dropdown width, you may use thefiltersFormWidth()method, and specify a width -ExtraSmall,Small,Medium,Large,ExtraLarge,TwoExtraLarge,ThreeExtraLarge,FourExtraLarge,FiveExtraLarge,SixExtraLargeorSevenExtraLarge. By default, the width isExtraSmall:

```php
use Filament\Support\Enums\Width;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ])
        ->filtersFormWidth(Width::FourExtraLarge);
}

```

## #Controlling the maximum height of the filters dropdown

To add a maximum height to the filters’ dropdown content, so that they scroll, you may use thefiltersFormMaxHeight()method, passing aCSS length:

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ])
        ->filtersFormMaxHeight('400px');
}

```

## #Displaying filters in a modal

To render the filters in a modal instead of in a dropdown, you may use:

```php
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ], layout: FiltersLayout::Modal);
}

```

You may use thetrigger action APItocustomize the modal, includingusing aslideOver().

## #Displaying filters above the table content

To render the filters above the table content instead of in a dropdown, you may use:

```php
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ], layout: FiltersLayout::AboveContent);
}

```

### #Allowing filters above the table content to be collapsed

To allow the filters above the table content to be collapsed, you may use:

```php
use Filament\Tables\Enums\FiltersLayout;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ], layout: FiltersLayout::AboveContentCollapsible);
}

```

## #Displaying filters below the table content

To render the filters below the table content instead of in a dropdown, you may use:

```php
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ], layout: FiltersLayout::BelowContent);
}

```

## #Displaying filters to the left or right of the table content

To render the filters to the left (before) or right (after) of the table content instead of in a dropdown, you may use:

```php
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ], layout: FiltersLayout::BeforeContent); // or `FiltersLayout::AfterContent`
}

```

### #Allowing filters to be collapsible when displayed to the left or right of the table content

To allow the filters to be collapsible when displayed to the left or right of the table content, you may use:

```php
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ], layout: FiltersLayout::BeforeContentCollapsible); // or `FiltersLayout::AfterContentCollapsible`
}

```

## #Hiding the filter indicators

To hide the active filters indicators above the table, you may usehiddenFilterIndicators():

```php
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ])
        ->hiddenFilterIndicators();
}

```

## #Customizing the filter form schema

You may customize theform schemaof the entire filter form at once, in order to rearrange filters into your desired layout, and use any of thelayout componentsavailable to forms. To do this, use thefilterFormSchema()method, passing a closure function that receives the array of defined$filtersthat you can insert:

```php
use Filament\Schemas\Components\Section;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            Filter::make('is_featured'),
            Filter::make('published_at'),
            Filter::make('author'),
        ])
        ->filtersFormColumns(2)
        ->filtersFormSchema(fn (array $filters): array => [
            Section::make('Visibility')
                ->description('These filters affect the visibility of the records in the table.')
                ->schema([
                    $filters['is_featured'],
                    $filters['published_at'],
                ])
                    ->columns(2)
                ->columnSpanFull(),
            $filters['author'],
        ]);
}

```

In this example, we have put two of the filters inside asectioncomponent, and used thecolumns()method to specify that the section should have two columns. We have also used thecolumnSpanFull()method to specify that the section should span the full width of the filter form, which is also 2 columns wide. We have inserted each filter into the form schema by using the filter’s name as the key in the$filtersarray.

## #Displaying the reset action in the footer

By default, the reset action appears in the header of the filters form. You may move it to the footer, next to the apply action, using thefiltersResetActionPosition()method:

```php
use Filament\Tables\Enums\FiltersResetActionPosition;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            // ...
        ])
        ->filtersResetActionPosition(FiltersResetActionPosition::Footer);
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
