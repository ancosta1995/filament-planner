# View action

**URL:** https://filamentphp.com/docs/5.x/actions/view  
**Section:** actions  
**Page:** view  
**Priority:** high  
**AI Context:** Interactive buttons with modals for user actions.

---

## #Introduction

Filament includes an action that is able to view Eloquent records. When the trigger button is clicked, a modal will open with information inside. Filament uses form fields to structure this information. All form fields are disabled, so they are not editable by the user. You may use it like so:

```php
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;

ViewAction::make()
    ->schema([
        TextInput::make('title')
            ->required()
            ->maxLength(255),
        // ...
    ])

```

## #Customizing data before filling the form

You may wish to modify the data from a record before it is filled into the form. To do this, you may use themutateRecordDataUsing()method to modify the$dataarray, and return the modified version before it is filled into the form:

```php
use Filament\Actions\ViewAction;

ViewAction::make()
    ->mutateRecordDataUsing(function (array $data): array {
        $data['user_id'] = auth()->id();

        return $data;
    })

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, tables, forms  
**Keywords:** buttons, modals, interactions, crud operations

*Extracted from Filament v5 Documentation - 2026-01-28*
