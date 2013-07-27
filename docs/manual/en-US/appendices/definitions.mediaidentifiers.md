Media file identifiers
----------------------

FOF expects you to give an abstracted path to your media (CSS, Javascript, image, …) files, also called an "identifier". It allows it to perform media file overrides very easily, in a fashion similar to how Joomla! performs template overrides for view files. This section will help you understand how they are used and how media file overrides work.

Media file identifiers are in the form:

	area://path
	
Where the `area` can be one of:

**media**
: The file is searched inside your site's `media` directory. FOF will also try to locate it in the media overrides directory of your site, e.g. `templates/your_template/media` where your_template is the name of the currently active template on your site.

In this case the `path` is the rest of the path relative to the media or media override directory. The first part of your path SHOULD be your extension's name, e.g. com_example.

Example: `media://com_example/css/style.css` will look for the file `templates/your_template/media/com_example/css/style.css` or, if it doesn't exist, `media/com_example/css/style.css`

**admin**
: The file is searched for in the administration section of your extension. The first part of the path MUST be your extension's name. The file is first searched for in your template override directory.

Example: `admin://com_example/assets/style.css` will look for the file `administrator/templates/your_template/com_example/assets/style.css` or, if it doesn't exist, `administrator/components/com_example/assets/style.css`

**site**
: The file is searched for in the front-end section of your extension. The first part of the path MUST be your extension's name. The file is first searched for in your template override directory.

Example: `site://com_example/assets/style.css` will look for the file `templates/your_template/com_example/assets/style.css` or, if it doesn't exist, `components/com_example/assets/style.css`

> **IMPORTANT**
> 
> FOF cannot know what is the other side's template. Let's put it simply. If you are in the front-end, your template is called "foobar123" and you use the identifier  `admin://com_example/assets/style.css`, FOF will look for the template override in `administrator/templates/foobar123/com_example/assets/style.css`. Of course this is incorrect, but there is no viable way to know what the back-end template in use is from the site's front-end and vice versa. As a result, we strongly recommend only using `media://` identifiers for media files.
> 
> On top of that there is a security aspect as well. The front-end of your component should never try to load media files from the back-end of the component. Many web masters choose to conceal the fact that they are using Joomla! by means of password protection or redirection of the `administrator` directory.