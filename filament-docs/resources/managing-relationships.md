# Managing relationships

**URL:** https://filamentphp.com/docs/5.x/resources/managing-relationships  
**Section:** resources  
**Page:** managing-relationships  
**Priority:** high  
**AI Context:** Core CRUD functionality. Main feature for building admin panels.

---

## #Choosing the right tool for the job

Filament provides many ways to manage relationships in the app. Which feature you should use depends on the type of relationship you are managing, and which UI you are looking for.

### #Relation managers - interactive tables underneath your resource forms

NOTE

These are compatible withHasMany,HasManyThrough,BelongsToMany,MorphManyandMorphToManyrelationships.

Relation managersare interactive tables that allow administrators to list, create, attach, associate, edit, detach, dissociate and delete related records without leaving the resource’s Edit or View page.

### #Select & checkbox list - choose from existing records or create a new one

NOTE

These are compatible withBelongsTo,MorphToandBelongsToManyrelationships.

Using aselect, users will be able to choose from a list of existing records. You may alsoadd a button that allows you to create a new record inside a modal, without leaving the page.

When using aBelongsToManyrelationship with a select, you’ll be able to select multiple options, not just one. Records will be automatically added to your pivot table when you submit the form. If you wish, you can swap out the multi-select dropdown with a simplecheckbox list. Both components work in the same way.

### #Repeaters - CRUD multiple related records inside the owner’s form

NOTE

These are compatible withHasManyandMorphManyrelationships.

Repeatersare standard form components, which can render a repeatable set of fields infinitely. They can be hooked up to a relationship, so records are automatically read, created, updated, and deleted from the related table. They live inside the main form schema, and can be used inside resource pages, as well as nesting within action modals.

From a UX perspective, this solution is only suitable if your related model only has a few fields. Otherwise, the form can get very long.

### #Layout form components - saving form fields to a single relationship

NOTE

These are compatible withBelongsTo,HasOneandMorphOnerelationships.

All layout form components (Grid,Section,Fieldset, etc.) have arelationship()method. When you use this, all fields within that layout are saved to the related model instead of the owner’s model:

```php
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;

Fieldset::make('Metadata')
    ->relationship('metadata')
    ->schema([
        TextInput::make('title'),
        Textarea::make('description'),
        FileUpload::make('image'),
    ])

```

In this example, thetitle,descriptionandimageare automatically loaded from themetadatarelationship, and saved again when the form is submitted. If themetadatarecord does not exist, it is automatically created.

This feature is explained more in depth in theForms documentation. Please visit that page for more information about how to use it.

## #Creating a relation manager

To create a relation manager, you can use themake:filament-relation-managercommand:

```php
php artisan make:filament-relation-manager CategoryResource posts title

```
- CategoryResourceis the name of the resource class for the owner (parent) model.
- postsis the name of the relationship you want to manage.
- titleis the name of the attribute that will be used to identify posts.


This will create aCategoryResource/RelationManagers/PostsRelationManager.phpfile. This contains a class where you are able to define aformandtablefor your relation manager:

```php
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

public function form(Schema $schema): Schema
{
    return $schema
        ->components([
            Forms\Components\TextInput::make('title')->required(),
            // ...
        ]);
}

public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('title'),
            // ...
        ]);
}

```

You must register the new relation manager in your resource’sgetRelations()method:

```php
public static function getRelations(): array
{
    return [
        RelationManagers\PostsRelationManager::class,
    ];
}

```

Once a table and form have been defined for the relation manager, visit theEditorViewpage of your resource to see it in action.

### #Customizing the relation manager’s URL parameter

If you pass a key to the array returned fromgetRelations(), it will be used in the URL for that relation manager when switching between multiple relation managers. For example, you can passpoststo use?relation=postsin the URL instead of a numeric array index:

```php
public static function getRelations(): array
{
    return [
        'posts' => RelationManagers\PostsRelationManager::class,
    ];
}

```

### #Read-only mode

