# AI-assisted development

**URL:** https://filamentphp.com/docs/5.x/introduction/ai  
**Section:** introduction  
**Page:** ai  
**Priority:** high  
**AI Context:** Fundamental concepts, installation, and setup. Start here for new users.

---

## #Introduction

NOTE

This page is inspired by Laravel’sAI Assisted Development documentation. Laravel Boost is developed by the Laravel team, and you can find out more about it in their official docs, alongside other information about building Laravel projects with AI assistance.

AI coding agents likeClaude Code,Cursor, andGitHub Copilotcan significantly accelerate your Filament development. Filament includes guidelines forLaravel Boostthat teach AI agents how to write idiomatic Filament code and follow framework conventions. Laravel Boost even allows your agent to search the Filament documentation for answers when it encounters unfamiliar requirements.

## #Installing Laravel Boost

Install Boost as a development dependency:

```php
composer require laravel/boost --dev

```

Then run the interactive installer and selectFilamentwhen prompted:

```php
php artisan boost:install

```

The installer will detect your IDE and AI agents, generating the necessary configuration files. To verify installation, check yourAGENTS.md,CLAUDE.md, or similar file for a newFilamentsection.

For more information about Laravel Boost, including available tools, documentation search, and IDE integration, see theLaravel AI documentation.

## #Filament Blueprint

The guidelines included with Boost are designed primarily forimplementing agents: they help agents write correct Filament code once they know what to build. However, the quality of AI-generated code depends heavily on the quality of the plan. When an implementing agent has a clear, detailed specification, it can focus entirely on writing correct code rather than guessing at requirements or making assumptions about your intent.

For complex features, you may find that agents struggle with the planning phase: choosing the right components, structuring relationships, and anticipating edge cases. A vague plan leads to vague code, and you end up spending more time correcting the agent than you saved by using it.

Filament Blueprint is a premium extension that helps AI agents produce accurate, detailed implementation plans for Filament.It’s compatible with Filament v4 and above.

Blueprint bridges the gap between what you want and what AI agents build. Instead of hoping an agent understands Filament’s conventions, Blueprint provides structured planning guidelines that produce unambiguous specification documents.

A blueprint specifies everything an implementing agent needs:
- Models: Attributes, casts, relationships, and enums with exact syntax
- Resources: Full namespaces, scaffold commands, and configuration
- Forms: Field components, validation rules, and layout structure
- Tables: Columns, filters, actions, and sorting behavior
- Authorization: Plain-English policy rules that translate directly to code
- Testing: What to test and how to verify it works
- More: Reactive fields, wizards, imports/exports, bulk actions, widgets, multi-tenancy, and more


The guidelines cover details that agents commonly get wrong, like namespaces, method names, component selection, and nested layout calculations, so the implementing agent can write correct code on the first try.

The planning guidelines are designed for planning agents only, they shouldn’t consume the implementing agent’s context window. The planning agent copies all necessary details (namespaces, documentation URLs to fetch, exact method syntax) into the blueprint itself, so the implementing agent has everything it needs without loading the guidelines.

If you’re interested in an example of a plan that Claude Opus 4.5 can write with and without Blueprint, visit theBlueprint Plan Examplesection.

### #Installing Blueprint

TIP

To celebrate the launch of Blueprint, we’re offering a20% discounton all Blueprint licenses using the codeBPLAUNCH20atcheckout.

Blueprint is compatible with Filament v4 and above.

Once you havepurchased a license for Blueprint, install it via Composer:

```php
composer config repositories.filament composer https://packages.filamentphp.com/composer
composer config --auth http-basic.packages.filamentphp.com "YOUR_EMAIL_ADDRESS" "YOUR_LICENSE_KEY"
composer require filament/blueprint --dev

```

Then run the Boost installer and selectFilament Blueprintwhen prompted:

```php
php artisan boost:install

```

To verify installation, check yourAGENTS.md,CLAUDE.md, or similar file for a newFilament Blueprintsection.

### #Using Blueprint

To create a blueprint, enableplanning modein your AI agent and ask it to create a Filament Blueprint for your feature:

```php
Create a Filament Blueprint for an order management system.

Orders belong to customers and have many order items. Each order has a status
(pending, confirmed, shipped, delivered, cancelled), shipping address, and
optional notes. Order items reference products with quantity and unit price.

I need to search orders by customer name and filter by status and date range.
The order form should calculate line totals automatically as items are added.
Only admins can delete orders, and orders can only be cancelled if not yet shipped.

```

The agent will produce a detailed specification document ready for direct implementation.

### #Blueprint Plan Example

The following prompt was used with Claude Opus 4.5 in planning mode using Claude Code CLI:

```php
Produce an implementation plan for a Filament v4 application. The application is
a SaaS invoicing system with the following capabilities:

- Manage customers
- Manage products
- Create and edit invoices
- Add line items to invoices
- Send invoices to customers
- Record and track payments
  
The plan should:
- Describe the primary user flows end to end (for example: creating an invoice,
  sending it, recording a payment).
- Map each domain concept and flow to concrete Filament primitives (Resources,
  Relation Managers, Pages, Actions).
- Identify state transitions (such as draft → sent → paid) and the Actions that
  trigger them.

```

You can read a plan written for this promptwithout Blueprintvs.with Blueprint, and compare the level of detail and thoughtfulness. You could have a go at passing these plans to your AI agent of choice in implementation mode to see how they perform!

NOTE

When using Blueprint,Using Filament Blueprintwas added at the start of the prompt.

### #Reporting issues with Blueprint

If you encounter any issues or have suggestions for improving Filament Blueprint, please open an issue or discussion on theFilament Blueprint Issues GitHub repository. If you have account or purchase-related questions, please email[email protected].

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** getting-started  
**Keywords:** installation, setup, basics, getting started

*Extracted from Filament v5 Documentation - 2026-01-28*
