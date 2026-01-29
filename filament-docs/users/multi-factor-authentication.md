# Multi-factor authentication

**URL:** https://filamentphp.com/docs/5.x/users/multi-factor-authentication  
**Section:** users  
**Page:** multi-factor-authentication  
**Priority:** medium  
**AI Context:** Authentication, authorization, and multi-tenancy.

---

## #Introduction

Users in Filament can sign in with their email address and password by default. However, you can enable multi-factor authentication (MFA) to add an extra layer of security to your users’ accounts.

When MFA is enabled, users must perform an extra step before they are authenticated and have access to the application.

Filament includes two methods of MFA which you can enable out of the box:
- App authenticationuses a Google Authenticator-compatible app (such as the Google Authenticator, Authy, or Microsoft Authenticator apps) to generate a time-based one-time password (TOTP) that is used to verify the user.
- Email authenticationsends a one-time code to the user’s email address, which they must enter to verify their identity.


In Filament, users set up multi-factor authentication from theirprofile page. If you use Filament’s profile page feature, setting up multi-factor authentication will automatically add the correct UI elements to the profile page:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->profile();
}

```

## #App authentication

To enable app authentication in a panel, you must first add a new column to youruserstable (or whichever table is being used for your “authenticatable” Eloquent model in this panel). The column needs to store the secret key used to generate and verify the time-based one-time passwords. It can be a normaltext()column in a migration:

```php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('users', function (Blueprint $table) {
    $table->text('app_authentication_secret')->nullable();
});

```

In theUsermodel, you should implement theHasAppAuthenticationinterface and use theInteractsWithAppAuthenticationtrait which provides the necessary methods to interact with the secret code and other information about the integration:

```php
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAppAuthentication, MustVerifyEmail
{
    use InteractsWithAppAuthentication;
    
    // ...
}

```

TIP

Filament provides a default implementation for speed and simplicity, but you could implement the required methods yourself and customize the column name or store the secret in a completely separate table.

Finally, you should activate the app authentication feature in your panel. To do this, use themultiFactorAuthentication()method in theconfiguration, and pass aAppAuthenticationinstance to it:

```php
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            AppAuthentication::make(),
        ]);
}

```

### #Setting up app recovery codes

If your users lose access to their two-factor authentication app, they will be unable to sign in to your application. To prevent this, you can generate a set of recovery codes that users can use to sign in if they lose access to their two-factor authentication app.

In a similar way to theapp_authentication_secretcolumn, you should add a new column to youruserstable (or whichever table is being used for your “authenticatable” Eloquent model in this panel). The column needs to store the recovery codes. It can be a normaltext()column in a migration:

```php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('users', function (Blueprint $table) {
    $table->text('app_authentication_recovery_codes')->nullable();
});

```

Next, you should implement theHasAppAuthenticationRecoveryinterface on theUsermodel and use theInteractsWithAppAuthenticationRecoverytrait which provides Filament with the necessary methods to interact with the recovery codes:

```php
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthenticationRecovery;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery, MustVerifyEmail
{
    use InteractsWithAppAuthentication;
    use InteractsWithAppAuthenticationRecovery;
    
    // ...
}

```

TIP

Filament provides a default implementation for speed and simplicity, but you could implement the required methods yourself and customize the column name or store the recovery codes in a completely separate table.

Finally, you should activate the app authentication recovery codes feature in your panel. To do this, pass therecoverable()method to theAppAuthenticationinstance in themultiFactorAuthentication()method in theconfiguration:

```php
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            AppAuthentication::make()
                ->recoverable(),
        ]);
}

```

#### #Changing the number of recovery codes that are generated

By default, Filament generates 8 recovery codes for each user. If you want to change this, you can use therecoveryCodeCount()method on theAppAuthenticationinstance in themultiFactorAuthentication()method in theconfiguration:

```php
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            AppAuthentication::make()
                ->recoverable()
                ->recoveryCodeCount(10),
        ]);
}

