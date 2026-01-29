# Testing actions

**URL:** https://filamentphp.com/docs/5.x/testing/testing-actions  
**Section:** testing  
**Page:** testing-actions  
**Priority:** medium  
**AI Context:** Testing Filament applications with PHPUnit/Pest.

---

## #Calling an action in a test

You can call an action by passing its name or class tocallAction():

```php
use function Pest\Livewire\livewire;

it('can send invoices', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->callAction('send');

    expect($invoice->refresh())
        ->isSent()->toBeTrue();
});

```

## #Testing table actions

To test table actions, you can use aTestActionobject with thetable()method. This object receives the name of the action you want to test, and replaces the name of the action in any testing method you want to use. For example:

```php
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

$invoice = Invoice::factory()->create();

livewire(ListInvoices::class)
    ->callAction(TestAction::make('send')->table($invoice));

livewire(ListInvoices::class)
    ->assertActionVisible(TestAction::make('send')->table($invoice))

livewire(ListInvoices::class)
    ->assertActionExists(TestAction::make('send')->table($invoice))

```

### #Testing table header actions

To test a header action, you can use thetable()method without passing in a specific record to test with:

```php
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

livewire(ListInvoices::class)
    ->callAction(TestAction::make('create')->table());

livewire(ListInvoices::class)
    ->assertActionVisible(TestAction::make('create')->table())

livewire(ListInvoices::class)
    ->assertActionExists(TestAction::make('create')->table())

```

### #Testing table bulk actions

To test a bulk action, first callselectTableRecords()and pass in any records you want to select. Then, use theTestAction’sbulk()method to specify the action you want to test. For example:

```php
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

$invoices = Invoice::factory()->count(3)->create();

livewire(ListInvoices::class)
    ->selectTableRecords($invoices->pluck('id')->toArray())
    ->callAction(TestAction::make('send')->table()->bulk());

livewire(ListInvoices::class)
    ->assertActionVisible(TestAction::make('send')->table()->bulk())

livewire(ListInvoices::class)
    ->assertActionExists(TestAction::make('send')->table()->bulk())

```

## #Testing actions in a schema

If an action belongs to a component in a resource’s infolist, for example, if it is in thebelowContent()method of an infolist entry, you can use theTestActionobject with theschemaComponent()method. This object receives the name of the action you want to test and replaces the name of the action in any testing method you want to use. For example:

```php
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

$invoice = Invoice::factory()->create();

livewire(EditInvoice::class)
    ->callAction(TestAction::make('send')->schemaComponent('customer_id'));

livewire(EditInvoice::class)
    ->assertActionVisible(TestAction::make('send')->schemaComponent('customer_id'))

livewire(EditInvoice::class)
    ->assertActionExists(TestAction::make('send')->schemaComponent('customer_id'))

```

## #Testing actions inside another action’s schema / form

If an action belongs to a component in another action’sschema()(orform()), for example, if it is in thebelowContent()method of a form field in an action modal, you can use theTestActionobject with theschemaComponent()method. This object receives the name of the action you want to test and replaces the name of the action in any testing method you want to use. You should pass an array ofTestActionobjects in order, for example:

```php
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

$invoice = Invoice::factory()->create();

livewire(ManageInvoices::class)
    ->callAction([
        TestAction::make('view')->table($invoice),
        TestAction::make('send')->schemaComponent('customer.name'),
    ]);
    
livewire(ManageInvoices::class)
    ->assertActionVisible([
        TestAction::make('view')->table($invoice),
        TestAction::make('send')->schemaComponent('customer.name'),
    ]);
    
livewire(ManageInvoices::class)
    ->assertActionExists([
        TestAction::make('view')->table($invoice),
        TestAction::make('send')->schemaComponent('customer.name'),
    ]);

```

## #Testing resourcegetFormActions()

For details on how to test custom actions in thegetFormActions()of a resource page, refer to theTesting resourcesdocumentation.

## #Testing forms in action modals

To pass an array of data into an action, use thedataparameter:

```php
use function Pest\Livewire\livewire;

it('can send invoices', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->callAction('send', data: [
            'email' => $email = fake()->email(),
        ])
        ->assertHasNoFormErrors();

    expect($invoice->refresh())
        ->isSent()->toBeTrue()
        ->recipient_email->toBe($email);
});

```

