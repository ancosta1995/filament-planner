# Multi-tenancy

**URL:** https://filamentphp.com/docs/5.x/users/tenancy  
**Section:** users  
**Page:** tenancy  
**Priority:** medium  
**AI Context:** Authentication, authorization, and multi-tenancy.

---

## #Introduction

Multi-tenancy is a concept where a single instance of an application serves multiple customers. Each customer has their own data and access rules that prevent them from viewing or modifying each other’s data. This is a common pattern in SaaS applications. Users often belong to groups of users (often called teams or organizations). Records are owned by the group, and users can be members of multiple groups. This is suitable for applications where users need to collaborate on data.

Multi-tenancy is a very sensitive topic. It’s important to understand the security implications of multi-tenancy and how to properly implement it. If implemented partially or incorrectly, data belonging to one tenant may be exposed to another tenant. Filament provides a set of tools to help you implement multi-tenancy in your application, but it is up to you to understand how to use them.

NOTE

Filament does not provide any guarantees about the security of your application. It is your responsibility to ensure that your application is secure. Please see thesecuritysection for more information.

## #Simple one-to-many tenancy

The term “multi-tenancy” is broad and may mean different things in different contexts. Filament’s tenancy system implies that the user belongs tomanytenants (organizations, teams, companies, etc.) and may switch between them.

If your case is simpler and you don’t need a many-to-many relationship, then you don’t need to set up the tenancy in Filament. You could useobserversandglobal scopesinstead.

Let’s say you have a database columnusers.team_id, you can scope all records to have the sameteam_idas the user using aglobal scope:

```php
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope('team', function (Builder $query) {
            if (auth()->hasUser()) {
                $query->where('team_id', auth()->user()->team_id);
                // or with a `team` relationship defined:
                $query->whereBelongsTo(auth()->user()->team);
            }
        });
    }
}

```

To automatically set theteam_idon the record when it’s created, you can create anobserver:

```php
class PostObserver
{
    public function creating(Post $post): void
    {
        if (auth()->hasUser()) {
            $post->team_id = auth()->user()->team_id;
            // or with a `team` relationship defined:
            $post->team()->associate(auth()->user()->team);
        }
    }
}

```

## #Setting up tenancy

To set up tenancy, you’ll need to specify the “tenant” (like team or organization) model in theconfiguration:

```php
use App\Models\Team;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenant(Team::class);
}

```

You’ll also need to tell Filament which tenants a user belongs to. You can do this by implementing theHasTenantsinterface on theApp\Models\Usermodel:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    // ...

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams()->whereKey($tenant)->exists();
    }
}

```

In this example, users belong to many teams, so there is ateams()relationship. ThegetTenants()method returns the teams that the user belongs to. Filament uses this to list the tenants that the user has access to.

For security, you also need to implement thecanAccessTenant()method of theHasTenantsinterface to prevent users from accessing the data of other tenants by guessing their tenant ID and putting it into the URL.

You’ll also want users to be able toregister new teams.

## #Adding a tenant registration page

A registration page will allow users to create a new tenant.

When visiting your app after logging in, users will be redirected to this page if they don’t already have a tenant.

To set up a registration page, you’ll need to create a new page class that extendsFilament\Pages\Tenancy\RegisterTenant. This is a full-page Livewire component. You can put this anywhere you want, such asapp/Filament/Pages/Tenancy/RegisterTeam.php:

```php
namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                // ...
            ]);
    }

    protected function handleRegistration(array $data): Team
    {
        $team = Team::create($data);

        $team->members()->attach(auth()->user());

        return $team;
    }
}

```

You may add anyform componentsto theform()method, and create the team inside thehandleRegistration()method.

Now, we need to tell Filament to use this page. We can do this in theconfiguration:

```php
use App\Filament\Pages\Tenancy\RegisterTeam;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantRegistration(RegisterTeam::class);
}

