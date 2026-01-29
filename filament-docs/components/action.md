# Rendering an action in a Livewire component

**URL:** https://filamentphp.com/docs/5.x/components/action  
**Section:** components  
**Page:** action  
**Priority:** medium  
**AI Context:** Use Filament components outside panels in Blade/Livewire.

---

NOTE

Before proceeding, make surefilament/actionsis installed in your project. You can check by running:

```php
composer show filament/actions

```

If it’s not installed, consult theinstallation guideand configure theindividual componentsaccording to the instructions.

## #Setting up the Livewire component

First, generate a new Livewire component:

```php
php artisan make:livewire ManagePost

```

Then, render your Livewire component on the page:

```php
@livewire('manage-post')

```

Alternatively, you can use a full-page Livewire component:

```php
use App\Livewire\ManagePost;
use Illuminate\Support\Facades\Route;

Route::get('posts/{post}/manage', ManagePost::class);

```

You must use theInteractsWithActionsandInteractsWithSchemastraits, and implement theHasActionsandHasSchemasinterfaces on your Livewire component class:

```php
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Component;

class ManagePost extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    // ...
}

```

## #Adding the action

Add a method that returns your action. The method must share the exact same name as the action, or the name followed byAction:

```php
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Component;

class ManagePost extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Post $post;

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->color('danger')
            ->requiresConfirmation()
            ->action(fn () => $this->post->delete());
    }
    
    // This method name also works, since the action name is `delete`:
    // public function delete(): Action
    
    // This method name does not work, since the action name is `delete`, not `deletePost`:
    // public function deletePost(): Action

    // ...
}

```

Finally, you need to render the action in your view. To do this, you can use{{ $this->deleteAction }}, where you replacedeleteActionwith the name of your action method:

```php
<div>
    {{ $this->deleteAction }}

    <x-filament-actions::modals />
</div>

```

You also need<x-filament-actions::modals />which injects the HTML required to render action modals. This only needs to be included within the Livewire component once, regardless of how many actions you have for that component.

NOTE

filament/actionsalso includes the following packages:
- filament/forms
- filament/infolists
- filament/notifications
- filament/support


These packages allow you to use their components within Livewire components.
For example, if your action usesNotifications, remember to include@livewire('notifications')in your layout and add@import '../../vendor/filament/notifications/resources/css/index.css'to your CSS file.

If you are using any otherFilament componentsin your action, make sure to install and integrate the corresponding package as well.

## #Passing action arguments

Sometimes, you may wish to pass arguments to your action. For example, if you’re rendering the same action multiple times in the same view, but each time for a different model, you could pass the model ID as an argument, and then retrieve it later. To do this, you can invoke the action in your view and pass in the arguments as an array:

```php
<div>
    @foreach ($posts as $post)
        <h2>{{ $post->title }}</h2>

        {{ ($this->deleteAction)(['post' => $post->id]) }}
    @endforeach

    <x-filament-actions::modals />
</div>

```

Now, you can access the post ID in your action method:

```php
use App\Models\Post;
use Filament\Actions\Action;

public function deleteAction(): Action
{
    return Action::make('delete')
        ->color('danger')
        ->requiresConfirmation()
        ->action(function (array $arguments) {
            $post = Post::find($arguments['post']);

            $post?->delete();
        });
}

```

## #Hiding actions in a Livewire view

If you usehidden()orvisible()to control if an action is rendered, you should wrap the action in an@ifcheck forisVisible():

```php
<div>
    @if ($this->deleteAction->isVisible())
        {{ $this->deleteAction }}
    @endif
    
    {{-- Or --}}
    
    @if (($this->deleteAction)(['post' => $post->id])->isVisible())
        {{ ($this->deleteAction)(['post' => $post->id]) }}
    @endif
</div>

```

Thehidden()andvisible()methods also control if the action isdisabled(), so they are still useful to protect the action from being run if the user does not have permission. Encapsulating this logic in thehidden()orvisible()of the action itself is good practice otherwise you need to define the condition in the view and indisabled().

You can also take advantage of this to hide any wrapping elements that may not need to be rendered if the action is hidden:

```php
<div>
    @if ($this->deleteAction->isVisible())
        <div>
            {{ $this->deleteAction }}
        </div>
    @endif
</div>

```

## #Grouping actions in a Livewire view

You maygroup actions together into a dropdown menuby using the<x-filament-actions::group>Blade component, passing in theactionsarray as an attribute:

```php
<div>
    <x-filament-actions::group :actions="[
        $this->editAction,
        $this->viewAction,
        $this->deleteAction,
    ]" />

    <x-filament-actions::modals />
</div>

```

You can also pass in any attributes to customize the appearance of the trigger button and dropdown:

```php
<div>
    <x-filament-actions::group
        :actions="[
            $this->editAction,
            $this->viewAction,
            $this->deleteAction,
        ]"
        label="Actions"
        icon="heroicon-m-ellipsis-vertical"
        color="primary"
        size="md"
        tooltip="More actions"
        dropdown-placement="bottom-start"
    />

    <x-filament-actions::modals />
</div>

```

## #Chaining actions

You can chain multiple actions together, by calling thereplaceMountedAction()method to replace the current action with another when it has finished:

```php
use App\Models\Post;
use Filament\Actions\Action;

public function editAction(): Action
{
    return Action::make('edit')
        ->schema([
            // ...
        ])
        // ...
        ->action(function (array $arguments) {
            $post = Post::find($arguments['post']);

            // ...

            $this->replaceMountedAction('publish', $arguments);
        });
}

public function publishAction(): Action
{
    return Action::make('publish')
        ->requiresConfirmation()
        // ...
        ->action(function (array $arguments) {
            $post = Post::find($arguments['post']);

            $post->publish();
        });
}

```

Now, when the first action is submitted, the second action will open in its place. Theargumentsthat were originally passed to the first action get passed to the second action, so you can use them to persist data between requests.

If the first action is canceled, the second one is not opened. If the second action is canceled, the first one has already run and cannot be cancelled.

## #Programmatically triggering actions

Sometimes you may need to trigger an action without the user clicking on the built-in trigger button, especially from JavaScript. Here is an example action which could be registered on a Livewire component:

```php
use Filament\Actions\Action;

public function testAction(): Action
{
    return Action::make('test')
        ->requiresConfirmation()
        ->action(function (array $arguments) {
            dd('Test action called', $arguments);
        });
}

```

You can trigger that action from a click in your HTML using thewire:clickattribute, calling themountAction()method and optionally passing in any arguments that you want to be available:

```php
<button wire:click="mountAction('test', { id: 12345 })">
    Button
</button>

```

To trigger that action from JavaScript, you can use the$wireutility, passing in the same arguments:

```php
$wire.mountAction('test', { id: 12345 })

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables  
**Keywords:** standalone, blade, livewire, components

*Extracted from Filament v5 Documentation - 2026-01-28*
