# Button Blade component

**URL:** https://filamentphp.com/docs/5.x/components/button  
**Section:** components  
**Page:** button  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

The button component is used to render a clickable button that can perform an action:

```php
<x-filament::button wire:click="openNewUserModal">
    New user
</x-filament::button>

```

## #Using a button as an anchor link

By default, a button’s underlying HTML tag is<button>. You can change it to be an<a>tag by using thetagattribute:

```php
<x-filament::button
    href="https://filamentphp.com"
    tag="a"
>
    Filament
</x-filament::button>

```

## #Setting the size of a button

By default, the size of a button is “medium”. You can make it “extra small”, “small”, “large” or “extra large” by using thesizeattribute:

```php
<x-filament::button size="xs">
    New user
</x-filament::button>

<x-filament::button size="sm">
    New user
</x-filament::button>

<x-filament::button size="lg">
    New user
</x-filament::button>

<x-filament::button size="xl">
    New user
</x-filament::button>

```

## #Changing the color of a button

By default, the color of a button is “primary”. You can change it to bedanger,gray,info,successorwarningby using thecolorattribute:

```php
<x-filament::button color="danger">
    New user
</x-filament::button>

<x-filament::button color="gray">
    New user
</x-filament::button>

<x-filament::button color="info">
    New user
</x-filament::button>

<x-filament::button color="success">
    New user
</x-filament::button>

<x-filament::button color="warning">
    New user
</x-filament::button>

```

## #Adding an icon to a button

You can add aniconto a button by using theiconattribute:

```php
<x-filament::button icon="heroicon-m-sparkles">
    New user
</x-filament::button>

```

You can also change the icon’s position to be after the text instead of before it, using theicon-positionattribute:

```php
<x-filament::button
    icon="heroicon-m-sparkles"
    icon-position="after"
>
    New user
</x-filament::button>

```

## #Making a button outlined

You can make a button use an “outlined” design using theoutlinedattribute:

```php
<x-filament::button outlined>
    New user
</x-filament::button>

```

## #Adding a tooltip to a button

You can add a tooltip to a button by using thetooltipattribute:

```php
<x-filament::button tooltip="Register a user">
    New user
</x-filament::button>

```

## #Adding a badge to a button

You can render abadgeon top of a button by using thebadgeslot:

```php
<x-filament::button>
    Mark notifications as read
    
    <x-slot name="badge">
        3
    </x-slot>
</x-filament::button>

```

You canchange the colorof the badge using thebadge-colorattribute:

```php
<x-filament::button badge-color="danger">
    Mark notifications as read
    
    <x-slot name="badge">
        3
    </x-slot>
</x-filament::button>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
