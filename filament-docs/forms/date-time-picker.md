# Date-time picker

**URL:** https://filamentphp.com/docs/5.x/forms/date-time-picker  
**Section:** forms  
**Page:** date-time-picker  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The date-time picker provides an interactive interface for selecting a date and/or a time.

```php
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;

DateTimePicker::make('published_at')
DatePicker::make('date_of_birth')
TimePicker::make('alarm_at')

```

## #Customizing the storage format

You may customize the format of the field when it is saved in your database, using theformat()method. This accepts a string date format, usingPHP date formatting tokens:

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date_of_birth')
    ->format('d/m/Y')

```

## #Disabling the seconds input

When using the time picker, you may disable the seconds input using theseconds(false)method:

```php
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('published_at')
    ->seconds(false)

```

## #Timezones

If you’d like users to be able to manage dates in their own timezone, you can use thetimezone()method:

```php
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('published_at')
    ->timezone('America/New_York')

```

While dates will still be stored using the app’s configured timezone, the date will now load in the new timezone, and it will be converted back when the form is saved.

If you do not pass atimezone()to the component, it will use Filament’s default timezone. You can set Filament’s default timezone using theFilamentTimezone::set()method in theboot()method of a service provider such asAppServiceProvider:

```php
use Filament\Support\Facades\FilamentTimezone;

public function boot(): void
{
    FilamentTimezone::set('America/New_York');
}

```

This is useful if you want to set a default timezone for all date-time pickers in your application. It is also used in other places where timezones are used in Filament.

NOTE

Filament’s default timezone will only apply when the field stores a time. If the field stores a date only (DatePickerinstead ofDateTimePickerorTimePicker), the timezone will not be applied. This is to prevent timezone shifts when storing dates without times.

## #Enabling the JavaScript date picker

By default, Filament uses the native HTML5 date picker. You may enable a more customizable JavaScript date picker using thenative(false)method:

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date_of_birth')
    ->native(false)

```

NOTE

The JavaScript date picker does not support full keyboard input in the same way that the native date picker does. If you require full keyboard input, you should use the native date picker.

### #Customizing the display format

You may customize the display format of the field, separately from the format used when it is saved in your database. For this, use thedisplayFormat()method, which also accepts a string date format, usingPHP date formatting tokens:

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date_of_birth')
    ->native(false)
    ->displayFormat('d/m/Y')

```

You may also configure the locale that is used when rendering the display, if you want to use different locale from your app config. For this, you can use thelocale()method:

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date_of_birth')
    ->native(false)
    ->displayFormat('d F Y')
    ->locale('fr')

```

### #Configuring the time input intervals

You may customize the input interval for increasing/decreasing the hours/minutes /seconds using thehoursStep(),minutesStep()orsecondsStep()methods:

```php
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('published_at')
    ->native(false)
    ->hoursStep(2)
    ->minutesStep(15)
    ->secondsStep(10)

```

### #Configuring the first day of the week

In some countries, the first day of the week is not Monday. To customize the first day of the week in the date picker, use thefirstDayOfWeek()method on the component. 0 to 7 are accepted values, with Monday as 1 and Sunday as 7 or 0:

```php
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('published_at')
    ->native(false)
    ->firstDayOfWeek(7)

```

There are additionally convenient helper methods to set the first day of the week more semantically:

```php
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('published_at')
    ->native(false)
    ->weekStartsOnMonday()

DateTimePicker::make('published_at')
    ->native(false)
    ->weekStartsOnSunday()

```

### #Disabling specific dates

To prevent specific dates from being selected:

```php
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('date')
    ->native(false)
    ->disabledDates(['2000-01-03', '2000-01-15', '2000-01-20'])

```

### #Closing the picker when a date is selected

To close the picker when a date is selected, you can use thecloseOnDateSelection()method:

```php
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('date')
    ->native(false)
    ->closeOnDateSelection()

```

Optionally, you may pass a boolean value to control if the input should close when a date is selected or not:

```php
use Filament\Forms\Components\DateTimePicker;

DateTimePicker::make('date')
    ->native(false)
    ->closeOnDateSelection(FeatureFlag::active())

```

## #Autocompleting dates with a datalist

Unless you’re using theJavaScript date picker, you may specifydatalistoptions for a date picker using thedatalist()method:

```php
use Filament\Forms\Components\TimePicker;

TimePicker::make('appointment_at')
    ->datalist([
        '09:00',
        '09:30',
        '10:00',
        '10:30',
        '11:00',
        '11:30',
        '12:00',
    ])

```

Datalists provide autocomplete options to users when they use the picker. However, these are purely recommendations, and the user is still able to type any value into the input. If you’re looking to strictly limit users to a set of predefined options, check out theselect field.

### #Focusing a default calendar date

By default, if the field has no state, opening the calendar panel will open the calendar at the current date. This might not be convenient for situations where you want to open the calendar on a specific date instead. You can use thedefaultFocusedDate()to set a default focused date on the calendar:

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('custom_starts_at')
    ->native(false)
    ->placeholder(now()->startOfMonth())
    ->defaultFocusedDate(now()->startOfMonth())

```

## #Adding affix text aside the field

You may place text before and after the input using theprefix()andsuffix()methods:

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date')
    ->prefix('Starts')
    ->suffix('at midnight')

```

### #Using icons as affixes

You may place aniconbefore and after the input using theprefixIcon()andsuffixIcon()methods:

```php
use Filament\Forms\Components\TimePicker;
use Filament\Support\Icons\Heroicon;

TimePicker::make('at')
    ->prefixIcon(Heroicon::Play)

```

#### #Setting the affix icon’s color

Affix icons are gray by default, but you may set a different color using theprefixIconColor()andsuffixIconColor()methods:

```php
use Filament\Forms\Components\TimePicker;
use Filament\Support\Icons\Heroicon;

TimePicker::make('at')
    ->prefixIcon(Heroicon::CheckCircle)
    ->prefixIconColor('success')

```

## #Making the field read-only

Not to be confused withdisabling the field, you may make the field “read-only” using thereadonly()method:

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date_of_birth')
    ->readonly()

```

Please note that this setting is only enforced on native date pickers. If you’re using theJavaScript date picker, you’ll need to usedisabled().

There are a few differences, compared todisabled():
- When usingreadOnly(), the field will still be sent to the server when the form is submitted. It can be mutated with the browser console, or via JavaScript. You can usesaved(false)to prevent this.
- There are no styling changes, such as less opacity, when usingreadOnly().
- The field is still focusable when usingreadOnly().


Optionally, you may pass a boolean value to control if the field should be read-only or not:

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date_of_birth')
    ->readOnly(FeatureFlag::active())

```

## #Date-time picker validation

As well as all rules listed on thevalidationpage, there are additional rules that are specific to date-time pickers.

### #Max date / min date validation

You may restrict the minimum and maximum date that can be selected with the picker. TheminDate()andmaxDate()methods accept aDateTimeinstance (e.g.Carbon), or a string:

```php
use Filament\Forms\Components\DatePicker;

DatePicker::make('date_of_birth')
    ->native(false)
    ->minDate(now()->subYears(150))
    ->maxDate(now())

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
