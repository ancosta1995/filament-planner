# Code editor

**URL:** https://filamentphp.com/docs/5.x/forms/code-editor  
**Section:** forms  
**Page:** code-editor  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The code editor component allows you to write code in a textarea with line numbers. By default, no syntax highlighting is applied.

```php
use Filament\Forms\Components\CodeEditor;

CodeEditor::make('code')

```

## #Using language syntax highlighting

You may change the language syntax highlighting of the code editor using thelanguage()method. The editor supports the following languages:
- C++
- CSS
- Go
- HTML
- Java
- JavaScript
- JSON
- Markdown
- PHP
- Python
- SQL
- XML
- YAML


You can open theFilament\Forms\Components\CodeEditor\Enums\Languageenum class to see this list. To switch to using JavaScript syntax highlighting, you can use theLanguage::JavaScriptenum value:

```php
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;

CodeEditor::make('code')
    ->language(Language::JavaScript)

```

## #Allowing lines to wrap

By default, long lines in the code editor will create a horizontal scrollbar. If you would like to allow long lines to wrap instead, you may use thewrap()method:

```php
use Filament\Forms\Components\CodeEditor;

CodeEditor::make('code')
    ->wrap()

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
