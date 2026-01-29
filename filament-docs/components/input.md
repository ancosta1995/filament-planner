# Input Blade component

**URL:** https://filamentphp.com/docs/5.x/components/input  
**Section:** components  
**Page:** input  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

The input component is a wrapper around the native<input>element. It provides a simple interface for entering a single line of text.

```php
<x-filament::input.wrapper>
    <x-filament::input
        type="text"
        wire:model="name"
    />
</x-filament::input.wrapper>

```

To use the input component, you must wrap it in an “input wrapper” component, which provides a border and other elements such as a prefix or suffix. You can learn more about customizing the input wrapper componenthere.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
