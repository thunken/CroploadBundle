ThunkenCropload
===============

Cropload is a simple Upload and Cropping manager for a single file upload on a form.
It gives you all the tools needed to add an upload & crop field to any form in minutes.

# Dependencies
## PHP / Managed by Composer
- OneUploaderBundle : https://github.com/1up-lab/OneupUploaderBundle

## Javascript / Css or Less / You can manage these with Bower
- Jquery: https://jquery.com/
- Jquery UI: http://jqueryui.com/
- Blueimp javascript fileupload: https://blueimp.github.io/jQuery-File-Upload/
- Cropper: https://fengyuanchen.github.io/cropper/
- Bootstrap 3, See how to install and use: http://getbootstrap.com/

# Install bundle
## Installation
Not yet on packagist.  
Modify and add these lines to your composer.json file as follows:
~~~
    [...]
    "require" : {
        [...]
        "thunken/croploadbundle" : "dev-master"
    },
    "repositories" : [{
        "type" : "vcs",
        "url" : "https://github.com/thunken/CroploadBundle.git"
    }],
    [...]
~~~

## Configure bundle

app/config/config.yml, add these parameters (you can change upload_web_dir to your need):
~~~
parameters:
    root_web_dir: "%kernel.root_dir%/../web"
    upload_web_dir: "media/uploads"
    upload_root_dir: "%root_web_dir%/%upload_web_dir%"

oneup_uploader:
  mappings:
    post: # Your mapping name for a post in this example
      frontend: blueimp # For now, only blueimp is handled
      storage:
        directory: "%upload_root_dir%/post" # Your upload folder for a post
      namer: cropload.date_chunk_namer # Upload namer for a post
    category: # Your mapping name for a category in this example
      frontend: blueimp # For now, only blueimp is handled
      storage:
        directory: "%upload_root_dir%/category"
      namer: cropload.date_chunk_namer # Upload namer for a category
~~~

## Create or modify a form to add your Cropload field
~~~
<?php

namespace Acme\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Thunken\CroploadBundle\Form\DimensionType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Title'
                ]
            ])
            ->add('abstract', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Abstract'
                ]
            ])
            // ...
            ->add('illustration', HiddenType::class, [ // Specify your image field here and replace illustration
                'required' => false,
                'attr' => [
                    'class' => 'fake-file-field',
                    'data-ratio' => 1.989212903 // Here you can configure the forced ratio of your cropped image
                ]
            ])
            ->add('dimension', DimensionType::class, [ // This field is needed to submit your 
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'crop-width'
                ]
            ])
        ;
    }

    public function getBlockPrefix() {
        return 'post';
    }
}
~~~

## Handle the file after form submission
### Modify your controller as follow:
~~~
<?php

namespace Acme\BlogBundle\Controller;

// ...

class BlogController extends Controller
{

    use \Thunken\CroploadBundle\Traits\UploadableController;
    
    // ...
    
    public function createPost(Request $request) {
        // ...
        if ($form->isValid($form)) {

            $webPath = $flow->getFormData()->getIllustration();

            $postParameters = $request->request->get('post');
            $this->handleIllustration($webPath, $postParameters['dimension']);
            // ...
        }
        // ...
    }
}
~~~

Your target entity doesn't need any change.


## Add frontend dependencies
### Bower includes example (in a bower.json file)

~~~
{
  "name": "yourprojectname",
  "version": "yourprojectversion",
  "dependencies": {
    "bootstrap": "3.*",
    "jquery": "3.*",
    "jquery-ui": "1.11.*",
    "blueimp-file-upload": "9.14.*",
    "cropper": "2.3.*"
  }
}
~~~

### Then, add these lines in each twig form template containing a Cropload field
~~~
{% block extraJs %}
    {% javascripts

    '<your_front_vendor_folder>/jquery-ui/ui/widget.js'
    '<your_front_vendor_folder>/blueimp-file-upload/js/jquery.iframe-transport.js'
    '<your_front_vendor_folder>/blueimp-file-upload/js/jquery.fileupload.js'
    '<your_front_vendor_folder>/cropper/dist/cropper.js'

    '@Thunken/CroploadBundle/Resources/js/image-upload-manager/image-upload-manager.js'

    output="<your_public_assets_folder>/cropload.js"
    filter=''
    %}

    <script type="text/javascript" src="{{ asset_url }}"></script>

    {% endjavascripts %}
{% endblock %}
~~~

### Include the stylesheets as well (on each template using a Cropload field)
CropLoad specific style, this file is only containing style for the "modal" displaying the crop preview. The bundle provides a less example.
~~~
Example file path: CroploadBundle/Resources/less/cropload.less
~~~

Cropper library specific style:
~~~
<your_front_vendor_folder>/cropper/dist/cropper.css
~~~

# Notes
## Namer classes
This bundle provides a default file namer class, designed to avoid reaching maximum file numbers in a single folder.  
You can extend it or write your own by defining a service 
~~~
services:
    cropload.date_chunk_namer:
        class: YourCompany\YourBundle\Naming\YourNamer
~~~

# Enhancing this bundle:
- This bundle has been first developed for a specific project, we could make it more modular.
- To avoid coding what already exists, it strongly relies on amazing third party libs, we could make it more configurable.
- We could make the forced ratio optional.
- We could handle multiple files upload.
- We could use events to handle file upload.
- Make the documentation clearer.
- ??

It has been quickly made for a inside project, needs some improvements, we wanted to share the result.

For these reasons, feel free to contact us or submit if you have any suggestions, enhancements, questions or if you need help.
Any feedback is appreciated as well.
