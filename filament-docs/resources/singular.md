# Singular resources

**URL:** https://filamentphp.com/docs/5.x/resources/singular  
**Section:** resources  
**Page:** singular  
**Priority:** high  
**AI Context:** Core CRUD functionality. Main feature for building admin panels.

---

## #Overview

Resources aren’t the only way to interact with Eloquent records in a Filament panel. Even though resources may solve many of your requirements, the “index” (root) page of a resource contains a table with a list of records in that resource.

Sometimes there is no need for a table that lists records in a resource. There is only a single record that the user interacts with. If it doesn’t yet exist when the user visits the page, it gets created when the form is first submitted by the user to save it. If the record already exists, it is loaded into the form when the page is first loaded, and updated when the form is submitted.

For example, a CMS might have aPageEloquent model and aPageResource, but you may also want to create a singular page outside thePageResourcefor editing the “homepage” of the website. This allows the user to directly edit the homepage without having to navigate to thePageResourceand find the homepage record in the table.

Other examples of this include a “Settings” page, or a “Profile” page for the currently logged-in user. For these use cases, though, we recommend that you use theSpatie Settings pluginand theProfilefeatures of Filament, which require less code to implement.

## #Creating a singular resource

Although there is no specific “singular resource” feature in Filament, it is a highly-requested behavior and can be implemented quite simply using acustom pagewith aform. This guide will explain how to do this.

Firstly, create acustom page:

```php
php artisan make:filament-page ManageHomepage

```

This command will create two files - a page class in the/Filament/Pagesdirectory of your resource directory, and a Blade view in the/filament/pagesdirectory of the resource views directory.

The page class should contain the following elements:
- A$dataproperty, which will hold the current state of the form.
- Amount()method, which will load the current record from the database and fill the form with its data. If the record doesn’t exist,nullwill be passed to thefill()method of the form, which will assign any default values to the form fields.
- Aform()method, which will define the form schema. The form contains fields in thecomponents()method. Therecord()method should be used to specify the record that the form should load relationship data from. ThestatePath()method should be used to specify the name of the property ($data) where the form’s state should be stored.
- Asave()method, which will save the form data to the database. ThegetState()method runs form validation and returns valid form data. This method should check if the record already exists, and if not, create a new one. ThewasRecentlyCreatedproperty of the model can be used to determine if the record was just created, and if so then any relationships should be saved as well. A notification is sent to the user to confirm that the record was saved.
- AgetRecord()method, while not strictly necessary, is a good idea to have. This method will return the Eloquent record that the form is editing. It can be used across the other methods to avoid code duplication.


```php
namespace App\Filament\Pages;

use App\Models\WebsitePage;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;

/**
 * @property-read Schema $form
 */
class ManageHomepage extends Page
{
    protected string $view = 'filament.pages.manage-homepage';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    RichEditor::make('content'),
                    // ...
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        $record = $this->getRecord();
        
        if (! $record) {
            $record = new WebsitePage();
            $record->is_homepage = true;
        }
        
        $record->fill($data);
        $record->save();
        
        if ($record->wasRecentlyCreated) {
            $this->form->record($record)->saveRelationships();
        }

        Notification::make()
            ->success()
            ->title('Saved')
            ->send();
    }
    
    public function getRecord(): ?WebsitePage
    {
        return WebsitePage::query()
            ->where('is_homepage', true)
            ->first();
    }
}

```

The page Blade view should render the form:

```php
<x-filament::page>
    {{ $this->form }}
</x-filament::page>

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables, actions  
**Keywords:** crud, database, eloquent, models, admin panel

*Extracted from Filament v5 Documentation - 2026-01-28*
