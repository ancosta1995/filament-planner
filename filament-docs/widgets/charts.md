# Chart widgets

**URL:** https://filamentphp.com/docs/5.x/widgets/charts  
**Section:** widgets  
**Page:** charts  
**Priority:** medium  
**AI Context:** Dashboard widgets for stats and charts.

---

## #Introduction

Filament comes with many “chart” widget templates, which you can use to display real-time, interactive charts.

Start by creating a widget with the command:

```php
php artisan make:filament-widget BlogPostsChart --chart

```

There is a singleChartWidgetclass that is used for all charts. The type of chart is set by thegetType()method. In this example, that method returns the string'line'.

Theprotected ?string $headingvariable is used to set the heading that describes the chart. If you need to set the heading dynamically, you can override thegetHeading()method.

ThegetData()method is used to return an array of datasets and labels. Each dataset is a labeled array of points to plot on the chart, and each label is a string. This structure is identical to theChart.jslibrary, which Filament uses to render charts. You may use theChart.js documentationto fully understand the possibilities to return fromgetData(), based on the chart type.

```php
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class BlogPostsChart extends ChartWidget
{
    protected ?string $heading = 'Blog Posts';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

```

Now, check out your widget in the dashboard.

## #Available chart types

Below is a list of available chart widget classes which you may extend, and their correspondingChart.jsdocumentation page, for inspiration on what to return fromgetData():
- Bar chart -Chart.js documentation
- Bubble chart -Chart.js documentation
- Doughnut chart -Chart.js documentation
- Line chart -Chart.js documentation
- Pie chart -Chart.js documentation
- Polar area chart -Chart.js documentation
- Radar chart -Chart.js documentation
- Scatter chart -Chart.js documentation


## #Customizing the chart color

You can customize thecolorof the chart data by setting the$colorproperty:

```php
protected string $color = 'info';

```

If you’re looking to customize the color further, or use multiple colors across multiple datasets, you can still make use of Chart.js’scolor optionsin the data:

```php
protected function getData(): array
{
    return [
        'datasets' => [
            [
                'label' => 'Blog posts created',
                'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                'backgroundColor' => '#36A2EB',
                'borderColor' => '#9BD0F5',
            ],
        ],
        'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    ];
}

```

## #Generating chart data from an Eloquent model

To generate chart data from an Eloquent model, Filament recommends that you install theflowframe/laravel-trendpackage. You can view thedocumentation.

Here is an example of generating chart data from a model using thelaravel-trendpackage:

```php
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

protected function getData(): array
{
    $data = Trend::model(BlogPost::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Blog posts',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
}

```

## #Filtering chart data

### #Basic Select filter

You can set up chart filters to change the data that is presented. Commonly, this is used to change the time period that chart data is rendered for.

To set a default filter value, set the$filterproperty:

```php
public ?string $filter = 'today';

```

Then, define thegetFilters()method to return an array of values and labels for your filter:

```php
protected function getFilters(): ?array
{
    return [
        'today' => 'Today',
        'week' => 'Last week',
        'month' => 'Last month',
        'year' => 'This year',
    ];
}

```

You can use the active filter value within yourgetData()method:

```php
protected function getData(): array
{
    $activeFilter = $this->filter;

    // ...
}

```

### #Custom filters

You can useschema componentsto build custom filters for your chart widget. This approach offers a more flexible way to define filters.

To get started, use theHasFiltersSchematrait and implement thefiltersSchema()method:

```php
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;

class BlogPostsChart extends ChartWidget
{
    use HasFiltersSchema;
    
    // ...
    
    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('startDate')
                ->default(now()->subDays(30)),
            DatePicker::make('endDate')
                ->default(now()),
        ]);
    }
}

```

The filter values are accessible via the$this->filtersarray. You can use these values inside yourgetData()method:

```php
protected function getData(): array
{
    $startDate = $this->filters['startDate'] ?? null;
    $endDate = $this->filters['endDate'] ?? null;

    return [
        // ...
    ];
}

```

The$this->filtersarray will always reflect the current form data. Please note that this data is not validated, as it is available live and not intended to be used for anything other than querying the database. You must ensure that the data is valid before using it.

NOTE

If you want to add filters that apply to multiple widgets at once, seefiltering widget datain the dashboard.

## #Live updating chart data (polling)

By default, chart widgets refresh their data every 5 seconds.

To customize this, you may override the$pollingIntervalproperty on the class to a new interval:

```php
protected ?string $pollingInterval = '10s';

```

Alternatively, you may disable polling altogether:

```php
protected ?string $pollingInterval = null;

```

## #Setting a maximum chart height

You may place a maximum height on the chart to ensure that it doesn’t get too big, using the$maxHeightproperty:

```php
protected ?string $maxHeight = '300px';

```

## #Setting chart configuration options

You may specify an$optionsvariable on the chart class to control the many configuration options that the Chart.js library provides. For instance, you could turn off thelegendfor a line chart:

```php
protected ?array $options = [
    'plugins' => [
        'legend' => [
            'display' => false,
        ],
    ],
];

```

Alternatively, you can override thegetOptions()method to return a dynamic array of options:

```php
protected function getOptions(): array
{
    return [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];
}

```

These PHP arrays will get transformed into JSON objects when the chart is rendered. If you want to return raw JavaScript from this method instead, you can return aRawJsobject. This is useful if you want to use a JavaScript callback function, for example:

```php
use Filament\Support\RawJs;

protected function getOptions(): RawJs
{
    return RawJs::make(<<<JS
        {
            scales: {
                y: {
                    ticks: {
                        callback: (value) => '€' + value,
                    },
                },
            },
        }
    JS);
}

```

## #Adding a description

You may add a description, below the heading of the chart, using thegetDescription()method:

```php
public function getDescription(): ?string
{
    return 'The number of blog posts published per month.';
}

```

## #Disabling lazy loading

By default, widgets are lazy-loaded. This means that they will only be loaded when they are visible on the page.

To disable this behavior, you may override the$isLazyproperty on the widget class:

```php
protected static bool $isLazy = false;

```

## #Making the chart collapsible

You may allow the chart to be collapsible by setting the$isCollapsibleproperty on the widget class to betrue:

```php
protected bool $isCollapsible = true;

```

## #Using custom Chart.js plugins

Chart.js offers a powerful plugin system that allows you to extend its functionality and create custom chart behaviors. This guide details how to use them in a chart widget.

### #Step 1: Install the plugin with NPM

To start with, install the plugin using NPM into your project. In this guide, we will installchartjs-plugin-datalabels:

```php
npm install chartjs-plugin-datalabels --save-dev

```

### #Step 2: Create a JavaScript file importing the plugin

Create a new JavaScript file where you will define your custom plugin. In this guide, we’ll call itfilament-chart-js-plugins.js. Import the plugin, and add it to thewindow.filamentChartJsPluginsarray:

```php
import ChartDataLabels from 'chartjs-plugin-datalabels'

window.filamentChartJsPlugins ??= []
window.filamentChartJsPlugins.push(ChartDataLabels)

```

This is equivalent to including the plugins “inline” vianew Chart(..., { plugins: [...] })when instantiating a Chart.js chart.

It’s important to initialise the array if it has not been already, before pushing onto it. This ensures that multiple JavaScript files (especially those from Filament plugins) that register Chart.js plugins do not overwrite each other, regardless of the order they are booted in.

You can push as many plugins to the array as you would like to install, you do not need a separate file to import each plugin.

Additionally, you can also register any “global plugins” which will useChart.register([...])in thewindow.filamentChartJsGlobalPluginsarray:

```php
import ChartDataLabels from 'chartjs-plugin-datalabels'

window.filamentChartJsGlobalPlugins ??= []
window.filamentChartJsGlobalPlugins.push(ChartDataLabels)

```

### #Step 3: Compile the JavaScript file with Vite

Now, you need to build the JavaScript file with Vite, or your bundler of choice. Include the file in your Vite configuration (usuallyvite.config.js). For example:

```php
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
                'resources/js/filament-chart-js-plugins.js', // Include the new file in the `input` array so it is built
            ],
        }),
    ],
});

```

Build the file withnpm run build.

### #Step 4: Register the JavaScript file in Filament

Filament needs to know to include this JavaScript file when rendering chart widgets. You can do this in theboot()method of a service provider likeAppServiceProvider:

```php
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\Vite;

FilamentAsset::register([
    Js::make('chart-js-plugins', Vite::asset('resources/js/filament-chart-js-plugins.js'))->module(),
]);

```

You can find out more aboutasset registration, and evenregister assets for a specific panel.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources  
**Keywords:** dashboard, charts, statistics, analytics

*Extracted from Filament v5 Documentation - 2026-01-28*