```

### #Customizing the tenant registration page

You can override any method you want on the base registration page class to make it act as you want. Even the$viewproperty can be overridden to use a custom view of your choice.

## #Adding a tenant profile page

A profile page will allow users to edit information about the tenant.

To set up a profile page, you’ll need to create a new page class that extendsFilament\Pages\Tenancy\EditTenantProfile. This is a full-page Livewire component. You can put this anywhere you want, such asapp/Filament/Pages/Tenancy/EditTeamProfile.php:

```php
namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Schema;

class EditTeamProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Team profile';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name'),
                // ...
            ]);
    }
}

```

You may add anyform componentsto theform()method. They will get saved directly to the tenant model.

Now, we need to tell Filament to use this page. We can do this in theconfiguration:

```php
use App\Filament\Pages\Tenancy\EditTeamProfile;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantProfile(EditTeamProfile::class);
}

```

### #Customizing the tenant profile page

You can override any method you want on the base profile page class to make it act as you want. Even the$viewproperty can be overridden to use a custom view of your choice.

## #Accessing the current tenant

Anywhere in the app, you can access the tenant model for the current request usingFilament::getTenant():

```php
use Filament\Facades\Filament;

$tenant = Filament::getTenant();

```

## #Billing

### #Using Laravel Spark

Filament provides a billing integration withLaravel Spark. Your users can start subscriptions and manage their billing information.

To install the integration, firstinstall Sparkand configure it for your tenant model.

Now, you can install the Filament billing provider for Spark using Composer:

```php
composer require filament/spark-billing-provider

```

In theconfiguration, set Spark as thetenantBillingProvider():

```php
use Filament\Billing\Providers\SparkBillingProvider;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantBillingProvider(new SparkBillingProvider());
}

```

Now, you’re all good to go! Users can manage their billing by clicking a link in the tenant menu.

### #Requiring a subscription

To require a subscription to use any part of the app, you can use therequiresTenantSubscription()configuration method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->requiresTenantSubscription();
}

```

Now, users will be redirected to the billing page if they don’t have an active subscription.

#### #Requiring a subscription for specific resources and pages

Sometimes, you may wish to only require a subscription for certainresourcesandcustom pagesin your app. You can do this by returningtruefrom theisTenantSubscriptionRequired()method on the resource or page class:

```php
public static function isTenantSubscriptionRequired(Panel $panel): bool
{
    return true;
}

```

If you’re using therequiresTenantSubscription()configuration method, then you can returnfalsefrom this method to allow access to the resource or page as an exception.

### #Writing a custom billing integration

Billing integrations are quite simple to write. You just need a class that implements theFilament\Billing\Providers\Contracts\Providerinterface. This interface has two methods.

getRouteAction()is used to get the route action that should be run when the user visits the billing page. This could be a callback function, or the name of a controller, or a Livewire component - anything that works when usingRoute::get()in Laravel normally. For example, you could put in a simple redirect to your own billing page using a callback function.

getSubscribedMiddleware()returns the name of a middleware that should be used to check if the tenant has an active subscription. This middleware should redirect the user to the billing page if they don’t have an active subscription.

Here’s an example billing provider that uses a callback function for the route action and a middleware for the subscribed middleware:

```php
use App\Http\Middleware\RedirectIfUserNotSubscribed;
use Filament\Billing\Providers\Contracts\BillingProvider;
use Illuminate\Http\RedirectResponse;

class ExampleBillingProvider implements BillingProvider
{
    public function getRouteAction(): string
    {
        return function (): RedirectResponse {
            return redirect('https://billing.example.com');
        };
    }

    public function getSubscribedMiddleware(): string
    {
        return RedirectIfUserNotSubscribed::class;
    }
}

```

### #Customizing the billing route slug

You can customize the URL slug used for the billing route using thetenantBillingRouteSlug()method in theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantBillingRouteSlug('billing');
}

```

## #Customizing the tenant menu

The tenant-switching menu is featured in the admin layout. It’s fully customizable.

Each menu item is represented by anaction, and can be customized in the same way. To register new items, you can pass the actions to thetenantMenuItems()method of theconfiguration:

```php
use App\Filament\Pages\Settings;
use Filament\Actions\Action;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantMenuItems([
            Action::make('settings')
                ->url(fn (): string => Settings::getUrl())
                ->icon('heroicon-m-cog-8-tooth'),
            // ...
        ]);
}