If you ever need to only set an action’s data without immediately calling it, you can usefillForm():

```php
use function Pest\Livewire\livewire;

it('can send invoices', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->mountAction('send')
        ->fillForm([
            'email' => $email = fake()->email(),
        ])
});

```

### #Testing validation errors in an action modal’s form

assertHasNoFormErrors()is used to assert that no validation errors occurred when submitting the action form.

To check if a validation error has occurred with the data, useassertHasFormErrors(), similar toassertHasErrors()in Livewire:

```php
use function Pest\Livewire\livewire;

it('can validate invoice recipient email', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->callAction('send', data: [
            'email' => Str::random(),
        ])
        ->assertHasFormErrors(['email' => ['email']]);
});

```

To check if an action is pre-filled with data, you can use theassertSchemaStateSet()method:

```php
use function Pest\Livewire\livewire;

it('can send invoices to the primary contact by default', function () {
    $invoice = Invoice::factory()->create();
    $recipientEmail = $invoice->company->primaryContact->email;

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->mountAction('send')
        ->assertSchemaStateSet([
            'email' => $recipientEmail,
        ])
        ->callMountedAction()
        ->assertHasNoFormErrors();

    expect($invoice->refresh())
        ->isSent()->toBeTrue()
        ->recipient_email->toBe($recipientEmail);
});

```

## #Testing the content of an action modal

To assert the content of a modal, you should first mount the action (rather than call it which closes the modal). You can then useassertMountedActionModalSee(),assertMountedActionModalDontSee(),assertMountedActionModalSeeHtml()orassertMountedActionModalDontSeeHtml()to assert the modal contains the content that you expect it to:

```php
use function Pest\Livewire\livewire;

it('confirms the target address before sending', function () {
    $invoice = Invoice::factory()->create();
    $recipientEmail = $invoice->company->primaryContact->email;

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->mountAction('send')
        ->assertMountedActionModalSee($recipientEmail);
});

```

## #Testing the existence of an action

To ensure that an action exists or doesn’t, you can use theassertActionExists()orassertActionDoesNotExist()method:

```php
use function Pest\Livewire\livewire;

it('can send but not unsend invoices', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionExists('send')
        ->assertActionDoesNotExist('unsend');
});

```

You may pass a function as an additional argument to assert that an action passes a given “truth test”. This is useful for asserting that an action has a specific configuration:

```php
use Filament\Actions\Action;
use function Pest\Livewire\livewire;

it('has the correct description', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionExists('send', function (Action $action): bool {
            return $action->getModalDescription() === 'This will send an email to the customer\'s primary address, with the invoice attached as a PDF';
        });
});

```

## #Testing the visibility of an action

To ensure an action is hidden or visible for a user, you can use theassertActionHidden()orassertActionVisible()methods:

```php
use function Pest\Livewire\livewire;

it('can only print invoices', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionHidden('send')
        ->assertActionVisible('print');
});

```

## #Testing disabled actions

To ensure an action is enabled or disabled for a user, you can use theassertActionEnabled()orassertActionDisabled()methods:

```php
use function Pest\Livewire\livewire;

it('can only print a sent invoice', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionDisabled('send')
        ->assertActionEnabled('print');
});

```

To ensure sets of actions exist in the correct order, you can useassertActionsExistInOrder():

```php
use function Pest\Livewire\livewire;

it('can have actions in order', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionsExistInOrder(['send', 'export']);
});

```

To check if an action is hidden to a user, you can use theassertActionHidden()method:

```php
use function Pest\Livewire\livewire;

it('can not send invoices', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionHidden('send');
});

```

## #Testing the label of an action

To ensure an action has the correct label, you can useassertActionHasLabel()andassertActionDoesNotHaveLabel():

```php
use function Pest\Livewire\livewire;

it('send action has correct label', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionHasLabel('send', 'Email Invoice')
        ->assertActionDoesNotHaveLabel('send', 'Send');
});

```

## #Testing the icon of an action

To ensure an action’s button is showing the correct icon, you can useassertActionHasIcon()orassertActionDoesNotHaveIcon():

