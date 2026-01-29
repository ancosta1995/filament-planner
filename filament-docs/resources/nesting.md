# Nested resources

**URL:** https://filamentphp.com/docs/5.x/resources/nesting  
**Section:** resources  
**Page:** nesting  
**Priority:** high  
**AI Context:** Core CRUD functionality. Main feature for building admin panels.

---

## #Overview

Relation managersandrelation pagesprovide you with an easy way to render a table of related records inside a resource.

For example, in aCourseResource, you may have a relation manager or page forlessonsthat belong to that course. You can create and edit lessons from the table, which opens modal dialogs.

However, lessons may be too complex to be created and edited in a modal. You may wish that lessons had their own resource, so that creating and editing them would be a full page experience. This is a nested resource.

## #Creating a nested resource

To create a nested resource, you can use themake:filament-resourcecommand with the--nestedoption:

```php
php artisan make:filament-resource Lesson --nested

```

To access the nested resource, you will also need arelation managerorrelation page. This is where the user can see the list of related records, and click links to the “create” and “edit” pages.

To create a relation manager or page, you can use themake:filament-relation-managerormake:filament-pagecommand:

```php
php artisan make:filament-relation-manager CourseResource lessons title

php artisan make:filament-page ManageCourseLessons --resource=CourseResource --type=ManageRelatedRecords

```

When creating a relation manager or page, Filament will ask if you want each table row to link to a resource instead of opening a modal, to which you should answer “yes” and select the nested resource that you just created.

After generating the relation manager or page, it will have a property pointing to the nested resource:

```php
use App\Filament\Resources\Courses\Resources\Lessons\LessonResource;

protected static ?string $relatedResource = LessonResource::class;

```

The nested resource class will have a property pointing to the parent resource:

```php
use App\Filament\Resources\Courses\CourseResource;

protected static ?string $parentResource = CourseResource::class;

```

## #Customizing the relationship names

In the same way that relation managers and pages predict the name of relationships based on the models in those relationships, nested resources do the same. Sometimes, you may have a relationship that does not fit the traditional relationship naming convention, and you will need to inform Filament of the correct relationship names for the nested resource.

To customize the relationship names, first remove the$parentResourceproperty from the nested resource class. Then define agetParentResourceRegistration()method:

```php
use App\Filament\Resources\Courses\CourseResource;
use Filament\Resources\ParentResourceRegistration;

public static function getParentResourceRegistration(): ?ParentResourceRegistration
{
    return CourseResource::asParent()
        ->relationship('lessons')
        ->inverseRelationship('course');
}

```

You can omit the calls torelationship()andinverseRelationship()if you want to use the default names.

## #Registering a relation manager with the correct URL

When dealing with a nested resource that is listed by a relation manager, and the relation manager is amongst others on that page, you may notice that the URL to it is not correct when you redirect from the nested resource back to it. This is because each relation manager registered on a resource is assigned an integer, which is used to identify it in the URL when switching between multiple relation managers. For example,?relation=0might represent one relation manager in the URL, and?relation=1might represent another.

When redirecting from a nested resource back to a relation manager, Filament will assume that the relationship name is used to identify that relation manager in the URL. For example, if you have a nestedLessonResourceand aLessonsRelationManager, the relationship name islessons, and should be used as theURL parameter keyfor that relation manager when it is registered:

```php
public static function getRelations(): array
{
    return [
        'lessons' => LessonsRelationManager::class,
    ];
}

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables, actions  
**Keywords:** crud, database, eloquent, models, admin panel

*Extracted from Filament v5 Documentation - 2026-01-28*
