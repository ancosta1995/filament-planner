# Text entry

**URL:** https://filamentphp.com/docs/5.x/infolists/text-entry  
**Section:** infolists  
**Page:** text-entry  
**Priority:** medium  
**AI Context:** Display read-only data in structured layouts.

---

## #Introduction

Text entries display simple text:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('title')

```

## #Customizing the color

You may set acolorfor the text:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('status')
    ->color('primary')

```

## #Adding an icon

Text entries may also have anicon:

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Icons\Heroicon;

TextEntry::make('email')
    ->icon(Heroicon::Envelope)

```

You may set the position of an icon usingiconPosition():

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;

TextEntry::make('email')
    ->icon(Heroicon::Envelope)
    ->iconPosition(IconPosition::After) // `IconPosition::Before` or `IconPosition::After`

```

The icon color defaults to the text color, but you may customize the iconcolorseparately usingiconColor():

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Icons\Heroicon;

TextEntry::make('email')
    ->icon(Heroicon::Envelope)
    ->iconColor('primary')

```

## #Displaying as a “badge”

By default, text is quite plain and has no background color. You can make it appear as a “badge” instead using thebadge()method. A great use case for this is with statuses, where may want to display a badge with acolorthat matches the status:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('status')
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
use Filament\Infolists\Components\TextEntry;

TextEntry::make('status')
    ->badge(FeatureFlag::active())

```

## #Formatting

When using a text entry, you may want the actual outputted text in the UI to differ from the rawstateof the entry, which is often automatically retrieved from an Eloquent model. Formatting the state allows you to preserve the integrity of the raw data while also allowing it to be presented in a more user-friendly way.

To format the state of a text entry without changing the state itself, you can use theformatStateUsing()method. This method accepts a function that takes the state as an argument and returns the formatted state:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('status')
    ->formatStateUsing(fn (string $state): string => __("statuses.{$state}"))

```

In this case, thestatuscolumn in the database might contain values likedraft,reviewing,published, orrejected, but the formatted state will be the translated version of these values.

### #Date formatting

Instead of passing a function toformatStateUsing(), you may use thedate(),dateTime(), andtime()methods to format the entry’s state usingPHP date formatting tokens:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->date()

TextEntry::make('created_at')
    ->dateTime()

TextEntry::make('created_at')
    ->time()

```

You may customize the date format by passing a custom format string to thedate(),dateTime(), ortime()method. You may use anyPHP date formatting tokens:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->date('M j, Y')
    
TextEntry::make('created_at')
    ->dateTime('M j, Y H:i:s')
    
TextEntry::make('created_at')
    ->time('H:i:s')

```

#### #Date formatting using Carbon macro formats

You may use also theisoDate(),isoDateTime(), andisoTime()methods to format the entry’s state usingCarbon’s macro-formats:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->isoDate()

TextEntry::make('created_at')
    ->isoDateTime()

TextEntry::make('created_at')
    ->isoTime()

```

You may customize the date format by passing a custom macro format string to theisoDate(),isoDateTime(), orisoTime()method. You may use anyCarbon’s macro-formats:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->isoDate('L')

TextEntry::make('created_at')
    ->isoDateTime('LLL')

TextEntry::make('created_at')
    ->isoTime('LT')

```

#### #Relative date formatting

You may use thesince()method to format the entry’s state usingCarbon’sdiffForHumans():

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->since()

```

#### #Displaying a formatting date in a tooltip

Additionally, you can use thedateTooltip(),dateTimeTooltip(),timeTooltip(),isoDateTooltip(),isoDateTimeTooltip(),isoTime(),isoTimeTooltip(), orsinceTooltip()method to display a formatted date in atooltip, often to provide extra information:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->since()
    ->dateTooltip() // Accepts a custom PHP date formatting string

TextEntry::make('created_at')
    ->since()
    ->dateTimeTooltip() // Accepts a custom PHP date formatting string

TextEntry::make('created_at')
    ->since()
    ->timeTooltip() // Accepts a custom PHP date formatting string

TextEntry::make('created_at')
    ->since()
    ->isoDateTooltip() // Accepts a custom Carbon macro format string

TextEntry::make('created_at')
    ->since()
    ->isoDateTimeTooltip() // Accepts a custom Carbon macro format string

TextEntry::make('created_at')
    ->since()
    ->isoTimeTooltip() // Accepts a custom Carbon macro format string

TextEntry::make('created_at')
    ->dateTime()
    ->sinceTooltip()

```

#### #Setting the timezone for date formatting

Each of the date formatting methods listed above also accepts atimezoneargument, which allows you to convert the time set in the state to a different timezone:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->dateTime(timezone: 'America/New_York')

```

