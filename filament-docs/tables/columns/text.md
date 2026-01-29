# Text column

**URL:** https://filamentphp.com/docs/5.x/tables/columns/text  
**Section:** tables  
**Page:** text  
**Priority:** high  
**AI Context:** Interactive data tables with filtering, sorting, and actions.

---

## #Introduction

Text columns display simple text:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('title')

```

## #Customizing the color

You may set acolorfor the text:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('status')
    ->color('primary')

```

## #Adding an icon

Text columns may also have anicon:

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;

TextColumn::make('email')
    ->icon(Heroicon::Envelope)

```

You may set the position of an icon usingiconPosition():

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

TextColumn::make('email')
    ->icon(Heroicon::Envelope)
    ->iconPosition(IconPosition::After) // `IconPosition::Before` or `IconPosition::After`

```

The icon color defaults to the text color, but you may customize the iconcolorseparately usingiconColor():

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;

TextColumn::make('email')
    ->icon(Heroicon::Envelope)
    ->iconColor('primary')

```

## #Displaying as a “badge”

By default, text is quite plain and has no background color. You can make it appear as a “badge” instead using thebadge()method. A great use case for this is with statuses, where may want to display a badge with acolorthat matches the status:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('status')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'draft' => 'gray',
        'reviewing' => 'warning',
        'published' => 'success',
        'rejected' => 'danger',
    })

```

You may add other things to the badge, like anicon.

Optionally, you may pass a boolean value to control if the text should be in a badge or not:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('status')
    ->badge(FeatureFlag::active())

```

## #Formatting

When using a text column, you may want the actual outputted text in the UI to differ from the rawstateof the column, which is often automatically retrieved from an Eloquent model. Formatting the state allows you to preserve the integrity of the raw data while also allowing it to be presented in a more user-friendly way.

To format the state of a text column without changing the state itself, you can use theformatStateUsing()method. This method accepts a function that takes the state as an argument and returns the formatted state:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('status')
    ->formatStateUsing(fn (string $state): string => __("statuses.{$state}"))

```

In this case, thestatuscolumn in the database might contain values likedraft,reviewing,published, orrejected, but the formatted state will be the translated version of these values.

### #Date formatting

Instead of passing a function toformatStateUsing(), you may use thedate(),dateTime(), andtime()methods to format the column’s state usingPHP date formatting tokens:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('created_at')
    ->date()

TextColumn::make('created_at')
    ->dateTime()

TextColumn::make('created_at')
    ->time()

```

You may customize the date format by passing a custom format string to thedate(),dateTime(), ortime()method. You may use anyPHP date formatting tokens:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('created_at')
    ->date('M j, Y')
    
TextColumn::make('created_at')
    ->dateTime('M j, Y H:i:s')
    
TextColumn::make('created_at')
    ->time('H:i:s')

```

#### #Date formatting using Carbon macro formats

You may use also theisoDate(),isoDateTime(), andisoTime()methods to format the column’s state usingCarbon’s macro-formats:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('created_at')
    ->isoDate()

TextColumn::make('created_at')
    ->isoDateTime()

TextColumn::make('created_at')
    ->isoTime()

```

You may customize the date format by passing a custom macro format string to theisoDate(),isoDateTime(), orisoTime()method. You may use anyCarbon’s macro-formats:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('created_at')
    ->isoDate('L')

TextColumn::make('created_at')
    ->isoDateTime('LLL')

TextColumn::make('created_at')
    ->isoTime('LT')

```

#### #Relative date formatting

You may use thesince()method to format the column’s state usingCarbon’sdiffForHumans():

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('created_at')
    ->since()

```

#### #Displaying a formatting date in a tooltip

Additionally, you can use thedateTooltip(),dateTimeTooltip(),timeTooltip(),isoDateTooltip(),isoDateTimeTooltip(),isoTime(),isoTimeTooltip(), orsinceTooltip()method to display a formatted date in atooltip, often to provide extra information:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('created_at')
    ->since()
    ->dateTooltip() // Accepts a custom PHP date formatting string

TextColumn::make('created_at')
    ->since()
    ->dateTimeTooltip() // Accepts a custom PHP date formatting string

TextColumn::make('created_at')
    ->since()
    ->timeTooltip() // Accepts a custom PHP date formatting string

TextColumn::make('created_at')
    ->since()
    ->isoDateTooltip() // Accepts a custom Carbon macro format string

TextColumn::make('created_at')
    ->since()
    ->isoDateTimeTooltip() // Accepts a custom Carbon macro format string

TextColumn::make('created_at')
    ->since()
    ->isoTimeTooltip() // Accepts a custom Carbon macro format string

TextColumn::make('created_at')
    ->dateTime()
    ->sinceTooltip()

```

