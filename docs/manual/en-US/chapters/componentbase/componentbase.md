2 The Base Backend
==========================================

In this tutorial we will be creating a basic restaurant review component, as was well documented in Joomla 1.5 by Joe LeBlanc. To start with we'll make a basic backend and then make a front end interface for users.

2.1 File Structure
==========================================

The principle advantage of using the Joomla RAD layer is that very few files are needed. In this case for a simple backend we are going to have a install.sql file, the components xml file, a dispatcher.php which sets the default view, a reviews.php file which runs the dispatcher mentioned previously, and one xml file per view.