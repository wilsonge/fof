2.2 Frontend Views
==========================================
The time has now come for the users to see our restaurants. We're going to create two views in the frontend. One will display a list of restaurants with just the restaurant's name and the city of the restaurant. The second will display all the information on an individual restaurant.

In this case all we need are 3 files. A reviews.php to set the dispatcher and check for FOF, and and four XML files - one file for each view and the required Joomla metadata file for the information to display in the Menu Manager.

2.2.1 reviews.php
------------------------------------------
As before this file checks for FOF and then runs the dispatcher. As FOF implements a DRY (Don't Repeat Yourself) code principle the dispatcher is taken from the backend.

So in this file we're going to put in:

```php
<?php
/**
 * @copyright (C) 2013 JoomJunk. All rights reserved.
 * @package    Restaurant Reviews
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 **/
// no direct access
defined('_JEXEC') or die();

// Load FOF
include_once JPATH_LIBRARIES.'/fof/include.php';
if(!defined('FOF_INCLUDED')) {
	JError::raiseError ('500', JText::_('COM_FOF_NOT_INSTALLED'));
	
	return;
}

FOFDispatcher::getTmpInstance('com_reviews')->dispatch();
```

N.B. Also on a DRY principle backend language strings are also loaded in the front-end (so unlike current Joomla components you don't need to retype all your strings into a front-end language file).

2.2.1 Restaurants View
------------------------------------------
So as mentioned above for this view we need two files - the metadata file and the view xml file itself. In this case the metadata file is the same as that used by current Joomla components:

```xml
<?xml version="1.0" encoding="utf-8"?>
<metadata>
	<!-- View definition -->
	<view>
		<!-- Layout options -->
		<options>
			<!-- Default layout's name -->
			<default name="COM_REVIEWS_VIEW_ITEMS_TITLE" />
		</options>
	</view>
</metadata>
```

We then need to create the view itself in "views/restaurants/tmpl/restaurants.xml". Here we are showing a name field (which is searchable) 

```xml
<?xml version="1.0" encoding="utf-8"?>
<!--
	@copyright (C) 2013 JoomJunk. All rights reserved.
	@package    Restaurant Reviews
	@license    http://www.gnu.org/licenses/gpl-3.0.html

	Restaurants view form file
-->
<form
	type="browse"
	show_header="1"
	show_filters="1"
	show_pagination="1"
	norows_placeholder="COM_RESTAURANTS_COMMON_NORECORDS"
>
	<headerset>
		<header
			name="name"
			type="fieldsearchable"
			sortable="false"
			buttons="false"
		/>
		<header
			name="city"
			type="field"
			sortable="true"
			tdwidth="20%"
		/>
	</headerset>

	<fieldset name="items">
		<field name="name" type="text"
			show_link="true"
			url="index.php?option=com_reviews&amp;view=restaurant&amp;id=[ITEM:ID]"
			empty_replacement="(no title)"
		 />

		<field name="city" type="field" />
	</fieldset>
</form>
```

2.2.1 Restaurant View
------------------------------------------

We'll now add the final front-end view into our component. This will show all the details for a single restaurant. We will split it into two coloumns with two field sets

```xml
<?xml version="1.0" encoding="utf-8"?>
<!--
	@copyright (C) 2013 JoomJunk. All rights reserved.
	@package    Restaurant Reviews
	@license    http://www.gnu.org/licenses/gpl-3.0.html

	Restaurants view form file
-->
<form
	type="read"
>
	<fieldset
		name="item_details"
		class="form-horizontal span8"
	>
		<field
			name="name"
			type="text"
			label="COM_REVIEW_RESTAURANTS_FIELD_NAME"
			size="50"
		 />

		<field
			name="city"
			type="text"
			label="COM_REVIEWS_FIELD_CITY"
			size="20"
		 />
		<field
			name="description"
			type="editor"
			label="COM_REVIEWS_RATING_DESCRIPTION"
		/>
	</fieldset>
	<fieldset
		name="item_review"
		class="form-horizontal span4"
	>
		<field
			name="mainrating"
			type="rating"
			label="COM_REVIEWS_RATING_MAIN"
			size="50"
		 />
		<field
			name="staffrating"
			type="rating"
			label="COM_REVIEWS_RATING_STAFF"
			size="20"
		 />
		<field
			name="foodrating"
			type="rating"
			label="COM_REVIEWS_RATING_FOOD"
			size="50"
		 />
		<field
			name="servicerating"
			type="rating"
			label="COM_REVIEWS_RATING_SERVICE"
			size="20"
		 />
		<field
			name="atmosphererating"
			type="rating"
			label="COM_REVIEWS_RATING_ATMOSPHERE"
			class="inputbox"
			filter="intval"
		/>
		<field
			name="pricerating"
			type="rating"
			label="COM_REVIEWS_RATING_PRICE"
			class="inputbox"
			filter="intval"
		/>
	</fieldset>
	<fieldset
		name="clearfix"
		class="clearfix"
	/>
</form>
```

Finally we'll create our metadata view so we can reference a specific restaurant in the backend. Note the extra code here that allows us to select any restaurant that has been created in the backend.

```xml
<?xml version="1.0" encoding="utf-8"?>
<!--
	@copyright (C) 2013 JoomJunk. All rights reserved.
	@package    Restaurant Reviews
	@license    http://www.gnu.org/licenses/gpl-3.0.html

	Restaurants view form file
-->
<metadata>
	<!-- View definition -->
	<view>
		<!-- Layout options -->
		<options>
			<!-- Default layout's name -->
			<default name="COM_REVIEWS_VIEW_ITEM_TITLE" />
		</options>
	</view>
	<fields name="request">
		<fieldset name="request">
				<field
						name="id"
						type="sql"
						query="SELECT reviews_restaurant_id, name FROM #__reviews_restaurants" key_field="reviews_restaurant_id" value_field="name"
						label="COM_REVIEWS_FIELD_ID"
						description="COM_REVIEWS_FIELD_ID_DESC"
						required="true"
						default="1"
				/>
		</fieldset>
	</fields>
</metadata>
```

Congratulations in it's most basic rudimentary form you now have a working, fully functioning component. In part 3 we'll start to work further on this component including adding a custom form field and adding the options for users to make comments on restaurants with their own ratings.