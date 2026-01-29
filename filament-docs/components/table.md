# Rendering a table in a Blade view

**URL:** https://filamentphp.com/docs/5.x/components/table  
**Section:** components  
**Page:** table  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

NOTE

Before proceeding, make surefilament/tablesis installed in your project. You can check by running:

```php
composer show filament/tables

```

If it’s not installed, consult theinstallation guideand configure theindividual componentsaccording to the instructions.

## #Setting up the Livewire component

First, generate a new Livewire component:

```php
php artisan make:livewire ListProducts

```

Then, render your Livewire component on the page:

```php
@livewire('list-products')

```

Alternatively, you can use a full-page Livewire component:

```php
use App\Livewire\ListProducts;
use Illuminate\Support\Facades\Route;

Route::get('products', ListProducts::class);

```

## #Adding the table

There are 3 tasks when adding a table to a Livewire component class:
1. Implement theHasTableandHasSchemasinterfaces, and use theInteractsWithTableandInteractsWithSchemastraits.
2. Add atable()method, which is where you configure the table.Add the table’s columns, filters, and actions.
3. Make sure to define the base query that will be used to fetch rows in the table. For example, if you’re listing products from yourProductmodel, you will want to returnProduct::query().


```php
<?php

namespace App\Livewire;

use App\Models\Shop\Product;
use Filament\Actions\Concerns\InteractsWithActions;  
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ListProducts extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                // ...
            ])
            ->toolbarActions([
                // ...
            ]);
    }
    
    public function render(): View
    {
        return view('livewire.list-products');
    }
}

```

Finally, in your Livewire component’s view, render the table:

```php
<div>
    {{ $this->table }}
</div>

```

Visit your Livewire component in the browser, and you should see the table.

NOTE

filament/tablesalso includes the following packages:
- filament/actions
- filament/forms
- filament/support


These packages allow you to use their components within Livewire components.
For example, if your table usesActions, remember to implement theHasActionsinterface and include theInteractsWithActionstrait.

If you are using any otherFilament componentsin your table, make sure to install and integrate the corresponding package as well.

## #Building a table for an Eloquent relationship

If you want to build a table for an Eloquent relationship, you can use therelationship()andinverseRelationship()methods on the$tableinstead of passing aquery().HasMany,HasManyThrough,BelongsToMany,MorphManyandMorphToManyrelationships are compatible:

```php
use App\Models\Category;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

public Category $category;

public function table(Table $table): Table
{
    return $table
        ->relationship(fn (): BelongsToMany => $this->category->products())
        ->inverseRelationship('categories')
        ->columns([
            TextColumn::make('name'),
        ]);
}

```

In this example, we have a$categoryproperty which holds aCategorymodel instance. The category has a relationship namedproducts. We use a function to return the relationship instance. This is a many-to-many relationship, so the inverse relationship is calledcategories, and is defined on theProductmodel. We just need to pass the name of this relationship to theinverseRelationship()method, not the whole instance.

Now that the table is using a relationship instead of a plain Eloquent query, all actions will be performed on the relationship instead of the query. For example, if you use aCreateAction, the new product will be automatically attached to the category.

If your relationship uses a pivot table, you can use all pivot columns as if they were normal columns on your table, as long as they are listed in thewithPivot()method of the relationshipandinverse relationship definition.

Relationship tables are used in the Panel Builder as“relation managers”. Most of the documented features for relation managers are also available for relationship tables. For instance,attaching and detachingandassociating and dissociatingactions.

## #Generating table Livewire components with the CLI

It’s advised that you learn how to set up a Livewire component with the Table Builder manually, but once you are confident, you can use the CLI to generate a table for you.

```php
php artisan make:livewire-table Products/ListProducts

```

This will ask you for the name of a prebuilt model, for exampleProduct. Finally, it will generate a newapp/Livewire/Products/ListProducts.phpcomponent, which you can customize.

### #Automatically generating table columns

Filament is also able to guess which table columns you want in the table, based on the model’s database columns. You can use the--generateflag when generating your table:

```php
php artisan make:livewire-table Products/ListProducts --generate

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
