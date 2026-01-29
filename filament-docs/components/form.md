# Rendering a form in a Blade view

**URL:** https://filamentphp.com/docs/5.x/components/form  
**Section:** components  
**Page:** form  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

NOTE

Before proceeding, make surefilament/formsis installed in your project. You can check by running:

```php
composer show filament/forms

```

If it’s not installed, consult theinstallation guideand configure theindividual componentsaccording to the instructions.

## #Setting up the Livewire component

First, generate a new Livewire component:

```php
php artisan make:livewire CreatePost

```

Then, render your Livewire component on the page:

```php
@livewire('create-post')

```

Alternatively, you can use a full-page Livewire component:

```php
use App\Livewire\CreatePost;
use Illuminate\Support\Facades\Route;

Route::get('posts/create', CreatePost::class);

```

## #Adding the form

NOTE

Before proceeding, please ensure that theForms packageis installed in your project. Consult theinstallation guideand configure theindividual componentsaccording to the instructions.

There are 5 main tasks when adding a form to a Livewire component class. Each one is essential:
1. Implement theHasSchemasinterface and use theInteractsWithSchemastrait.
2. Define a public Livewire property to store your form’s data. In our example, we’ll call this$data, but you can call it whatever you want.
3. Add aform()method, which is where you configure the form.Add the form’s schema, and tell Filament to store the form data in the$dataproperty (usingstatePath('data')).
4. Initialize the form with$this->form->fill()inmount(). This is imperative for every form that you build, even if it doesn’t have any initial data.
5. Define a method to handle the form submission. In our example, we’ll call thiscreate(), but you can call it whatever you want. Inside that method, you can validate and get the form’s data using$this->form->getState(). It’s important that you use this method instead of accessing the$this->dataproperty directly, because the form’s data needs to be validated and transformed into a useful format before being returned.


```php
<?php

namespace App\Livewire;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Filament\Schemas\Schema;
use Livewire\Component;

class CreatePost extends Component implements HasSchemas
{
    use InteractsWithSchemas;
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }
    
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                MarkdownEditor::make('content'),
                // ...
            ])
            ->statePath('data');
    }
    
    public function create(): void
    {
        dd($this->form->getState());
    }
    
    public function render(): View
    {
        return view('livewire.create-post');
    }
}

```

Finally, in your Livewire component’s view, render the form:

```php
<div>
    <form wire:submit="create">
        {{ $this->form }}
        
        <button type="submit">
            Submit
        </button>
    </form>
    
    <x-filament-actions::modals />
</div>

```

NOTE

<x-filament-actions::modals />is used to render form componentaction modals. The code can be put anywhere outside the<form>element, as long as it’s within the Livewire component.

Visit your Livewire component in the browser, and you should see the form components fromcomponents():

Submit the form with data, and you’ll see the form’s data dumped to the screen. You can save the data to a model instead of dumping it:

```php
use App\Models\Post;

public function create(): void
{
    Post::create($this->form->getState());
}

```

NOTE

filament/formsalso includes the following packages:
- filament/actions
- filament/schemas
- filament/support


These packages allow you to use their components within Livewire components.
For example, if your form usesActions, remember to implement theHasActionsinterface and use theInteractsWithActionstrait on your Livewire component class.

If you are using any otherFilament componentsin your form, make sure to install and integrate the corresponding package as well.

## #Initializing the form with data

To fill the form with data, just pass that data to the$this->form->fill()method. For example, if you’re editing an existing post, you might do something like this:

```php
use App\Models\Post;

public function mount(Post $post): void
{
    $this->form->fill($post->attributesToArray());
}

```

It’s important that you use the$this->form->fill()method instead of assigning the data directly to the$this->dataproperty. This is because the post’s data needs to be internally transformed into a useful format before being stored.

## #Setting a form model

Giving the$formaccess to a model is useful for a few reasons:
- It allows fields within that form to load information from that model. For example, select fields canload their options from the databaseautomatically.
- The form can load and save the model’s relationship data automatically. For example, you have an Edit Post form, with aRepeaterwhich manages comments associated with that post. Filament will automatically load the comments for that post when you call$this->form->fill([...]), and save them back to the relationship when you call$this->form->getState().
- Validation rules likeexists()andunique()can automatically retrieve the database table name from the model.


It is advised to always pass the model to the form when there is one. As explained, it unlocks many new powers of Filament’s form system.

