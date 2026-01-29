# Rendering an infolist in a Blade view

**URL:** https://filamentphp.com/docs/5.x/components/infolist  
**Section:** components  
**Page:** infolist  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

NOTE

Before proceeding, make surefilament/infolistsis installed in your project. You can check by running:

```php
composer show filament/infolists

```

If it’s not installed, consult theinstallation guideand configure theindividual componentsaccording to the instructions.

## #Setting up the Livewire component

First, generate a new Livewire component:

```php
php artisan make:livewire ViewProduct

```

Then, render your Livewire component on the page:

```php
@livewire('view-product')

```

Alternatively, you can use a full-page Livewire component:

```php
use App\Livewire\ViewProduct;
use Illuminate\Support\Facades\Route;

Route::get('products/{product}', ViewProduct::class);

```

You must use theInteractsWithSchemastrait, and implement theHasSchemasinterface on your Livewire component class:

```php
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Component;

class ViewProduct extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    // ...
}

```

## #Adding the infolist

Next, add a method to the Livewire component which accepts an$infolistobject, modifies it, and returns it:

```php
use Filament\Schemas\Schema;

public function productInfolist(Schema $schema): Schema
{
    return $schema
        ->record($this->product)
        ->components([
            // ...
        ]);
}

```

Finally, render the infolist in the Livewire component’s view:

```php
{{ $this->productInfolist }}

```

NOTE

filament/infolistsalso includes the following packages:
- filament/actions
- filament/schemas
- filament/support


These packages allow you to use their components within Livewire components.
For example, if your infolist usesActions, remember to implement theHasActionsinterface and use theInteractsWithActionstrait on your Livewire component class.

If you are using any otherFilament componentsin your infolist, make sure to install and integrate the corresponding package as well.

## #Passing data to the infolist

You can pass data to the infolist in two ways:

Either pass an Eloquent model instance to therecord()method of the infolist, to automatically map all the model attributes and relationships to the entries in the infolist’s schema:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

public function productInfolist(Schema $schema): Schema
{
    return $schema
        ->record($this->product)
        ->components([
            TextEntry::make('name'),
            TextEntry::make('category.name'),
            // ...
        ]);
}

```

Alternatively, you can pass an array of data to thestate()method of the infolist, to manually map the data to the entries in the infolist’s schema:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

public function productInfolist(Schema $schema): Schema
{
    return $schema
        ->constantState([
            'name' => 'MacBook Pro',
            'category' => [
                'name' => 'Laptops',
            ],
            // ...
        ])
        ->components([
            TextEntry::make('name'),
            TextEntry::make('category.name'),
            // ...
        ]);
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
