# Custom components

**URL:** https://filamentphp.com/docs/5.x/schemas/custom-components  
**Section:** schemas  
**Page:** custom-components  
**Priority:** medium  
**AI Context:** Layout system for building complex UIs with sections, tabs, wizards.

---

## #Inserting a Blade view into a schema

You may use a “view” component to insert a Blade view into a schema arbitrarily:

```php
use Filament\Schemas\Components\View;

View::make('filament.schemas.components.chart')

```

This assumes that you have aresources/views/filament/schemas/components/chart.blade.phpfile.

You may pass data to this view through theviewData()method:

```php
use Filament\Schemas\Components\View;

View::make('filament.schemas.components.chart')
    ->viewData(['data' => $data])

```

### #Rendering the component’s child schema

You may pass an array of child schema components to theschema()method of the component:

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\View;

View::make('filament.schemas.components.chart')
    ->schema([
        TextInput::make('subtotal'),
        TextInput::make('total'),
    ])

```

Inside the Blade view, you may render the component’sschema()using the$getChildSchema()function:

```php
<div>
    {{ $getChildSchema() }}
</div>

```

### #Accessing the state of another component in the Blade view

Inside the Blade view, you may access the state of another component in the schema using the$get()function:

```php
<div>
    {{ $get('email') }}
</div>

```

TIP

Unless a form field isreactive, the Blade view will not refresh when the value of the field changes, only when the next user interaction occurs that makes a request to the server. If you need to react to changes in a field’s value, it should belive().

### #Accessing the Eloquent record in the Blade view

Inside the Blade view, you may access the current Eloquent record using the$recordvariable:

```php
<div>
    {{ $record->name }}
</div>

```

### #Accessing the current operation in the Blade view

Inside the Blade view, you may access the current operation, usuallycreate,editorview, using the$operationvariable:

```php
<p>
    @if ($operation === 'create')
        This is a new post.
    @else
        This is an existing post.
    @endif
</p>

```

### #Accessing the current Livewire component instance in the Blade view

Inside the Blade view, you may access the current Livewire component instance using$this:

```php
@php
    use Filament\Resources\Users\RelationManagers\PostsRelationManager;
@endphp

<p>
    @if ($this instanceof PostsRelationManager)
        You are editing posts the of a user.
    @endif
</p>

```

### #Accessing the current component instance in the Blade view

Inside the Blade view, you may access the current component instance using$schemaComponent. You can call public methods on this object to access other information that may not be available in variables:

```php
<p>
    @if ($schemaComponent->getState())
        This is a new post.
    @endif
</p>

```

## #Inserting a Livewire component into a schema

You may insert a Livewire component directly into a schema:

```php
use App\Livewire\Chart;
use Filament\Schemas\Components\Livewire;

Livewire::make(Chart::class)

```

NOTE

When inserting a Livewire component into the schema, there are limited capabilities. Only serializable data is accessible from the nested Livewire component, since they are rendered separately. As such, you can’trender a child schema,access another component’s live state,access the current Livewire component instance, oraccess the current component instance. Onlystatic data that you pass to the Livewire component, andthe current recordare accessible. Situations where you should render a nested Livewire component instead of aBlade vieware rare because of these limitations.

If you are rendering multiple of the same Livewire component, please make sure to pass a uniquekey()to each:

```php
use App\Livewire\Chart;
use Filament\Schemas\Components\Livewire;

Livewire::make(Chart::class)
    ->key('chart-first')

Livewire::make(Chart::class)
    ->key('chart-second')

Livewire::make(Chart::class)
    ->key('chart-third')

```

### #Passing parameters to a Livewire component

You can pass an array of parameters to a Livewire component:

```php
use App\Livewire\Chart;
use Filament\Schemas\Components\Livewire;

Livewire::make(Chart::class, ['bar' => 'baz'])

```

Now, those parameters will be passed to the Livewire component’smount()method:

```php
class Chart extends Component
{
    public function mount(string $bar): void
    {       
        // ...
    }
}

```

Alternatively, they will be available as public properties on the Livewire component:

```php
class Chart extends Component
{
    public string $bar;
}

```

#### #Accessing the current record in the Livewire component

You can access the current record in the Livewire component using the$recordparameter in themount()method, or the$recordproperty:

```php
use Illuminate\Database\Eloquent\Model;

