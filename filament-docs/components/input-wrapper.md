# Input wrapper Blade component

**URL:** https://filamentphp.com/docs/5.x/components/input-wrapper  
**Section:** components  
**Page:** input-wrapper  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

The input wrapper component should be used as a wrapper around theinputorselectcomponents. It provides a border and other elements such as a prefix or suffix.

```php
<x-filament::input.wrapper>
    <x-filament::input
        type="text"
        wire:model="name"
    />
</x-filament::input.wrapper>

<x-filament::input.wrapper>
    <x-filament::input.select wire:model="status">
        <option value="draft">Draft</option>
        <option value="reviewing">Reviewing</option>
        <option value="published">Published</option>
    </x-filament::input.select>
</x-filament::input.wrapper>

```

## #Triggering the error state of the input

The component has special styling that you can use if it is invalid. To trigger this styling, you can use either Blade or Alpine.js.

To trigger the error state using Blade, you can pass thevalidattribute to the component, which contains either true or false based on if the input is valid or not:

```php
<x-filament::input.wrapper :valid="! $errors->has('name')">
    <x-filament::input
        type="text"
        wire:model="name"
    />
</x-filament::input.wrapper>

```

Alternatively, you can use an Alpine.js expression to trigger the error state, based on if it evaluates totrueorfalse:

```php
<div x-data="{ errors: ['name'] }">
    <x-filament::input.wrapper alpine-valid="! errors.includes('name')">
        <x-filament::input
            type="text"
            wire:model="name"
        />
    </x-filament::input.wrapper>
</div>

```

## #Disabling the input

To disable the input, you must also pass thedisabledattribute to the wrapper component:

```php
<x-filament::input.wrapper disabled>
    <x-filament::input
        type="text"
        wire:model="name"
        disabled
    />
</x-filament::input.wrapper>

```

## #Adding affix text aside the input

You may place text before and after the input using theprefixandsuffixslots:

```php
<x-filament::input.wrapper>
    <x-slot name="prefix">
        https://
    </x-slot>

    <x-filament::input
        type="text"
        wire:model="domain"
    />

    <x-slot name="suffix">
        .com
    </x-slot>
</x-filament::input.wrapper>

```

### #Using icons as affixes

You may place aniconbefore and after the input using theprefix-iconandsuffix-iconattributes:

```php
<x-filament::input.wrapper suffix-icon="heroicon-m-globe-alt">
    <x-filament::input
        type="url"
        wire:model="domain"
    />
</x-filament::input.wrapper>

```

#### #Setting the affix icon’s color

Affix icons are gray by default, but you may set a different color using theprefix-icon-colorandaffix-icon-colorattributes:

```php
<x-filament::input.wrapper
    suffix-icon="heroicon-m-check-circle"
    suffix-icon-color="success"
>
    <x-filament::input
        type="url"
        wire:model="domain"
    />
</x-filament::input.wrapper>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
