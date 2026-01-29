# File upload

**URL:** https://filamentphp.com/docs/5.x/forms/file-upload  
**Section:** forms  
**Page:** file-upload  
**Priority:** high  
**AI Context:** Form builder with 20+ field types. Essential for data input.

---

## #Introduction

The file upload field is based onFilepond.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')

```

TIP

Filament also supportsspatie/laravel-medialibrary. See ourplugin documentationfor more information.

## #Configuring the storage disk and directory

By default, files will be uploaded to the storage disk defined in theconfiguration file. You can also set theFILESYSTEM_DISKenvironment variable to change this.

TIP

To correctly preview images and other files, FilePond requires files to be served from the same domain as the app, or the appropriate CORS headers need to be present. Ensure that theAPP_URLenvironment variable is correct, or modify thefilesystemdriver to set the correct URL. If you’re hosting files on a separate domain like S3, ensure that CORS headers are set up.

To change the disk and directory for a specific field, and the visibility of files, use thedisk(),directory()andvisibility()methods. By default, files are uploaded withprivatevisibility to your storage disk, unless the disk is set topublic:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->disk('s3')
    ->directory('form-attachments')
    ->visibility('public')

```

NOTE

It is the responsibility of the developer to delete these files from the disk if they are removed, as Filament is unaware if they are depended on elsewhere. One way to do this automatically is observing amodel event.

## #Uploading multiple files

You may also upload multiple files. This stores URLs in JSON:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()

```

Optionally, you may pass a boolean value to control if multiple files can be uploaded at once:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple(FeatureFlag::active())

```

If you’re saving the file URLs using Eloquent, you should be sure to add anarraycastto the model property:

```php
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    { 
        return [
            'attachments' => 'array',
        ];
    }

    // ...
}

```

### #Controlling the maximum parallel uploads

You can control the maximum number of parallel uploads using themaxParallelUploads()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->maxParallelUploads(1)

```

This will limit the number of parallel uploads to1. If unset, we’ll use thedefault FilePond valuewhich is2.

## #Controlling file names

By default, a random file name will be generated for newly-uploaded files. This is to ensure that there are never any conflicts with existing files.

### #Security implications of controlling file names

Before using thepreserveFilenames()orgetUploadedFileNameForStorageUsing()methods, please be aware of the security implications. If you allow users to upload files with their own file names, there are ways that they can exploit this to upload malicious files.This applies even if you use theacceptedFileTypes()methodto restrict the types of files that can be uploaded, since it uses Laravel’smimetypesrule which does not validate the extension of the file, only its mime type, which could be manipulated.

This is specifically an issue with thegetClientOriginalName()method on theTemporaryUploadedFileobject, which thepreserveFilenames()method uses. By default, Livewire generates a random file name for each file uploaded, and uses the mime type of the file to determine the file extension.

Using these methodswith thelocalorpublicfilesystem diskswill make your app vulnerable to remote code execution if the attacker uploads a PHP file with a deceptive mime type.Using an S3 disk protects you from this specific attack vector, as S3 will not execute PHP files in the same way that your server might when serving files from local storage.

If you are using thelocalorpublicdisk, you should consider using thestoreFileNamesIn()methodto store the original file names in a separate column in your database, and keep the randomly generated file names in the file system. This way, you can still display the original file names to users, while keeping the file system secure.

On top of this security issue, you should also be aware that allowing users to upload files with their own file names can lead to conflicts with existing files, and can make it difficult to manage your storage. Users could upload files with the same name and overwrite the other’s content if you do not scope them to a specific directory, so these features should in all cases only be accessible to trusted users.

### #Preserving original file names

NOTE

Before using this feature, please ensure that you have read thesecurity implications.

To preserve the original filenames of the uploaded files, use thepreserveFilenames()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->preserveFilenames()

```

Optionally, you may pass a boolean value to control if the original file names should be preserved:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->preserveFilenames(FeatureFlag::active())

```

### #Generating custom file names

NOTE

Before using this feature, please ensure that you have read thesecurity implications.

You may completely customize how file names are generated using thegetUploadedFileNameForStorageUsing()method, and returning a string from the closure based on the$filethat was uploaded:

```php
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

FileUpload::make('attachment')
    ->getUploadedFileNameForStorageUsing(
        fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
            ->prepend('custom-prefix-'),
    )

```

### #Storing original file names independently

You can keep the randomly generated file names, while still storing the original file name, using thestoreFileNamesIn()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->storeFileNamesIn('attachment_file_names')

```

attachment_file_nameswill now store the original file names of your uploaded files, so you can save them to the database when the form is submitted. If you’re uploadingmultiple()files, make sure that you add anarraycastto this Eloquent model property too.

