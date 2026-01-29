# Section Blade component

**URL:** https://filamentphp.com/docs/5.x/components/section  
**Section:** components  
**Page:** section  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

A section can be used to group content together, with an optional heading:

```php
<x-filament::section>
    <x-slot name="heading">
        User details
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

## #Adding a description to the section

You can add a description below the heading to the section by using thedescriptionslot:

```php
<x-filament::section>
    <x-slot name="heading">
        User details
    </x-slot>

    <x-slot name="description">
        This is all the information we hold about the user.
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

## #Adding an icon to the section header

You can add aniconto a section by using theiconattribute:

```php
<x-filament::section icon="heroicon-o-user">
    <x-slot name="heading">
        User details
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

### #Changing the color of the section icon

By default, the color of the section icon is “gray”. You can change it to bedanger,info,primary,successorwarningby using theicon-colorattribute:

```php
<x-filament::section
    icon="heroicon-o-user"
    icon-color="info"
>
    <x-slot name="heading">
        User details
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

### #Changing the size of the section icon

By default, the size of the section icon is “large”. You can change it to be “small” or “medium” by using theicon-sizeattribute:

```php
<x-filament::section
    icon="heroicon-m-user"
    icon-size="sm"
>
    <x-slot name="heading">
        User details
    </x-slot>

    {{-- Content --}}
</x-filament::section>

<x-filament::section
    icon="heroicon-m-user"
    icon-size="md"
>
    <x-slot name="heading">
        User details
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

## #Adding content to the end of the header

You may render additional content at the end of the header, next to the heading and description, using theafterHeaderslot:

```php
<x-filament::section>
    <x-slot name="heading">
        User details
    </x-slot>

    <x-slot name="afterHeader">
        {{-- Input to select the user's ID --}}
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

## #Making a section collapsible

You can make the content of a section collapsible by using thecollapsibleattribute:

```php
<x-filament::section collapsible>
    <x-slot name="heading">
        User details
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

### #Making a section collapsed by default

You can make a section collapsed by default by using thecollapsedattribute:

```php
<x-filament::section
    collapsible
    collapsed
>
    <x-slot name="heading">
        User details
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

### #Persisting collapsed sections

You can persist whether a section is collapsed in local storage using thepersist-collapsedattribute, so it will remain collapsed when the user refreshes the page. You will also need a uniqueidattribute to identify the section from others, so that each section can persist its own collapse state:

```php
<x-filament::section
    collapsible
    collapsed
    persist-collapsed
    id="user-details"
>
    <x-slot name="heading">
        User details
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

## #Adding the section header aside the content instead of above it

You can change the position of the section header to be aside the content instead of above it by using theasideattribute:

```php
<x-filament::section aside>
    <x-slot name="heading">
        User details
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

### #Positioning the content before the header

You can change the position of the content to be before the header instead of after it by using thecontent-beforeattribute:

```php
<x-filament::section
    aside
    content-before
>
    <x-slot name="heading">
        User details
    </x-slot>

    {{-- Content --}}
</x-filament::section>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
