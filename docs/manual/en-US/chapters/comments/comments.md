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

So in form.php we have put in the code above. If you were to install the component then you would see no change between what we had before and now. The next step however is to create the comments and comment view so we can include them into the restaurant view.

3.2 Adding the Backend
------------------------------------------
The next step is to create the four views for the comments (comments (list) frontend, comment (form) frontend, comments (list) backend, comment (form) backend). The backend forms were made in a similar method to the backend 'restaurant' view forms and so aren't covered in any more detail in this tutorial - we created a simple view for item and list with a select box for restaurants (using the standard Joomla SQL form field), description of restaurant and rating.

3.3 Adding the Frontend Comments View
------------------------------------------
This view is still relatively similar to the previous restaurants view however we will look to include this view inside a restaurant and only show the ratings of a given restaurant. To achieve this we will need to edit the query that FOF executes so we will only search for the form field. This will involve having to create a model for this view.

In a Joomla component you would generally override the buildQuery method to do this. However in FOF there are two methods in which to manipulate the query - ```onBeforeBuildQuery(&$model, &$query)``` and ```public function onAfterBuildQuery(&$model, &$query)``` which give both the model and the query. So we will simply create a model with the name in the style of ```ComponentModelView``` so in our case ```ReviewsModelComments``` and in this file we place:

```php
class ReviewsModelComments extends FOFModel
{
	/**
	 * The ID of the restaurant that the comments should be loaded for
	 *
	 * @var    integer
	 */
	protected $restaurant = null;

	/**
	 * Public constructor
	 *
	 * @param   array  $config  The configuration variables
	 */
    public function __construct($config = array())
	{
        parent::__construct($config);

		// Here we 'magically' find the restaurant id from the config
       	if ($config['input']->get('restaurantid', null))
		{
			$this->restaurant = $config['input']->get('restaurantid');
		}
    }

	/**
	 * This event allows us to search for the comments from a specific restaurant
	 *
	 * @param   FOFModel        &$model  The model which calls this event
	 * @param   JDatabaseQuery  &$query  The query being built
	 *
	 * @return  void
	 */
	public function onAfterBuildQuery(&$model, &$query)
	{
		if ($this->restaurant)
		{
			$db = $model->getDbo();
			// Now we inject the check for the restaurant into the SQL Query
			$query->where($db->quoteName('restaurant') . ' = ' . $this->restaurant);
		}
	}
}
```

Note currently we are just magically expecting the $config variable in the constructor to hold our restaurant id that we want to display the comments for. This will need to be injected in when we call the dispatcher from the restaurant view in section 3.5.

Other than this query edit we just create a similar XML file to previous views - in this case we choose to turn off the headers and filters as we just wish to display a list of comments. See the XML file for more details.

3.4 Adding the Frontend Comment View
------------------------------------------
However the comment view we want to change the layout for so it will fit in with the main restaurant view. So we create our form:

```php
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
	<input type="hidden" name="option" value="com_reviews" />
	<input type="hidden" name="view" value="comment" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="reviews_comment_id" value="0" />
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />
	<div class="control-group">
		<div class="control-label">
			<label for="username_field" class="control-label"><?php echo JText::_('COM_REVIEW_COMMENTS_FIELD_USER_NAME'); ?></label>
		</div>
		<div class="controls">
			<span id="username_field"><?php echo JFactory::getUser()->username; ?></span>
			<input type="hidden" name="username" value="<?php echo JFactory::getUser()->id; ?>" />
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<label for="calendar_field" class="control-label"><?php echo JText::_('COM_REVIEW_COMMENTS_FIELD_DATE'); ?></label>
		</div>
		<div class="controls">
			<?php echo JHtml::_('calendar', JFactory::getDate()->toSql(), 'date', 'calendar_field', '%Y-%m-%d %H:%i:%s', array('readonly'=>true)); ?>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<label for="comment_field" class="control-label"><?php echo JText::_('COM_REVIEW_COMMENTS_FIELD_COMMENT'); ?></label>
		</div>
		<div class="controls">
			<textarea id="comment_field" name="comment" class="comment"></textarea>
		</div>
	</div>
	<div class="control-group">
		<div class="control-label">
			<label for="restaurant_field" class="control-label"><?php echo JText::_('COM_REVIEW_COMMENTS_FIELD_RESTAURANT'); ?></label>
		</div>
		<div class="controls">
			<?php
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select(array($db->quoteName('reviews_restaurant_id', 'value'), $db->quoteName('name', 'text')))
					->from($db->quoteName('#__reviews_restaurants'));
				$db->setQuery($query);

				$items = $db->loadObjectList();

				// Let's assume their are restaurants because otherwise noone should be accessing this view!
				$options = array();

				for ($i = 0, $n = count($items); $i < $n; $i++)
				{
					$options[] = JHtml::_('select.option', $items[$i]->value, $items[$i]->text);
				}

				echo JHtml::_('select.genericlist', $options, 'restaurant');
			?>
		</div>
	</div>
	<div class="form-actions">
		<button id="commentnow" class="btn btn-small btn-primary" type="submit">
			<?php echo JText::_('COM_REVIEWS_COMMENT_SUBMIT')?>
		</button>
	</div>
</form>
```

As you can see here we have inserted all the code to get the inputs. This should look familiar if you have ever coded a Joomla component before

```php
	<input type="hidden" name="option" value="com_reviews" />
	<input type="hidden" name="view" value="comment" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="reviews_comment_id" value="0" />
```

These inputs are for specify the component (called option), the view that we are in (comment) and you leave the task blank as this will be filled in automatically by FOF (rather than the conventional model save method in Joomla). Finally as we want this to always be a new item we set the ```reviews_comment_id``` to be 0

The visible form fields are then written. Note here we are NOT using JForm as there is no XML form.

3.5 Putting everything together
------------------------------------------

So now we need to put the comments and comment view showing all the comments into the restaurant view. We do this as FOF has HMVC. So we now add the following to the restaurant.php file

```php
$inputvars = array(
	'restaurantid' => $this->config['input']->get('id'),
);
$input = new FOFInput($inputvars);

FOFDispatcher::getTmpInstance('com_reviews', 'comments', array('input' => $input))->dispatch();
FOFDispatcher::getTmpInstance('com_reviews', 'comment', array())->dispatch();
```

Note back in section 3.3 we needed to inject the restaurant that we wanted to see. And you can see this has taken place in the ```$inputvars``` variable, which is then injected into the Dispatcher. We can then retrieve this anywhere in the Controller, Model or View. Indeed note that the id of the restaurant we are viewing comes out the ```$config``` variable set for the restaurant view!
