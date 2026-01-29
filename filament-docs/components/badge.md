# Badge Blade component

**URL:** https://filamentphp.com/docs/5.x/components/badge  
**Section:** components  
**Page:** badge  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

The badge component is used to render a small box with some text inside:

```php
<x-filament::badge>
    New
</x-filament::badge>

```

## #Setting the size of a badge

By default, the size of a badge is “medium”. You can make it “extra small” or “small” by using thesizeattribute:

```php
<x-filament::badge size="xs">
    New
</x-filament::badge>

<x-filament::badge size="sm">
    New
</x-filament::badge>

```

## #Changing the color of the badge

By default, the color of a badge is “primary”. You can change it to bedanger,gray,info,successorwarningby using thecolorattribute:

```php
<x-filament::badge color="danger">
    New
</x-filament::badge>

<x-filament::badge color="gray">
    New
</x-filament::badge>

<x-filament::badge color="info">
    New
</x-filament::badge>

<x-filament::badge color="success">
    New
</x-filament::badge>

<x-filament::badge color="warning">
    New
</x-filament::badge>

```

## #Adding an icon to a badge

You can add aniconto a badge by using theiconattribute:

```php
<x-filament::badge icon="heroicon-m-sparkles">
    New
</x-filament::badge>

```

You can also change the icon’s position to be after the text instead of before it, using theicon-positionattribute:

```php
<x-filament::badge
    icon="heroicon-m-sparkles"
    icon-position="after"
>
    New
</x-filament::badge>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