Relation managers are usually displayed on either the Edit or View page of a resource. On the View page, Filament will automatically hide all actions that modify the relationship, such as create, edit, and delete. We call this “read-only mode”, and it is there by default to preserve the read-only behavior of the View page. However, you can disable this behavior, by overriding theisReadOnly()method on the relation manager class to returnfalseall the time:

```php
public function isReadOnly(): bool
{
    return false;
}

```

Alternatively, if you hate this functionality, you can disable it for all relation managers at once in the panelconfiguration:

```php
use Filament\Panel;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->readOnlyRelationManagersOnResourceViewPagesByDefault(false);
}

```

### #Unconventional inverse relationship names

For inverse relationships that do not follow Laravel’s naming guidelines, you may wish to use theinverseRelationship()method on the table:

```php
use Filament\Tables;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('title'),
            // ...
        ])
        ->inverseRelationship('section'); // Since the inverse related model is `Category`, this is normally `category`, not `section`.
}

```

### #Handling soft-deletes

By default, you will not be able to interact with deleted records in the relation manager. If you’d like to add functionality to restore, force-delete and filter trashed records in your relation manager, use the--soft-deletesflag when generating the relation manager:

```php
php artisan make:filament-relation-manager CategoryResource posts title --soft-deletes

```

You can find out more about soft-deletinghere.

## #Listing related records

Related records will be listed in a table. The entire relation manager is based around this table, which contains actions tocreate,edit,attach / detach,associate / dissociate, and delete records.

You may use any features of theTable Builderto customize relation managers.

### #Listing with pivot attributes

ForBelongsToManyandMorphToManyrelationships, you may also add pivot table attributes. For example, if you have aTeamsRelationManagerfor yourUserResource, and you want to add therolepivot attribute to the table, you can use:

```php
use Filament\Tables;

public function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('role'),
        ]);
}

```

Please ensure that any pivot attributes are listed in thewithPivot()method of the relationshipandinverse relationship.

## #Creating related records

### #Creating with pivot attributes

ForBelongsToManyandMorphToManyrelationships, you may also add pivot table attributes. For example, if you have aTeamsRelationManagerfor yourUserResource, and you want to add therolepivot attribute to the create form, you can use:

```php
use Filament\Forms;
use Filament\Schemas\Schema;

public function form(Schema $schema): Schema
{
    return $schema
        ->components([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('role')->required(),
            // ...
        ]);
}

```

Please ensure that any pivot attributes are listed in thewithPivot()method of the relationshipandinverse relationship.

### #Customizing theCreateAction

To learn how to customize theCreateAction, including mutating the form data, changing the notification, and adding lifecycle hooks, please see theActions documentation.

## #Editing related records

### #Editing with pivot attributes

ForBelongsToManyandMorphToManyrelationships, you may also edit pivot table attributes. For example, if you have aTeamsRelationManagerfor yourUserResource, and you want to add therolepivot attribute to the edit form, you can use:

```php
use Filament\Forms;
use Filament\Schemas\Schema;

public function form(Schema $schema): Schema
{
    return $schema
        ->components([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('role')->required(),
            // ...
        ]);
}

```

Please ensure that any pivot attributes are listed in thewithPivot()method of the relationshipandinverse relationship.

### #Customizing theEditAction

To learn how to customize theEditAction, including mutating the form data, changing the notification, and adding lifecycle hooks, please see theActions documentation.

## #Attaching and detaching records

Filament is able to attach and detach records forBelongsToManyandMorphToManyrelationships.

When generating your relation manager, you may pass the--attachflag to also addAttachAction,DetachActionandDetachBulkActionto the table:

```php
php artisan make:filament-relation-manager CategoryResource posts title --attach

```

Alternatively, if you’ve already generated your resource, you can just add the actions to the$tablearrays:

```php
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->headerActions([
            // ...
            AttachAction::make(),
        ])
        ->recordActions([
            // ...
            DetachAction::make(),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                // ...
                DetachBulkAction::make(),
            ]),
        ]);
}

```

### #Preloading the attachment modal select options

By default, as you search for a record to attach, options will load from the database via AJAX. If you wish to preload these options when the form is first loaded instead, you can use thepreloadRecordSelect()method ofAttachAction:

```php
use Filament\Actions\AttachAction;

AttachAction::make()
    ->preloadRecordSelect()

```

### #Attaching with pivot attributes

When you attach record with theAttachbutton, you may wish to define a custom form to add pivot attributes to the relationship:

```php
use Filament\Actions\AttachAction;
use Filament\Forms;

AttachAction::make()
    ->schema(fn (AttachAction $action): array => [
        $action->getRecordSelect(),
        Forms\Components\TextInput::make('role')->required(),
    ])

```

In this example,$action->getRecordSelect()returns the select field to pick the record to attach. Theroletext input is then saved to the pivot table’srolecolumn.

Please ensure that any pivot attributes are listed in thewithPivot()method of the relationshipandinverse relationship.

### #Scoping the options to attach

You may want to scope the options available toAttachAction:

```php
use Filament\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;

AttachAction::make()
    ->recordSelectOptionsQuery(fn (Builder $query) => $query->whereBelongsTo(auth()->user()))

```

### #Searching the options to attach across multiple columns

By default, the options available toAttachActionwill be searched in therecordTitleAttribute()of the table. If you wish to search across multiple columns, you can use therecordSelectSearchColumns()method:

```php
use Filament\Actions\AttachAction;

AttachAction::make()
    ->recordSelectSearchColumns(['title', 'description'])

```

### #Attaching multiple records

Themultiple()method on theAttachActioncomponent allows you to select multiple values:

```php
use Filament\Actions\AttachAction;

AttachAction::make()
    ->multiple()

```

### #Customizing the select field in the attached modal

You may customize the select field object that is used during attachment by passing a function to therecordSelect()method:

```php
use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;

AttachAction::make()
    ->recordSelect(
        fn (Select $select) => $select->placeholder('Select a post'),
    )

```

#### #Selecting records to attach using a modal table

You may use thetableSelect()method to select records in the attachment modal using a full Filament table, instead of a simple select dropdown:

```php
use App\Filament\Resources\Products\Tables\ProductsTable;
use Filament\Actions\AttachAction;

AttachAction::make()
    ->tableSelect(ProductsTable::class)

```

In this example, theProductsTableclass is a standard Filament table class, with aconfigure()method that defines the table’s columns:

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

public static function configure(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name'),
            TextColumn::make('sku'),
            // ...
        ])
        ->filters([
            // ...
        ]);
}

```

### #Handling duplicates

By default, you will not be allowed to attach a record more than once. This is because you must also set up a primaryidcolumn on the pivot table for this feature to work.

Please ensure that theidattribute is listed in thewithPivot()method of the relationshipandinverse relationship.

Finally, add theallowDuplicates()method to the table:

```php
public function table(Table $table): Table
{
    return $table
        ->allowDuplicates();
}

```

### #Improving the performance of detach bulk actions

By default, theDetachBulkActionwill load all Eloquent records into memory, before looping over them and detaching them one by one.

If you are detaching a large number of records, you may want to use thechunkSelectedRecords()method to fetch a smaller number of records at a time. This will reduce the memory usage of your application:

```php
use Filament\Actions\DetachBulkAction;

DetachBulkAction::make()
    ->chunkSelectedRecords(250)

```

Filament loads Eloquent records into memory before detaching them for two reasons:
- To allow individual records in the collection to be authorized with a model policy before detaching (usingauthorizeIndividualRecords('delete'), for example).
- To ensure that model events are run when detaching records, such as thedeletinganddeletedevents in a model observer.


If you do not require individual record policy authorization and model events, you can use thefetchSelectedRecords(false)method, which will not fetch the records into memory before detaching them, and instead will detach them in a single query:

```php
use Filament\Actions\DetachBulkAction;

DetachBulkAction::make()
    ->fetchSelectedRecords(false)

```

## #Associating and dissociating records

Filament is able to associate and dissociate records forHasManyandMorphManyrelationships.

When generating your relation manager, you may pass the--associateflag to also addAssociateAction,DissociateActionandDissociateBulkActionto the table:

```php
php artisan make:filament-relation-manager CategoryResource posts title --associate

