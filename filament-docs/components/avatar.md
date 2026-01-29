# Avatar Blade component

**URL:** https://filamentphp.com/docs/5.x/components/avatar  
**Section:** components  
**Page:** avatar  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

The avatar component is used to render a circular or square image, often used to represent a user or entity as their “profile picture”:

```php
<x-filament::avatar
    src="https://filamentphp.com/dan.jpg"
    alt="Dan Harrin"
/>

```

## #Setting the rounding of an avatar

Avatars are fully rounded by default, but you may make them square by setting thecircularattribute tofalse:

```php
<x-filament::avatar
    src="https://filamentphp.com/dan.jpg"
    alt="Dan Harrin"
    :circular="false"
/>

```

## #Setting the size of an avatar

By default, the avatar will be “medium” size. You can set the size to eithersm,md, orlgusing thesizeattribute:

```php
<x-filament::avatar
    src="https://filamentphp.com/dan.jpg"
    alt="Dan Harrin"
    size="lg"
/>

```

You can also pass your own custom size classes into thesizeattribute:

```php
<x-filament::avatar
    src="https://filamentphp.com/dan.jpg"
    alt="Dan Harrin"
    size="w-12 h-12"
/>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