```

#### #Preventing users from regenerating their recovery codes

By default, users can visit their profile to regenerate their recovery codes. If you want to prevent this, you can use theregenerableRecoveryCodes(false)method on theAppAuthenticationinstance in themultiFactorAuthentication()method in theconfiguration:

```php
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            AppAuthentication::make()
                ->recoverable()
                ->regenerableRecoveryCodes(false),
        ]);
}

```

### #Changing the app code expiration time

App codes are issued using a time-based one-time password (TOTP) algorithm, which means that they are only valid for a short period of time before and after the time they are generated. The time is defined in a “window” of time. By default, Filament uses an expiration window of8, which creates a 4-minute validity period on either side of the generation time (8 minutes in total).

To change the window, for example to only be valid for 2 minutes after it is generated, you can use thecodeWindow()method on theAppAuthenticationinstance, set to4:

```php
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            AppAuthentication::make()
                ->codeWindow(4),
        ]);
}

```

### #Customizing the app authentication brand name

Each app authentication integration has a “brand name” that is displayed in the authentication app. By default, this is the name of your app. If you want to change this, you can use thebrandName()method on theAppAuthenticationinstance in themultiFactorAuthentication()method in theconfiguration:

```php
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            AppAuthentication::make()
                ->brandName('Filament Demo'),
        ]);
}

```

## #Email authentication

Email authentication sends the user one-time codes to their email address, which they must enter to verify their identity.

To enable email authentication in a panel, you must first add a new column to youruserstable (or whichever table is being used for your “authenticatable” Eloquent model in this panel). The column needs to store a boolean indicating whether or not email authentication is enabled:

```php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::table('users', function (Blueprint $table) {
    $table->boolean('has_email_authentication')->default(false);
});

```

Next, you should implement theHasEmailAuthenticationinterface on theUsermodel and use theInteractsWithEmailAuthenticationtrait which provides Filament with the necessary methods to interact with the column that indicates whether or not email authentication is enabled:

```php
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Auth\MultiFactor\Email\Concerns\InteractsWithEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasEmailAuthentication, MustVerifyEmail
{
    use InteractsWithEmailAuthentication;
    
    // ...
}

```

TIP

Filament provides a default implementation for speed and simplicity, but you could implement the required methods yourself and customize the column name or store the value in a completely separate table.

Finally, you should activate the email authentication feature in your panel. To do this, use themultiFactorAuthentication()method in theconfiguration, and pass anEmailAuthenticationinstance to it:

```php
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            EmailAuthentication::make(),
        ]);
}

```

### #Changing the email code expiration time

Email codes are issued with a lifetime of 4 minutes, after which they expire.

To change the expiration period, for example to only be valid for 2 minutes after codes are generated, you can use thecodeExpiryMinutes()method on theEmailAuthenticationinstance, set to2:

```php
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            EmailAuthentication::make()
                ->codeExpiryMinutes(2),
        ]);
}

```

## #Requiring multi-factor authentication

By default, users are not required to set up multi-factor authentication. You can require users to configure it by passingisRequired: trueas a parameter to themultiFactorAuthentication()method in theconfiguration:

```php
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->multiFactorAuthentication([
            AppAuthentication::make(),
        ], isRequired: true);
}

```

When this is enabled, users will be prompted to set up multi-factor authentication after they sign in, if they have not already done so.

## #Security notes about multi-factor authentication

In Filament, the multi-factor authentication process occurs before the user is actually authenticated into the app. This allows you to be sure that no users can authenticate and access the app without passing the multi-factor authentication step. You do not need to remember to add middleware to any of your authenticated routes to ensure that users completed the multi-factor authentication step.

However, if you have other parts of your Laravel app that authenticate users, please bear in mind that they will not be challenged for multi-factor authentication if they are already authenticated elsewhere and then visit the panel, unlessmulti-factor authentication is requiredand they have not set it up yet.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** panel-configuration  
**Keywords:** auth, login, permissions, tenancy

*Extracted from Filament v5 Documentation - 2026-01-28*
