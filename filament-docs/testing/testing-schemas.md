# Testing schemas

**URL:** https://filamentphp.com/docs/5.x/testing/testing-schemas  
**Section:** testing  
**Page:** testing-schemas  
**Priority:** medium  
**AI Context:** Testing Filament applications with PHPUnit/Pest.

---

## #Filling a form in a test

To fill a form with data, pass the data tofillForm():

```php
use function Pest\Livewire\livewire;

livewire(CreatePost::class)
    ->fillForm([
        'title' => fake()->sentence(),
        // ...
    ]);

```
> If you have multiple schemas on a Livewire component, you can specify which form you want to fill usingfillForm([...], 'createPostForm').


If you have multiple schemas on a Livewire component, you can specify which form you want to fill usingfillForm([...], 'createPostForm').

## #Testing form field and infolist entry state

To check that a form has data, useassertSchemaStateSet():

```php
use Illuminate\Support\Str;
use function Pest\Livewire\livewire;

it('can automatically generate a slug from the title', function () {
    $title = fake()->sentence();

    livewire(CreatePost::class)
        ->fillForm([
            'title' => $title,
        ])
        ->assertSchemaStateSet([
            'slug' => Str::slug($title),
        ]);
});

```
> If you have multiple schemas on a Livewire component, you can specify which schema you want to check usingassertSchemaStateSet([...], 'createPostForm').


If you have multiple schemas on a Livewire component, you can specify which schema you want to check usingassertSchemaStateSet([...], 'createPostForm').

You may also find it useful to pass a function to theassertSchemaStateSet()method, which allows you to access the form$stateand perform additional assertions:

```php
use Illuminate\Support\Str;
use function Pest\Livewire\livewire;

it('can automatically generate a slug from the title without any spaces', function () {
    $title = fake()->sentence();

    livewire(CreatePost::class)
        ->fillForm([
            'title' => $title,
        ])
        ->assertSchemaStateSet(function (array $state): array {
            expect($state['slug'])
                ->not->toContain(' ');
                
            return [
                'slug' => Str::slug($title),
            ];
        });
});

```

You can return an array from the function if you want Filament to continue to assert the schema state after the function has been run.

## #Testing form validation

UseassertHasFormErrors()to ensure that data is properly validated in a form:

```php
use function Pest\Livewire\livewire;

it('can validate input', function () {
    livewire(CreatePost::class)
        ->fillForm([
            'title' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

```

AndassertHasNoFormErrors()to ensure there are no validation errors:

```php
use function Pest\Livewire\livewire;

livewire(CreatePost::class)
    ->fillForm([
        'title' => fake()->sentence(),
        // ...
    ])
    ->call('create')
    ->assertHasNoFormErrors();

```
> If you have multiple schemas on a Livewire component, you can pass the name of a specific form as the second parameter likeassertHasFormErrors(['title' => 'required'], 'createPostForm')orassertHasNoFormErrors([], 'createPostForm').


If you have multiple schemas on a Livewire component, you can pass the name of a specific form as the second parameter likeassertHasFormErrors(['title' => 'required'], 'createPostForm')orassertHasNoFormErrors([], 'createPostForm').

## #Testing the existence of a form

To check that a Livewire component has a form, useassertFormExists():

```php
use function Pest\Livewire\livewire;

it('has a form', function () {
    livewire(CreatePost::class)
        ->assertFormExists();
});

```
> If you have multiple schemas on a Livewire component, you can pass the name of a specific form likeassertFormExists('createPostForm').


If you have multiple schemas on a Livewire component, you can pass the name of a specific form likeassertFormExists('createPostForm').

## #Testing the existence of form fields

To ensure that a form has a given field, pass the field name toassertFormFieldExists():

```php
use function Pest\Livewire\livewire;

it('has a title field', function () {
    livewire(CreatePost::class)
        ->assertFormFieldExists('title');
});

```

You may pass a function as an additional argument to assert that a field passes a given “truth test”. This is useful for asserting that a field has a specific configuration:

```php
use function Pest\Livewire\livewire;

it('has a title field', function () {
    livewire(CreatePost::class)
        ->assertFormFieldExists('title', function (TextInput $field): bool {
            return $field->isDisabled();
        });
});

```

To assert that a form does not have a given field, pass the field name toassertFormFieldDoesNotExist():

```php
use function Pest\Livewire\livewire;

it('does not have a conditional field', function () {
    livewire(CreatePost::class)
        ->assertFormFieldDoesNotExist('no-such-field');
});

```
> If you have multiple schemas on a Livewire component, you can specify which form you want to check for the existence of the field likeassertFormFieldExists('title', 'createPostForm').


