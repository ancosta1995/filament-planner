# Custom pages

**URL:** https://filamentphp.com/docs/5.x/navigation/custom-pages  
**Section:** navigation  
**Page:** custom-pages  
**Priority:** medium  
**AI Context:** Configure panel navigation and menu structure.

---

## #Introduction

Filament allows you to create completely custom pages for the app.

## #Creating a page

To create a new page, you can use:

```php
php artisan make:filament-page Settings

```

This command will create two files - a page class in the/Pagesdirectory of the Filament directory, and a view in the/pagesdirectory of the Filament views directory.

Page classes are all full-pageLivewirecomponents with a few extra utilities you can use with the panel.

## #Authorization

You can prevent pages from appearing in the menu by overriding thecanAccess()method in your Page class. This is useful if you want to control which users can see the page in the navigation, and also which users can visit the page directly:

```php
public static function canAccess(): bool
{
    return auth()->user()->canManageSettings();
}

```

## #Adding actions to pages

Actions are buttons that can perform tasks on the page, or visit a URL. You can read more about their capabilitieshere.

Since all pages are Livewire components, you canadd actionsanywhere. Pages already have theInteractsWithActionstrait,HasActionsinterface, and<x-filament-actions::modals />Blade component all set up for you.

### #Header actions

You can also easily add actions to the header of any page, includingresource pages. You don’t need to worry about adding anything to the Blade template, we handle that for you. Just return your actions from thegetHeaderActions()method of the page class:

```php
use Filament\Actions\Action;

protected function getHeaderActions(): array
{
    return [
        Action::make('edit')
            ->url(route('posts.edit', ['post' => $this->post])),
        Action::make('delete')
            ->requiresConfirmation()
            ->action(fn () => $this->post->delete()),
    ];
}

```

#### #Aligning header actions

By default, header actions are aligned to the left on mobile. To change the alignment of the header actions on mobile, set$headerActionsAlignment:

```php
use Filament\Support\Enums\Alignment;

protected ?Alignment $headerActionsAlignment = Alignment::End;

```

### #Opening an action modal when a page loads

You can also open an action when a page loads by setting the$defaultActionproperty to the name of the action you want to open:

```php
use Filament\Actions\Action;

public $defaultAction = 'onboarding';

public function onboardingAction(): Action
{
    return Action::make('onboarding')
        ->modalHeading('Welcome')
        ->visible(fn (): bool => ! auth()->user()->isOnBoarded());
}

```

You can also pass an array of arguments to the default action using the$defaultActionArgumentsproperty:

```php
public $defaultActionArguments = ['step' => 2];

```

Alternatively, you can open an action modal when a page loads by specifying theactionas a query string parameter to the page:

```php
/admin/products/edit/932510?action=onboarding

```

### #Refreshing form data

If you’re using actions on anEditorViewresource page, you can refresh data within the main form using therefreshFormData()method:

```php
use App\Models\Post;
use Filament\Actions\Action;

Action::make('approve')
    ->action(function (Post $record) {
        $record->approve();

        $this->refreshFormData([
            'status',
        ]);
    })

```

This method accepts an array of model attributes that you wish to refresh in the form.

## #Adding widgets to pages

Filament allows you to displaywidgetsinside pages, below the header and above the footer.

To add a widget to a page, use thegetHeaderWidgets()orgetFooterWidgets()methods:

```php
use App\Filament\Widgets\StatsOverviewWidget;

protected function getHeaderWidgets(): array
{
    return [
        StatsOverviewWidget::class
    ];
}

```

getHeaderWidgets()returns an array of widgets to display above the page content, whereasgetFooterWidgets()are displayed below.

If you’d like to learn how to build and customize widgets, check out theWidgetsdocumentation section.

### #Customizing the widgets’ grid

You may change how many grid columns are used to display widgets.

You may override thegetHeaderWidgetsColumns()orgetFooterWidgetsColumns()methods to return a number of grid columns to use:

```php
public function getHeaderWidgetsColumns(): int | array
{
    return 3;
}

```

#### #Responsive widgets grid

You may wish to change the number of widget grid columns based on the responsivebreakpointof the browser. You can do this using an array that contains the number of columns that should be used at each breakpoint:

```php
public function getHeaderWidgetsColumns(): int | array
{
    return [
        'md' => 4,
        'xl' => 5,
    ];
}

```

This pairs well withresponsive widget widths.

#### #Passing data to widgets from the page

You may pass data to widgets from the page using thegetWidgetData()method:

```php
public function getWidgetData(): array
{
    return [
        'stats' => [
            'total' => 100,
        ],
    ];
}

```

Now, you can define a corresponding public$statsarray property on the widget class, which will be automatically filled:

```php
public $stats = [];

```

### #Passing properties to widgets on pages

When registering a widget on a page, you can use themake()method to pass an array ofLivewire propertiesto it:

```php
use App\Filament\Widgets\StatsOverviewWidget;

protected function getHeaderWidgets(): array
{
    return [
        StatsOverviewWidget::make([
            'status' => 'active',
        ]),
    ];
}

```

This array of properties gets mapped topublic Livewire propertieson the widget class:

```php
use Filament\Widgets\Widget;

class StatsOverviewWidget extends Widget
{
    public string $status;

    // ...
}

```

