<?php

require_once 'PHPUnit/Autoload.php';
// require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Extensions/Database/DataSet/XmlDataSet.php';

abstract class FtestCaseDatabase extends PHPUnit_Extensions_Database_TestCase
{
	public static   $database;
	public static   $dbo;

	protected       $factoryState = array ();

	public static function tearDownAfterClass()
	{
		JFactory::$database = self::$database;
	}

	/**
	 * Saves the Factory pointers
	 *
	 * @return void
	 */
	protected function saveFactoryState()
	{
		$this->savedFactoryState['application'] = JFactory::$application;
		$this->savedFactoryState['config'] 		= JFactory::$config;
		$this->savedFactoryState['session'] 	= JFactory::$session;
		$this->savedFactoryState['language'] 	= JFactory::$language;
		$this->savedFactoryState['document'] 	= JFactory::$document;
		$this->savedFactoryState['acl']	 		= JFactory::$acl;
		$this->savedFactoryState['database'] 	= JFactory::$database;
		$this->savedFactoryState['mailer']		= JFactory::$mailer;
	}

	protected function setUp()
	{
		parent::setUp();
		$this->saveFactoryState();
	}

	protected function tearDown()
	{
		parent::tearDown();
		$this->restoreFactoryState();
	}

	/**
	 * Sets the Factory pointers
	 *
	 * @return void
	 */
	protected function restoreFactoryState()
	{
		JFactory::$application 	= $this->savedFactoryState['application'];
		JFactory::$config 		= $this->savedFactoryState['config'];
		JFactory::$session 		= $this->savedFactoryState['session'];
		JFactory::$language 	= $this->savedFactoryState['language'];
		JFactory::$document 	= $this->savedFactoryState['document'];
		JFactory::$acl 			= $this->savedFactoryState['acl'];
		JFactory::$database 	= $this->savedFactoryState['database'];
		JFactory::$mailer 		= $this->savedFactoryState['mailer'];
	}

	public static function setUpBeforeClass()
	{
		jimport('joomla.database.database');
		jimport('joomla.database.table');

		if (!is_object(self :: $dbo))
		{
			// Let's use the config file of our guinea pig
			require_once JPATH_BASE.'/configuration.php';

			$config = new JConfig();

			$options = array (
				'driver'	=> ((isset ($config)) && ($config->dbtype != 'mysqli')) ? $config->dbtype : 'mysql',
				'host' 		=> isset ($config) ? $config->host : '127.0.0.1',
				'user' 		=> isset ($config) ? $config->user : 'utuser',
				'password' 	=> isset ($config) ? $config->password : 'ut1234',
				'database' 	=> isset ($config) ? $config->db : 'joomla_ut',
				'prefix' 	=> isset ($config) ? $config->dbprefix : 'jos_'
			);

			try
			{
				self::$dbo = JDatabase::getInstance($options);
			}
			catch (RuntimeException $e)
			{
				define('DB_NOT_AVAILABLE', true);
			}

			if (class_exists('JError') && JError::isError(self::$dbo))
			{
				//ignore errors
				define('DB_NOT_AVAILABLE', true);
			}
		}

		self::$database 	= JFactory::$database;
		JFactory::$database = self::$dbo;
	}

	/**
	 * Sets the connection to the database
	 *
	 * @return connection
	 */
	protected function getConnection()
	{
		// Let's use the config file of our guinea pig
		require_once JPATH_BASE.'/configuration.php';

		$config = new JConfig();

		// P.A. Test database prefix is fixed with jos_ so we can setup common tables
		$options = array (
			'driver'	=> ((isset ($config)) && ($config->dbtype != 'mysqli')) ? $config->dbtype : 'mysql',
			'host' 		=> isset ($config) ? $config->host : '127.0.0.1',
			'user' 		=> isset ($config) ? $config->user : 'utuser',
			'password' 	=> isset ($config) ? $config->password : 'ut1234',
			'database' 	=> isset ($config) ? $config->db : 'joomla_ut',
			'prefix' 	=> 'jos_'
		);

		$pdo = new PDO($options['driver'].':host='.$options['host'].';dbname='.$options['database'], $options['user'], $options['password']);
		return $this->createDefaultDBConnection($pdo, $options['database']);
	}

	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return xml dataset
	 */
	protected function getDataSet()
	{
		return $this->createXMLDataSet(JPATH_TESTS.'/unit/stubs/test.xml');
	}
}
