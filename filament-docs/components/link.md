# Link Blade component

**URL:** https://filamentphp.com/docs/5.x/components/link  
**Section:** components  
**Page:** link  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

The link component is used to render a clickable link that can perform an action:

```php
<x-filament::link :href="route('users.create')">
    New user
</x-filament::link>

```

## #Using a link as a button

By default, a link’s underlying HTML tag is<a>. You can change it to be a<button>tag by using thetagattribute:

```php
<x-filament::link
    wire:click="openNewUserModal"
    tag="button"
>
    New user
</x-filament::link>

```

## #Setting the size of a link

By default, the size of a link is “medium”. You can make it “small”, “large”, “extra large” or “extra extra large” by using thesizeattribute:

```php
<x-filament::link size="sm">
    New user
</x-filament::link>

<x-filament::link size="lg">
    New user
</x-filament::link>

<x-filament::link size="xl">
    New user
</x-filament::link>

<x-filament::link size="2xl">
    New user
</x-filament::link>

```

## #Setting the font weight of a link

By default, the font weight of links issemibold. You can make itthin,extralight,light,normal,medium,bold,extraboldorblackby using theweightattribute:

```php
<x-filament::link weight="thin">
    New user
</x-filament::link>

<x-filament::link weight="extralight">
    New user
</x-filament::link>

<x-filament::link weight="light">
    New user
</x-filament::link>

<x-filament::link weight="normal">
    New user
</x-filament::link>

<x-filament::link weight="medium">
    New user
</x-filament::link>

<x-filament::link weight="semibold">
    New user
</x-filament::link>
   
<x-filament::link weight="bold">
    New user
</x-filament::link>

<x-filament::link weight="black">
    New user
</x-filament::link> 

```

Alternatively, you can pass in a custom CSS class to define the weight:

```php
<x-filament::link weight="md:font-[650]">
    New user
</x-filament::link>

```

## #Changing the color of a link

By default, the color of a link is “primary”. You can change it to bedanger,gray,info,successorwarningby using thecolorattribute:

```php
<x-filament::link color="danger">
    New user
</x-filament::link>

<x-filament::link color="gray">
    New user
</x-filament::link>

<x-filament::link color="info">
    New user
</x-filament::link>

<x-filament::link color="success">
    New user
</x-filament::link>

<x-filament::link color="warning">
    New user
</x-filament::link>

```

## #Adding an icon to a link

You can add aniconto a link by using theiconattribute:

```php
<x-filament::link icon="heroicon-m-sparkles">
    New user
</x-filament::link>

```

You can also change the icon’s position to be after the text instead of before it, using theicon-positionattribute:

```php
<x-filament::link
    icon="heroicon-m-sparkles"
    icon-position="after"
>
    New user
</x-filament::link>

```

## #Adding a tooltip to a link

You can add a tooltip to a link by using thetooltipattribute:

```php
<x-filament::link tooltip="Register a user">
    New user
</x-filament::link>

```

## #Adding a badge to a link

You can render abadgeon top of a link by using thebadgeslot:

```php
<x-filament::link>
    Mark notifications as read

    <x-slot name="badge">
        3
    </x-slot>
</x-filament::link>

```

You canchange the colorof the badge using thebadge-colorattribute:

```php
<x-filament::link badge-color="danger">
    Mark notifications as read

    <x-slot name="badge">
        3
    </x-slot>
</x-filament::link>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
