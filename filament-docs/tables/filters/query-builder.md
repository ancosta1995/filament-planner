# Query builder

**URL:** https://filamentphp.com/docs/5.x/tables/filters/query-builder  
**Section:** tables  
**Page:** query-builder  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

The query builder allows you to define a complex set of conditions to filter the data in your table. It is able to handle unlimited nesting of conditions, which you can group together with “and” and “or” operations.

To use it, you need to define a set of “constraints” that will be used to filter the data. Filament includes some built-in constraints, that follow common data types, but you can also define your own custom constraints.

You can add a query builder to any table using theQueryBuilderfilter:

```php
use Filament\Tables\Filters\QueryBuilder;
use Filament\QueryBuilder\Constraints\BooleanConstraint;
use Filament\QueryBuilder\Constraints\DateConstraint;
use Filament\QueryBuilder\Constraints\NumberConstraint;
use Filament\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\QueryBuilder\Constraints\SelectConstraint;
use Filament\QueryBuilder\Constraints\TextConstraint;

QueryBuilder::make()
    ->constraints([
        TextConstraint::make('name'),
        BooleanConstraint::make('is_visible'),
        NumberConstraint::make('stock'),
        SelectConstraint::make('status')
            ->options([
                'draft' => 'Draft',
                'reviewing' => 'Reviewing',
                'published' => 'Published',
            ])
            ->multiple(),
        DateConstraint::make('created_at'),
        RelationshipConstraint::make('categories')
            ->multiple()
            ->selectable(
                IsRelatedToOperator::make()
                    ->titleAttribute('name')
                    ->searchable()
                    ->multiple(),
            ),
        NumberConstraint::make('reviews.rating')
            ->integer(),
    ])

```

When deeply nesting the query builder, you might need to increase the amount of space that the filters can consume. One way of doing this is toposition the filters above the table content:

```php
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->filters([
            QueryBuilder::make()
                ->constraints([
                    // ...
                ]),
        ], layout: FiltersLayout::AboveContent);
}

```

## #Available constraints

Filament ships with many different constraints that you can use out of the box. You can alsocreate your own custom constraints:
- Text constraint
- Boolean constraint
- Number constraint
- Date constraint
- Select constraint
- Relationship constraint


### #Text constraints

Text constraints allow you to filter text fields. They can be used to filter any text field, including via relationships.

```php
use Filament\QueryBuilder\Constraints\TextConstraint;

TextConstraint::make('name') // Filter the `name` column

TextConstraint::make('creator.name') // Filter the `name` column on the `creator` relationship using dot syntax

```

By default, the following operators are available:
- Contains - filters a column to contain the search term
- Does not contain - filters a column to not contain the search term
- Starts with - filters a column to start with the search term
- Does not start with - filters a column to not start with the search term
- Ends with - filters a column to end with the search term
- Does not end with - filters a column to not end with the search term
- Equals - filters a column to equal the search term
- Does not equal - filters a column to not equal the search term
- Is filled - filters a column to not be empty
- Is blank - filters a column to be empty


### #Boolean constraints

Boolean constraints allow you to filter boolean fields. They can be used to filter any boolean field, including via relationships.

```php
use Filament\QueryBuilder\Constraints\BooleanConstraint;

BooleanConstraint::make('is_visible') // Filter the `is_visible` column

BooleanConstraint::make('creator.is_admin') // Filter the `is_admin` column on the `creator` relationship using dot syntax

```

By default, the following operators are available:
- Is true - filters a column to betrue
- Is false - filters a column to befalse


### #Number constraints

Number constraints allow you to filter numeric fields. They can be used to filter any numeric field, including via relationships.

```php
use Filament\QueryBuilder\Constraints\NumberConstraint;

NumberConstraint::make('stock') // Filter the `stock` column

NumberConstraint::make('orders.item_count') // Filter the `item_count` column on the `orders` relationship using dot syntax

```

