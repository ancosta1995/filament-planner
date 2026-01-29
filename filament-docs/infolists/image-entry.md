# Image entry

**URL:** https://filamentphp.com/docs/5.x/infolists/image-entry  
**Section:** infolists  
**Page:** image-entry  
**Priority:** medium  
**AI Context:** Display read-only data in structured layouts.

---

## #Introduction

Infolists can render images, based on the path in the state of the entry:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('header_image')

```

In this case, theheader_imagestate could containposts/header-images/4281246003439.jpg, which is relative to the root directory of the storage disk. The storage disk is defined in theconfiguration file,localby default. You can also set theFILESYSTEM_DISKenvironment variable to change this.

Alternatively, the state could contain an absolute URL to an image, such ashttps://example.com/images/header.jpg.

## #Managing the image disk

The default storage disk is defined in theconfiguration file,localby default. You can also set theFILESYSTEM_DISKenvironment variable to change this. If you want to deviate from the default disk, you may pass a custom disk name to thedisk()method:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('header_image')
    ->disk('s3')

```

## #Public images

By default, Filament will generate temporary URLs to images in the filesystem, unless thediskis set topublic. If your images are stored in a public disk, you can set thevisibility()topublic:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('header_image')
    ->visibility('public')

```

## #Customizing the size

You may customize the image size by passing aimageWidth()andimageHeight(), or both withimageSize():

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('header_image')
    ->imageWidth(200)

ImageEntry::make('header_image')
    ->imageHeight(50)

ImageEntry::make('author.avatar')
    ->imageSize(40)

```

### #Square images

You may display the image using a 1:1 aspect ratio:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('author.avatar')
    ->imageHeight(40)
    ->square()

```

Optionally, you may pass a boolean value to control if the image should be square or not:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('author.avatar')
    ->imageHeight(40)
    ->square(FeatureFlag::active())

```

## #Circular images

You may make the image fully rounded, which is useful for rendering avatars:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('author.avatar')
    ->imageHeight(40)
    ->circular()

```

Optionally, you may pass a boolean value to control if the image should be circular or not:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('author.avatar')
    ->imageHeight(40)
    ->circular(FeatureFlag::active())

```

## #Adding a default image URL

You can display a placeholder image if one doesn’t exist yet, by passing a URL to thedefaultImageUrl()method:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('header_image')
    ->defaultImageUrl(url('storage/posts/header-images/default.jpg'))

```

## #Stacking images

You may display multiple images as a stack of overlapping images by usingstacked():

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('colleagues.avatar')
    ->imageHeight(40)
    ->circular()
    ->stacked()

```

Optionally, you may pass a boolean value to control if the images should be stacked or not:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('colleagues.avatar')
    ->imageHeight(40)
    ->circular()
    ->stacked(FeatureFlag::active())

```

### #Customizing the stacked ring width

The default ring width is3, but you may customize it to be from0to8:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('colleagues.avatar')
    ->imageHeight(40)
    ->circular()
    ->stacked()
    ->ring(5)

```

### #Customizing the stacked overlap

The default overlap is4, but you may customize it to be from0to8:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('colleagues.avatar')
    ->imageHeight(40)
    ->circular()
    ->stacked()
    ->overlap(2)

```

## #Setting a limit

You may limit the maximum number of images you want to display by passinglimit():

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('colleagues.avatar')
    ->imageHeight(40)
    ->circular()
    ->stacked()
    ->limit(3)

```

### #Showing the remaining images count

When you set a limit you may also display the count of remaining images by passinglimitedRemainingText().

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('colleagues.avatar')
    ->imageHeight(40)
    ->circular()
    ->stacked()
    ->limit(3)
    ->limitedRemainingText()

```

Optionally, you may pass a boolean value to control if the remaining text should be displayed or not:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('colleagues.avatar')
    ->imageHeight(40)
    ->circular()
    ->stacked()
    ->limit(3)
    ->limitedRemainingText(FeatureFlag::active())

```

#### #Customizing the limited remaining text size

By default, the size of the remaining text isTextSize::Small. You can customize this to beTextSize::ExtraSmall,TextSize::MediumorTextSize::Largeusing thesizeparameter:

```php
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\TextSize;

ImageEntry::make('colleagues.avatar')
    ->imageHeight(40)
    ->circular()
    ->stacked()
    ->limit(3)
    ->limitedRemainingText(size: TextSize::Large)

```

## #Prevent file existence checks

When the schema is loaded, it will automatically detect whether the images exist to prevent errors for missing files. This is all done on the backend. When using remote storage with many images, this can be time-consuming. You can use thecheckFileExistence(false)method to disable this feature:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('attachment')
    ->checkFileExistence(false)

```

## #Adding extra HTML attributes to the image

You can pass extra HTML attributes to the<img>element via theextraImgAttributes()method. The attributes should be represented by an array, where the key is the attribute name and the value is the attribute value:

```php
use Filament\Infolists\Components\ImageEntry;

ImageEntry::make('logo')
    ->extraImgAttributes([
        'alt' => 'Logo',
        'loading' => 'lazy',
    ])

```

By default, callingextraImgAttributes()multiple times will overwrite the previous attributes. If you wish to merge the attributes instead, you can passmerge: trueto the method.

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources  
**Keywords:** read-only, display, view, presentation

*Extracted from Filament v5 Documentation - 2026-01-28*
