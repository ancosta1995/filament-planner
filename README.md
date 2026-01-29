# Filament Planner 🚀

**AI-Powered Code Generation Engine for Filament v5**

Filament Planner is a "Blueprint Engine" designed to be the perfect bridge between AI Agents (like Cursor, Windsurf, Antigravity) and your Filament v5 codebase.

Instead of letting AI hallucinate invalid PHP syntax or deprecated methods, Filament Planner provides a strict **JSON Schema**. Your AI Agent generates the Plan (JSON), and Filament Planner generates the Code (PHP).

## ⚡ Why use this?

- **Zero Halucinations**: The Generator logic is deterministic. PHP code is always valid.
- **Agent-First Design**: Includes a `SCHEMA.md` specifically crafted to teach AI Agents how to use this tool.
- **Context Aware**: Built to work with local `filament-docs` to ensure compliance with the latest Filament versions.

## 📦 Installation

Since this package is currently in **Alpha** and hosted on GitHub (not Packagist), you need to configure your project to find it.

1.  Open your application's `composer.json`.
2.  Add the `repositories` key (or append to it):

    ```json
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/ancosta1995/filament-planner"
        }
    ]
    ```

3.  Run the require command:

    ```bash
    composer require ancosta.dev/filament-planner:dev-main
    ```

4.  (Optional) If auto-discovery fails, add the ServiceProvider to `bootstrap/providers.php` or `config/app.php`:

    ```php
    AncostaDev\FilamentPlanner\FilamentPlannerServiceProvider::class,
    ```

## 🤖 How to Use (Code with AI)

1.  **Install the package** in your Laravel application.
2.  **Open your AI Chat** (Cursor, implementation, etc.).
3.  **Paste the following System Instruction**:

> "You are an expert Filament Developer. When I ask for code, DO NOT generate PHP directly. Instead, consult the `vendor/ancosta/filament-planner/SCHEMA.md` file, generate a strict JSON Blueprint that solves my request, and then execute `php artisan filament:build-plan path/to/plan.json`."

4.  **Ask for what you want**:
    > "Create a CRM Dashboard with Customer and Order resources."

5.  **Watch the magic happen**. The Agent will create all the files with correct imports, methods, and configurations.

## 🛠️ Manual Usage

You can also write plans manually if you prefer JSON over PHP classes.

1.  Create a file `my-plan.json`:
    ```json
    {
      "resources": [
        {
          "name": "CategoryResource",
          "model": "App\\Models\\Category",
          "form": { "schema": [{ "type": "TextInput", "name": "name", "required": true }] },
          "table": { "columns": [{ "type": "TextColumn", "name": "name" }] }
        }
      ]
    }
    ```

2.  Run the command:
    ```bash
    php artisan filament:build-plan my-plan.json
    ```

## 📝 The Schema

See [SCHEMA.md](SCHEMA.md) for the full definition of available components and options supported by the current version of the planner.

## 🤝 Contributing

This is an experimental project managed by **ancosta1995**. Feedback and PRs are welcome!
