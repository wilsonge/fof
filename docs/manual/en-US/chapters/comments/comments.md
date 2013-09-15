3 The Comments View
==========================================

In this section we will go through and create a comments view. The plural view will display the comments for a given restaurant, whilst the single view will add the ability for frontend users to add their own comments

3.1 Allowing comments to be shown in the Restaurant View
------------------------------------------
To add comments into the restaurant view we now need to take advantage of the RAD layer's HMVC functionality. However this requires us to have a php file. So we are going to show our form.item.xml file in a php file.

To do this in the site/view/restaurant/tmpl folder we are going to create a form.php file. This will now be used instead of the form.item.xml file we had previously. So to show the contents of the xml file we must retrieve the XML file and render its contents in the PHP file.

```php
<?php
/**
 * @copyright (C) 2013 JoomJunk. All rights reserved.
 * @package    Restaurant Reviews
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 **/

// Get the restaurant view from the XML file
$viewTemplate = $this->getRenderedForm();
echo $viewTemplate;
```

So in form.php we have put in the code above. If you were to install the component then you would see now change between what we had before and now. The next step however is to create the comments and comment view so we can include them into the restaurant view.

3.2 Adding the comments view
------------------------------------------