```php
use function Pest\Livewire\livewire;

it('when enabled the send button has correct icon', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionEnabled('send')
        ->assertActionHasIcon('send', 'envelope-open')
        ->assertActionDoesNotHaveIcon('send', 'envelope');
});

```

## #Testing the color of an action

To ensure that an action’s button is displaying the right color, you can useassertActionHasColor()orassertActionDoesNotHaveColor():

```php
use function Pest\Livewire\livewire;

it('actions display proper colors', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionHasColor('delete', 'danger')
        ->assertActionDoesNotHaveColor('print', 'danger');
});

```

## #Testing the URL of an action

To ensure an action has the correct URL, you can useassertActionHasUrl(),assertActionDoesNotHaveUrl(),assertActionShouldOpenUrlInNewTab(), andassertActionShouldNotOpenUrlInNewTab():

```php
use function Pest\Livewire\livewire;

it('links to the correct Filament sites', function () {
    $invoice = Invoice::factory()->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->assertActionHasUrl('filament', 'https://filamentphp.com/')
        ->assertActionDoesNotHaveUrl('filament', 'https://github.com/filamentphp/filament')
        ->assertActionShouldOpenUrlInNewTab('filament')
        ->assertActionShouldNotOpenUrlInNewTab('github');
});

```

## #Testing action arguments

To test action arguments, you can use aTestActionobject with thearguments()method. This object receives the name of the action you want to test and replaces the name of the action in any testing method you want to use. For example:

```php
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

$invoice = Invoice::factory()->create();

livewire(ManageInvoices::class)
    ->callAction(TestAction::make('send')->arguments(['invoice' => $invoice->getKey()]));

livewire(ManageInvoices::class)
    ->assertActionVisible(TestAction::make('send')->arguments(['invoice' => $invoice->getKey()]))

livewire(ManageInvoices::class)
    ->assertActionExists(TestAction::make('send')->arguments(['invoice' => $invoice->getKey()]))

```

## #Testing if an action has been halted

To check if an action has been halted, you can useassertActionHalted():

```php
use function Pest\Livewire\livewire;

it('stops sending if invoice has no email address', function () {
    $invoice = Invoice::factory(['email' => null])->create();

    livewire(EditInvoice::class, [
        'invoice' => $invoice,
    ])
        ->callAction('send')
        ->assertActionHalted('send');
});

```

## #Using action class names in tests

Filament includes a host of prebuilt actions such asCreateAction,EditActionandDeleteAction, and you can use these class names in your tests instead of action names, for example:

```php
use Filament\Actions\CreateAction;
use function Pest\Livewire\livewire;

livewire(ManageInvoices::class)
    ->callAction(CreateAction::class)

```

If you have your own action classes in your app with amake()method, the name of your action is not discoverable by Filament unless it runs themake()method, which is not efficient. To use your own action class names in your tests, you can add an#[ActionName]attribute to your action class, which Filament can use to discover the name of your action. The name passed to the#[ActionName]attribute should be the same as the name of the action you would normally use in your tests. For example:

```php
use Filament\Actions\Action;
use Filament\Actions\ActionName;

#[ActionName('send')]
class SendInvoiceAction
{
    public static function make(): Action
    {
        return Action::make('send')
            ->requiresConfirmation()
            ->action(function () {
                // ...
            });
    }
}

```

Now, you can use the class name in your tests:

```php
use App\Filament\Resources\Invoices\Actions\SendInvoiceAction;
use Filament\Actions\Testing\TestAction;
use function Pest\Livewire\livewire;

$invoice = Invoice::factory()->create();

livewire(ManageInvoices::class)
    ->callAction(TestAction::make(SendInvoiceAction::class)->table($invoice);

```

If you have an action class that extends theActionclass, you can add agetDefaultName()static method to the class, which will be used to discover the name of the action. It also allows users to omit the name of the action from themake()method when instantiating it. For example:

```php
use Filament\Actions\Action;

class SendInvoiceAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'send';
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        $this
            ->requiresConfirmation()
            ->action(function () {
                // ...
            });
    }
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, forms, tables  
**Keywords:** tests, phpunit, pest, quality

*Extracted from Filament v5 Documentation - 2026-01-28*
