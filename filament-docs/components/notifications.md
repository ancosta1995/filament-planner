# Rendering notifications outside of a panel

**URL:** https://filamentphp.com/docs/5.x/components/notifications  
**Section:** components  
**Page:** notifications  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

NOTE

Before proceeding, make surefilament/notificationsis installed in your project. You can check by running:

```php
composer show filament/notifications

```

If it’s not installed, consult theinstallation guideand configure theindividual componentsaccording to the instructions.

## #Introduction

To render notifications in your app, make sure thenotificationsLivewire component is rendered in your layout:

```php
<div>
    @livewire('notifications')
</div>

```

Now, whensending a notificationfrom a Livewire request, it will appear for the user.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
