# Build a standalone plugin

**URL:** https://filamentphp.com/docs/5.x/plugins/building-a-standalone-plugin  
**Section:** plugins  
**Page:** building-a-standalone-plugin  
**Priority:** low  
**AI Context:** Building and distributing Filament plugins.

---

## #Preface

Please read the docs onpanel plugin developmentand thegetting started guidebefore continuing.

## #Introduction

In this walkthrough, we’ll build a simple plugin that adds a new form component that can be used in forms. This also means it will be available to users in their panels.

You can find the final code for this plugin athttps://github.com/awcodes/headings.

## #Step 1: Create the plugin

First, we’ll create the plugin using the steps outlined in thegetting started guide.

## #Step 2: Clean up

Next, we’ll clean up the plugin to remove the boilerplate code we don’t need. This will seem like a lot, but since this is a simple plugin, we can remove a lot of the boilerplate code.

Remove the following directories and files:
1. bin
2. config
3. database
4. src/Commands
5. src/Facades
6. stubs


Now we can clean up ourcomposer.jsonfile to remove unneeded options.

```php
"autoload": {
    "psr-4": {
        // We can remove the database factories
        "Awcodes\\Headings\\Database\\Factories\\": "database/factories/"
    }
},
"extra": {
    "laravel": {
        // We can remove the facade
        "aliases": {
            "Headings": "Awcodes\\Headings\\Facades\\ClockWidget"
        }
    }
},

```

Normally, Filament recommends that users style their plugins with a custom filament theme, but for the sake of example let’s provide our own stylesheet that can be loaded asynchronously using the newx-loadfeatures in Filament v3. So, let’s update ourpackage.jsonfile to includecssnano,postcss,postcss-cliandpostcss-nestingto build our stylesheet.

```php
{
    "private": true,
    "scripts": {
        "build": "postcss resources/css/index.css -o resources/dist/headings.css"
    },
    "devDependencies": {
        "cssnano": "^6.0.1",
        "postcss": "^8.4.27",
        "postcss-cli": "^10.1.0",
        "postcss-nesting": "^13.0.0"
    }
}

```

Then we need to install our dependencies.

```php
npm install

```

We will also need to update ourpostcss.config.jsfile to configure postcss.

```php
module.exports = {
    plugins: [
        require('postcss-nesting')(),
        require('cssnano')({
            preset: 'default',
        }),
    ],
};

```

You may also remove the testing directories and files, but we’ll leave them in for now, although we won’t be using them for this example, and we highly recommend that you write tests for your plugins.

## #Step 3: Setting up the provider

Now that we have our plugin cleaned up, we can start adding our code. The boilerplate in thesrc/HeadingsServiceProvider.phpfile has a lot going on so, let’s delete everything and start from scratch.

We need to be able to register our stylesheet with the Filament Asset Manager so that we can load it on demand in our Blade view. To do this, we’ll need to add the following to thepackageBootedmethod in our service provider.

Note theloadedOnRequest()method. This is important, because it tells Filament to only load the stylesheet when it’s needed.

```php
namespace Awcodes\Headings;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HeadingsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'headings';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register([
            Css::make('headings', __DIR__ . '/../resources/dist/headings.css')->loadedOnRequest(),
        ], 'awcodes/headings');
    }
}

```

## #Step 4: Creating our component

Next, we’ll need to create our component. Create a new file atsrc/Heading.phpand add the following code.

```php
namespace Awcodes\Headings;

use Closure;
use Filament\Schemas\Components\Component;
use Filament\Support\Colors\Color;
use Filament\Support\Concerns\HasColor;

class Heading extends Component
{
    use HasColor;

    protected string | int $level = 2;

    protected string | Closure $content = '';

    protected string $view = 'headings::heading';

    final public function __construct(string | int $level)
    {
        $this->level($level);
    }

    public static function make(string | int $level): static
    {
        return app(static::class, ['level' => $level]);
    }

    public function content(string | Closure $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function level(string | int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getColor(): array
    {
        return $this->evaluate($this->color) ?? Color::Amber;
    }

    public function getContent(): string
    {
        return $this->evaluate($this->content);
    }

    public function getLevel(): string
    {
        return is_int($this->level) ? 'h' . $this->level : $this->level;
    }
}

```

## #Step 5: Rendering our component

Next, we’ll need to create the view for our component. Create a new file atresources/views/heading.blade.phpand add the following code.

We are using x-load to asynchronously load stylesheet, so it’s only loaded when necessary. You can learn more about this in theCore Conceptssection of the docs.

```php
@php
    $level = $getLevel();
    $color = $getColor();
@endphp

<{{ $level }}
    x-data
    x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('headings', package: 'awcodes/headings'))]"
    {{
        $attributes
            ->class([
                'headings-component',
                match ($color) {
                    'gray' => 'text-gray-600 dark:text-gray-400',
                    default => 'text-custom-500',
                },
            ])
            ->style([
                \Filament\Support\get_color_css_variables($color, [500]) => $color !== 'gray',
            ])
    }}
>
    {{ $getContent() }}
</{{ $level }}>

```

## #Step 6: Adding some styles

Next, let’s provide some custom styling for our field. We’ll add the following toresources/css/index.css. And runnpm run buildto compile our CSS.

```php
.headings-component {
    &:is(h1, h2, h3, h4, h5, h6) {
         font-weight: 700;
         letter-spacing: -.025em;
         line-height: 1.1;
     }

    &h1 {
         font-size: 2rem;
     }

    &h2 {
         font-size: 1.75rem;
     }

    &h3 {
         font-size: 1.5rem;
     }

    &h4 {
         font-size: 1.25rem;
     }

    &h5,
    &h6 {
         font-size: 1rem;
     }
}

```

Then we need to build our stylesheet.

```php
npm run build

```

## #Step 7: Update your README

You’ll want to update yourREADME.mdfile to include instructions on how to install your plugin and any other information you want to share with users, Like how to use it in their projects. For example:

```php
use Awcodes\Headings\Heading;

Heading::make(2)
    ->content('Product Information')
    ->color(Color::Lime),

```

And, that’s it, our users can now install our plugin and use it in their projects.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:**   
**Keywords:** plugin, package, extension

*Extracted from Filament v5 Documentation - 2026-01-28*
