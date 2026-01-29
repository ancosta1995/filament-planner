# Hidden

**URL:** https://filamentphp.com/docs/5.x/forms/hidden  
**Section:** forms  
**Page:** hidden  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The hidden component allows you to create a hidden field in your form that holds a value.

```php
use Filament\Forms\Components\Hidden;

Hidden::make('token')

```

Please be aware that the value of this field is still editable by the user if they decide to use the browser’s developer tools. You should not use this component to store sensitive or read-only information.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
