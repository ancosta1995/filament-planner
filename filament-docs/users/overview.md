# Overview

**URL:** https://filamentphp.com/docs/5.x/users/overview  
**Section:** users  
**Page:** overview  
**Priority:** medium  
**AI Context:** Authentication, authorization, and multi-tenancy.

---

## #Introduction

By default, allApp\Models\Users can access Filament locally. To allow them to access Filament in production, you must take a few extra steps to ensure that only the correct users have access to the app.

## #Authorizing access to the panel

To set up yourApp\Models\Userto access Filament in non-local environments, you must implement theFilamentUsercontract:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    // ...

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
    }
}

```

ThecanAccessPanel()method returnstrueorfalsedepending on whether the user is allowed to access the$panel. In this example, we check if the user’s email ends with@yourdomain.comand if they have verified their email address.

Since you have access to the current$panel, you can write conditional checks for separate panels. For example, only restricting access to the admin panel while allowing all users to access the other panels of your app:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    // ...

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
        }

        return true;
    }
}

```

## #Authorizing access to Resources

See theAuthorizationsection in the Resource documentation for controlling access to Resource pages and their data records.

## #Setting up user avatars

Out of the box, Filament usesui-avatars.comto generate avatars based on a user’s name. However, if your user model has anavatar_urlattribute, that will be used instead. To customize how Filament gets a user’s avatar URL, you can implement theHasAvatarcontract:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    // ...

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }
}

```

ThegetFilamentAvatarUrl()method is used to retrieve the avatar of the current user. Ifnullis returned from this method, Filament will fall back toui-avatars.com.

### #Using a different avatar provider

You can easily swap outui-avatars.comfor a different service, by creating a new avatar provider.

In this example, we create a new file atapp/Filament/AvatarProviders/BoringAvatarsProvider.phpforboringavatars.com. Theget()method accepts a user model instance and returns an avatar URL for that user:

```php
<?php

namespace App\Filament\AvatarProviders;

use Filament\AvatarProviders\Contracts;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class BoringAvatarsProvider implements Contracts\AvatarProvider
{
    public function get(Model | Authenticatable $record): string
    {
        $name = str(Filament::getNameForDefaultAvatar($record))
            ->trim()
            ->explode(' ')
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join(' ');

        return 'https://source.boringavatars.com/beam/120/' . urlencode($name);
    }
}

```

Now, register this new avatar provider in theconfiguration:

```php
use App\Filament\AvatarProviders\BoringAvatarsProvider;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->defaultAvatarProvider(BoringAvatarsProvider::class);
}

```

## #Configuring the user’s name attribute

By default, Filament will use thenameattribute of the user to display their name in the app. To change this, you can implement theHasNamecontract:

```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasName
{
    // ...

    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}

```

ThegetFilamentName()method is used to retrieve the name of the current user.

## #Authentication features

You can easily enable authentication features for a panel in the configuration file:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->login()
        ->registration()
        ->passwordReset()
        ->emailVerification()
        ->emailChangeVerification()
        ->profile();
}

```

Filament also supports multi-factor authentication, which you can learn about in theMulti-factor authenticationsection.

### #Customizing the authentication features

If you’d like to replace these pages with your own, you can pass in any Filament page class to these methods.

Most people will be able to make their desired customizations by extending the base page class from the Filament codebase, overriding methods likeform(), and then passing the new page class in to the configuration:

```php
use App\Filament\Pages\Auth\EditProfile;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->profile(EditProfile::class);
}

```

In this example, we will customize the profile page. We need to create a new PHP class atapp/Filament/Pages/Auth/EditProfile.php:

```php
<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}

```

This class extends the base profile page class from the Filament codebase. Other page classes you could extend include:
- Filament\Auth\Pages\Login
- Filament\Auth\Pages\Register
- Filament\Auth\Pages\EmailVerification\EmailVerificationPrompt
- Filament\Auth\Pages\PasswordReset\RequestPasswordReset
- Filament\Auth\Pages\PasswordReset\ResetPassword