#### #Setting the timezone for date formatting

Each of the date formatting methods listed above also accepts atimezoneargument, which allows you to convert the time set in the state to a different timezone:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('created_at')
    ->dateTime(timezone: 'America/New_York')

```

You can also pass a timezone to thetimezone()method of the column to apply a timezone to all date-time formatting methods at once:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('created_at')
    ->timezone('America/New_York')
    ->dateTime()

```

If you do not pass atimezone()to the column, it will use Filament’s default timezone. You can set Filament’s default timezone using theFilamentTimezone::set()method in theboot()method of a service provider such asAppServiceProvider:

```php
use Filament\Support\Facades\FilamentTimezone;

public function boot(): void
{
    FilamentTimezone::set('America/New_York');
}

```

This is useful if you want to set a default timezone for all text columns in your application. It is also used in other places where timezones are used in Filament.

NOTE

Filament’s default timezone will only apply when the column stores a time. If the column stores a date only (date()instead ofdateTime()), the timezone will not be applied. This is to prevent timezone shifts when storing dates without times.

### #Number formatting

Instead of passing a function toformatStateUsing(), you can use thenumeric()method to format a column as a number:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('stock')
    ->numeric()

```

If you would like to customize the number of decimal places used to format the number with, you can use thedecimalPlacesargument:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('stock')
    ->numeric(decimalPlaces: 0)

```

By default, your app’s locale will be used to format the number suitably. If you would like to customize the locale used, you can pass it to thelocaleargument:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('stock')
    ->numeric(locale: 'nl')

```

### #Money formatting

Instead of passing a function toformatStateUsing(), you can use themoney()method to easily format amounts of money, in any currency:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('price')
    ->money('EUR')

```

There is also adivideByargument formoney()that allows you to divide the original value by a number before formatting it. This could be useful if your database stores the price in cents, for example:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('price')
    ->money('EUR', divideBy: 100)

```

By default, your app’s locale will be used to format the money suitably. If you would like to customize the locale used, you can pass it to thelocaleargument:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('price')
    ->money('EUR', locale: 'nl')

```

If you would like to customize the number of decimal places used to format the number with, you can use thedecimalPlacesargument:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('price')
    ->money('EUR', decimalPlaces: 3)

```

### #Rendering Markdown

If your column value is Markdown, you may render it usingmarkdown():

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->markdown()

```

Optionally, you may pass a boolean value to control if the text should be rendered as Markdown or not:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->markdown(FeatureFlag::active())

```

### #Rendering HTML

If your column value is HTML, you may render it usinghtml():

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->html()

```

Optionally, you may pass a boolean value to control if the text should be rendered as HTML or not:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->html(FeatureFlag::active())

```

#### #Rendering raw HTML without sanitization

If you use this method, then the HTML will be sanitized to remove any potentially unsafe content before it is rendered. If you’d like to opt out of this behavior, you can wrap the HTML in anHtmlStringobject by formatting it:

```php
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

TextColumn::make('description')
    ->formatStateUsing(fn (string $state): HtmlString => new HtmlString($state))

```

NOTE

Be cautious when rendering raw HTML, as it may contain malicious content, which can lead to security vulnerabilities in your app such as cross-site scripting (XSS) attacks. Always ensure that the HTML you are rendering is safe before using this method.

Alternatively, you can return aview()object from theformatStateUsing()method, which will also not be sanitized:

```php
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;

TextColumn::make('description')
    ->formatStateUsing(fn (string $state): View => view(
        'filament.tables.columns.description-column-content',
        ['state' => $state],
    ))

```

## #Displaying a description

Descriptions may be used to easily render additional text above or below the column contents.

You can display a description below the contents of a text column using thedescription()method:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('title')
    ->description(fn (Post $record): string => $record->description)

```

By default, the description is displayed below the main text, but you can move it using'above'as the second parameter:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('title')
    ->description(fn (Post $record): string => $record->description, position: 'above')

```

## #Listing multiple values

Multiple values can be rendered in a text column if itsstateis an array. This can happen if you are using anarraycast on an Eloquent attribute, an Eloquent relationship with multiple results, or if you have passed an array to thestate()method. If there are multiple values inside your text column, they will be comma-separated. You may use thelistWithLineBreaks()method to display them on new lines instead:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('authors.name')
    ->listWithLineBreaks()

```

