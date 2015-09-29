<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Generator\Command;

use FOF30\Generator\Command\Command as Command;

class Init extends Command
{
	public function execute()
    {
        $composer = $this->composer;

		// We do have a composer file, so we can start working
		$composer->extra = $composer->extra ? $composer->extra : array('fof' => new \stdClass());
		$composer->extra->fof = $composer->extra->fof ? $composer->extra->fof : new \stdClass();

		$info = $composer->extra->fof;

		// Component Name (default: what's already stored in composer / composer package name)
		$info->name = $this->getComponentName($composer);

		$files = array(
			'backend'               => 'component/backend',
			'frontend'              => 'component/frontend',
			'media'                 => 'component/media',
			'translationsbackend'   => 'translations/component/backend',
			'translationsfrontend'  => 'translations/component/frontend'
		);

		$info->paths = array();

		foreach ($files as $key => $default)
        {
			$info->paths[$key] = $this->getPath($composer, $key, $default);
		}

		// Create the directories if necessary
		foreach ($info->paths as $folder)
        {
			if (!is_dir($folder))
            {
				\JFolder::create(getcwd() . '/' . $folder);
			}
		}

		// Now check for fof.xml file
		$fof_xml = getcwd() .  '/' . $info->paths['backend'] . '/fof.xml';

		if (file_exists($fof_xml))
        {
            // ????
		}

		// Store back the info into the composer.json
		$composer->extra->fof = $info;
		\JFile::write(getcwd() . '/composer.json', json_encode($composer, JSON_PRETTY_PRINT));

		$this->setDevServer(true);
	}


	/**
	 * Ask the user the path for each of the files folders
	 * @param  object $composer The composer json object
	 * @param  string $key      The key of the folder (backend)
	 * @param  string $default  The default path to use
	 * @return string           The user chosen path
	 */
	protected function getPath($composer, $key, $default)
	{
		$extra = $composer->extra ? $composer->extra->fof : false;
		$default_path = ($extra && $extra->paths && $extra->paths->$key) ? $extra->paths->$key : $default;

		$this->out("Location of " . $key . " files: (" . $default_path . ")");
		$path = $this->in();

		if (!$path)
        {
			$path = $default_path;
		}

		// Keep asking while the path is not valid
		while(!$path)
        {
			$path = $this->getPath($composer, $key, $default);
		}

		return $path;
	}
}