To pass the model to the form, use the$form->model()method:

```php
use Filament\Schemas\Schema;

public Post $post;

public function form(Schema $schema): Schema
{
    return $schema
        ->components([
            // ...
        ])
        ->statePath('data')
        ->model($this->post);
}

```

### #Passing the form model after the form has been submitted

In some cases, the form’s model is not available until the form has been submitted. For example, in a Create Post form, the post does not exist until the form has been submitted. Therefore, you can’t pass it in to$form->model(). However, you can pass a model class instead:

```php
use App\Models\Post;
use Filament\Schemas\Schema;

public function form(Schema $schema): Schema
{
    return $schema
        ->components([
            // ...
        ])
        ->statePath('data')
        ->model(Post::class);
}

```

On its own, this isn’t as powerful as passing a model instance. For example, relationships won’t be saved to the post after it is created. To do that, you’ll need to pass the post to the form after it has been created, and callsaveRelationships()to save the relationships to it:

```php
use App\Models\Post;

public function create(): void
{
    $post = Post::create($this->form->getState());
    
    // Save the relationships from the form to the post after it is created.
    $this->form->model($post)->saveRelationships();
}

```

## #Saving form data to individual properties

In all of our previous examples, we’ve been saving the form’s data to the public$dataproperty on the Livewire component. However, you can save the data to individual properties instead. For example, if you have a form with atitlefield, you can save the form’s data to the$titleproperty instead. To do this, don’t pass astatePath()to the form at all. Ensure that all of your fields have their ownpublicproperties on the class.

```php
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

public ?string $title = null;

public ?string $content = null;

public function form(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('title')
                ->required(),
            MarkdownEditor::make('content'),
            // ...
        ]);
}

```

## #Using multiple forms

Many forms can be defined using theInteractsWithSchemastrait. Each of the forms should use a method with the same name:

```php
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

public ?array $postData = [];

public ?array $commentData = [];

public function editPostForm(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('title')
                ->required(),
            MarkdownEditor::make('content'),
            // ...
        ])
        ->statePath('postData')
        ->model($this->post);
}

public function createCommentForm(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('name')
                ->required(),
            TextInput::make('email')
                ->email()
                ->required(),
            MarkdownEditor::make('content')
                ->required(),
            // ...
        ])
        ->statePath('commentData')
        ->model(Comment::class);
}

```

Now, each form is addressable by its name instead ofform. For example, to fill the post form, you can use$this->editPostForm->fill([...]), or to get the data from the comment form you can use$this->createCommentForm->getState().

You’ll notice that each form has its own uniquestatePath(). Each form will write its state to a different array on your Livewire component, so it’s important to define the public properties,$postDataand$commentDatain this example.

## #Resetting a form’s data

You can reset a form back to its default data at any time by calling$this->form->fill(). For example, you may wish to clear the contents of a form every time it’s submitted:

```php
use App\Models\Comment;

public function createComment(): void
{
    Comment::create($this->form->getState());

    // Reinitialize the form to clear its data.
    $this->form->fill();
}

```

## #Generating form Livewire components with the CLI

It’s advised that you learn how to set up a Livewire component with forms manually, but once you are confident, you can use the CLI to generate a form for you.

```php
php artisan make:filament-livewire-form RegistrationForm

```

This will generate a newapp/Livewire/RegistrationForm.phpcomponent, which you can customize.

### #Generating a form for an Eloquent model

Filament is also able to generate forms for a specific Eloquent model. These are more powerful, as they will automatically save the data in the form for you, andensure the form fields are properly configuredto access that model.

When generating a form with themake:livewire-formcommand, it will ask for the name of the model:

```php
php artisan make:filament-livewire-form Products/CreateProduct

```

#### #Generating an edit form for an Eloquent record

By default, passing a model to themake:livewire-formcommand will result in a form that creates a new record in your database. If you pass the--editflag to the command, it will generate an edit form for a specific record. This will automatically fill the form with the data from the record, and save the data back to the model when the form is submitted.

```php
php artisan make:filament-livewire-form Products/EditProduct --edit

```

### #Automatically generating form schemas

Filament is also able to guess which form fields you want in the schema, based on the model’s database columns. You can use the--generateflag when generating your form:

```php
php artisan make:filament-livewire-form Products/CreateProduct --generate

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