```

Alternatively, if you’ve already generated your resource, you can just add the actions to the$tablearrays:

```php
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->headerActions([
            // ...
            AssociateAction::make(),
        ])
        ->recordActions([
            // ...
            DissociateAction::make(),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                // ...
                DissociateBulkAction::make(),
            ]),
        ]);
}

```

### #Preloading the associate modal select options

By default, as you search for a record to associate, options will load from the database via AJAX. If you wish to preload these options when the form is first loaded instead, you can use thepreloadRecordSelect()method ofAssociateAction:

```php
use Filament\Actions\AssociateAction;

AssociateAction::make()
    ->preloadRecordSelect()

```

### #Scoping the options to associate

You may want to scope the options available toAssociateAction:

```php
use Filament\Actions\AssociateAction;
use Illuminate\Database\Eloquent\Builder;

AssociateAction::make()
    ->recordSelectOptionsQuery(fn (Builder $query) => $query->whereBelongsTo(auth()->user()))

```

### #Searching the options to associate across multiple columns

By default, the options available toAssociateActionwill be searched in therecordTitleAttribute()of the table. If you wish to search across multiple columns, you can use therecordSelectSearchColumns()method:

```php
use Filament\Actions\AssociateAction;

AssociateAction::make()
    ->recordSelectSearchColumns(['title', 'description'])

```

### #Associating multiple records

Themultiple()method on theAssociateActioncomponent allows you to select multiple values:

```php
use Filament\Actions\AssociateAction;

AssociateAction::make()
    ->multiple()

```

### #Customizing the select field in the associate modal

You may customize the select field object that is used during association by passing a function to therecordSelect()method:

```php
use Filament\Actions\AssociateAction;
use Filament\Forms\Components\Select;

AssociateAction::make()
    ->recordSelect(
        fn (Select $select) => $select->placeholder('Select a post'),
    )

```

### #Improving the performance of dissociate bulk actions

By default, theDissociateBulkActionwill load all Eloquent records into memory, before looping over them and dissociating them one by one.

If you are dissociating a large number of records, you may want to use thechunkSelectedRecords()method to fetch a smaller number of records at a time. This will reduce the memory usage of your application:

```php
use Filament\Actions\DissociateBulkAction;

DissociateBulkAction::make()
    ->chunkSelectedRecords(250)

```

Filament loads Eloquent records into memory before dissociating them for two reasons:
- To allow individual records in the collection to be authorized with a model policy before dissociation (usingauthorizeIndividualRecords('update'), for example).
- To ensure that model events are run when dissociating records, such as theupdatingandupdatedevents in a model observer.


If you do not require individual record policy authorization and model events, you can use thefetchSelectedRecords(false)method, which will not fetch the records into memory before dissociating them, and instead will dissociate them in a single query:

```php
use Filament\Actions\DissociateBulkAction;

DissociateBulkAction::make()
    ->fetchSelectedRecords(false)

```

## #Viewing related records

When generating your relation manager, you may pass the--viewflag to also add aViewActionto the table:

```php
php artisan make:filament-relation-manager CategoryResource posts title --view

```

Alternatively, if you’ve already generated your relation manager, you can just add theViewActionto the$table->recordActions()array:

```php
use Filament\Actions\ViewAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->recordActions([
            ViewAction::make(),
            // ...
        ]);
}

```

## #Deleting related records

By default, you will not be able to interact with deleted records in the relation manager. If you’d like to add functionality to restore, force-delete and filter trashed records in your relation manager, use the--soft-deletesflag when generating the relation manager:

```php
php artisan make:filament-relation-manager CategoryResource posts title --soft-deletes

```

Alternatively, you may add soft-deleting functionality to an existing relation manager:

```php
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

public function table(Table $table): Table
{
    return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]))
        ->columns([
            // ...
        ])
        ->filters([
            TrashedFilter::make(),
            // ...
        ])
        ->recordActions([
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
            // ...
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
                // ...
            ]),
        ]);
}