```

### #Allowing the tenants to be searched

You can use thesearchableTenantMenu()method in theconfigurationto allow the tenants to be searched:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->searchableTenantMenu();
}

```

This is automatically enabled when there are more than 10 tenants in a user’s list. You can disable it usingsearchableTenantMenu(false).

### #Customizing the registration link

To customize theregistrationlink in the tenant menu, register a new item with theregisterarray key, and pass a function thatcustomizes the actionobject:

```php
use Filament\Actions\Action;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantMenuItems([
            'register' => fn (Action $action) => $action->label('Register new team'),
            // ...
        ]);
}

```

### #Customizing the profile link

To customize the user profile link at the start of the tenant menu, register a new item with theprofilearray key, and pass a function thatcustomizes the actionobject:

```php
use Filament\Actions\Action;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantMenuItems([
            'profile' => fn (Action $action) => $action->label('Edit team profile'),
            // ...
        ]);
}

```

### #Customizing the billing link

To customize the billing link in the tenant menu, register a new item with theprofilearray key, and pass a function thatcustomizes the actionobject:

```php
use Filament\Actions\Action
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantMenuItems([
            'billing' => fn (Action $action) => $action->label('Manage subscription'),
            // ...
        ]);
}

```

### #Conditionally hiding tenant menu items

You can also conditionally hide a tenant menu item by using thevisible()orhidden()methods, passing in a condition to check. Passing a function will defer condition evaluation until the menu is actually being rendered:

```php
use Filament\Actions\Action;

Action::make('settings')
    ->visible(fn (): bool => auth()->user()->can('manage-team'))
    // or
    ->hidden(fn (): bool => ! auth()->user()->can('manage-team'))

```

### #Sending aPOSTHTTP request from a tenant menu item

You can send aPOSTHTTP request from a tenant menu item by passing a URL to theurl()method, and also usingpostToUrl():

```php
use Filament\Actions\Action;

Action::make('lockSession')
    ->url(fn (): string => route('lock-session'))
    ->postToUrl()

```

### #Hiding the tenant menu

You can hide the tenant menu by using thetenantMenu(false)

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantMenu(false);
}

```

However, this is a sign that Filament’s tenancy feature is not suitable for your project. If each user only belongs to one tenant, you should stick tosimple one-to-many tenancy.

## #Setting up avatars

Out of the box, Filament usesui-avatars.comto generate avatars based on a user’s name. However, if your user model has anavatar_urlattribute, that will be used instead. To customize how Filament gets a user’s avatar URL, you can implement theHasAvatarcontract:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Model;

class Team extends Model implements HasAvatar
{
    // ...

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
}

```

ThegetFilamentAvatarUrl()method is used to retrieve the avatar of the current user. Ifnullis returned from this method, Filament will fall back toui-avatars.com.

You can easily swap outui-avatars.comfor a different service, by creating a new avatar provider.You can learn how to do this here.

## #Configuring the tenant relationships

When creating and listing records associated with a Tenant, Filament needs access to two Eloquent relationships for each resource - an “ownership” relationship that is defined on the resource model class, and a relationship on the tenant model class. By default, Filament will attempt to guess the names of these relationships based on standard Laravel conventions. For example, if the tenant model isApp\Models\Team, it will look for ateam()relationship on the resource model class. And if the resource model class isApp\Models\Post, it will look for aposts()relationship on the tenant model class.

### #Customizing the ownership relationship name

You can customize the name of the ownership relationship used across all resources at once, using theownershipRelationshipargument on thetenant()configuration method. In this example, resource model classes have anownerrelationship defined:

```php
use App\Models\Team;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenant(Team::class, ownershipRelationship: 'owner');
}

```

Alternatively, you can set the$tenantOwnershipRelationshipNamestatic property on the resource class, which can then be used to customize the ownership relationship name that is just used for that resource. In this example, thePostmodel class has anownerrelationship defined:

```php
use Filament\Resources\Resource;

class PostResource extends Resource
{
    protected static ?string $tenantOwnershipRelationshipName = 'owner';

    // ...
}

```