## #Avatar mode

You can enable avatar mode for your file upload field using theavatar()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('avatar')
    ->avatar()

```

This will only allow images to be uploaded, and when they are, it will display them in a compact circle layout that is perfect for avatars.

This feature pairs well with thecircle cropper.

## #Image editor

You can enable an image editor for your file upload field using theimageEditor()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->imageEditor()

```

You can open the editor once you upload an image by clicking the pencil icon. You can also open the editor by clicking the pencil icon on an existing image, which will remove and re-upload it on save.

Optionally, you may pass a boolean value to control if the image editor is enabled:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->imageEditor(FeatureFlag::active())

```

### #Allowing users to crop images to aspect ratios

You can allow users to crop images to a set of specific aspect ratios using theimageEditorAspectRatioOptions()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->imageEditor()
    ->imageEditorAspectRatioOptions([
        '16:9',
        '4:3',
        '1:1',
    ])

```

You can also allow users to choose no aspect ratio, “free cropping”, by passingnullas an option:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->imageEditor()
    ->imageEditorAspectRatioOptions([
        null,
        '16:9',
        '4:3',
        '1:1',
    ])

```

### #Setting the image editor’s mode

You can change the mode of the image editor using theimageEditorMode()method, which accepts either1,2or3. These options are explained in theCropper.js documentation:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->imageEditor()
    ->imageEditorMode(2)

```

### #Customizing the image editor’s empty fill color

By default, the image editor will make the empty space around the image transparent. You can customize this using theimageEditorEmptyFillColor()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->imageEditor()
    ->imageEditorEmptyFillColor('#000000')

```

### #Setting the image editor’s viewport size

You can change the size of the image editor’s viewport using theimageEditorViewportWidth()andimageEditorViewportHeight()methods, which generate an aspect ratio to use across device sizes:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->imageEditor()
    ->imageEditorViewportWidth('1920')
    ->imageEditorViewportHeight('1080')

```

### #Allowing users to crop images as a circle

You can allow users to crop images as a circle using thecircleCropper()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->avatar()
    ->imageEditor()
    ->circleCropper()

```

This is perfectly accompanied by theavatar()method, which renders the images in a compact circle layout.

Optionally, you may pass a boolean value to control if the circle cropper is enabled:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->avatar()
    ->imageEditor()
    ->circleCropper(FeatureFlag::active())

```

### #Enforcing a specific aspect ratio

If you need to ensure all uploaded images conform to a specific aspect ratio, you can combine theimageAspectRatio()validation methodwithautomaticallyOpenImageEditorForAspectRatio(). This will automatically open a simplified image editor when a user uploads an image that doesn’t match the required aspect ratio, allowing them to crop the image before it is saved:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('banner')
    ->image()
    ->imageAspectRatio('16:9')
    ->automaticallyOpenImageEditorForAspectRatio()

```

The editor that appears when cropping is required only shows the crop area and save/cancel buttons - it does not include the full editing controls (rotation, position inputs, etc.) that appear when usingimageEditor(). This provides a streamlined experience focused on getting the correct aspect ratio.

If you want users to have access to the full image editor controls, you can enable both:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('banner')
    ->image()
    ->imageEditor()
    ->imageAspectRatio('16:9')
    ->automaticallyOpenImageEditorForAspectRatio()

```

With both enabled, the image editor will still open automatically when the aspect ratio doesn’t match, but users will also see an edit button on each uploaded image and have access to all editing controls.

Optionally, you may pass a boolean value to control if the aspect ratio editor is enabled:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('banner')
    ->image()
    ->imageAspectRatio('16:9')
    ->automaticallyOpenImageEditorForAspectRatio(FeatureFlag::active())

```

NOTE

TheautomaticallyOpenImageEditorForAspectRatio()method can only be used with a single aspect ratio. If you need to allow multiple aspect ratios, useimageAspectRatio()for validation only, and consider usingimageEditor()withimageEditorAspectRatioOptions()to let users choose their preferred ratio.

NOTE

TheautomaticallyOpenImageEditorForAspectRatio()method is not available whenmultiple()is enabled.

### #Cropping and resizing images without the editor

Filepond allows you to crop and resize images before they are uploaded, without the need for a separate editor. You can customize this behavior using theautomaticallyResizeImagesToHeight()andautomaticallyResizeImagesToWidth()methods.automaticallyResizeImagesMode()should be set for these methods to have an effect - eitherforce,cover, orcontain.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->automaticallyCropImagesToAspectRatio('16:9')
    ->automaticallyResizeImagesMode('cover')
    ->automaticallyResizeImagesToWidth('1920')
    ->automaticallyResizeImagesToHeight('1080')

```

