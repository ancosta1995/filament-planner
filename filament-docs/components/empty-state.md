# Empty State Blade component

**URL:** https://filamentphp.com/docs/5.x/components/empty-state  
**Section:** components  
**Page:** empty-state  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

## #Introduction

An empty state can be used to communicate that there is no content to display yet, and to guide the user towards the next action. A heading is required:

```php
<x-filament::empty-state>
    <x-slot name="heading">
        No users yet
    </x-slot>
</x-filament::empty-state>

```

## #Adding a description to the empty state

You can add a description below the heading to the empty state by using thedescriptionslot:

```php
<x-filament::empty-state>
    <x-slot name="heading">
        No users yet
    </x-slot>

    <x-slot name="description">
        Get started by creating a new user.
    </x-slot>
</x-filament::empty-state>

```

## #Adding an icon to the empty state

You can add aniconto an empty state by using theiconattribute:

```php
<x-filament::empty-state
    icon="heroicon-o-user"
>
    <x-slot name="heading">
        No users yet
    </x-slot>
</x-filament::empty-state>

```

### #Changing the color of the empty state icon

By default, the color of the empty state icon isprimary. You can change it to begray,danger,info,successorwarningby using theicon-colorattribute:

```php
<x-filament::empty-state
    icon="heroicon-o-user"
    icon-color="info"
>
    <x-slot name="heading">
        No users yet
    </x-slot>
</x-filament::empty-state>

```

### #Changing the size of the empty state icon

By default, the size of the empty state icon is “large”. You can change it to be “small” or “medium” by using theicon-sizeattribute:

```php
<x-filament::empty-state
    icon="heroicon-m-user"
    icon-size="sm"
>
    <x-slot name="heading">
        No users yet
    </x-slot>
</x-filament::empty-state>

<x-filament::empty-state
    icon="heroicon-m-user"
    icon-size="md"
>
    <x-slot name="heading">
        No users yet
    </x-slot>
</x-filament::empty-state>

```

## #Adding footer actions to the empty state

You can add actions below the description by using thefooterslot. This is useful for placing buttons, like the<x-filament::button>component:

```php
<x-filament::empty-state>
    <x-slot name="heading">
        No users yet
    </x-slot>
    
    <x-slot name="footer">
        <x-filament::button icon="heroicon-m-plus">
            Create user
        </x-filament::button>
    </x-slot>
</x-filament::empty-state>

```

## #Removing the empty state container

By default, empty states have a background color, shadow and border. You can remove these styles and just render the content of the empty state without the container using the:containedattribute:

```php
<x-filament::empty-state :contained="false">
    <x-slot name="heading">
        No users yet
    </x-slot>
</x-filament::empty-state>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
