# Custom fields

**URL:** https://filamentphp.com/docs/5.x/forms/custom-fields  
**Section:** forms  
**Page:** custom-fields  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

Livewire components are PHP classes that have their state stored in the user’s browser. When a network request is made, the state is sent to the server, and filled into public properties on the Livewire component class, where it can be accessed in the same way as any other class property in PHP can be.

Imagine you had a Livewire component with a public property called$name. You could bind that property to an input field in the HTML of the Livewire component in one of two ways: with thewire:modelattribute, or byentanglingit with an Alpine.js property:

```php
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <input wire:model="name" />
    
    <!-- Or -->
    
    <div x-data="{ state: $wire.$entangle('name') }">
        <input x-model="state" />
    </div>
</x-dynamic-component>

```

When the user types into the input field, the$nameproperty is updated in the Livewire component class. When the user submits the form, the$nameproperty is sent to the server, where it can be saved.

This is the basis of how fields work in Filament. Each field is assigned to a public property in the Livewire component class, which is where the state of the field is stored. We call the name of this property the “state path” of the field. You can access the state path of a field using the$getStatePath()function in the field’s view:

```php
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <input wire:model="{{ $getStatePath() }}" />

    <!-- Or -->
    
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <input x-model="state" />
    </div>
</x-dynamic-component>

```

If your component heavily relies on third party libraries, we advise that you asynchronously load the Alpine.js component using the Filament asset system. This ensures that the Alpine.js component is only loaded when it’s needed, and not on every page load. To find out how to do this, check out ourAssets documentation.

### #Custom field classes

You may create your own custom field classes and views, which you can reuse across your project, and even release as a plugin to the community.

To create a custom field class and view, you may use the following command:

```php
php artisan make:filament-form-field LocationPicker

```

This will create the following component class:

```php
use Filament\Forms\Components\Field;

class LocationPicker extends Field
{
    protected string $view = 'filament.forms.components.location-picker';
}

```

It will also create a view file atresources/views/filament/forms/components/location-picker.blade.php.

NOTE

Filament form fields arenotLivewire components. Defining public properties and methods on a form field class will not make them accessible in the Blade view.

## #Accessing the state of another component in the Blade view

Inside the Blade view, you may access the state of another component in the schema using the$get()function:

```php
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    {{ $get('email') }}
</x-dynamic-component>

```

TIP

Unless a form field isreactive, the Blade view will not refresh when the value of the field changes, only when the next user interaction occurs that makes a request to the server. If you need to react to changes in a field’s value, it should belive().

## #Accessing the Eloquent record in the Blade view

Inside the Blade view, you may access the current Eloquent record using the$recordvariable:

```php
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    {{ $record->name }}
</x-dynamic-component>

```

## #Accessing the current operation in the Blade view

Inside the Blade view, you may access the current operation, usuallycreate,editorview, using the$operationvariable:

```php
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @if ($operation === 'create')
        This is a new conference.
    @else
        This is an existing conference.
    @endif
</x-dynamic-component>

```

## #Accessing the current Livewire component instance in the Blade view

Inside the Blade view, you may access the current Livewire component instance using$this:

```php
@php
    use Filament\Resources\Users\RelationManagers\ConferencesRelationManager;
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @if ($this instanceof ConferencesRelationManager)
        You are editing conferences the of a user.
    @endif
</x-dynamic-component>

```

## #Accessing the current field instance in the Blade view

Inside the Blade view, you may access the current field instance using$field. You can call public methods on this object to access other information that may not be available in variables:

```php
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @if ($field->getState())
        This is a new conference.
    @endif
</x-dynamic-component>

```

## #Adding a configuration method to a custom field class

You may add a public method to the custom field class that accepts a configuration value, stores it in a protected property, and returns it again from another public method:

```php
use Filament\Forms\Components\Field;

class LocationPicker extends Field
{
    protected string $view = 'filament.forms.components.location-picker';
    
    protected ?float $zoom = null;

    public function zoom(?float $zoom): static
    {
        $this->zoom = $zoom;

        return $this;
    }

    public function getZoom(): ?float
    {
        return $this->zoom;
    }
}

```

Now, in the Blade view for the custom field, you may access the zoom using the$getZoom()function:

```php
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    {{ $getZoom() }}
</x-dynamic-component>

```

Any public method that you define on the custom field class can be accessed in the Blade view as a variable function in this way.

To pass the configuration value to the custom field class, you may use the public method:

```php
use App\Filament\Forms\Components\LocationPicker;

LocationPicker::make('location')
    ->zoom(0.5)

```

## #Allowing utility injection in a custom field configuration method

Utility injectionis a powerful feature of Filament that allows users to configure a component using functions that can access various utilities. You can allow utility injection by ensuring that the parameter type and property type of the configuration allows the user to pass aClosure. In the getter method, you should pass the configuration value to the$this->evaluate()method, which will inject utilities into the user’s function if they pass one, or return the value if it is static:

```php
use Closure;
use Filament\Forms\Components\Field;

class LocationPicker extends Field
{
    protected string $view = 'filament.forms.components.location-picker';
    
    protected float | Closure | null $zoom = null;

    public function zoom(float | Closure | null $zoom): static
    {
        $this->zoom = $zoom;

        return $this;
    }

    public function getZoom(): ?float
    {
        return $this->evaluate($this->zoom);
    }
}

```

Now, you can pass a static value or a function to thezoom()method, andinject any utilityas a parameter:

```php
use App\Filament\Forms\Components\LocationPicker;

LocationPicker::make('location')
    ->zoom(fn (Conference $record): float => $record->isGlobal() ? 1 : 0.5)

```

## #Obeying state binding modifiers

When you bind a field to a state path, you may use thedefermodifier to ensure that the state is only sent to the server when the user submits the form, or whenever the next Livewire request is made. This is the default behavior.

However, you may use thelive()on a field to ensure that the state is sent to the server immediately when the user interacts with the field. This allows for lots of advanced use cases as explained in thereactivitysection of the documentation.

Filament provides a$applyStateBindingModifiers()function that you may use in your view to apply any state binding modifiers to awire:modelor$wire.$entangle()binding:

```php
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <input {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}" />
    
    <!-- Or -->
    
    <div x-data="{ state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }} }">
        <input x-model="state" />
    </div>
</x-dynamic-component>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