To enable automatic cropping with a specific aspect ratio, use theautomaticallyCropImagesToAspectRatio()method. If you also haveimageAspectRatio()set for validation and want the automatic crop to use the same ratio, you can callautomaticallyCropImagesToAspectRatio()without any arguments:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()
    ->imageAspectRatio('16:9')
    ->automaticallyCropImagesToAspectRatio()
    ->automaticallyResizeImagesMode('cover')
    ->automaticallyResizeImagesToWidth('1920')
    ->automaticallyResizeImagesToHeight('1080')

```

NOTE

When using automatic image cropping, the crop is applied automatically without user interaction. The user cannot choose which part of the image to keep. If you want users to control how their images are cropped, useautomaticallyOpenImageEditorForAspectRatio()instead.

## #Altering the appearance of the file upload area

You may also alter the general appearance of the Filepond component. Available options for these methods are available on theFilepond website.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->imagePreviewHeight('250')
    ->loadingIndicatorPosition('left')
    ->panelAspectRatio('2:1')
    ->panelLayout('integrated')
    ->removeUploadedFileButtonPosition('right')
    ->uploadButtonPosition('left')
    ->uploadProgressIndicatorPosition('left')

```

### #Displaying files in a grid

You can use theFilepondgridlayoutby setting thepanelLayout():

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->panelLayout('grid')

```

## #Reordering files

You can also allow users to re-order uploaded files using thereorderable()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->reorderable()

```

When using this method, FilePond may add newly-uploaded files to the beginning of the list, instead of the end. To fix this, use theappendFiles()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->reorderable()
    ->appendFiles()

```

Optionally, thereorderable()andappendFiles()methods accept a boolean value to control if the files can be reordered and if new files should be appended to the end of the list:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->reorderable(FeatureFlag::active())
    ->appendFiles(FeatureFlag::active())

```

## #Opening files in a new tab

You can add a button to open each file in a new tab with theopenable()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->openable()

```

Optionally, you may pass a boolean value to control if the files can be opened in a new tab:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->openable(FeatureFlag::active())

```

## #Downloading files

If you wish to add a download button to each file instead, you can use thedownloadable()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->downloadable()

```

Optionally, you may pass a boolean value to control if the files can be downloaded:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->downloadable(FeatureFlag::active())

```

## #Previewing files

By default, some file types can be previewed in FilePond. If you wish to disable the preview for all files, you can use thepreviewable(false)method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->previewable(false)

```

## #Moving files instead of copying when the form is submitted

By default, files are initially uploaded to Livewire’s temporary storage directory, and then copied to the destination directory when the form is submitted. If you wish to move the files instead, providing that temporary uploads are stored on the same disk as permanent files, you can use themoveFiles()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->moveFiles()

```

Optionally, you may pass a boolean value to control if the files should be moved:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->moveFiles(FeatureFlag::active())

```

## #Preventing files from being stored permanently

If you wish to prevent files from being stored permanently when the form is submitted, you can use thestoreFiles(false)method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->storeFiles(false)

```

When the form is submitted, a temporary file upload object will be returned instead of a permanently stored file path. This is perfect for temporary files like imported CSVs.

NOTE

Images, video and audio files will not show the stored file name in the form’s preview, unless you usepreviewable(false). This is due to a limitation with the FilePond preview plugin.

## #Orienting images from their EXIF data

By default, FilePond will automatically orient images based on their EXIF data. If you wish to disable this behavior, you can use theorientImagesFromExif(false)method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->orientImagesFromExif(false)

```

## #Hiding the remove file button

It is also possible to hide the remove uploaded file button by usingdeletable(false):

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->deletable(false)

```

## #Preventing pasting files

You can disable the ability to paste files via the clipboard using thepasteable(false)method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->pasteable(false)

```

## #Preventing file information fetching

While the form is loaded, it will automatically detect whether the files exist, what size they are, and what type of files they are. This is all done on the backend. When using remote storage with many files, this can be time-consuming. You can use thefetchFileInformation(false)method to disable this feature:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->fetchFileInformation(false)

```

## #Customizing the uploading message

You may customize the uploading message that is displayed in the form’s submit button using theuploadingMessage()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->uploadingMessage('Uploading attachment...')

