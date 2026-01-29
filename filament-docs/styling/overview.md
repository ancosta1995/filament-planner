# Overview

**URL:** https://filamentphp.com/docs/5.x/styling/overview  
**Section:** styling  
**Page:** overview  
**Priority:** low  
**AI Context:** Customize appearance with CSS and theming.

---

## #Changing the colors

In theconfiguration, you can easily change the colors that are used. Filament ships with 6 predefined colors that are used everywhere within the framework. They are customizable as follows:

```php
use Filament\Panel;
use Filament\Support\Colors\Color;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->colors([
            'danger' => Color::Rose,
            'gray' => Color::Gray,
            'info' => Color::Blue,
            'primary' => Color::Indigo,
            'success' => Color::Emerald,
            'warning' => Color::Orange,
        ]);
}

```

TheFilament\Support\Colors\Colorclass contains color options for allTailwind CSS color palettes.

You can also pass in a function toregister()which will only get called when the app is getting rendered. This is useful if you are callingregister()from a service provider, and want to access objects like the currently authenticated user, which are initialized later in middleware.

Alternatively, you may pass your own palette in as an array of OKLCH colors:

```php
$panel
    ->colors([
        'primary' => [
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
    ])

```

### #Generating a color palette

If you want us to attempt to generate a palette for you based on a singular hex or RGB value, you can pass that in:

```php
$panel
    ->colors([
        'primary' => '#6366f1',
    ])

$panel
    ->colors([
        'primary' => 'rgb(99, 102, 241)',
    ])

```

## #Changing the font

By default, we use theInterfont. You can change this using thefont()method in theconfigurationfile:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->font('Poppins');
}

```

AllGoogle Fontsare available to use.

### #Changing the font provider

Bunny Fonts CDNis used to serve the fonts. It is GDPR-compliant. If you’d like to useGoogle Fonts CDNinstead, you can do so using theproviderargument of thefont()method:

```php
use Filament\FontProviders\GoogleFontProvider;

$panel->font('Inter', provider: GoogleFontProvider::class)

```

Or if you’d like to serve the fonts from a local stylesheet, you can use theLocalFontProvider:

```php
use Filament\FontProviders\LocalFontProvider;

$panel->font(
    'Inter',
    url: asset('css/fonts.css'),
    provider: LocalFontProvider::class,
)

```

## #Creating a custom theme

Filament allows you to change the CSS used to render the UI by compiling a custom stylesheet to replace the default one. This custom stylesheet is called a “theme”. Themes useTailwind CSS.

To create a custom theme for a panel, you can use thephp artisan make:filament-themecommand:

```php
php artisan make:filament-theme

```

If you have multiple panels, you can specify the panel you want to create a theme for:

```php
php artisan make:filament-theme admin

```

By default, this command will use NPM to install dependencies. If you want to use a different package manager, you can use the--pmoption:

```php
php artisan make:filament-theme --pm=bun

```

This command will:
1. Install the required Tailwind CSS dependencies
2. Generate a CSS file inresources/css/filament/{panel}/theme.css
3. Attempt to automatically add the theme to yourvite.config.jsinput array
4. Attempt to automatically register->viteTheme()in your panel provider
5. Offer to compile the theme with Vite


If the command cannot automatically configure your files (due to non-standard formatting), it will display manual instructions instead. In that case, follow these steps:

### #Manual configuration

Add the theme’s CSS file to the Laravel plugin’sinputarray invite.config.js:

```php
input: [
    // ...
    'resources/css/filament/admin/theme.css',
]

```

Register the Vite-compiled theme CSS file in the panel’s provider:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->viteTheme('resources/css/filament/admin/theme.css');
}

```

Then compile the theme with Vite:

```php
npm run build

```

NOTE

Check the command output for the exact file path (e.g.,admin/theme.css), as it may vary depending on your panel’s ID.

You can now customize the theme by editing the CSS file inresources/css/filament.

## #Using Tailwind CSS classes in your Blade views or PHP files

NOTE

A custom theme is required to use Tailwind CSS classes in your own code.Filament’s default compiled stylesheet does not include arbitrary Tailwind classes - it only contains the styles needed for Filament’s own UI components.

If you want to use Tailwind CSS utility classes (liketext-primary-600,bg-gray-100,p-4, etc.) in your own Blade views, Livewire components, or PHP files,you must create a custom theme first.

Without a custom theme, any Tailwind classes you add to your code will simply not work - the styles won’t be applied because they’re not included in the compiled CSS.

### #Setting up Tailwind CSS for your project

To use Tailwind CSS classes in your project, you need to set up acustom theme. Run the following command:

```php
php artisan make:filament-theme

```

In the generatedtheme.cssfile, you will find@sourcedirectives that tell Tailwind CSS where to scan for classes:

```php
@source '../../../../app/Filament/**/*';
@source '../../../../resources/views/filament/**/*';

```

Add your own directorieswhere you use Tailwind classes. For example:

```php
@source '../../../../app/Filament/**/*';
@source '../../../../resources/views/filament/**/*';
@source '../../../../resources/views/components/**/*';
@source '../../../../resources/views/livewire/**/*';
@source '../../../../app/Livewire/**/*';

```

After adding your directories, rebuild your theme:

```php
npm run build

```

You canlearn more about the@sourcedirectivein the Tailwind CSS documentation.

## #Disabling dark mode

To disable dark mode switching, you can use theconfigurationfile:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->darkMode(false);
}

```

## #Changing the default theme mode

By default, Filament uses the user’s system theme as the default mode. For example, if the user’s computer is in dark mode, Filament will use dark mode by default. The system mode in Filament is reactive if the user changes their computer’s mode. If you want to change the default mode to force light or dark mode, you can use thedefaultThemeMode()method, passingThemeMode::LightorThemeMode::Dark:

```php
use Filament\Enums\ThemeMode;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->defaultThemeMode(ThemeMode::Light);
}

```

## #Adding a logo

By default, Filament uses your app’s name to render a simple text-based logo. However, you can easily customize this.

If you want to simply change the text that is used in the logo, you can use thebrandName()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->brandName('Filament Demo');
}

```

To render an image instead, you can pass a URL to thebrandLogo()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->brandLogo(asset('images/logo.svg'));
}

```

Alternatively, you may directly pass HTML to thebrandLogo()method to render an inline SVG element for example:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->brandLogo(fn () => view('filament.admin.logo'));
}

```

```php
<svg
    viewBox="0 0 128 26"
    xmlns="http://www.w3.org/2000/svg"
    class="h-full fill-gray-500 dark:fill-gray-400"
>
    <!-- ... -->
</svg>

```

If you need a different logo to be used when the application is in dark mode, you can pass it todarkModeBrandLogo()in the same way.

The logo height defaults to a sensible value, but it’s impossible to account for all possible aspect ratios. Therefore, you may customize the height of the rendered logo using thebrandLogoHeight()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->brandLogo(fn () => view('filament.admin.logo'))
        ->brandLogoHeight('2rem');
}

```

## #Adding a favicon

To add a favicon, you can use theconfigurationfile, passing the public URL of the favicon:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->favicon(asset('images/favicon.png'));
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:**   
**Keywords:** theme, css, design, appearance

*Extracted from Filament v5 Documentation - 2026-01-28*
