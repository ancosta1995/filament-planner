# Prime components

**URL:** https://filamentphp.com/docs/5.x/schemas/primes  
**Section:** schemas  
**Page:** primes  
**Priority:** medium  
**AI Context:** Layout system for building complex UIs with sections, tabs, wizards.

---

## #Introduction

Within Filament schemas, prime components are the most basic building blocks that can be used to insert arbitrary content into a schema, such as text and images. As the name suggests, prime components are not divisible and cannot be made simpler. Filament provides a set of built-in prime components:
- Text
- Icon
- Image
- Unordered list


You may alsocreate your own custom componentsto add your own arbitrary content to a schema.

In this example, prime components are being used to display instructions to the user, a QR code that the user can scan, and list of recovery codes that the user can save:

```php
use Filament\Actions\Action;
use Filament\Schemas\Components\Image;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\UnorderedList;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;

$schema
    ->components([
        Text::make('Scan this QR code with your authenticator app:')
            ->color('neutral'),
        Image::make(
            url: asset('images/qr.jpg'),
            alt: 'QR code to scan with an authenticator app',
        )
            ->imageHeight('12rem')
            ->alignCenter(),
        Section::make()
            ->schema([
                Text::make('Please save the following recovery codes in a safe place. They will only be shown once, but you\'ll need them if you lose access to your authenticator app:')
                    ->weight(FontWeight::Bold)
                    ->color('neutral'),
                UnorderedList::make(fn (): array => array_map(
                    fn (string $recoveryCode): Text => Text::make($recoveryCode)
                        ->copyable()
                        ->fontFamily(FontFamily::Mono)
                        ->size(TextSize::ExtraSmall)
                        ->color('neutral'),
                    ['tYRnCqNLUx-3QOLNKyDiV', 'cKok2eImKc-oWHHH4VhNe', 'C0ZstEcSSB-nrbyk2pv8z', '49EXLRQ8MI-FpWywpSDHE', 'TXjHnvkUrr-KuiVJENPmJ', 'BB574ookll-uI20yxP6oa', 'BbgScF2egu-VOfHrMtsCl', 'cO0dJYqmee-S9ubJHpRFR'],
                ))
                    ->size(TextSize::ExtraSmall),
            ])
            ->compact()
            ->secondary(),
    ])

```

Although text can be rendered in a schema using aninfolist text entry, entries are intended to render a label-value detail about an entity (like an Eloquent model), and not to render arbitrary text. Prime components are more suitable for this purpose. Infolists can be considered more similar todescription listsin HTML.

Prime component classes can be found in theFilament\Schemas\Componentsnamespace. They reside within the schema array of components.

## #Text component

Text can be inserted into a schema using theTextcomponent. Text content is passed to themake()method:

```php
use Filament\Schemas\Components\Text;

Text::make('Modifying these permissions may give users access to sensitive information.')

```

To render raw HTML content, you can pass anHtmlStringobject to themake()method:

```php
use Filament\Schemas\Components\Text;
use Illuminate\Support\HtmlString;

Text::make(new HtmlString('<strong>Warning:</strong> Modifying these permissions may give users access to sensitive information.'))

```

NOTE

Be aware that you will need to ensure that the HTML is safe to render, otherwise your application will be vulnerable to XSS attacks.

To render Markdown, you can use Laravel’sstr()helper to convert Markdown to HTML, and then transform it into anHtmlStringobject:

```php
use Filament\Schemas\Components\Text;

Text::make(
    str('**Warning:** Modifying these permissions may give users access to sensitive information.')
        ->inlineMarkdown()
        ->toHtmlString(),
)

```

### #Customizing the text color

You may set acolorfor the text:

```php
use Filament\Schemas\Components\Text;

Text::make('Information')
    ->color('info')

```

### #Using a neutral color

By default, the text color is set togray, which is typically fairly dim against its background. You can darken it using thecolor('neutral')method:

```php
use Filament\Schemas\Components\Text;

Text::make('Modifying these permissions may give users access to sensitive information.')

Text::make('Modifying these permissions may give users access to sensitive information.')
    ->color('neutral')

```

### #Displaying as a “badge”

By default, text is quite plain and has no background color. You can make it appear as a “badge” instead using thebadge()method. A great use case for this is with statuses, where may want to display a badge with acolorthat matches the status:

```php
use Filament\Schemas\Components\Text;

Text::make('Warning')
    ->color('warning')
    ->badge()

```

Optionally, you may pass a boolean value to control if the text should be in a badge or not:

```php
use Filament\Schemas\Components\Text;

Text::make('Warning')
    ->color('warning')
    ->badge(FeatureFlag::active())

```

#### #Adding an icon to the badge

You may add other things to the badge, like anicon:

```php
use Filament\Schemas\Components\Text;
use Filament\Support\Icons\Heroicon;

Text::make('Warning')
    ->color('warning')
    ->badge()
    ->icon(Heroicon::ExclamationTriangle)

```

### #Customizing the text size

Text has a small font size by default, but you may change this toTextSize::ExtraSmall,TextSize::Medium, orTextSize::Large.

For instance, you may make the text larger usingsize(TextSize::Large):

```php
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\TextSize;

Text::make('Modifying these permissions may give users access to sensitive information.')
    ->size(TextSize::Large)

```

### #Customizing the font weight

Text entries have regular font weight by default, but you may change this to any of the following options:FontWeight::Thin,FontWeight::ExtraLight,FontWeight::Light,FontWeight::Medium,FontWeight::SemiBold,FontWeight::Bold,FontWeight::ExtraBoldorFontWeight::Black.

For instance, you may make the font bold usingweight(FontWeight::Bold):

