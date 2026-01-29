# Select Blade component

**URL:** https://filamentphp.com/docs/5.x/components/select  
**Section:** components  
**Page:** select  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

The select component is a wrapper around the native<select>element. It provides a simple interface for selecting a single value from a list of options:

```php
<x-filament::input.wrapper>
    <x-filament::input.select wire:model="status">
        <option value="draft">Draft</option>
        <option value="reviewing">Reviewing</option>
        <option value="published">Published</option>
    </x-filament::input.select>
</x-filament::input.wrapper>

```

To use the select component, you must wrap it in an “input wrapper” component, which provides a border and other elements such as a prefix or suffix. You can learn more about customizing the input wrapper componenthere.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