```

## #File upload validation

As well as all rules listed on thevalidationpage, there are additional rules that are specific to file uploads.

Since Filament is powered by Livewire and uses its file upload system, you will want to refer to the default Livewire file upload validation rules in theconfig/livewire.phpfile as well. This also controls the 12MB file size maximum.

NOTE

Many of these validation rules only apply to newly uploaded files. Existing files that were uploaded before the validation rules were added will not be re-validated.

### #File type validation

You may restrict the types of files that may be uploaded using theacceptedFileTypes()method, and passing an array of MIME types.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('document')
    ->acceptedFileTypes(['application/pdf'])

```

You may also use theimage()method as shorthand to allow all image MIME types.

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('image')
    ->image()

```

#### #Custom MIME type mapping

Some file formats may not be recognized correctly by the browser when uploading files. Filament allows you to manually define MIME types for specific file extensions using themimeTypeMap()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('designs')
    ->acceptedFileTypes([
        'x-world/x-3dmf',
        'application/vnd.sketchup.skp',
    ])
    ->mimeTypeMap([
        '3dm' => 'x-world/x-3dmf',
        'skp' => 'application/vnd.sketchup.skp',
    ]);

```

### #File size validation

You may also restrict the size of uploaded files in kilobytes:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachment')
    ->minSize(512)
    ->maxSize(1024)

```

#### #Uploading large files

If you experience issues when uploading large files, such as HTTP requests failing with a response status of 422 in the browser’s console, you may need to tweak your configuration.

In thephp.inifile for your server, increasing the maximum file size may fix the issue:

```php
post_max_size = 120M
upload_max_filesize = 120M

```

Livewire also validates file size before uploading. To publish the Livewire config file, run:

```php
php artisan livewire:publish --config

```

Themax upload size can be adjusted in theruleskey oftemporary_file_upload. In this instance, KB are used in the rule, and 120MB is 122880KB:

```php
'temporary_file_upload' => [
    // ...
    'rules' => ['required', 'file', 'max:122880'],
    // ...
],

```

### #Image dimension validation

You may restrict the dimensions of uploaded images using therule()method with Laravel’sRule::dimensions():

```php
use Filament\Forms\Components\FileUpload;
use Illuminate\Validation\Rule;

FileUpload::make('photo')
    ->image()
    ->rule(Rule::dimensions()->minWidth(800)->minHeight(600))

```

```php
use Filament\Forms\Components\FileUpload;
use Illuminate\Validation\Rule;

FileUpload::make('photo')
    ->image()
    ->rule(Rule::dimensions()->maxWidth(1920)->maxHeight(1080))

```

You can combine minimum and maximum constraints:

```php
use Filament\Forms\Components\FileUpload;
use Illuminate\Validation\Rule;

FileUpload::make('photo')
    ->image()
    ->rule(
        Rule::dimensions()
            ->minWidth(800)
            ->minHeight(600)
            ->maxWidth(1920)
            ->maxHeight(1080)
    )

```

NOTE

These dimension validation rules only apply to newly uploaded files. Existing files that were uploaded before the validation rules were added will not be re-validated.

### #Image aspect ratio validation

You may restrict the aspect ratio of uploaded images using theimageAspectRatio()method:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('banner')
    ->image()
    ->imageAspectRatio('16:9')

```

You can allow multiple aspect ratios by passing an array:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('banner')
    ->image()
    ->imageAspectRatio(['16:9', '4:3', '1:1'])

```

You can also specify a range of acceptable aspect ratios usingRule::dimensions():

```php
use Filament\Forms\Components\FileUpload;
use Illuminate\Validation\Rule;

FileUpload::make('banner')
    ->image()
    ->rule(Rule::dimensions()->minRatio(4 / 3)->maxRatio(16 / 9))

```

NOTE

These aspect ratio validation rules only apply to newly uploaded files. Existing files that were uploaded before the validation rules were added will not be re-validated.

TIP

If you want to help users meet the aspect ratio requirement rather than just rejecting invalid uploads, consider usingautomaticallyOpenImageEditorForAspectRatio()alongsideimageAspectRatio(). This will automatically open a crop editor when an uploaded image doesn’t match the required ratio. Alternatively, you can useautomaticallyCropImagesToAspectRatio()to automatically crop images to the required ratio without user interaction.

### #Number of files validation

You may customize the number of files that may be uploaded, using theminFiles()andmaxFiles()methods:

```php
use Filament\Forms\Components\FileUpload;

FileUpload::make('attachments')
    ->multiple()
    ->minFiles(2)
    ->maxFiles(5)

```

Still need help? Join ourDiscord communityor open aGitHub discussion

---

**Related Topics:** schemas, resources, actions  
**Keywords:** input, fields, validation, forms, data entry

*Extracted from Filament v5 Documentation - 2026-01-28*