By default, the following operators are available:
- Is minimum - filters a column to be greater than or equal to the search number
- Is less than - filters a column to be less than the search number
- Is maximum - filters a column to be less than or equal to the search number
- Is greater than - filters a column to be greater than the search number
- Equals - filters a column to equal the search number
- Does not equal - filters a column to not equal the search number
- Is filled - filters a column to not be empty
- Is blank - filters a column to be empty


When using relationship column with a number constraint, users also have the ability to “aggregate” related records. This means that they can filter the column to be the sum, average, minimum or maximum of all the related records at once.

#### #Integer constraints

By default, number constraints will allow decimal values. If you’d like to only allow integer values, you can use theinteger()method:

```php
use Filament\QueryBuilder\Constraints\NumberConstraint;

NumberConstraint::make('stock')
    ->integer()

```

### #Date constraints

Date constraints allow you to filter date fields. They can be used to filter any date field, including via relationships.

```php
use Filament\QueryBuilder\Constraints\DateConstraint;

DateConstraint::make('created_at') // Filter the `created_at` column

DateConstraint::make('creator.created_at') // Filter the `created_at` column on the `creator` relationship using dot syntax

```

By default, the following operators are available:
- Is after - filters a column to be after the search date
- Is not after - filters a column to not be after the search date, or to be the same date
- Is before - filters a column to be before the search date
- Is not before - filters a column to not be before the search date, or to be the same date
- Is date - filters a column to be the same date as the search date
- Is not date - filters a column to not be the same date as the search date
- Is month - filters a column to be in the same month as the selected month
- Is not month - filters a column to not be in the same month as the selected month
- Is year - filters a column to be in the same year as the searched year
- Is not year - filters a column to not be in the same year as the searched year


#### #Datetime constraints

By default, date constraints only filter by date. If you have a datetime column and want to enable time-based filtering, you can use thetime()method:

```php
use Filament\QueryBuilder\Constraints\DateConstraint;

DateConstraint::make('published_at')
    ->time()

```

### #Select constraints

Select constraints allow you to filter fields using a select field. They can be used to filter any field, including via relationships.

```php
use Filament\QueryBuilder\Constraints\SelectConstraint;

SelectConstraint::make('status') // Filter the `status` column
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])

SelectConstraint::make('creator.department') // Filter the `department` column on the `creator` relationship using dot syntax
    ->options([
        'sales' => 'Sales',
        'marketing' => 'Marketing',
        'engineering' => 'Engineering',
        'purchasing' => 'Purchasing',
    ])

```

#### #Searchable select constraints

By default, select constraints will not allow the user to search the options. If you’d like to allow the user to search the options, you can use thesearchable()method:

```php
use Filament\QueryBuilder\Constraints\SelectConstraint;

SelectConstraint::make('status')
    ->searchable()
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])

```

#### #Multi-select constraints

By default, select constraints will only allow the user to select a single option. If you’d like to allow the user to select multiple options, you can use themultiple()method:

```php
use Filament\QueryBuilder\Constraints\SelectConstraint;

SelectConstraint::make('status')
    ->multiple()
    ->options([
        'draft' => 'Draft',
        'reviewing' => 'Reviewing',
        'published' => 'Published',
    ])

```

When the user selects multiple options, the table will be filtered to show records that match any of the selected options.

### #Relationship constraints

Relationship constraints allow you to filter fields using data about a relationship:

```php
use Filament\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;

RelationshipConstraint::make('creator') // Filter the `creator` relationship
    ->selectable(
        IsRelatedToOperator::make()
            ->titleAttribute('name')
            ->searchable()
            ->multiple(),
    )

```

TheIsRelatedToOperatoris used to configure the “Is / Contains” and “Is not / Does not contain” operators. It provides a select field which allows the user to filter the relationship by which records are attached to it. ThetitleAttribute()method is used to specify which attribute should be used to identify each related record in the list. Thesearchable()method makes the list searchable. Themultiple()method allows the user to select multiple related records, and if they do, the table will be filtered to show records that match any of the selected related records.