You can also pass a timezone to thetimezone()method of the entry to apply a timezone to all date-time formatting methods at once:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('created_at')
    ->timezone('America/New_York')
    ->dateTime()

```

If you do not pass atimezone()to the entry, it will use Filament’s default timezone. You can set Filament’s default timezone using theFilamentTimezone::set()method in theboot()method of a service provider such asAppServiceProvider:

```php
use Filament\Support\Facades\FilamentTimezone;

public function boot(): void
{
    FilamentTimezone::set('America/New_York');
}

```

This is useful if you want to set a default timezone for all text entries in your application. It is also used in other places where timezones are used in Filament.

NOTE

Filament’s default timezone will only apply when the entry stores a time. If the entry stores a date only (date()instead ofdateTime()), the timezone will not be applied. This is to prevent timezone shifts when storing dates without times.

### #Number formatting

Instead of passing a function toformatStateUsing(), you can use thenumeric()method to format an entry as a number:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('stock')
    ->numeric()

```

If you would like to customize the number of decimal places used to format the number with, you can use thedecimalPlacesargument:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('stock')
    ->numeric(decimalPlaces: 0)

```

By default, your app’s locale will be used to format the number suitably. If you would like to customize the locale used, you can pass it to thelocaleargument:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('stock')
    ->numeric(locale: 'nl')

```

### #Money formatting

Instead of passing a function toformatStateUsing(), you can use themoney()method to easily format amounts of money, in any currency:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('price')
    ->money('EUR')

```

There is also adivideByargument formoney()that allows you to divide the original value by a number before formatting it. This could be useful if your database stores the price in cents, for example:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('price')
    ->money('EUR', divideBy: 100)

```

By default, your app’s locale will be used to format the money suitably. If you would like to customize the locale used, you can pass it to thelocaleargument:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('price')
    ->money('EUR', locale: 'nl')

```

If you would like to customize the number of decimal places used to format the number with, you can use thedecimalPlacesargument:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('price')
    ->money('EUR', decimalPlaces: 3)

```

### #Rendering Markdown

If your entry value is Markdown, you may render it usingmarkdown():

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->markdown()

```

Optionally, you may pass a boolean value to control if the text should be rendered as Markdown or not:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->markdown(FeatureFlag::active())

```

### #Rendering HTML

If your entry value is HTML, you may render it usinghtml():

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->html()

```

Optionally, you may pass a boolean value to control if the text should be rendered as HTML or not:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->html(FeatureFlag::active())

```

#### #Rendering raw HTML without sanitization

If you use this method, then the HTML will be sanitized to remove any potentially unsafe content before it is rendered. If you’d like to opt out of this behavior, you can wrap the HTML in anHtmlStringobject by formatting it:

```php
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\HtmlString;

TextEntry::make('description')
    ->formatStateUsing(fn (string $state): HtmlString => new HtmlString($state))

```

NOTE

Be cautious when rendering raw HTML, as it may contain malicious content, which can lead to security vulnerabilities in your app such as cross-site scripting (XSS) attacks. Always ensure that the HTML you are rendering is safe before using this method.

Alternatively, you can return aview()object from theformatStateUsing()method, which will also not be sanitized:

```php
use Filament\Infolists\Components\TextEntry;
use Illuminate\Contracts\View\View;

TextEntry::make('description')
    ->formatStateUsing(fn (string $state): View => view(
        'filament.infolists.components.description-entry-content',
        ['state' => $state],
    ))

```

## #Listing multiple values

Multiple values can be rendered in a text entry if itsstateis an array. This can happen if you are using anarraycast on an Eloquent attribute, an Eloquent relationship with multiple results, or if you have passed an array to thestate()method. If there are multiple values inside your text entry, they will be comma-separated. You may use thelistWithLineBreaks()method to display them on new lines instead:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('authors.name')
    ->listWithLineBreaks()

```

Optionally, you may pass a boolean value to control if the text should have line breaks between each item or not:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('authors.name')
    ->listWithLineBreaks(FeatureFlag::active())

```

### #Adding bullet points to the list

You may add a bullet point to each list item using thebulleted()method:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('authors.name')
    ->bulleted()

```

Optionally, you may pass a boolean value to control if the text should have bullet points or not:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('authors.name')
    ->bulleted(FeatureFlag::active())

```

### #Limiting the number of values in the list

You can limit the number of values in the list using thelimitList()method:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('authors.name')
    ->listWithLineBreaks()
    ->limitList(3)

```

#### #Expanding the limited list

You can allow the limited items to be expanded and collapsed, using theexpandableLimitedList()method:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('authors.name')
    ->listWithLineBreaks()
    ->limitList(3)
    ->expandableLimitedList()

```

