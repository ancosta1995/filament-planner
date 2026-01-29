# Key-value entry

**URL:** https://filamentphp.com/docs/5.x/infolists/key-value-entry  
**Section:** infolists  
**Page:** key-value-entry  
**Priority:** medium  
**AI Context:** Display read-only data in structured layouts.

---

## #Introduction

The key-value entry allows you to render key-value pairs of data, from a one-dimensional JSON object / PHP array.

```php
use Filament\Infolists\Components\KeyValueEntry;

KeyValueEntry::make('meta')

```

For example, the state of this entry might be represented as:

```php
[
    'description' => 'Filament is a collection of Laravel packages',
    'og:type' => 'website',
    'og:site_name' => 'Filament',
]

```

If you’re saving the data in Eloquent, you should be sure to add anarraycastto the model property:

```php
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    { 
        return [
            'meta' => 'array',
        ];
    }

    // ...
}

```

## #Customizing the key column’s label

You may customize the label for the key column using thekeyLabel()method:

```php
use Filament\Infolists\Components\KeyValueEntry;

KeyValueEntry::make('meta')
    ->keyLabel('Property name')

```

## #Customizing the value column’s label

You may customize the label for the value column using thevalueLabel()method:

```php
use Filament\Infolists\Components\KeyValueEntry;

KeyValueEntry::make('meta')
    ->valueLabel('Property value')

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources  
**Keywords:** read-only, display, view, presentation

*Extracted from Filament v5 Documentation - 2026-01-28*
