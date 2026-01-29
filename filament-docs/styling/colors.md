# Colors

**URL:** https://filamentphp.com/docs/5.x/styling/colors  
**Section:** styling  
**Page:** colors  
**Priority:** low  
**AI Context:** Customize appearance with CSS and theming.

---

## #Introduction

Filament uses CSS variables to define its color palette. These CSS variables are mapped to Tailwind classes in the preset file that you load when installing Filament. The reason why Filament uses CSS variables is that it allows the framework to pass a color palette from PHP via a<style>element that gets rendered as part of the@filamentStylesBlade directive.

By default, Filament’s Tailwind preset file ships with 6 colors:
- primary, which isTailwind’sambercolorby default
- success, which isTailwind’sgreencolorby default
- warning, which isTailwind’sambercolorby default
- danger, which isTailwind’sredcolorby default
- info, which isTailwind’sbluecolorby default
- gray, which isTailwind’szinccolorby default


You canlearn how to change these colors and register new ones.

## #How to pass a color to Filament

A registered “color” in Filament is not just one shade. In fact, it is an entire color palette made of11 shades:50,100,200,300,400,500,600,700,800,900, and950. When you use a color in Filament, the framework will decide which shade to use based on the context. For example, it might use the600shade for a component’s background,500when it is hovered, and400for its border. If the user has dark mode enabled, it might use700,800, or900instead.

On one hand, this means that you can specify a color in Filament without having to worry about the exact shade to use, or to specify a shade for each part of the component. Filament takes care of selecting a shade that should create an accessible contrast with other elements where possible.

To customize the color that something is in Filament, you can use its name. For example, if you wanted to use thesuccesscolor, you could pass it to a color method of a PHP component like so:

```php
use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;

Action::make('proceed')
    ->color('success')
    
Toggle::make('is_active')
    ->onColor('success')

```

If you would like to use a color in aBlade component, you can pass it as an attribute:

```php
<x-filament::badge color="success">
    Active
</x-filament::badge>

```

## #Customizing the default colors

From a service provider’sboot()method, or middleware, you can call theFilamentColor::register()method, which you can use to customize which colors Filament uses for UI elements.

There are 6 default colors that are used throughout Filament that you are able to customize:

```php
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

FilamentColor::register([
    'danger' => Color::Red,
    'gray' => Color::Zinc,
    'info' => Color::Blue,
    'primary' => Color::Amber,
    'success' => Color::Green,
    'warning' => Color::Amber,
]);

```

TheColorclass contains everyTailwind CSS colorto choose from.

You can also pass in a function toregister()which will only get called when the app is getting rendered. This is useful if you are callingregister()from a service provider, and want to access objects like the currently authenticated user, which are initialized later in middleware.

### #Registering extra colors

You may register a new color to use in any Filament component by passing it to theFilamentColor::register()method, with its name as the key in the array:

```php
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

FilamentColor::register([
    'secondary' => Color::Indigo,
]);

```

You will now be able to usesecondaryas a color in any Filament component.

## #Using a non-Tailwind color

You can use custom colors that are not included in theTailwind CSS colorpalette by passing an array of color shades from50to950in OKLCH format:

```php
use Filament\Support\Facades\FilamentColor;

FilamentColor::register([
    'danger' => [
        50 => 'oklch(0.969 0.015 12.422)',
        100 => 'oklch(0.941 0.03 12.58)',
        200 => 'oklch(0.892 0.058 10.001)',
        300 => 'oklch(0.81 0.117 11.638)',
        400 => 'oklch(0.712 0.194 13.428)',
        500 => 'oklch(0.645 0.246 16.439)',
        600 => 'oklch(0.586 0.253 17.585)',
        700 => 'oklch(0.514 0.222 16.935)',
        800 => 'oklch(0.455 0.188 13.697)',
        900 => 'oklch(0.41 0.159 10.272)',
        950 => 'oklch(0.271 0.105 12.094)',
    ],
]);

```

### #Generating a custom color palette

If you want us to attempt to generate a palette for you based on a singular hex or RGB value, you can pass that in:

```php
use Filament\Support\Facades\FilamentColor;

FilamentColor::register([
    'danger' => '#ff0000',
]);

FilamentColor::register([
    'danger' => 'rgb(255, 0, 0)',
]);

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:**   
**Keywords:** theme, css, design, appearance

*Extracted from Filament v5 Documentation - 2026-01-28*