NOTE

This is only a feature forlistWithLineBreaks()orbulleted(), where each item is on its own line.

Optionally, you may pass a boolean value to control if the text should be expandable or not:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('authors.name')
    ->listWithLineBreaks()
    ->limitList(3)
    ->expandableLimitedList(FeatureFlag::active())

```

### #Splitting a single value into multiple list items

If you want to “explode” a text string from your model into multiple list items, you can do so with theseparator()method. This is useful for displaying comma-separated tagsas badges, for example:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('tags')
    ->badge()
    ->separator(',')

```

## #Aggregating relationships

Filament provides several methods for aggregating a relationship field, includingavg(),max(),min()andsum(). For instance, if you wish to show the average of a field on all related records, you may use theavg()method:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('users_avg_age')->avg('users', 'age')

```

In this example,usersis the name of the relationship, whileageis the field that is being averaged. The name of the entry must beusers_avg_age, as this is the convention thatLaravel usesfor storing the result.

If you’d like to scope the relationship before aggregating, you can pass an array to the method, where the key is the relationship name and the value is the function to scope the Eloquent query with:

```php
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Builder;

TextEntry::make('users_avg_age')->avg([
    'users' => fn (Builder $query) => $query->where('is_active', true),
], 'age')

```

## #Customizing the text size

Text entries have small font size by default, but you may change this toTextSize::ExtraSmall,TextSize::Medium, orTextSize::Large.

For instance, you may make the text larger usingsize(TextSize::Large):

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\TextSize;

TextEntry::make('title')
    ->size(TextSize::Large)

```

## #Customizing the font weight

Text entries have regular font weight by default, but you may change this to any of the following options:FontWeight::Thin,FontWeight::ExtraLight,FontWeight::Light,FontWeight::Medium,FontWeight::SemiBold,FontWeight::Bold,FontWeight::ExtraBoldorFontWeight::Black.

For instance, you may make the font bold usingweight(FontWeight::Bold):

```php
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;

TextEntry::make('title')
    ->weight(FontWeight::Bold)

```

## #Customizing the font family

You can change the text font family to any of the following options:FontFamily::Sans,FontFamily::SeriforFontFamily::Mono.

For instance, you may make the font monospaced usingfontFamily(FontFamily::Mono):

```php
use Filament\Support\Enums\FontFamily;
use Filament\Infolists\Components\TextEntry;

TextEntry::make('apiKey')
    ->label('API key')
    ->fontFamily(FontFamily::Mono)

```

## #Handling long text

### #Limiting text length

You maylimit()the length of the entry’s value:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->limit(50)

```

By default, when text is truncated, an ellipsis (...) is appended to the end of the text. You may customize this by passing a custom string to theendargument:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->limit(50, end: ' (more)')

```

You may also reuse the value that is being passed tolimit()in a function, by getting it using thegetCharacterLimit()method:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->limit(50)
    ->tooltip(function (TextEntry $component): ?string {
        $state = $component->getState();

        if (strlen($state) <= $component->getCharacterLimit()) {
            return null;
        }

        // Only render the tooltip if the entry contents exceeds the length limit.
        return $state;
    })

```

### #Limiting word count

You may limit the number ofwords()displayed in the entry:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->words(10)

```

By default, when text is truncated, an ellipsis (...) is appended to the end of the text. You may customize this by passing a custom string to theendargument:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->words(10, end: ' (more)')

```

### #Limiting text to a specific number of lines

You may want to limit text to a specific number of lines instead of limiting it to a fixed length. Clamping text to a number of lines is useful in responsive interfaces where you want to ensure a consistent experience across all screen sizes. This can be achieved using thelineClamp()method:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->lineClamp(2)

```

### #Preventing text wrapping

By default, text will wrap to the next line if it exceeds the width of the container. You can prevent this behavior using thewrap(false)method:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('description')
    ->wrap(false)

```

## #Allowing the text to be copied to the clipboard

You may make the text copyable, such that clicking on the entry copies the text to the clipboard, and optionally specify a custom confirmation message and duration in milliseconds:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('apiKey')
    ->label('API key')
    ->copyable()
    ->copyMessage('Copied!')
    ->copyMessageDuration(1500)

```

Optionally, you may pass a boolean value to control if the text should be copyable or not:

```php
use Filament\Infolists\Components\TextEntry;

TextEntry::make('apiKey')
    ->label('API key')
    ->copyable(FeatureFlag::active())

```

NOTE

This feature only works when SSL is enabled for the app.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources  
**Keywords:** read-only, display, view, presentation

*Extracted from Filament v5 Documentation - 2026-01-28*
