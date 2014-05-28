# What is this?

This directory contains the source files for all the CSS and JS in Akeeba Strapper. Akeeba Strapper now only includes
the minified versions of these files to reduce its size. Also note that the LESS files have been removed from Strapper
and moved in this directory.

Whenever a change is made to one of these files the minified version (.min.css or .min.js) must be transferred back to
the respective akeeba_strapper directory.

## Notes on Bootstrap integration

**Last update**: May 28th, 2014 w/ Bootstrap 2.3.2 (CSS) and the customised Bootstrap JS code from Joomla! 3.3.0

When importing a new Bootstrap release you have to:
* Build all LESS files in the strapper/akeeba_strapper/less directory
* Copy and rename the generated CSS files in the strapper/akeeba_strapper/css directory. Hint: look at the headers.
* Update the strapper/akeeba_strapper/img directory with the new Bootstrap assets
* Update the strapper/akeeba_strapper/js directory with the new Bootstrap bootstrap.min.js file. It's a good idea
  keeping it in sync with Joomla!'s customised file to prevent problems with drop-down menus.

**IMPORTANT!** To ensure compatibility with Joomla! 3 we cannot upgrade to Bootstrap 3 or later. Even though the CSS
would be namespaced, the Javascript wouldn't, causing problems with rendering core Joomla! page elements such as
dropdowns.