```

### #Customizing theDeleteAction

To learn how to customize theDeleteAction, including changing the notification and adding lifecycle hooks, please see theActions documentation.

## #Importing related records

TheImportActioncan be added to the header of a relation manager to import records. In this case, you probably want to tell the importer which owner these new records belong to. You can useimport optionsto pass through the ID of the owner record:

```php
ImportAction::make()
    ->importer(ProductImporter::class)
    ->options(['categoryId' => $this->getOwnerRecord()->getKey()])

```

Now, in the importer class, you can associate the owner in a one-to-many relationship with the imported record:

```php
public function resolveRecord(): ?Product
{
    $product = Product::firstOrNew([
        'sku' => $this->data['sku'],
    ]);
    
    $product->category()->associate($this->options['categoryId']);
    
    return $product;
}

```

Alternatively, you can attach the record in a many-to-many relationship using theafterSave()hook of the importer:

```php
protected function afterSave(): void
{
    $this->record->categories()->syncWithoutDetaching([$this->options['categoryId']]);
}

```

## #Accessing the relationship’s owner record

Relation managers are Livewire components. When they are first loaded, the owner record (the Eloquent record which serves as a parent - the main resource model) is saved into a property. You can read this property using:

```php
$this->getOwnerRecord()

```

However, if you’re inside astaticmethod likeform()ortable(),$thisisn’t accessible. So, you mayuse a callbackto access the$livewireinstance:

```php
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;

public function form(Schema $schema): Schema
{
    return $schema
        ->components([
            Forms\Components\Select::make('store_id')
                ->options(function (RelationManager $livewire): array {
                    return $livewire->getOwnerRecord()->stores()
                        ->pluck('name', 'id')
                        ->toArray();
                }),
            // ...
        ]);
}

```

All methods in Filament accept a callback which you can access$livewire->ownerRecordin.

## #Grouping relation managers

You may choose to group relation managers together into one tab. To do this, you may wrap multiple managers in aRelationGroupobject, with a label:

```php
use Filament\Resources\RelationManagers\RelationGroup;

public static function getRelations(): array
{
    return [
        // ...
        RelationGroup::make('Contacts', [
            RelationManagers\IndividualsRelationManager::class,
            RelationManagers\OrganizationsRelationManager::class,
        ]),
        // ...
    ];
}

```

## #Conditionally showing relation managers

By default, relation managers will be visible if theviewAny()method for the related model policy returnstrue.

You may use thecanViewForRecord()method to determine if the relation manager should be visible for a specific owner record and page:

```php
use Illuminate\Database\Eloquent\Model;

public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
{
    return $ownerRecord->status === Status::Draft;
}

```

## #Combining the relation manager tabs with the form

On the Edit or View page class, override thehasCombinedRelationManagerTabsWithContent()method:

```php
public function hasCombinedRelationManagerTabsWithContent(): bool
{
    return true;
}

```

### #Customizing the content tab

On the Edit or View page class, override thegetContentTabComponent()method, and use anyTabcustomization methods:

```php
use Filament\Schemas\Components\Tabs\Tab;

public function getContentTabComponent(): Tab
{
    return Tab::make('Settings')
        ->icon('heroicon-m-cog');
}

```

### #Setting the position of the form tab

By default, the form tab is rendered before the relation tabs. To render it after, you can override thegetContentTabPosition()method on the Edit or View page class:

```php
use Filament\Resources\Pages\Enums\ContentTabPosition;

public function getContentTabPosition(): ?ContentTabPosition
{
    return ContentTabPosition::After;
}

```

## #Customizing relation manager tabs

To customize the tab for a relation manager, override thegetTabComponent()method, and use anyTabcustomization methods:

```php
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

public static function getTabComponent(Model $ownerRecord, string $pageClass): Tab
{
    return Tab::make('Blog posts')
        ->badge($ownerRecord->posts()->count())
        ->badgeColor('info')
        ->badgeTooltip('The number of posts in this category')
        ->icon('heroicon-m-document-text');
}

```

If you are using arelation group, you can use thetab()method:

```php
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Model;

RelationGroup::make('Contacts', [
    // ...
])
    ->tab(fn (Model $ownerRecord): Tab => Tab::make('Blog posts')
        ->badge($ownerRecord->posts()->count())
        ->badgeColor('info')
        ->badgeTooltip('The number of posts in this category')
        ->icon('heroicon-m-document-text'));

