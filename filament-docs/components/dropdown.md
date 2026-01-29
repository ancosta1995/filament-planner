# Dropdown Blade component

**URL:** https://filamentphp.com/docs/5.x/components/dropdown  
**Section:** components  
**Page:** dropdown  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

The dropdown component allows you to render a dropdown menu with a button that triggers it:

```php
<x-filament::dropdown>
    <x-slot name="trigger">
        <x-filament::button>
            More actions
        </x-filament::button>
    </x-slot>
    
    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item wire:click="openViewModal">
            View
        </x-filament::dropdown.list.item>
        
        <x-filament::dropdown.list.item wire:click="openEditModal">
            Edit
        </x-filament::dropdown.list.item>
        
        <x-filament::dropdown.list.item wire:click="openDeleteModal">
            Delete
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>

```

## #Using a dropdown item as an anchor link

By default, a dropdown item’s underlying HTML tag is<button>. You can change it to be an<a>tag by using thetagattribute:

```php
<x-filament::dropdown.list.item
    href="https://filamentphp.com"
    tag="a"
>
    Filament
</x-filament::dropdown.list.item>

```

## #Changing the color of a dropdown item

By default, the color of a dropdown item is “gray”. You can change it to bedanger,info,primary,successorwarningby using thecolorattribute:

```php
<x-filament::dropdown.list.item color="danger">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item color="info">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item color="primary">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item color="success">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item color="warning">
    Edit
</x-filament::dropdown.list.item>

```

## #Adding an icon to a dropdown item

You can add aniconto a dropdown item by using theiconattribute:

```php
<x-filament::dropdown.list.item icon="heroicon-m-pencil">
    Edit
</x-filament::dropdown.list.item>

```

### #Changing the icon color of a dropdown item

By default, the icon color uses thesame color as the item itself. You can override it to bedanger,info,primary,successorwarningby using theicon-colorattribute:

```php
<x-filament::dropdown.list.item icon="heroicon-m-pencil" icon-color="danger">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item icon="heroicon-m-pencil" icon-color="info">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item icon="heroicon-m-pencil" icon-color="primary">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item icon="heroicon-m-pencil" icon-color="success">
    Edit
</x-filament::dropdown.list.item>

<x-filament::dropdown.list.item icon="heroicon-m-pencil" icon-color="warning">
    Edit
</x-filament::dropdown.list.item>

```

## #Adding an image to a dropdown item

You can add a circular image to a dropdown item by using theimageattribute:

```php
<x-filament::dropdown.list.item image="https://filamentphp.com/dan.jpg">
    Dan Harrin
</x-filament::dropdown.list.item>

```

## #Adding a badge to a dropdown item

You can render abadgeon top of a dropdown item by using thebadgeslot:

```php
<x-filament::dropdown.list.item>
    Mark notifications as read
    
    <x-slot name="badge">
        3
    </x-slot>
</x-filament::dropdown.list.item>

```

You canchange the colorof the badge using thebadge-colorattribute:

```php
<x-filament::dropdown.list.item badge-color="danger">
    Mark notifications as read
    
    <x-slot name="badge">
        3
    </x-slot>
</x-filament::dropdown.list.item>

```

## #Setting the placement of a dropdown

The dropdown may be positioned relative to the trigger button by using theplacementattribute:

```php
<x-filament::dropdown placement="top-start">
    {{-- Dropdown items --}}
</x-filament::dropdown>

```

## #Setting the width of a dropdown

The dropdown may be set to a width by using thewidthattribute. Options correspond toTailwind’s max-width scale. The options arexs,sm,md,lg,xl,2xl,3xl,4xl,5xl,6xland7xl:

```php
<x-filament::dropdown width="xs">
    {{-- Dropdown items --}}
</x-filament::dropdown>

```

## #Controlling the maximum height of a dropdown

The dropdown content can have a maximum height using themax-heightattribute, so that it scrolls. You can pass aCSS length:

```php
<x-filament::dropdown max-height="400px">
    {{-- Dropdown items --}}
</x-filament::dropdown>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
