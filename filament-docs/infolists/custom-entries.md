# Custom entries

**URL:** https://filamentphp.com/docs/5.x/infolists/custom-entries  
**Section:** infolists  
**Page:** custom-entries  
**Priority:** medium  
**AI Context:** Display read-only data in structured layouts.

---

## #Introduction

You may create your own custom entry classes and views, which you can reuse across your project, and even release as a plugin to the community.

To create a custom entry class and view, you may use the following command:

```php
php artisan make:filament-infolist-entry AudioPlayerEntry

```

This will create the following component class:

```php
use Filament\Infolists\Components\Entry;

class AudioPlayerEntry extends Entry
{
    protected string $view = 'filament.infolists.components.audio-player-entry';
}

```

It will also create a view file atresources/views/filament/infolists/components/audio-player-entry.blade.php.

NOTE

Filament infolist entries arenotLivewire components. Defining public properties and methods on a infolist entry class will not make them accessible in the Blade view.

## #Accessing the state of the entry in the Blade view

Inside the Blade view, you may access thestateof the entry using the$getState()function:

```php
<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    {{ $getState() }}
</x-dynamic-component>

```

## #Accessing the state of another component in the Blade view

Inside the Blade view, you may access the state of another component in the schema using the$get()function:

```php
<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
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
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    {{ $record->name }}
</x-dynamic-component>

```

## #Accessing the current operation in the Blade view

Inside the Blade view, you may access the current operation, usuallycreate,editorview, using the$operationvariable:

```php
<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
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
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    @if ($this instanceof ConferencesRelationManager)
        You are editing conferences the of a user.
    @endif
</x-dynamic-component>

```

## #Accessing the current entry instance in the Blade view

Inside the Blade view, you may access the current entry instance using$entry. You can call public methods on this object to access other information that may not be available in variables:

```php
<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    @if ($entry->isLabelHidden())
        This is a new conference.
    @endif
</x-dynamic-component>

```

## #Adding a configuration method to a custom entry class

You may add a public method to the custom entry class that accepts a configuration value, stores it in a protected property, and returns it again from another public method:

```php
use Filament\Infolists\Components\Entry;

class AudioPlayerEntry extends Entry
{
    protected string $view = 'filament.infolists.components.audio-player-entry';
    
    protected ?float $speed = null;

    public function speed(?float $speed): static
    {
        $this->speed = $speed;

        return $this;
    }

    public function getSpeed(): ?float
    {
        return $this->speed;
    }
}

```

Now, in the Blade view for the custom entry, you may access the speed using the$getSpeed()function:

```php
<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry"
>
    {{ $getSpeed() }}
</x-dynamic-component>

```

Any public method that you define on the custom entry class can be accessed in the Blade view as a variable function in this way.

To pass the configuration value to the custom entry class, you may use the public method:

```php
use App\Filament\Infolists\Components\AudioPlayerEntry;

AudioPlayerEntry::make('recording')
    ->speed(0.5)

```

## #Allowing utility injection in a custom entry configuration method

Utility injectionis a powerful feature of Filament that allows users to configure a component using functions that can access various utilities. You can allow utility injection by ensuring that the parameter type and property type of the configuration allows the user to pass aClosure. In the getter method, you should pass the configuration value to the$this->evaluate()method, which will inject utilities into the user’s function if they pass one, or return the value if it is static:

```php
use Closure;
use Filament\Infolists\Components\Entry;

class AudioPlayerEntry extends Entry
{
    protected string $view = 'filament.infolists.components.audio-player-entry';
    
    protected float | Closure | null $speed = null;

    public function speed(float | Closure | null $speed): static
    {
        $this->speed = $speed;

        return $this;
    }

    public function getSpeed(): ?float
    {
        return $this->evaluate($this->speed);
    }
}

```

Now, you can pass a static value or a function to thespeed()method, andinject any utilityas a parameter:

```php
use App\Filament\Infolists\Components\AudioPlayerEntry;

AudioPlayerEntry::make('recording')
    ->speed(fn (Conference $record): float => $record->isGlobal() ? 1 : 0.5)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources  
**Keywords:** read-only, display, view, presentation

*Extracted from Filament v5 Documentation - 2026-01-28*