### #Customizing the resource relationship name

You can set the$tenantRelationshipNamestatic property on the resource class, which can then be used to customize the relationship name that is used to fetch that resource. In this example, the tenant model class has anblogPostsrelationship defined:

```php
use Filament\Resources\Resource;

class PostResource extends Resource
{
    protected static ?string $tenantRelationshipName = 'blogPosts';

    // ...
}

```

## #Configuring the slug attribute

When using a tenant like a team, you might want to add a slug field to the URL rather than the team’s ID. You can do that with theslugAttributeargument on thetenant()configuration method:

```php
use App\Models\Team;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenant(Team::class, slugAttribute: 'slug');
}

```

## #Configuring the name attribute

By default, Filament will use thenameattribute of the tenant to display its name in the app. To change this, you can implement theHasNamecontract:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Model;

class Team extends Model implements HasName
{
    // ...

    public function getFilamentName(): string
    {
        return "{$this->name} {$this->subscription_plan}";
    }
}

```

ThegetFilamentName()method is used to retrieve the name of the current user.

## #Setting the current tenant label

Inside the tenant switcher, you may wish to add a small label like “Active team” above the name of the current team. You can do this by implementing theHasCurrentTenantLabelmethod on the tenant model:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Model;

class Team extends Model implements HasCurrentTenantLabel
{
    // ...

    public function getCurrentTenantLabel(): string
    {
        return 'Active team';
    }
}

```

## #Setting the default tenant

When signing in, Filament will redirect the user to the first tenant returned from thegetTenants()method.

Sometimes, you might wish to change this. For example, you might store which team was last active, and redirect the user to that team instead.

To customize this, you can implement theHasDefaultTenantcontract on the user:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Model implements FilamentUser, HasDefaultTenant, HasTenants
{
    // ...

    public function getDefaultTenant(Panel $panel): ?Model
    {
        return $this->latestTeam;
    }

    public function latestTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'latest_team_id');
    }
}

```

## #Applying middleware to tenant-aware routes

You can apply extra middleware to all tenant-aware routes by passing an array of middleware classes to thetenantMiddleware()method in thepanel configuration file:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantMiddleware([
            // ...
        ]);
}

```

By default, middleware will be run when the page is first loaded, but not on subsequent Livewire AJAX requests. If you want to run middleware on every request, you can make it persistent by passingtrueas the second argument to thetenantMiddleware()method:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantMiddleware([
            // ...
        ], isPersistent: true);
}

```

## #Adding a tenant route prefix

By default the URL structure will put the tenant ID or slug immediately after the panel path. If you wish to prefix it with another URL segment, use thetenantRoutePrefix()method:

```php
use App\Models\Team;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->path('admin')
        ->tenant(Team::class)
        ->tenantRoutePrefix('team');
}

```

Before, the URL structure was/admin/1for tenant 1. Now, it is/admin/team/1.

## #Using a domain to identify the tenant

When using a tenant, you might want to use domain or subdomain routing liketeam1.example.com/postsinstead of a route prefix like/team1/posts. You can do that with thetenantDomain()method, alongside thetenant()configuration method. Thetenantargument corresponds to the slug attribute of the tenant model:

```php
use App\Models\Team;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenant(Team::class, slugAttribute: 'slug')
        ->tenantDomain('{tenant:slug}.example.com');
}

```

In the above examples, the tenants live on subdomains of the main app domain. You may also set the system up to resolve the entire domain from the tenant as well:

```php
use App\Models\Team;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenant(Team::class, slugAttribute: 'domain')
        ->tenantDomain('{tenant:domain}');
}

```

In this example, thedomainattribute should contain a valid domain host, likeexample.comorsubdomain.example.com.

NOTE

When using a parameter for the entire domain (tenantDomain('{tenant:domain}')), Filament will register aglobal route parameter patternfor alltenantparameters in the application to be[a-z0-9.\-]+. This is because Laravel does not allow the.character in route parameters by default. This might conflict with other panels using tenancy, or other parts of your application that use atenantroute parameter.

## #Disabling tenancy for a resource

By default, all resources within a panel with tenancy will be scoped to the current tenant. If you have resources that are shared between tenants, you can disable tenancy for them by setting the$isScopedToTenantstatic property tofalseon the resource class:

```php
protected static bool $isScopedToTenant = false;