In theform()method of the example, we call methods likegetNameFormComponent()to get the default form components for the page. You can customize these components as required. For all the available customization options, see the baseEditProfilepage class in the Filament codebase - it contains all the methods that you can override to make changes.

#### #Customizing an authentication field without needing to re-define the form

If you’d like to customize a field in an authentication form without needing to define a newform()method, you could extend the specific field method and chain your customizations:

```php
use Filament\Schemas\Components\Component;

protected function getPasswordFormComponent(): Component
{
    return parent::getPasswordFormComponent()
        ->revealable(false);
}

```

### #Email change verification

If you’re using theprofile()feature with theemailChangeVerification()feature, users that change their email address from the profile form will be required to verify their new email address before they can log in with it. This is done by sending a verification email to the new address, which contains a link that the user must click to verify their new email address. The email address in the database is not updated until the user clicks the link in the email.

The link that a user is sent is valid for 60 minutes. At the same time as the email to the new address is sent, an email to the old address is also sent, with a link to block the change. This is a security feature to potentially prevent a user from being affected by a malicious actor.

### #Using a sidebar on the profile page

By default, the profile page does not use the standard page layout with a sidebar. This is so that it works with thetenancyfeature, otherwise it would not be accessible if the user had no tenants, since the sidebar links are routed to the current tenant.

If you aren’t usingtenancyin your panel, and you’d like the profile page to use the standard page layout with a sidebar, you can pass theisSimple: falseparameter to$panel->profile()when registering the page:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->profile(isSimple: false);
}

```

### #Customizing the authentication route slugs

You can customize the URL slugs used for the authentication routes in theconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->loginRouteSlug('login')
        ->registrationRouteSlug('register')
        ->passwordResetRoutePrefix('password-reset')
        ->passwordResetRequestRouteSlug('request')
        ->passwordResetRouteSlug('reset')
        ->emailVerificationRoutePrefix('email-verification')
        ->emailVerificationPromptRouteSlug('prompt')
        ->emailVerificationRouteSlug('verify')
        ->emailChangeVerificationRoutePrefix('email-change-verification')
        ->emailChangeVerificationRouteSlug('verify');
}

```

### #Setting the authentication guard

To set the authentication guard that Filament uses, you can pass in the guard name to theauthGuard()configurationmethod:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->authGuard('web');
}

```

### #Setting the password broker

To set the password broker that Filament uses, you can pass in the broker name to theauthPasswordBroker()configurationmethod:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->authPasswordBroker('users');
}

```

### #Disabling revealable password inputs

By default, all password inputs in authentication forms arerevealable(). This allows the user to see a plain text version of the password they’re typing by clicking a button. To disable this feature, you can passfalseto therevealablePasswords()configurationmethod:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->revealablePasswords(false);
}

```

You could also disable this feature on a per-field basis by calling->revealable(false)on the field object whenextending the base page class.

## #Setting up guest access to a panel

By default, Filament expects to work with authenticated users only. To allow guests to access a panel, you need to avoid using components which expect a signed-in user (such as profiles, avatars), and remove the built-in Authentication middleware:
- Remove the defaultAuthenticate::classfrom theauthMiddleware()array in the panel configuration.
- Remove->login()and any otherauthentication featuresfrom the panel.
- Remove the defaultAccountWidgetfrom thewidgets()array, because it reads the current user’s data.


### #Authorizing guests in policies

When present, Filament relies onLaravel Model Policiesfor access control. To give read-access forguest users in a model policy, create the Policy and update theviewAny()andview()methods, changing theUser $userparam to?User $userso that it’s optional, andreturn true;. Alternatively, you can remove those methods from the policy entirely.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** panel-configuration  
**Keywords:** auth, login, permissions, tenancy

*Extracted from Filament v5 Documentation - 2026-01-28*