```

## #Sharing a resource’s form and table with a relation manager

You may decide that you want a resource’s form and table to be identical to a relation manager’s, and subsequently want to reuse the code you previously wrote. This is easy, by calling theform()andtable()methods of the resource from the relation manager:

```php
use App\Filament\Resources\Blog\Posts\PostResource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

public function form(Schema $schema): Schema
{
    return PostResource::form($schema);
}

public function table(Table $table): Table
{
    return PostResource::table($table);
}

```

### #Hiding a shared form component on the relation manager

If you’re sharing a form component from the resource with the relation manager, you may want to hide it on the relation manager. This is especially useful if you want to hide aSelectfield for the owner record in the relation manager, since Filament will handle this for you anyway. To do this, you may use thehiddenOn()method, passing the name of the relation manager:

```php
use App\Filament\Resources\Blog\Posts\PostResource\RelationManagers\CommentsRelationManager;
use Filament\Forms\Components\Select;

Select::make('post_id')
    ->relationship('post', 'title')
    ->hiddenOn(CommentsRelationManager::class)

```

### #Hiding a shared table column on the relation manager

If you’re sharing a table column from the resource with the relation manager, you may want to hide it on the relation manager. This is especially useful if you want to hide a column for the owner record in the relation manager, since this is not appropriate when the owner record is already listed above the relation manager. To do this, you may use thehiddenOn()method, passing the name of the relation manager:

```php
use App\Filament\Resources\Blog\Posts\PostResource\RelationManagers\CommentsRelationManager;
use Filament\Tables\Columns\TextColumn;

TextColumn::make('post.title')
    ->hiddenOn(CommentsRelationManager::class)

```

### #Hiding a shared table filter on the relation manager

If you’re sharing a table filter from the resource with the relation manager, you may want to hide it on the relation manager. This is especially useful if you want to hide a filter for the owner record in the relation manager, since this is not appropriate when the table is already filtered by the owner record. To do this, you may use thehiddenOn()method, passing the name of the relation manager:

```php
use App\Filament\Resources\Blog\Posts\PostResource\RelationManagers\CommentsRelationManager;
use Filament\Tables\Filters\SelectFilter;

SelectFilter::make('post')
    ->relationship('post', 'title')
    ->hiddenOn(CommentsRelationManager::class)

```

### #Overriding shared configuration on the relation manager

Any configuration that you make inside the resource can be overwritten on the relation manager. For example, if you wanted to disable pagination on the relation manager’s inherited table but not the resource itself:

```php
use App\Filament\Resources\Blog\Posts\PostResource;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return PostResource::table($table)
        ->paginated(false);
}

```

It is probably also useful to provide extra configuration on the relation manager if you wanted to add a header action tocreate,attach, orassociaterecords in the relation manager:

```php
use App\Filament\Resources\Blog\Posts\PostResource;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return PostResource::table($table)
        ->headerActions([
            CreateAction::make(),
            AttachAction::make(),
        ]);
}

```

## #Customizing the relation manager Eloquent query

You can apply your own query constraints ormodel scopesthat affect the entire relation manager. To do this, you can pass a function to themodifyQueryUsing()method of the table, inside which you can customize the query:

```php
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

public function table(Table $table): Table
{
    return $table
        ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true))
        ->columns([
            // ...
        ]);
}

```

## #Customizing the relation manager title

To set the title of the relation manager, you can use the$titleproperty on the relation manager class:

```php
protected static ?string $title = 'Posts';

```

To set the title of the relation manager dynamically, you can override thegetTitle()method on the relation manager class:

```php
use Illuminate\Database\Eloquent\Model;

public static function getTitle(Model $ownerRecord, string $pageClass): string
{
    return __('relation-managers.posts.title');
}

```

The title will be reflected in theheading of the table, as well as the relation manager tab if there is more than one. If you want to customize the table heading independently, you can still use the$table->heading()method:

```php
use Filament\Tables;

public function table(Table $table): Table
{
    return $table
        ->heading('Posts')
        ->columns([
            // ...
        ]);
}

