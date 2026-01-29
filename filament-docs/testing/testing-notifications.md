# Testing notifications

**URL:** https://filamentphp.com/docs/5.x/testing/testing-notifications  
**Section:** testing  
**Page:** testing-notifications  
**Priority:** medium  
**AI Context:** Testing Filament applications with PHPUnit/Pest.

---

## #Testing session notifications

To check if a notification was sent using the session, use theassertNotified()helper:

```php
use function Pest\Livewire\livewire;

it('sends a notification', function () {
    livewire(CreatePost::class)
        ->assertNotified();
});

```

```php
use Filament\Notifications\Notification;

it('sends a notification', function () {
    Notification::assertNotified();
});

```

```php
use function Filament\Notifications\Testing\assertNotified;

it('sends a notification', function () {
    assertNotified();
});

```

You may optionally pass a notification title to test for:

```php
use Filament\Notifications\Notification;
use function Pest\Livewire\livewire;

it('sends a notification', function () {
    livewire(CreatePost::class)
        ->assertNotified('Unable to create post');
});

```

Or test if the exact notification was sent:

```php
use Filament\Notifications\Notification;
use function Pest\Livewire\livewire;

it('sends a notification', function () {
    livewire(CreatePost::class)
        ->assertNotified(
            Notification::make()
                ->danger()
                ->title('Unable to create post')
                ->body('Something went wrong.'),
        );
});

```

Conversely, you can assert that a notification was not sent:

```php
use Filament\Notifications\Notification;
use function Pest\Livewire\livewire;

it('does not send a notification', function () {
    livewire(CreatePost::class)
        ->assertNotNotified()
        // or
        ->assertNotNotified('Unable to create post')
        // or
        ->assertNotNotified(
            Notification::make()
                ->danger()
                ->title('Unable to create post')
                ->body('Something went wrong.'),
        );

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, forms, tables  
**Keywords:** tests, phpunit, pest, quality

*Extracted from Filament v5 Documentation - 2026-01-28*
