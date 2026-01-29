# Code entry

**URL:** https://filamentphp.com/docs/5.x/infolists/code-entry  
**Section:** infolists  
**Page:** code-entry  
**Priority:** medium  
**AI Context:** Display read-only data in structured layouts.

---

## #Introduction

The code entry allows you to present a highlighted code snippet in your infolist. It usesPhikifor code highlighting on the server:

```php
use Filament\Infolists\Components\CodeEntry;
use Phiki\Grammar\Grammar;

CodeEntry::make('code')
    ->grammar(Grammar::Php)

```

To use the code entry, you need to first install thephiki/phikiComposer package. Filament does not include it by default to allow you to choose which major version of Phiki to use explicitly, since major versions can have different grammars and themes available. You can install the latest version of Phiki using the following command:

```php
composer require phiki/phiki

```

## #Changing the code’s grammar (language)

You may change the grammar (language) of the code using thegrammar()method. Over 200 grammars are available, and you can open thePhiki\Grammar\Grammarenum class to see the full list. To switch to use JavaScript as the grammar, you can use theGrammar::Javascriptenum value:

```php
use Filament\Infolists\Components\CodeEntry;
use Phiki\Grammar\Grammar;

CodeEntry::make('code')
    ->grammar(Grammar::Javascript)

```

TIP

If your code entry’s content is a PHP array, it will automatically be converted to a JSON string, and the grammar will be set toGrammar::Json. You can customize theJSON_flags used during conversion with thejsonFlags()method.

## #Changing the code’s theme (highlighting)

You may change the theme of the code using thelightTheme()anddarkTheme()methods. Over 50 themes are available, and you can open thePhiki\Theme\Themeenum class to see the full list. To use the popularDraculatheme, you can use theTheme::Draculaenum value:

```php
use Filament\Infolists\Components\CodeEntry;
use Phiki\Theme\Theme;

CodeEntry::make('code')
    ->lightTheme(Theme::Dracula)
    ->darkTheme(Theme::Dracula)

```

## #Allowing the code to be copied to the clipboard

You may make the code copyable, such that clicking on it copies the code to the clipboard, and optionally specify a custom confirmation message and duration in milliseconds. This feature only works when SSL is enabled for the app.

```php
use Filament\Infolists\Components\CodeEntry;

CodeEntry::make('code')
    ->copyable()
    ->copyMessage('Copied!')
    ->copyMessageDuration(1500)

```

Optionally, you may pass a boolean value to control if the code should be copyable or not:

```php
use Filament\Infolists\Components\CodeEntry;

CodeEntry::make('code')
    ->copyable(FeatureFlag::active())

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources  
**Keywords:** read-only, display, view, presentation

*Extracted from Filament v5 Documentation - 2026-01-28*