```

### #Disabling tenancy for all resources

If you wish to opt-in to tenancy for each resource instead of opting-out, you can callResource::scopeToTenant(false)inside a service provider’sboot()method or a middleware:

```php
use Filament\Resources\Resource;

Resource::scopeToTenant(false);

```

Now, you can opt-in to tenancy for each resource by setting the$isScopedToTenantstatic property totrueon a resource class:

```php
protected static bool $isScopedToTenant = true;

```

## #Tenancy security

It’s important to understand the security implications of multi-tenancy and how to properly implement it. If implemented partially or incorrectly, data belonging to one tenant may be exposed to another tenant. Filament provides a set of tools to help you implement multi-tenancy in your application, but it is up to you to understand how to use them. Filament does not provide any guarantees about the security of your application. It is your responsibility to ensure that your application is secure.

Below is a list of features that Filament provides to help you implement multi-tenancy in your application:
- Automatic global scoping of Eloquent model queries fortenant-awareresources that belong to the panel with tenancy enabled. The query used to fetch records for a resource is automatically scoped to the current tenant. This query is used to render the resource’s list table, and is also used to resolve records from the current URL when editing or viewing a record. This means that if a user attempts to view a record that does not belong to the current tenant, they will receive a 404 error.Atenant-awareresource has to exist in the panel with tenancy enabled for the resource’s model to have the global scope applied. If you want to scope the queries for a model that does not have a corresponding resource, you mustuse middleware to apply additional global scopesfor that model.The global scopes are applied after the tenant has been identified from the request. This happens during the middleware stack of panel requests. If you make a query before the tenant has been identified, such as from early middleware in the stack or in a service provider, the query will not be scoped to the current tenant. To guarantee that middleware runs after the current tenant is identified, you should register it astenant middleware.As per the point above, queries made outside the panel with tenancy enabled do not have access to the current tenant, so are not scoped. If in doubt, please check if your queries are properly scoped or not before deploying your application.If you need to disable the tenancy global scope for a specific query, you can use thewithoutGlobalScope(filament()->getTenancyScopeName())method on the query.If any of your queries disable all global scopes, the tenancy global scope will be disabled as well. You should be careful when using this method, as it can lead to data leakage. If you need to disable all global scopes except the tenancy global scope, you can use thewithoutGlobalScopes()method passing an array of the global scopes you want to disable.
- Automatic association of newly created Eloquent models with the current tenant. When a new record is created for atenant-awareresource, the tenant is automatically associated with the record. This means that the record will belong to the current tenant, as the foreign key column is automatically set to the tenant’s ID. This is done by Filament registering an event listener for thecreatingandcreatedevents on the resource’s Eloquent model.Atenant-awareresource has to exist in the panel with tenancy enabled for the resource’s model to have the automatic association to happen. If you want automatic association for a model that does not have a corresponding resource, you mustregister a listener for thecreatingeventfor that model, and associate thefilament()->getTenant()with it.The events run after the tenant has been identified from the request. This happens during the middleware stack of panel requests. If you create a model before the tenant has been identified, such as from early middleware in the stack or in a service provider, it will not be associated with the current tenant. To guarantee that middleware runs after the current tenant is identified, you should register it astenant middleware.As per the point above, models created outside the panel with tenancy enabled do not have access to the current tenant, so are not associated. If in doubt, please check if your models are properly associated or not before deploying your application.If you need to disable the automatic association for a particular model, you canmute the eventstemporarily while you create it. If any of your code currently does this or removes event listeners permanently, you should check this is not affecting the tenancy feature.


Automatic global scoping of Eloquent model queries fortenant-awareresources that belong to the panel with tenancy enabled. The query used to fetch records for a resource is automatically scoped to the current tenant. This query is used to render the resource’s list table, and is also used to resolve records from the current URL when editing or viewing a record. This means that if a user attempts to view a record that does not belong to the current tenant, they will receive a 404 error.
- Atenant-awareresource has to exist in the panel with tenancy enabled for the resource’s model to have the global scope applied. If you want to scope the queries for a model that does not have a corresponding resource, you mustuse middleware to apply additional global scopesfor that model.
- The global scopes are applied after the tenant has been identified from the request. This happens during the middleware stack of panel requests. If you make a query before the tenant has been identified, such as from early middleware in the stack or in a service provider, the query will not be scoped to the current tenant. To guarantee that middleware runs after the current tenant is identified, you should register it astenant middleware.
- As per the point above, queries made outside the panel with tenancy enabled do not have access to the current tenant, so are not scoped. If in doubt, please check if your queries are properly scoped or not before deploying your application.
- If you need to disable the tenancy global scope for a specific query, you can use thewithoutGlobalScope(filament()->getTenancyScopeName())method on the query.
- If any of your queries disable all global scopes, the tenancy global scope will be disabled as well. You should be careful when using this method, as it can lead to data leakage. If you need to disable all global scopes except the tenancy global scope, you can use thewithoutGlobalScopes()method passing an array of the global scopes you want to disable.


Automatic association of newly created Eloquent models with the current tenant. When a new record is created for atenant-awareresource, the tenant is automatically associated with the record. This means that the record will belong to the current tenant, as the foreign key column is automatically set to the tenant’s ID. This is done by Filament registering an event listener for thecreatingandcreatedevents on the resource’s Eloquent model.
- Atenant-awareresource has to exist in the panel with tenancy enabled for the resource’s model to have the automatic association to happen. If you want automatic association for a model that does not have a corresponding resource, you mustregister a listener for thecreatingeventfor that model, and associate thefilament()->getTenant()with it.
- The events run after the tenant has been identified from the request. This happens during the middleware stack of panel requests. If you create a model before the tenant has been identified, such as from early middleware in the stack or in a service provider, it will not be associated with the current tenant. To guarantee that middleware runs after the current tenant is identified, you should register it astenant middleware.
- As per the point above, models created outside the panel with tenancy enabled do not have access to the current tenant, so are not associated. If in doubt, please check if your models are properly associated or not before deploying your application.
- If you need to disable the automatic association for a particular model, you canmute the eventstemporarily while you create it. If any of your code currently does this or removes event listeners permanently, you should check this is not affecting the tenancy feature.


### #uniqueandexistsvalidation

Laravel’suniqueandexistsvalidation rules do not use Eloquent models to query the database by default, so it will not use any global scopes defined on the model, including for multi-tenancy. As such, even if there is a soft-deleted record with the same value in a different tenant, the validation will fail.

If you would like two tenants to have complete data separation, you should use thescopedUnique()orscopedExists()methods instead, which replace Laravel’suniqueandexistsimplementations with ones that uses the model to query the database, applying any global scopes defined on the model, including for multi-tenancy:

```php
use Filament\Forms\Components\TextInput;

TextInput::make('email')
    ->scopedUnique()
    // or
    ->scopedExists()

```

For more information, see thevalidation documentationforunique()andexists().

### #Using tenant-aware middleware to apply additional global scopes

Since only models with resources that exist in the panel are automatically scoped to the current tenant, it might be useful to apply additional tenant scoping to other Eloquent models while they are being used in your panel. This would allow you to forget about scoping your queries to the current tenant, and instead have the scoping applied automatically. To do this, you can create a new middleware class likeApplyTenantScopes:

```php
php artisan make:middleware ApplyTenantScopes

```

Inside thehandle()method, you can apply any global scopes that you wish:

```php
use App\Models\Author;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApplyTenantScopes
{
    public function handle(Request $request, Closure $next)
    {
        Author::addGlobalScope(
            'tenant',
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        return $next($request);
    }
}

```

You can nowregister this middlewarefor all tenant-aware routes, and ensure that it is used across all Livewire AJAX requests by making it persistent:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantMiddleware([
            ApplyTenantScopes::class,
        ], isPersistent: true);
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** panel-configuration  
**Keywords:** auth, login, permissions, tenancy

*Extracted from Filament v5 Documentation - 2026-01-28*
