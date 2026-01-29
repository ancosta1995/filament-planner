# Checkbox Blade component

**URL:** https://filamentphp.com/docs/5.x/components/checkbox  
**Section:** components  
**Page:** checkbox  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

You can use the checkbox component to render a checkbox input that can be used to toggle a boolean value:

```php
<label>
    <x-filament::input.checkbox wire:model="isAdmin" />

    <span>
        Is Admin
    </span>
</label>

```

## #Triggering the error state of the checkbox

The checkbox has special styling that you can use if it is invalid. To trigger this styling, you can use either Blade or Alpine.js.

To trigger the error state using Blade, you can pass thevalidattribute to the component, which contains either true or false based on if the checkbox is valid or not:

```php
<x-filament::input.checkbox
    wire:model="isAdmin"
    :valid="! $errors->has('isAdmin')"
/>

```

Alternatively, you can use an Alpine.js expression to trigger the error state, based on if it evaluates totrueorfalse:

```php
<div x-data="{ errors: ['isAdmin'] }">
    <x-filament::input.checkbox
        x-model="isAdmin"
        alpine-valid="! errors.includes('isAdmin')"
    />
</div>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
