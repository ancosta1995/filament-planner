# Testing resources

**URL:** https://filamentphp.com/docs/5.x/testing/testing-resources  
**Section:** testing  
**Page:** testing-resources  
**Priority:** medium  
**AI Context:** Testing Filament applications with PHPUnit/Pest.

---

## #Authenticating as a user

Ensure that you are authenticated to access the app in yourTestCase:

```php
use App\Models\User;

protected function setUp(): void
{
    parent::setUp();

    $this->actingAs(User::factory()->create());
}

```

Alternatively, if you are using Pest you can use abeforeEach()function at the top of your test file to authenticate:

```php
use App\Models\User;

beforeEach(function () {
    $user = User::factory()->create();

    actingAs($user);
});

```

## #Testing a resource list page

To test if the list page is able to load, test the list page as a Livewire component, and callassertOk()to ensure that the HTTP response was 200 OK. You can also use theassertCanSeeTableRecords()method to check if records are being displayed in the table:

```php
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;

it('can load the page', function () {
    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($users);
});

```

To test the table on the list page, you should visit theTesting tablessection. To test any actions in the header of the page or actions in the table, you should visit theTesting actionssection. Below are some common examples of other tests that you can run on the list page.

Totest that the table search is working, you can use thesearchTable()method to search for a specific record. You can also use theassertCanSeeTableRecords()andassertCanNotSeeTableRecords()methods to check if the correct records are being displayed in the table:

```php
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;

it('can search users by `name` or `email`', function () {
    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->searchTable($users->first()->name)
        ->assertCanSeeTableRecords($users->take(1))
        ->assertCanNotSeeTableRecords($users->skip(1))
        ->searchTable($users->last()->email)
        ->assertCanSeeTableRecords($users->take(-1))
        ->assertCanNotSeeTableRecords($users->take($users->count() - 1));
});

```

Totest that the table sorting is working, you can use thesortTable()method to sort the table by a specific column. You can also use theassertCanSeeTableRecords()method to check if the records are being displayed in the correct order:

```php
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;

it('can sort users by `name`', function () {
    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->sortTable('name')
        ->assertCanSeeTableRecords($users->sortBy('name'), inOrder: true)
        ->sortTable('name', 'desc')
        ->assertCanSeeTableRecords($users->sortByDesc('name'), inOrder: true);
});

```

Totest that the table filtering is working, you can use thefilterTable()method to filter the table by a specific column. You can also use theassertCanSeeTableRecords()andassertCanNotSeeTableRecords()methods to check if the correct records are being displayed in the table:

```php
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;

it('can filter users by `locale`', function () {
    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->filterTable('locale', $users->first()->locale)
        ->assertCanSeeTableRecords($users->where('locale', $users->first()->locale))
        ->assertCanNotSeeTableRecords($users->where('locale', '!=', $users->first()->locale));
});

```

Totest that the table bulk actions are working, you can use theselectTableRecords()method to select multiple records in the table. You can also use thecallAction()method to call a specific action on the selected records:

```php
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use function Pest\Laravel\assertDatabaseMissing;

it('can bulk delete users', function () {
    $users = User::factory()->count(5)->create();

    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->selectTableRecords($users)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified()
        ->assertCanNotSeeTableRecords($users);

    $users->each(fn (User $user) => assertDatabaseMissing($user));
});

```

## #Testing a resource create page

To test if the create page is able to load, test the create page as a Livewire component, and callassertOk()to ensure that the HTTP response was 200 OK:

```php
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Models\User;

it('can load the page', function () {
    livewire(CreateUser::class)
        ->assertOk();
});

```

To test the form on the create page, you should visit theTesting schemassection. To test any actions in the header of the page or in the form, you should visit theTesting actionssection. Below are some common examples of other tests that you can run on the create page.

To test that the form is creating records correctly, you can use thefillForm()method to fill in the form fields, and then use thecall('create')method to create the record. You can also use theassertNotified()method to check if a notification was displayed, and theassertRedirect()method to check if the user was redirected to another page:

```php
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;

it('can create a user', function () {
    $newUserData = User::factory()->make();

    livewire(CreateUser::class)
        ->fillForm([
            'name' => $newUserData->name,
            'email' => $newUserData->email,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(User::class, [
        'name' => $newUserData->name,
        'email' => $newUserData->email,
    ]);
});

```

To test that the form is validating properly, you can use thefillForm()method to fill in the form fields, and then use thecall('create')method to create the record. You can also use theassertHasFormErrors()method to check if the form has any errors, and theassertNotNotified()method to check if no notification was displayed. You can also use theassertNoRedirect()method to check if the user was not redirected to another page. In this example, we use aPest datasetto test multiple rules without having to repeat the test code:

```php
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Models\User;
use Illuminate\Support\Str;

it('validates the form data', function (array $data, array $errors) {
    $newUserData = User::factory()->make();

    livewire(CreateUser::class)
        ->fillForm([
            'name' => $newUserData->name,
            'email' => $newUserData->email,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`email` is a valid email address' => [['email' => Str::random()], ['email' => 'email']],
    '`email` is required' => [['email' => null], ['email' => 'required']],
    '`email` is max 255 characters' => [['email' => Str::random(256)], ['email' => 'max']],
]);

```

## #Testing a resource edit page

To test if the edit page is able to load, test the edit page as a Livewire component, and callassertOk()to ensure that the HTTP response was 200 OK. You can also use theassertSchemaStateSet()method to check if the form fields are set to the correct values:

```php
use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;

it('can load the page', function () {
    $user = User::factory()->create();

    livewire(EditUser::class, [
        'record' => $user->id,
    ])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

```

To test the form on the edit page, you should visit theTesting schemassection. To test any actions in the header of the page or in the form, you should visit theTesting actionssection. Below are some common examples of other tests that you can run on the edit page.

```php
use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;

it('can update a user', function () {
    $user = User::factory()->create();

    $newUserData = User::factory()->make();

    livewire(EditUser::class, [
        'record' => $user->id,
    ])
        ->fillForm([
            'name' => $newUserData->name,
            'email' => $newUserData->email,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(User::class, [
        'id' => $user->id,
        'name' => $newUserData->name,
        'email' => $newUserData->email,
    ]);
});

```

To test that the form is validating properly, you can use thefillForm()method to fill in the form fields, and then use thecall('save')method to save the record. You can also use theassertHasFormErrors()method to check if the form has any errors, and theassertNotNotified()method to check if no notification was displayed. In this example, we use aPest datasetto test multiple rules without having to repeat the test code:

```php
use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;
use Illuminate\Support\Str;

it('validates the form data', function (array $data, array $errors) {
    $user = User::factory()->create();

    $newUserData = User::factory()->make();

    livewire(EditUser::class, [
        'record' => $user->id,
    ])
        ->fillForm([
            'name' => $newUserData->name,
            'email' => $newUserData->email,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`name` is required' => [['name' => null], ['name' => 'required']],
    '`name` is max 255 characters' => [['name' => Str::random(256)], ['name' => 'max']],
    '`email` is a valid email address' => [['email' => Str::random()], ['email' => 'email']],
    '`email` is required' => [['email' => null], ['email' => 'required']],
    '`email` is max 255 characters' => [['email' => Str::random(256)], ['email' => 'max']],
]);

```

Totest that an action is working, such as theDeleteAction, you can use thecallAction()method to call the delete action. You can also use theassertNotified()method to check if a notification was displayed, and theassertRedirect()method to check if the user was redirected to another page:

```php
use App\Filament\Resources\Users\Pages\EditUser;
use App\Models\User;
use Filament\Actions\DeleteAction;
use function Pest\Laravel\assertDatabaseMissing;

it('can delete a user', function () {
    $user = User::factory()->create();

    livewire(EditUser::class, [
        'record' => $user->id,
    ])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseMissing($user);
});

```

## #Testing a resource view page

To test if the view page is able to load, test the view page as a Livewire component, and callassertOk()to ensure that the HTTP response was 200 OK. You can also use theassertSchemaStateSet()method to check if the infolist entries are set to the correct values:

```php
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Models\User;

it('can load the page', function () {
    $user = User::factory()->create();

    livewire(ViewUser::class, [
        'record' => $user->id,
    ])
        ->assertOk()
        ->assertSchemaStateSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

```

To test the infolist on the view page, you should visit theTesting schemassection. To test any actions in the header of the page or in the infolist, you should visit theTesting actionssection.

## #Testing relation managers

To test if a relation manager is rendered on a page, such as the edit page of a resource, you can use theassertSeeLivewire()method to check if the relation manager is being rendered:

```php
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\RelationManagers\PostsRelationManager;
use App\Models\User;

it('can load the relation manager', function () {
    $user = User::factory()->create();

    livewire(EditUser::class, [
        'record' => $user->id,
    ])
        ->assertSeeLivewire(PostsRelationManager::class);
});

```

Since relation managers are Livewire components, you can also test a relation manager’s functionality itself, like its ability to load successfully with a 200 OK response, with the correct records in the table. When testing a relation manager, you need to pass in theownerRecord, which is the record from the resource you are inside, and thepageClass, which is the class of the page you are on:

```php
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\RelationManagers\PostsRelationManager;
use App\Models\Post;
use App\Models\User;

it('can load the relation manager', function () {
    $user = User::factory()
        ->has(Post::factory()->count(5))
        ->create();

    livewire(PostsRelationManager::class, [
        'ownerRecord' => $user,
        'pageClass' => EditUser::class,
    ])
        ->assertOk()
        ->assertCanSeeTableRecords($user->posts);
});

```

You cantest searching,sorting, andfilteringin the same way as you would on a resource list page.

You can alsotest actions, for example, theCreateActionin the header of the table:

```php
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\RelationManagers\PostsRelationManager;
use App\Models\Post;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use function Pest\Laravel\assertDatabaseHas;

it('can create a post', function () {
    $user = User::factory()->create();

    $newPostData = Post::factory()->make();

    livewire(PostsRelationManager::class, [
        'ownerRecord' => $user,
        'pageClass' => EditUser::class,
    ])
        ->callAction(TestAction::make(CreateAction::class)->table(), [
            'title' => $newPostData->title,
            'content' => $newPostData->content,
        ])
        ->assertNotified();

    assertDatabaseHas(Post::class, [
        'title' => $newPostData->title,
        'content' => $newPostData->content,
        'user_id' => $user->id,
    ]);
});

```

## #Testing create / edit pagegetFormActions()

When testing actions ingetFormActions()on a resource page, use theschemaComponent()method targeting theform-actionskey in thecontentschema. For example, if you have a customAction::make('createAndVerifyEmail')action in thegetFormActions()method of yourCreateUserpage, you can test it like this:

```php
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Models\User;
use Filament\Actions\Testing\TestAction;

it('can create a user and verify their email address', function () {
    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => '[email protected]',
        ])
        ->callAction(TestAction::make('createAndVerifyEmail')->schemaComponent('form-actions', schema: 'content'));

    expect(User::query()->where('email', '[email protected]')->first())
        ->hasVerifiedEmail()->toBeTrue();
});

```

## #Testing multiple panels

If you have multiple panels and you would like to test a non-default panel, you will need to tell Filament which panel you are testing. This can be done in thesetUp()method of the test case, or you can do it at the start of a particular test. Filament usually does this in a middleware when you access the panel through a request, so if you’re not making a request in your test like when testing a Livewire component, you need to set the current panel manually:

```php
use Filament\Facades\Filament;

Filament::setCurrentPanel('app'); // Where `app` is the ID of the panel you want to test.

```

## #Testing multi-tenant panels

When testing resources in multi-tenant panels, you may need to callFilament::bootCurrentPanel()after setting the tenant in order to apply tenant scopes and model event listeners:

```php
use Filament\Facades\Filament;

$team = Team::factory()->create();

Filament::setTenant($this->team);
Filament::bootCurrentPanel();

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** resources, forms, tables  
**Keywords:** tests, phpunit, pest, quality

*Extracted from Filament v5 Documentation - 2026-01-28*
