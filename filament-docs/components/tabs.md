# Tabs Blade component

**URL:** https://filamentphp.com/docs/5.x/components/tabs  
**Section:** components  
**Page:** tabs  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

The tabs component allows you to render a set of tabs, which can be used to toggle between multiple sections of content:

```php
<x-filament::tabs label="Content tabs">
    <x-filament::tabs.item>
        Tab 1
    </x-filament::tabs.item>

    <x-filament::tabs.item>
        Tab 2
    </x-filament::tabs.item>

    <x-filament::tabs.item>
        Tab 3
    </x-filament::tabs.item>
</x-filament::tabs>

```

## #Triggering the active state of the tab

By default, tabs do not appear “active”. To make a tab appear active, you can use theactiveattribute:

```php
<x-filament::tabs>
    <x-filament::tabs.item active>
        Tab 1
    </x-filament::tabs.item>

    {{-- Other tabs --}}
</x-filament::tabs>

```

You can also use theactiveattribute to make a tab appear active conditionally:

```php
<x-filament::tabs>
    <x-filament::tabs.item
        :active="$activeTab === 'tab1'"
        wire:click="$set('activeTab', 'tab1')"
    >
        Tab 1
    </x-filament::tabs.item>

    {{-- Other tabs --}}
</x-filament::tabs>

```

Or you can use thealpine-activeattribute to make a tab appear active conditionally using Alpine.js:

```php
<x-filament::tabs x-data="{ activeTab: 'tab1' }">
    <x-filament::tabs.item
        alpine-active="activeTab === 'tab1'"
        x-on:click="activeTab = 'tab1'"
    >
        Tab 1
    </x-filament::tabs.item>

    {{-- Other tabs --}}
</x-filament::tabs>

```

## #Setting a tab icon

Tabs may have anicon, which you can set using theiconattribute:

```php
<x-filament::tabs>
    <x-filament::tabs.item icon="heroicon-m-bell">
        Notifications
    </x-filament::tabs.item>

    {{-- Other tabs --}}
</x-filament::tabs>

```

### #Setting the tab icon position

The icon of the tab may be positioned before or after the label using theicon-positionattribute:

```php
<x-filament::tabs>
    <x-filament::tabs.item
        icon="heroicon-m-bell"
        icon-position="after"
    >
        Notifications
    </x-filament::tabs.item>

    {{-- Other tabs --}}
</x-filament::tabs>

```

## #Setting a tab badge

Tabs may have abadge, which you can set using thebadgeslot:

```php
<x-filament::tabs>
    <x-filament::tabs.item>
        Notifications

        <x-slot name="badge">
            5
        </x-slot>
    </x-filament::tabs.item>

    {{-- Other tabs --}}
</x-filament::tabs>

```

## #Using a tab as an anchor link

By default, a tab’s underlying HTML tag is<button>. You can change it to be an<a>tag by using thetagattribute:

```php
<x-filament::tabs>
    <x-filament::tabs.item
        :href="route('notifications')"
        tag="a"
    >
        Notifications
    </x-filament::tabs.item>

    {{-- Other tabs --}}
</x-filament::tabs>

```

## #Using vertical tabs

You can render the tabs vertically by using theverticalattribute:

```php
<x-filament::tabs vertical>
    <x-filament::tabs.item>
        Tab 1
    </x-filament::tabs.item>

    <x-filament::tabs.item>
        Tab 2
    </x-filament::tabs.item>

    <x-filament::tabs.item>
        Tab 3
    </x-filament::tabs.item>
</x-filament::tabs>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