Now, you can access thestatusin the widget class using$this->status.

## #Customizing the page title

By default, Filament will automatically generate a title for your page based on its name. You may override this by defining a$titleproperty on your page class:

```php
protected static ?string $title = 'Custom Page Title';

```

Alternatively, you may return a string from thegetTitle()method:

```php
use Illuminate\Contracts\Support\Htmlable;

public function getTitle(): string | Htmlable
{
    return __('Custom Page Title');
}

```

## #Customizing the page navigation label

By default, Filament will use the page’stitleas itsnavigationitem label. You may override this by defining a$navigationLabelproperty on your page class:

```php
protected static ?string $navigationLabel = 'Custom Navigation Label';

```

Alternatively, you may return a string from thegetNavigationLabel()method:

```php
public static function getNavigationLabel(): string
{
    return __('Custom Navigation Label');
}

```

## #Customizing the page URL

By default, Filament will automatically generate a URL (slug) for your page based on its name. You may override this by defining a$slugproperty on your page class:

```php
protected static ?string $slug = 'custom-url-slug';

```

## #Customizing the page heading

By default, Filament will use the page’stitleas its heading. You may override this by defining a$headingproperty on your page class:

```php
protected ?string $heading = 'Custom Page Heading';

```

Alternatively, you may return a string from thegetHeading()method:

```php
public function getHeading(): string
{
    return __('Custom Page Heading');
}

```

### #Adding a page subheading

You may also add a subheading to your page by defining a$subheadingproperty on your page class:

```php
protected ?string $subheading = 'Custom Page Subheading';

```

Alternatively, you may return a string from thegetSubheading()method:

```php
public function getSubheading(): ?string
{
    return __('Custom Page Subheading');
}

```

## #Replacing the page header with a custom view

You may replace the defaultheading,subheadingandactionswith a custom header view for any page. You may return it from thegetHeader()method:

```php
use Illuminate\Contracts\View\View;

public function getHeader(): ?View
{
    return view('filament.settings.custom-header');
}

```

This example assumes you have a Blade view atresources/views/filament/settings/custom-header.blade.php.

## #Rendering a custom view in the footer of the page

You may also add a footer to any page, below its content. You may return it from thegetFooter()method:

```php
use Illuminate\Contracts\View\View;

public function getFooter(): ?View
{
    return view('filament.settings.custom-footer');
}

```

This example assumes you have a Blade view atresources/views/filament/settings/custom-footer.blade.php.

## #Customizing the maximum content width

By default, Filament will restrict the width of the content on the page, so it doesn’t become too wide on large screens. To change this, you may override thegetMaxContentWidth()method. Options correspond toTailwind’s max-width scale. The options areExtraSmall,Small,Medium,Large,ExtraLarge,TwoExtraLarge,ThreeExtraLarge,FourExtraLarge,FiveExtraLarge,SixExtraLarge,SevenExtraLarge,Full,MinContent,MaxContent,FitContent,Prose,ScreenSmall,ScreenMedium,ScreenLarge,ScreenExtraLargeandScreenTwoExtraLarge. The default isSevenExtraLarge:

```php
use Filament\Support\Enums\Width;

public function getMaxContentWidth(): Width
{
    return Width::Full;
}

```

## #Generating URLs to pages

Filament providesgetUrl()static method on page classes to generate URLs to them. Traditionally, you would need to construct the URL by hand or by using Laravel’sroute()helper, but these methods depend on knowledge of the page’s slug or route naming conventions.

ThegetUrl()method, without any arguments, will generate a URL:

```php
use App\Filament\Pages\Settings;

Settings::getUrl(); // /admin/settings

```

If your page uses URL / query parameters, you should use the argument:

```php
use App\Filament\Pages\Settings;

Settings::getUrl(['section' => 'notifications']); // /admin/settings?section=notifications

```

### #Generating URLs to pages in other panels

If you have multiple panels in your app,getUrl()will generate a URL within the current panel. You can also indicate which panel the page is associated with, by passing the panel ID to thepanelargument:

```php
use App\Filament\Pages\Settings;

Settings::getUrl(panel: 'marketing');

```

## #Adding sub-navigation between pages

You may want to add a common sub-navigation to multiple pages, to allow users to quickly navigate between them. You can do this by defining acluster. Clusters can also containresources, and you can switch between multiple pages or resources within a cluster.

## #Setting the sub-navigation position

The sub-navigation is rendered at the start of the page by default. You may change the position for a page by setting the$subNavigationPositionproperty on the page. The value may beSubNavigationPosition::Start,SubNavigationPosition::End, orSubNavigationPosition::Topto render the sub-navigation as tabs:

```php
use Filament\Pages\Enums\SubNavigationPosition;

protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

```

## #Adding extra attributes to the body tag of a page

You may wish to add extra attributes to the<body>tag of a page. To do this, you can set an array of attributes in$extraBodyAttributes:

```php
protected array $extraBodyAttributes = [];

```

Or, you can return an array of attributes and their values from thegetExtraBodyAttributes()method:

```php
public function getExtraBodyAttributes(): array
{
    return [
        'class' => 'settings-page',
    ];
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** panel-configuration  
**Keywords:** menu, sidebar, navigation, routing

*Extracted from Filament v5 Documentation - 2026-01-28*