```php
use Filament\Schemas\Components\Text;
use Filament\Support\Enums\FontWeight;

Text::make('Modifying these permissions may give users access to sensitive information.')
    ->weight(FontWeight::Bold)

```

### #Customizing the font family

You can change the text font family to any of the following options:FontFamily::Sans,FontFamily::SeriforFontFamily::Mono.

For instance, you may make the font monospaced usingfontFamily(FontFamily::Mono):

```php
use Filament\Support\Enums\FontFamily;
use Filament\Schemas\Components\Text;

Text::make('28o.-AK%D~xh*.:[4"3)zPiC')
    ->fontFamily(FontFamily::Mono)

```

### #Adding a tooltip to the text

You may add a tooltip to the text using thetooltip()method:

```php
use Filament\Schemas\Components\Text;

Text::make('28o.-AK%D~xh*.:[4"3)zPiC')
    ->tooltip('Your secret recovery code')

```

### #Using JavaScript to determine the content of the text

You can use JavaScript to determine the content of the text. This is useful if you want to display a different message depending on the state of aform field, without making a request to the server to re-render the schema. To allow this, you can use thejs()method:

```php
use Filament\Schemas\Components\Text;

Text::make(<<<'JS'
    $get('name') ? `Hello, ${$get('name')}` : 'Please enter your name.'
    JS)
    ->js()

```

The$stateand$get()utilities are available in the JavaScript context, so you can use them to get the state of fields in the schema.

## #Icon component

Icons can be inserted into a schema using theIconcomponent.Iconsare passed to themake()method:

```php
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

Icon::make(Heroicon::Star)

```

### #Customizing the icon color

You may set acolorfor the icon:

```php
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

Icon::make(Heroicon::ExclamationCircle)
    ->color('danger')

```

### #Adding a tooltip to the icon

You may add a tooltip to the icon using thetooltip()method:

```php
use Filament\Schemas\Components\Icon;
use Filament\Support\Icons\Heroicon;

Icon::make(Heroicon::ExclamationTriangle)
    ->tooltip('Warning')

```

## #Image component

Images can be inserted into a schema using theImagecomponent. The image URL and alt text are passed to themake()method:

```php
use Filament\Schemas\Components\Image;

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)

```

### #Customizing the image size

You may customize the image size by passing aimageWidth()andimageHeight(), or both withimageSize():

```php
use Filament\Schemas\Components\Image;

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->imageWidth('12rem')

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->imageHeight('12rem')

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->imageSize('12rem')

```

### #Aligning the image

You may align the image to the start (left in left-to-right interfaces, right in right-to-left interfaces), center, or end (right in left-to-right interfaces, left in right-to-left interfaces) using thealignStart(),alignCenter()oralignEnd()methods:

```php
use Filament\Schemas\Components\Image;

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->alignStart() // This is the default alignment.

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->alignCenter()

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->alignEnd()

```

Alternatively, you may pass anAlignmentenum to thealignment()method:

```php
use Filament\Schemas\Components\Image;
use Filament\Support\Enums\Alignment;

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->alignment(Alignment::Center)

```

### #Adding a tooltip to the image

You may add a tooltip to the image using thetooltip()method:

```php
use Filament\Schemas\Components\Image;

Image::make(
    url: asset('images/qr.jpg'),
    alt: 'QR code to scan with an authenticator app',
)
    ->tooltip('Scan this QR code with your authenticator app')
    ->alignCenter()

```

## #Unordered list component

Unordered lists can be inserted into a schema using theUnorderedListcomponent. The list items, comprising plain text ortext components, are passed to themake()method:

```php
use Filament\Schemas\Components\UnorderedList;

UnorderedList::make([
    'Tables',
    'Schemas',
    'Actions',
    'Notifications',
])

```

Text components can be used as list items, which allows you to customize the formatting of each item:

```php
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\UnorderedList;
use Filament\Support\Enums\FontFamily;

UnorderedList::make([
    Text::make('Tables')->fontFamily(FontFamily::Mono),
    Text::make('Schemas')->fontFamily(FontFamily::Mono),
    Text::make('Actions')->fontFamily(FontFamily::Mono),
    Text::make('Notifications')->fontFamily(FontFamily::Mono),
])

```

### #Customizing the bullet size

If you are modifying the text size of the list content, you will probably want to adjust the size of the bullets to match. To do this, you can use thesize()method. Bullets have small font size by default, but you may change this toTextSize::ExtraSmall,TextSize::Medium, orTextSize::Large.

For instance, you may make the bullets larger usingsize(TextSize::Large):

```php
use Filament\Schemas\Components\Text;
use Filament\Schemas\Components\UnorderedList;

UnorderedList::make([
    Text::make('Tables')->size(TextSize::Large),
    Text::make('Schemas')->size(TextSize::Large),
    Text::make('Actions')->size(TextSize::Large),
    Text::make('Notifications')->size(TextSize::Large),
])
    ->size(TextSize::Large)

```

## #Adding extra HTML attributes to a prime component

You can pass extra HTML attributes to the component via theextraAttributes()method, which will be merged onto its outer HTML element. The attributes should be represented by an array, where the key is the attribute name and the value is the attribute value:

```php
use Filament\Schemas\Components\Text;

Text::make('Modifying these permissions may give users access to sensitive information.')
    ->extraAttributes(['class' => 'custom-text-style'])

```

By default, callingextraAttributes()multiple times will overwrite the previous attributes. If you wish to merge the attributes instead, you can passmerge: trueto the method.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, infolists  
**Keywords:** layout, structure, organization, ui

*Extracted from Filament v5 Documentation - 2026-01-28*
