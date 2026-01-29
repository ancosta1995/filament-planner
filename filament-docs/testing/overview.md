# Overview

**URL:** https://filamentphp.com/docs/5.x/testing/overview  
**Section:** testing  
**Page:** overview  
**Priority:** medium  
**AI Context:** Testing Filament applications with PHPUnit/Pest.

---

## #Introduction

All examples in this guide will be written usingPest. To use Pest’s Livewire plugin for testing, you can follow the installation instructions in the Pest documentation on plugins:Livewire plugin for Pest. However, you can easily adapt this to PHPUnit, mostly by switching out thelivewire()function from Pest with theLivewire::test()method.

Since all Filament components are mounted to a Livewire component, we’re just using Livewire testing helpers everywhere. If you’ve never tested Livewire components before, please readthis guidefrom the Livewire docs.

## #Testing guides

Looking for a full example on how to test a panel resource? Check out theTesting resourcessection.

If you would like to learn the different methods available to test tables, check out theTesting tablessection.

If you need to test a schema, which encompasses both forms and infolists, check out theTesting schemassection.

If you would like to test an action, including actions that exist in tables or in schemas, check out theTesting actionssection.

If you would like to test a notification that you have sent, check out theTesting notificationssection.

If you would like to test a custom page in a panel, these are Livewire components with no special behavior, so you should visit thetestingsection of the Livewire documentation.

## #What is a Livewire component when using Filament?

When testing Filament, it is useful to understand which components are Livewire components and which aren’t. With this information, you know which classes to pass to thelivewire()function in Pest or theLivewire::test()method in PHPUnit.

Some examples of Livewire components are:
- Pages in a panel, including page classes in a resource’sPagesdirectory
- Relation managers in a resource
- Widgets


Some examples of classes that are not Livewire components are:
- Resource classes
- Schema components
- Actions


These classes all interact with Livewire, but they are not Livewire components themselves. You can still test them, for example, by calling various methods and using thePest expectation APIto assert the expected behavior. However, the most useful tests will involve Livewire components, since they provide the best end-to-end testing coverage of your users’ experience.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, forms, tables  
**Keywords:** tests, phpunit, pest, quality

*Extracted from Filament v5 Documentation - 2026-01-28*
