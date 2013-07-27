2.1 Basic Views
==========================================
If you were to install the component at this point with FOF installed - you would see the screen below:

<img src="../../../../assets/img/fof-without-views.png" alt="Component installed without views" />

As you can see there are two errors being shown specifying that the views directory cannot be found and also the default XML file cannot be found.

This is our cue to start creating the files. There is no need for the view.html.php files here or the default/form.php files all we need is two XML files.

As ever there is a specific format for the names of these files:
*A list view should be called form.default.xml
*A form view should be called form.form.xml

We have already defined the form view name in the SQL in the previous section and here list view simply has an 's' appended to it.

So in this tutorial we will be using views called 'restaurant' and 'restaurants'

2.1.1 List View
------------------------------------------
In the file form.default.xml (placed in JPATH_COMPONENT_ADMINISTRATOR/views/restaurants/tmpl/form.default.xml) we will create the list view. This is a browse type form.

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
</form>
```

First of all we create a form. You can see that we define the type to be browse, we force the header, filters and pagination to be shown (by setting them to 1). If no records are present we also set a language string as a placeholder. A full list of possible attributes is listed here: https://www.akeebabackup.com/documentation/fof/xml-forms.html#idp13043728

The next step is to add some column headers. Within the form element we add:

```xml
	<headerset>
		<header
			name="reviews_restaurant_id"
			label="COM_REVIEW_RESTAURANTS_FIELD_ID"
			type="rowselect"
			tdwidth="20"
		/>
		
		<header
			name="name"
			type="fieldsearchable"
			sortable="true"
			buttons="no"
			buttonclass="btn"
			label="COM_REVIEW_RESTAURANTS_FIELD_NAME"
		/>

		<header
			name="enabled"
			type="published"
			sortable="true"
			tdwidth="8%"
		/>

		<header
			name="city"
			type="field"
			label="COM_REVIEWS_FIELD_CITY"
			sortable="true"
		/>

		<header
			name="mainrating"
			type="field"
			sortable="true"
			tdwidth="10%"
			label="COM_REVIEWS_RATING_MAIN"
		/>
	</headerset>
```

The first field is the standard Joomla checkbox, the second is the name of the restaurant, the third is the published status (N.B. By default in FOF this is just publish and unpublish - however this can be overridden in this file.

The final step in this file is to add the fields for the rows themselves (still inside the form tags but beneath the header tags):

```xml
	<fieldset name="items">
		<field
			name="reviews_restaurant_id"
			type="selectrow"
		/>

		<field
			name="name"
			type="text"
			show_link="true"
			url="index.php?option=com_reviews&amp;view=restaurant&amp;id=[ITEM:ID]"
		 />

		<field
			name="enabled"
			type="published"
		/>

		<field
			name="city"
			type="text"
		/>

		<field
			name="mainrating"
			type="rating"
		/>
	</fieldset>
```

Note we've chosen to use a custom type="rating" field for the mainrating coloumn. This will be created later.

Filling in the relevent langauge strings into our language file will then give us something like in the screenshot below:

<img src="../../../../fof-restaurants-view.png" alt="Restaurants View" />

2.1.1 Form View
------------------------------------------

```xml
<?xml version="1.0" encoding="utf-8"?>
<form
	validate="true"
>
	<fieldset name="basic_configuration"
		label="COM_REVIEWS_ITEMS_GROUP_BASIC"
		description="COM_REVIEWS_ITEMS_GROUP_BASIC_DESC"
		class="span4"
	>
		<field name="name" type="text"
			class="inputbox"
			label="COM_REVIEW_RESTAURANTS_FIELD_NAME"
			required="true"
			size="50"
		 />

		<field
			name="enabled"
			type="list"
			label="JSTATUS"
			description="JFIELD_PUBLISHED_DESC"
			class="inputbox"
			filter="intval"
			size="1"
			default="1"
		>
			<option value="1">JPUBLISHED</option>
			<option value="0">JUNPUBLISHED</option>
		</field>
		
		<field
			name="address"
			type="text"
			label="COM_REVIEWS_FIELD_ADDRESS"
			class="inputbox"
		/>
		<field
			name="city"
			type="text"
			label="COM_REVIEWS_FIELD_CITY"
			class="inputbox"
		/>
		<field
			name="county"
			type="text"
			label="COM_REVIEWS_FIELD_COUNTY"
			class="inputbox"
		/>
		<field
			name="country"
			type="text"
			label="COM_REVIEWS_FIELD_COUNTRY"
			class="inputbox"
		/>
		<field
			name="postcode"
			type="text"
			label="COM_REVIEWS_FIELD_POSTCODE"
			class="inputbox"
		/>
		<field
			name="telephone"
			type="tel"
			label="COM_REVIEWS_FIELD_TELEPHONE"
			class="inputbox"
		/>
	</fieldset>
	<fieldset
		name="description_group"
		label="COM_REVIEWS_RESTAURANTS_REVIEW"
		description="COM_REVIEWS_RESTAURANTS_REVIEW_DESC"
		class="span8"
	>
		<field
			name="mainrating"
			type="rating"
			label="COM_REVIEWS_RATING_MAIN"
			class="inputbox"
			required="true"
			filter="intval"
		/>
		<field
			name="staffrating"
			type="rating"
			label="COM_REVIEWS_RATING_STAFF"
			class="inputbox"
			required="true"
			filter="intval"
		/>
		<field
			name="foodrating"
			type="rating"
			label="COM_REVIEWS_RATING_FOOD"
			class="inputbox"
			required="true"
			filter="intval"
		/>
		<field
			name="atmosphererating"
			type="rating"
			label="COM_REVIEWS_RATING_ATMOSPHERE"
			class="inputbox"
			required="true"
			filter="intval"
		/>
		<field
			name="pricerating"
			type="rating"
			label="COM_REVIEWS_RATING_PRICE"
			class="inputbox"
			required="true"
			filter="intval"
		/>
		<field name="description"
			type="editor"
			label="COM_REVIEWS_RATING_DESCRIPTION"
			class="inputbox"
			required="true"
			filter="JComponentHelper::filterText"
			buttons="true"
		/>
	</fieldset>
</form>
```

In this view here - we define the form with javascript validation. We then add in the fields as is done in existing components in the model folder (see https://github.com/joomla/joomla-cms/blob/master/administrator/components/com_weblinks/models/forms/weblink.xml). It is expected for the user to be reasonably familiar with the methods used here. Consult the <a href="http://docs.joomla.org/Form_field">Joomla Form Field docs</a> for more info.