If you have multiple schemas on a Livewire component, you can specify which form you want to check for the existence of the field likeassertFormFieldExists('title', 'createPostForm').

## #Testing the visibility of form fields

To ensure that a field is visible, pass the name toassertFormFieldVisible():

```php
use function Pest\Livewire\livewire;

test('title is visible', function () {
    livewire(CreatePost::class)
        ->assertFormFieldVisible('title');
});

```

Or to ensure that a field is hidden you can pass the name toassertFormFieldHidden():

```php
use function Pest\Livewire\livewire;

test('title is hidden', function () {
    livewire(CreatePost::class)
        ->assertFormFieldHidden('title');
});

```
> For bothassertFormFieldHidden()andassertFormFieldVisible()you can pass the name of a specific form the field belongs to as the second argument likeassertFormFieldHidden('title', 'createPostForm').


For bothassertFormFieldHidden()andassertFormFieldVisible()you can pass the name of a specific form the field belongs to as the second argument likeassertFormFieldHidden('title', 'createPostForm').

## #Testing disabled form fields

To ensure that a field is enabled, pass the name toassertFormFieldEnabled():

```php
use function Pest\Livewire\livewire;

test('title is enabled', function () {
    livewire(CreatePost::class)
        ->assertFormFieldEnabled('title');
});

```

Or to ensure that a field is disabled you can pass the name toassertFormFieldDisabled():

```php
use function Pest\Livewire\livewire;

test('title is disabled', function () {
    livewire(CreatePost::class)
        ->assertFormFieldDisabled('title');
});

```
> For bothassertFormFieldEnabled()andassertFormFieldDisabled()you can pass the name of a specific form the field belongs to as the second argument likeassertFormFieldEnabled('title', 'createPostForm').


For bothassertFormFieldEnabled()andassertFormFieldDisabled()you can pass the name of a specific form the field belongs to as the second argument likeassertFormFieldEnabled('title', 'createPostForm').

## #Testing other schema components

If you need to check if a particular schema component exists rather than a field, you may useassertSchemaComponentExists().  As components do not have names, this method uses thekey()provided by the developer:

```php
use Filament\Schemas\Components\Section;

Section::make('Comments')
    ->key('comments-section')
    ->schema([
        //
    ])

```

```php
use function Pest\Livewire\livewire;

test('comments section exists', function () {
    livewire(EditPost::class)
        ->assertSchemaComponentExists('comments-section');
});

```

To assert that a schema does not have a given component, pass the component key toassertSchemaComponentDoesNotExist():

```php
use function Pest\Livewire\livewire;

it('does not have a conditional component', function () {
    livewire(CreatePost::class)
        ->assertSchemaComponentDoesNotExist('no-such-section');
});

```

To check if the component exists and passes a given truth test, you can pass a function to thecheckComponentUsingargument ofassertSchemaComponentExists(), returning true or false if the component passes the test or not:

```php
use Filament\Schemas\Components\Section;

use function Pest\Livewire\livewire;

test('comments section has heading', function () {
    livewire(EditPost::class)
        ->assertSchemaComponentExists(
            'comments-section',
            checkComponentUsing: function (Section $component): bool {
                return $component->getHeading() === 'Comments';
            },
        );
});

```

If you want more informative test results, you can embed an assertion within your truth test callback:

```php
use Filament\Schemas\Components\Section;
use Illuminate\Testing\Assert;

use function Pest\Livewire\livewire;

test('comments section is enabled', function () {
    livewire(EditPost::class)
        ->assertSchemaComponentExists(
            'comments-section',
            checkComponentUsing: function (Section $component): bool {
                Assert::assertTrue(
                    $component->isEnabled(),
                    'Failed asserting that comments-section is enabled.',
                );
                
                return true;
            },
        );
});

```

## #Testing repeaters

Internally, repeaters generate UUIDs for items to keep track of them in the Livewire HTML easier. This means that when you are testing a form with a repeater, you need to ensure that the UUIDs are consistent between the form and the test. This can be tricky, and if you don’t do it correctly, your tests can fail as the tests are expecting a UUID, not a numeric key.

However, since Livewire doesn’t need to keep track of the UUIDs in a test, you can disable the UUID generation and replace them with numeric keys, using theRepeater::fake()method at the start of your test:

```php
use Filament\Forms\Components\Repeater;
use function Pest\Livewire\livewire;

$undoRepeaterFake = Repeater::fake();

livewire(EditPost::class, ['record' => $post])
    ->assertSchemaStateSet([
        'quotes' => [
            [
                'content' => 'First quote',
            ],
            [
                'content' => 'Second quote',
            ],
        ],
        // ...
    ]);

$undoRepeaterFake();

```

You may also find it useful to test the number of items in a repeater by passing a function to theassertSchemaStateSet()method:

```php
use Filament\Forms\Components\Repeater;
use function Pest\Livewire\livewire;

$undoRepeaterFake = Repeater::fake();

livewire(EditPost::class, ['record' => $post])
    ->assertSchemaStateSet(function (array $state) {
        expect($state['quotes'])
            ->toHaveCount(2);
    });

$undoRepeaterFake();

```

### #Testing repeater actions

In order to test that repeater actions are working as expected, you can utilize thecallFormComponentAction()method to call your repeater actions and thenperform additional assertions.

To interact with an action on a particular repeater item, you need to pass in theitemargument with the key of that repeater item. If your repeater is reading from a relationship, you should prefix the ID (key) of the related record withrecord-to form the key of the repeater item:

```php
use App\Models\Quote;
use Filament\Forms\Components\Repeater;
use function Pest\Livewire\livewire;

$quote = Quote::first();

livewire(EditPost::class, ['record' => $post])
    ->callAction(TestAction::make('sendQuote')->schemaComponent('quotes')->arguments([
        'item' => "record-{$quote->getKey()}",
    ]))
    ->assertNotified('Quote sent!');

```

## #Testing builders

Internally, builders generate UUIDs for items to keep track of them in the Livewire HTML easier. This means that when you are testing a form with a builder, you need to ensure that the UUIDs are consistent between the form and the test. This can be tricky, and if you don’t do it correctly, your tests can fail as the tests are expecting a UUID, not a numeric key.

However, since Livewire doesn’t need to keep track of the UUIDs in a test, you can disable the UUID generation and replace them with numeric keys, using theBuilder::fake()method at the start of your test:

```php
use Filament\Forms\Components\Builder;
use function Pest\Livewire\livewire;

$undoBuilderFake = Builder::fake();

livewire(EditPost::class, ['record' => $post])
    ->assertSchemaStateSet([
        'content' => [
            [
                'type' => 'heading',
                'data' => [
                    'content' => 'Hello, world!',
                    'level' => 'h1',
                ],
            ],
            [
                'type' => 'paragraph',
                'data' => [
                    'content' => 'This is a test post.',
                ],
            ],
        ],
        // ...
    ]);

$undoBuilderFake();

```

You may also find it useful to access test the number of items in a repeater by passing a function to theassertSchemaStateSet()method:

```php
use Filament\Forms\Components\Builder;
use function Pest\Livewire\livewire;

$undoBuilderFake = Builder::fake();

livewire(EditPost::class, ['record' => $post])
    ->assertSchemaStateSet(function (array $state) {
        expect($state['content'])
            ->toHaveCount(2);
    });

$undoBuilderFake();

```

## #Testing wizards

To go to a wizard’s next step, usegoToNextWizardStep():

```php
use function Pest\Livewire\livewire;

it('moves to next wizard step', function () {
    livewire(CreatePost::class)
        ->goToNextWizardStep()
        ->assertHasFormErrors(['title']);
});

```

You can also go to the previous step by callinggoToPreviousWizardStep():

```php
use function Pest\Livewire\livewire;

it('moves to next wizard step', function () {
    livewire(CreatePost::class)
        ->goToPreviousWizardStep()
        ->assertHasFormErrors(['title']);
});

```

If you want to go to a specific step, usegoToWizardStep(), then theassertWizardCurrentStepmethod which can ensure you are on the desired step without validation errors from the previous:

```php
use function Pest\Livewire\livewire;

it('moves to the wizards second step', function () {
    livewire(CreatePost::class)
        ->goToWizardStep(2)
        ->assertWizardCurrentStep(2);
});

```

If you have multiple schemas on a single Livewire component, any of the wizard test helpers can accept aschemaparameter:

```php
use function Pest\Livewire\livewire;

it('moves to next wizard step only for fooForm', function () {
    livewire(CreatePost::class)
        ->goToNextWizardStep(schema: 'fooForm')
        ->assertHasFormErrors(['title'], schema: 'fooForm');
});

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, forms, tables  
**Keywords:** tests, phpunit, pest, quality

*Extracted from Filament v5 Documentation - 2026-01-28*