```

## #Customizing the relation manager record title

The relation manager uses the concept of a “record title attribute” to determine which attribute of the related model should be used to identify it. When creating a relation manager, this attribute is passed as the third argument to themake:filament-relation-managercommand:

```php
php artisan make:filament-relation-manager CategoryResource posts title

```

In this example, thetitleattribute of thePostmodel will be used to identify a post in the relation manager.

This is mainly used by the action classes. For instance, when youattachorassociatea record, the titles will be listed in the select field. When youedit,viewordeletea record, the title will be used in the header of the modal.

In some cases, you may want to concatenate multiple attributes together to form a title. You can do this by replacing therecordTitleAttribute()configuration method withrecordTitle(), passing a function that transforms a model into a title:

```php
use App\Models\Post;
use Filament\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->recordTitle(fn (Post $record): string => "{$record->title} ({$record->id})")
        ->columns([
            // ...
        ]);
}

```

If you’re usingrecordTitle(), and you have anassociate actionorattach action, you will also want to specify search columns for those actions:

```php
use Filament\Actions\AssociateAction;
use Filament\Actions\AttachAction;

AssociateAction::make()
    ->recordSelectSearchColumns(['title', 'id']);

AttachAction::make()
    ->recordSelectSearchColumns(['title', 'id'])

```

## #Relation pages

Using aManageRelatedRecordspage is an alternative to using a relation manager, if you want to keep the functionality of managing a relationship separate from editing or viewing the owner record.

This feature is ideal if you are usingresource sub-navigation, as you are easily able to switch between the View or Edit page and the relation page.

To create a relation page, you should use themake:filament-pagecommand:

```php
php artisan make:filament-page ManageCustomerAddresses --resource=CustomerResource --type=ManageRelatedRecords

```

When you run this command, you will be asked a series of questions to customize the page, for example, the name of the relationship and its title attribute.

You must register this new page in your resource’sgetPages()method:

```php
public static function getPages(): array
{
    return [
        'index' => Pages\ListCustomers::route('/'),
        'create' => Pages\CreateCustomer::route('/create'),
        'view' => Pages\ViewCustomer::route('/{record}'),
        'edit' => Pages\EditCustomer::route('/{record}/edit'),
        'addresses' => Pages\ManageCustomerAddresses::route('/{record}/addresses'),
    ];
}

```

NOTE

When using a relation page, you do not need to generate a relation manager withmake:filament-relation-manager, and you do not need to register it in thegetRelations()method of the resource.

Now, you can customize the page in exactly the same way as a relation manager, with the sametable()andform().

### #Adding relation pages to resource sub-navigation

If you’re usingresource sub-navigation, you can register this page as normal ingetRecordSubNavigation()of the resource:

```php
use App\Filament\Resources\Customers\Pages;
use Filament\Resources\Pages\Page;

public static function getRecordSubNavigation(Page $page): array
{
    return $page->generateNavigationItems([
        // ...
        Pages\ManageCustomerAddresses::class,
    ]);
}

```

## #Passing properties to relation managers

When registering a relation manager in a resource, you can use themake()method to pass an array ofLivewire propertiesto it:

```php
use App\Filament\Resources\Blog\Posts\PostResource\RelationManagers\CommentsRelationManager;

public static function getRelations(): array
{
    return [
        CommentsRelationManager::make([
            'status' => 'approved',
        ]),
    ];
}

```

This array of properties gets mapped topublic Livewire propertieson the relation manager class:

```php
use Filament\Resources\RelationManagers\RelationManager;

class CommentsRelationManager extends RelationManager
{
    public string $status;

    // ...
}

```

Now, you can access thestatusin the relation manager class using$this->status.

## #Disabling lazy loading

By default, relation managers are lazy-loaded. This means that they will only be loaded when they are visible on the page.

To disable this behavior, you may override the$isLazyproperty on the relation manager class:

```php
protected static bool $isLazy = false;

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** forms, tables, actions  
**Keywords:** crud, database, eloquent, models, admin panel

*Extracted from Filament v5 Documentation - 2026-01-28*
