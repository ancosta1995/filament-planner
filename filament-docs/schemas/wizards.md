# Wizards

**URL:** https://filamentphp.com/docs/5.x/schemas/wizards  
**Section:** schemas  
**Page:** wizards  
**Priority:** medium  
**AI Context:** Layout system for building complex UIs with sections, tabs, wizards.

---

## #Introduction

Similar totabs, you may want to use a multistep wizard to reduce the number of components that are visible at once. These are especially useful if your form has a definite chronological order, in which you want each step to be validated as the user progresses.

```php
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;

Wizard::make([
    Step::make('Order')
        ->schema([
            // ...
        ]),
    Step::make('Delivery')
        ->schema([
            // ...
        ]),
    Step::make('Billing')
        ->schema([
            // ...
        ]),
])

```

TIP

We have different setup instructions if you’re looking to add a wizard to the creation process inside apanel resourceor anaction modal. Following that documentation will ensure that the ability to submit the form is only available on the last step of the wizard.

## #Rendering a submit button on the last step

You may use thesubmitAction()method to render submit button HTML or a view at the end of the wizard, on the last step. This provides a clearer UX than displaying a submit button below the wizard at all times:

```php
use Filament\Schemas\Components\Wizard;
use Illuminate\Support\HtmlString;

Wizard::make([
    // ...
])->submitAction(view('order-form.submit-button'))

Wizard::make([
    // ...
])->submitAction(new HtmlString('<button type="submit">Submit</button>'))

```

Alternatively, you can use the built-in Filament button Blade component:

```php
use Filament\Schemas\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

Wizard::make([
    // ...
])->submitAction(new HtmlString(Blade::render(<<<BLADE
    <x-filament::button
        type="submit"
        size="sm"
    >
        Submit
    </x-filament::button>
BLADE)))

```

You could extract this component to a separate Blade view if you prefer.

## #Setting a step icon

Steps may have anicon, which you can set using theicon()method:

```php
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

Step::make('Order')
    ->icon(Heroicon::ShoppingBag)
    ->schema([
        // ...
    ]),

```

## #Customizing the icon for completed steps

You may customize theiconof a completed step using thecompletedIcon()method:

```php
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

Step::make('Order')
    ->completedIcon(Heroicon::HandThumbUp)
    ->schema([
        // ...
    ]),

```

## #Adding descriptions to steps

You may add a short description after the title of each step using thedescription()method:

```php
use Filament\Schemas\Components\Wizard\Step;

Step::make('Order')
    ->description('Review your basket')
    ->schema([
        // ...
    ]),

```

## #Setting the default active step

You may use thestartOnStep()method to load a specific step in the wizard:

```php
use Filament\Schemas\Components\Wizard;

Wizard::make([
    // ...
])->startOnStep(2)

```

## #Allowing steps to be skipped

If you’d like to allow free navigation, so all steps are skippable, use theskippable()method:

```php
use Filament\Schemas\Components\Wizard;

Wizard::make([
    // ...
])->skippable()

```

Optionally, theskippable()method accepts a boolean value to control if the steps are skippable or not:

```php
use Filament\Schemas\Components\Wizard;

Wizard::make([
    // ...
])->skippable(FeatureFlag::active())

```

## #Persisting the current step in the URL’s query string

By default, the current step is not persisted in the URL’s query string. You can change this behavior using thepersistStepInQueryString()method:

```php
use Filament\Schemas\Components\Wizard;

Wizard::make([
    // ...
])->persistStepInQueryString()

```

When enabled, the current step is persisted in the URL’s query string using thestepkey. You can change this key by passing it to thepersistStepInQueryString()method:

```php
use Filament\Schemas\Components\Wizard;

Wizard::make([
    // ...
])->persistStepInQueryString('wizard-step')

```

## #Step lifecycle hooks

You may use theafterValidation()andbeforeValidation()methods to run code before and after validation occurs on the step:

```php
use Filament\Schemas\Components\Wizard\Step;

Step::make('Order')
    ->afterValidation(function () {
        // ...
    })
    ->beforeValidation(function () {
        // ...
    })
    ->schema([
        // ...
    ]),

```

### #Preventing the next step from being loaded

InsideafterValidation()orbeforeValidation(), you may throwFilament\Support\Exceptions\Halt, which will prevent the wizard from loading the next step:

```php
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Exceptions\Halt;

Step::make('Order')
    ->afterValidation(function () {
        // ...

        if (true) {
            throw new Halt();
        }
    })
    ->schema([
        // ...
    ]),

```

## #Using grid columns within a step

You may use thecolumns()method to customize thegridwithin the step:

```php
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;

Wizard::make([
    Step::make('Order')
        ->columns(2)
        ->schema([
            // ...
        ]),
    // ...
])

```

## #Customizing the wizard action objects

This component uses action objects for easy customization of buttons within it. You can customize these buttons by passing a function to an action registration method. The function has access to the$actionobject, which you can use tocustomize it. The following methods are available to customize the actions:
- nextAction()
- previousAction()


Here is an example of how you might customize an action:

```php
use Filament\Actions\Action;
use Filament\Schemas\Components\Wizard;

Wizard::make([
    // ...
])
    ->nextAction(
        fn (Action $action) => $action->label('Next step'),
    )

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, infolists  
**Keywords:** layout, structure, organization, ui

*Extracted from Filament v5 Documentation - 2026-01-28*