class Chart extends Component
{
    public function mount(?Model $record = null): void
    {       
        // ...
    }
    
    // or
    
    public ?Model $record = null;
}

```

Please be aware that when the record has not yet been created, it will benull. If you’d like to hide the Livewire component when the record isnull, you can use thehidden()method:

```php
use Filament\Schemas\Components\Livewire;
use Illuminate\Database\Eloquent\Model;

Livewire::make(Chart::class)
    ->hidden(fn (?Model $record): bool => $record === null)

```

### #Lazy loading a Livewire component

You may allow the component tolazily loadusing thelazy()method:

```php
use Filament\Schemas\Components\Livewire;
use App\Livewire\Chart;

Livewire::make(Chart::class)
    ->lazy()       

```

## #Custom component classes

You may create your own custom component classes and views, which you can reuse across your project, and even release as a plugin to the community.

TIP

If you’re just creating a simple custom component to use once, you could instead use aview componentto render any custom Blade file.

To create a custom component class and view, you may use the following command:

```php
php artisan make:filament-schema-component Chart

```

This will create the following component class:

```php
use Filament\Schemas\Components\Component;

class Chart extends Component
{
    protected string $view = 'filament.schemas.components.chart';

    public static function make(): static
    {
        return app(static::class);
    }
}

```

It will also create a view file atresources/views/filament/schemas/components/chart.blade.php.

You may use the same utilities as you would wheninserting a Blade view into a schematorender the component’s child schema,access another component’s live state,access the current Eloquent record,access the current operation,access the current Livewire component instance, andaccess the current component instance.

NOTE

Filament schema components arenotLivewire components. Defining public properties and methods on a schema component class will not make them accessible in the Blade view.

### #Adding a configuration method to a custom component class

You may add a public method to the custom component class that accepts a configuration value, stores it in a protected property, and returns it again from another public method:

```php
use Filament\Schemas\Components\Component;

class Chart extends Component
{
    protected string $view = 'filament.schemas.components.chart';
    
    protected ?string $heading = null;

    public static function make(): static
    {
        return app(static::class);
    }

    public function heading(?string $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }
}

```

Now, in the Blade view for the custom component, you may access the heading using the$getHeading()function:

```php
<div>
    {{ $getHeading() }}
</div>

```

Any public method that you define on the custom component class can be accessed in the Blade view as a variable function in this way.

To pass the configuration value to the custom component class, you may use the public method:

```php
use App\Filament\Schemas\Components\Chart;

Chart::make()
    ->heading('Sales')

```

#### #Allowing utility injection in a custom component configuration method

Utility injectionis a powerful feature of Filament that allows users to configure a component using functions that can access various utilities. You can allow utility injection by ensuring that the parameter type and property type of the configuration allows the user to pass aClosure. In the getter method, you should pass the configuration value to the$this->evaluate()method, which will inject utilities into the user’s function if they pass one, or return the value if it is static:

```php
use Closure;
use Filament\Schemas\Components\Component;

class Chart extends Component
{
    protected string $view = 'filament.schemas.components.chart';
    
    protected string | Closure | null $heading = null;

    public static function make(): static
    {
        return app(static::class);
    }

    public function heading(string | Closure | null $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->evaluate($this->heading);
    }
}

```

Now, you can pass a static value or a function to theheading()method, andinject any utilityas a parameter:

```php
use App\Filament\Schemas\Components\Chart;

Chart::make()
    ->heading(fn (Product $record): string => "{$record->name} Sales")

```

### #Accepting a configuration value in the constructor of a custom component class

You may accept a configuration value in themake()constructor method of the custom component and pass it to the corresponding setter method:

```php
use Closure;
use Filament\Schemas\Components\Component;

class Chart extends Component
{
    protected string $view = 'filament.schemas.components.chart';
    
    protected string | Closure | null $heading = null;

    public function __construct(string | Closure | null $heading = null)
    {
        $this->heading($heading)
    }

    public static function make(string | Closure | null $heading = null): static
    {
        return app(static::class, ['heading' => $heading]);
    }
    
    public function heading(string | Closure | null $heading): static
    {
        $this->heading = $heading;

        return $this;
    }

    public function getHeading(): ?string
    {
        return $this->evaluate($this->heading);
    }
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, infolists  
**Keywords:** layout, structure, organization, ui

*Extracted from Filament v5 Documentation - 2026-01-28*