Optionally, you may pass a boolean value to control if the text should have line breaks between each item or not:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('authors.name')
    ->listWithLineBreaks(FeatureFlag::active())

```

### #Adding bullet points to the list

You may add a bullet point to each list item using thebulleted()method:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('authors.name')
    ->bulleted()

```

Optionally, you may pass a boolean value to control if the text should have bullet points or not:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('authors.name')
    ->bulleted(FeatureFlag::active())

```

### #Limiting the number of values in the list

You can limit the number of values in the list using thelimitList()method:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('authors.name')
    ->listWithLineBreaks()
    ->limitList(3)

```

#### #Expanding the limited list

You can allow the limited items to be expanded and collapsed, using theexpandableLimitedList()method:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('authors.name')
    ->listWithLineBreaks()
    ->limitList(3)
    ->expandableLimitedList()

```

NOTE

This is only a feature forlistWithLineBreaks()orbulleted(), where each item is on its own line.

Optionally, you may pass a boolean value to control if the text should be expandable or not:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('authors.name')
    ->listWithLineBreaks()
    ->limitList(3)
    ->expandableLimitedList(FeatureFlag::active())

```

### #Splitting a single value into multiple list items

If you want to “explode” a text string from your model into multiple list items, you can do so with theseparator()method. This is useful for displaying comma-separated tagsas badges, for example:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('tags')
    ->badge()
    ->separator(',')

```

## #Customizing the text size

Text columns have small font size by default, but you may change this toTextSize::ExtraSmall,TextSize::Medium, orTextSize::Large.

For instance, you may make the text larger usingsize(TextSize::Large):

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\TextSize;

TextColumn::make('title')
    ->size(TextSize::Large)

```

## #Customizing the font weight

Text columns have regular font weight by default, but you may change this to any of the following options:FontWeight::Thin,FontWeight::ExtraLight,FontWeight::Light,FontWeight::Medium,FontWeight::SemiBold,FontWeight::Bold,FontWeight::ExtraBoldorFontWeight::Black.

For instance, you may make the font bold usingweight(FontWeight::Bold):

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Enums\FontWeight;

TextColumn::make('title')
    ->weight(FontWeight::Bold)

```

## #Customizing the font family

You can change the text font family to any of the following options:FontFamily::Sans,FontFamily::SeriforFontFamily::Mono.

For instance, you may make the font monospaced usingfontFamily(FontFamily::Mono):

```php
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('email')
    ->fontFamily(FontFamily::Mono)

```

## #Handling long text

### #Limiting text length

You maylimit()the length of the column’s value:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->limit(50)

```

By default, when text is truncated, an ellipsis (...) is appended to the end of the text. You may customize this by passing a custom string to theendargument:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->limit(50, end: ' (more)')

```

You may also reuse the value that is being passed tolimit()in a function, by getting it using thegetCharacterLimit()method:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->limit(50)
    ->tooltip(function (TextColumn $column): ?string {
        $state = $column->getState();

        if (strlen($state) <= $column->getCharacterLimit()) {
            return null;
        }

        // Only render the tooltip if the column contents exceeds the length limit.
        return $state;
    })

```

### #Limiting word count

You may limit the number ofwords()displayed in the column:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->words(10)

```

By default, when text is truncated, an ellipsis (...) is appended to the end of the text. You may customize this by passing a custom string to theendargument:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->words(10, end: ' (more)')

```

### #Allowing text wrapping

By default, text will not wrap to the next line if it exceeds the width of the container. You can enable this behavior using thewrap()method:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->wrap()

```

Optionally, you may pass a boolean value to control if the text should wrap or not:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->wrap(FeatureFlag::active())

```

#### #Limiting text to a specific number of lines

You may want to limit text to a specific number of lines instead of limiting it to a fixed length. Clamping text to a number of lines is useful in responsive interfaces where you want to ensure a consistent experience across all screen sizes. This can be achieved using thelineClamp()method:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('description')
    ->wrap()
    ->lineClamp(2)

```

## #Allowing the text to be copied to the clipboard

You may make the text copyable, such that clicking on the column copies the text to the clipboard, and optionally specify a custom confirmation message and duration in milliseconds:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('email')
    ->copyable()
    ->copyMessage('Email address copied')
    ->copyMessageDuration(1500)

```

Optionally, you may pass a boolean value to control if the text should be copyable or not:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('email')
    ->copyable(FeatureFlag::active())

```

NOTE

This feature only works when SSL is enabled for the app.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, filters, actions  
**Keywords:** table, list, grid, data display, columns

*Extracted from Filament v5 Documentation - 2026-01-28*