#### #Multiple relationships

By default, relationship constraints only include operators that are appropriate for filtering a singular relationship, like aBelongsTo. If you have a relationship such as aHasManyorBelongsToMany, you may wish to mark the constraint asmultiple():

```php
use Filament\QueryBuilder\Constraints\RelationshipConstraint;

RelationshipConstraint::make('categories')
    ->multiple()

```

This will add the following operators to the constraint:
- Has minimum - filters a column to have at least the specified number of related records
- Has less than - filters a column to have less than the specified number of related records
- Has maximum - filters a column to have at most the specified number of related records
- Has more than - filters a column to have more than the specified number of related records
- Has - filters a column to have the specified number of related records
- Does not have - filters a column to not have the specified number of related records


#### #Empty relationship constraints

TheRelationshipConstraintdoes not supportnullable()in the same way as other constraints.

If the relationship ismultiple(), then the constraint will show an option to filter out “empty” relationships. This means that the relationship has no related records. If your relationship is singular, then you can use theemptyable()method to show an option to filter out “empty” relationships:

```php
use Filament\QueryBuilder\Constraints\RelationshipConstraint;

RelationshipConstraint::make('creator')
    ->emptyable()

```

If you have amultiple()relationship that must always have at least 1 related record, then you can use theemptyable(false)method to hide the option to filter out “empty” relationships:

```php
use Filament\QueryBuilder\Constraints\RelationshipConstraint;

RelationshipConstraint::make('categories')
    ->emptyable(false)

```

#### #Nullable constraints

By default, constraints will not show an option to filternullvalues. If you’d like to show an option to filternullvalues, you can use thenullable()method:

```php
use Filament\QueryBuilder\Constraints\TextConstraint;

TextConstraint::make('name')
    ->nullable()

```

Now, the following operators are also available:
- Is filled - filters a column to not be empty
- Is blank - filters a column to be empty


## #Scoping relationships

When you use a relationship constraint, you can scope the relationship to filter the related records using themodifyRelationshipQueryUsing()method:

```php
use Filament\QueryBuilder\Constraints\TextConstraint;
use Illuminate\Database\Eloquent\Builder;

TextConstraint::make('creator.name')
    ->label('Admin creator name')
    ->modifyRelationshipQueryUsing(fn (Builder $query) => $query->where('is_admin', true))

```

## #Customizing the constraint icon

Each constraint type has a defaulticon, which is displayed next to the label in the picker. You can customize the icon for a constraint by passing its name to theicon()method:

```php
use Filament\QueryBuilder\Constraints\TextConstraint;

TextConstraint::make('author.name')
    ->icon('heroicon-m-user')

```

## #Overriding the default operators

Each constraint type has a set of default operators, which you can customize by using theoperators()method:

```php
use Filament\QueryBuilder\Constraints\Operators\IsFilledOperator;
use Filament\QueryBuilder\Constraints\TextConstraint;

TextConstraint::make('author.name')
    ->operators([
        IsFilledOperator::make(),
    ])

```

This will remove all operators, and register theEqualsOperator.

If you’d like to add an operator to the end of the list, usepushOperators()instead:

```php
use Filament\QueryBuilder\Constraints\Operators\IsFilledOperator;
use Filament\QueryBuilder\Constraints\TextConstraint;

TextConstraint::make('author.name')
    ->pushOperators([
        IsFilledOperator::class,
    ])

```

If you’d like to add an operator to the start of the list, useunshiftOperators()instead:

```php
use Filament\QueryBuilder\Constraints\Operators\IsFilledOperator;
use Filament\QueryBuilder\Constraints\TextConstraint;

TextConstraint::make('author.name')
    ->unshiftOperators([
        IsFilledOperator::class,
    ])

```

## #Creating custom constraints

Custom constraints can be created “inline” with other constraints using theConstraint::make()method. You should also pass aniconto theicon()method:

```php
use Filament\QueryBuilder\Constraints\Constraint;

Constraint::make('subscribed')
    ->icon('heroicon-m-bell')
    ->operators([
        // ...
    ]),

```

If you want to customize the label of the constraint, you can use thelabel()method:

```php
use Filament\QueryBuilder\Constraints\Constraint;

Constraint::make('subscribed')
    ->label('Subscribed to updates')
    ->icon('heroicon-m-bell')
    ->operators([
        // ...
    ]),

```

Now, you mustdefine operatorsfor the constraint. These are options that you can pick from to filter the column. If the column isnullable, you can also register that built-in operator for your custom constraint:

```php
use Filament\QueryBuilder\Constraints\Constraint;
use Filament\QueryBuilder\Constraints\Operators\IsFilledOperator;

Constraint::make('subscribed')
    ->label('Subscribed to updates')
    ->icon('heroicon-m-bell')
    ->operators([
        // ...
        IsFilledOperator::class,
    ]),

```

### #Creating custom operators

Custom operators can be created using theOperator::make()method:

```php
use Filament\QueryBuilder\Constraints\Operators\Operator;

Operator::make('subscribed')
    ->label(fn (bool $isInverse): string => $isInverse ? 'Not subscribed' : 'Subscribed')
    ->summary(fn (bool $isInverse): string => $isInverse ? 'You are not subscribed' : 'You are subscribed')
    ->baseQuery(fn (Builder $query, bool $isInverse) => $query->{$isInverse ? 'whereDoesntHave' : 'whereHas'}(
        'subscriptions.user',
        fn (Builder $query) => $query->whereKey(auth()->user()),
    )),

```

In this example, the operator is able to filter records based on whether or not the authenticated user is subscribed to the record. A subscription is recorded in thesubscriptionsrelationship of the table.

ThebaseQuery()method is used to define the query that will be used to filter the records. The$isInverseargument isfalsewhen the “Subscribed” option is selected, andtruewhen the “Not subscribed” option is selected. The function is applied to the base query of the table, wherewhereHas()can be used. If your function does not need to be applied to the base query of the table, like when you are using a simplewhere()orwhereIn(), you can use thequery()method instead, which has the bonus of being able to be used inside nested “OR” groups.

Thelabel()method is used to render the options in the operator select. Two options are registered for each operator, one for when the operator is not inverted, and one for when it is inverted.

Thesummary()method is used in the header of the constraint when it is applied to the query, to provide an overview of the active constraint.

## #Customizing the constraint picker

### #Changing the number of columns in the constraint picker

The constraint picker has only 1 column. You may customize it by passing a number of columns toconstraintPickerColumns():

```php
use Filament\Tables\Filters\QueryBuilder;

QueryBuilder::make()
    ->constraintPickerColumns(2)
    ->constraints([
        // ...
    ])

```

This method can be used in a couple of different ways:
- You can pass an integer likeconstraintPickerColumns(2). This integer is the number of columns used on thelgbreakpoint and higher. All smaller devices will have just 1 column.
- You can pass an array, where the key is the breakpoint and the value is the number of columns. For example,constraintPickerColumns(['md' => 2, 'xl' => 4])will create a 2 column layout on medium devices, and a 4 column layout on extra large devices. The default breakpoint for smaller devices uses 1 column, unless you use adefaultarray key.


Breakpoints (sm,md,lg,xl,2xl) are defined by Tailwind, and can be found in theTailwind documentation.

### #Increasing the width of the constraint picker

When youincrease the number of columns, the width of the dropdown should increase incrementally to handle the additional columns. If you’d like more control, you can manually set a maximum width for the dropdown using theconstraintPickerWidth()method. Options correspond toTailwind’s max-width scale. The options arexs,sm,md,lg,xl,2xl,3xl,4xl,5xl,6xl,7xl:

```php
use Filament\Tables\Filters\QueryBuilder;

QueryBuilder::make()
    ->constraintPickerColumns(3)
    ->constraintPickerWidth('2xl')
    ->constraints([
        // ...
    ])

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
