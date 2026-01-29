# Rendering a widget in a Blade view

**URL:** https://filamentphp.com/docs/5.x/components/widget  
**Section:** components  
**Page:** widget  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

NOTE

Before proceeding, make surefilament/widgetsis installed in your project. You can check by running:

```php
composer show filament/widgets

```

If it’s not installed, consult theinstallation guideand configure theindividual componentsaccording to the instructions.

## #Creating a widget

Use themake:filament-widgetcommand to generate a new widget. For details on customization and usage, see thewidgets section.

## #Adding the widget

Since widgets are Livewire components, you can easily render a widget in any Blade view using the@livewiredirective:

```php
<div>
    @livewire(\App\Livewire\Dashboard\PostsChart::class)
</div>

```

NOTE

If you’re using atable widget, make sure to installfilament/tablesas well.Refer to theinstallation guideand follow the steps to configure theindividual componentsproperly.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
