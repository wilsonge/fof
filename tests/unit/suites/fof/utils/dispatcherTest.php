<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Utils
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once __DIR__ . '/eventInspector.php';
require_once JPATH_TESTS.'/unit/core/reflection/reflection.php';

class FOFUtilsObservableDispatcherTest extends FtestCase
{
	/**
	 * @var FOFUtilsObservableDispatcher
	 */
	protected $object;

	protected function setUp()
	{
		$this->object = new FOFUtilsObservableDispatcher;
		TestReflection::setValue($this->object, 'instance', $this->object);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		TestReflection::setValue($this->object, 'instance', null);
	}

	/**
     * @group   FOFUtilsObservableDispatcher
     * @covers  FOFUtilsObservableDispatcher::getInstance
	 */
	public function testGetInstance()
	{
		$mock = FOFUtilsObservableDispatcher::getInstance();

		$this->assertInstanceOf(
			'FOFUtilsObservableDispatcher',
			$mock
		);

		TestReflection::setValue($this->object, 'instance', null);

		$instance = FOFUtilsObservableDispatcher::getInstance();

		$this->assertInstanceOf(
			'FOFUtilsObservableDispatcher',
			$instance,
			'Tests that getInstance returns a FOFUtilsObservableDispatcher object.'
		);

		// Push a new instance into the class.
		TestReflection::setValue($this->object, 'instance', 'foo');

		$this->assertThat(
			FOFUtilsObservableDispatcher::getInstance(),
			$this->equalTo('foo'),
			'Tests that a subsequent call to FOFUtilsObservableDispatcher::getInstance returns the cached singleton.'
		);

		TestReflection::setValue($this->object, 'instance', $mock);
	}

	/**
     * @group   FOFUtilsObservableDispatcher
     * @covers  FOFUtilsObservableDispatcher::getState()
	 */
	public function testGetState()
	{
		$this->assertThat(
			$this->object->getState(),
			$this->equalTo(null)
		);

		TestReflection::setValue($this->object, '_state', 'test');

		$this->assertThat(
			$this->object->getState(),
			$this->equalTo('test')
		);
	}

	/**
     * @group   FOFUtilsObservableDispatcher
     * @covers  FOFUtilsObservableDispatcher::register()
	 */
	public function testRegister()
	{
		// We have an empty Dispatcher object
		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(array())
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(array())
		);

		// We register a function on the event 'onTestEvent'
		$this->object->register('onTestEvent', 'FOFUtilsObservableEventMockFunction');

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					array('event' => 'onTestEvent', 'handler' => 'FOFUtilsObservableEventMockFunction')
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(
				array('ontestevent' => array(0))
			)
		);

		// We register the same function on a different event 'onTestOtherEvent'
		$this->object->register('onTestOtherEvent', 'FOFUtilsObservableEventMockFunction');

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					array('event' => 'onTestEvent', 'handler' => 'FOFUtilsObservableEventMockFunction'),
					array('event' => 'onTestOtherEvent', 'handler' => 'FOFUtilsObservableEventMockFunction')
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(
				array(
					'ontestevent' => array(0),
					'ontestotherevent' => array(1)
				)
			)
		);

		// Now we attach a class to the dispatcher
		$this->object->register('', 'FOFUtilsObservableEventInspector');

		$observers = TestReflection::getValue($this->object, '_observers');
		$object = $observers[2];

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					array('event' => 'onTestEvent', 'handler' => 'FOFUtilsObservableEventMockFunction'),
					array('event' => 'onTestOtherEvent', 'handler' => 'FOFUtilsObservableEventMockFunction'),
					$object
				)
			)
		);

        // Since I am using a super-class of FOFUtilsObservableEvent which extends FOFUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(0, 2),
					'ontestotherevent' => array(1)
				)
			)
		);
	}

	/**
	 * @group   FOFUtilsObservableDispatcher
     * @covers  FOFUtilsObservableDispatcher::register()
     *
	 * @expectedException  InvalidArgumentException
	 */
	public function testRegisterException()
	{
		$this->object->register('fakeevent', 'nonExistingClass');
	}

	/**
     * @group   FOFUtilsObservableDispatcher
     * @covers  FOFUtilsObservableDispatcher::trigger()
	 */
	public function testTrigger()
	{
		$this->object->register('onTestEvent', 'FOFUtilsObservableEventMockFunction');
		$this->object->register('', 'FOFUtilsObservableEventInspector');

		// We check a non-existing event
		$this->assertThat(
			$this->object->trigger('onFakeEvent'),
			$this->equalTo(array())
		);

		// Let's check the existing event "onTestEvent" without parameters
		$this->assertThat(
			$this->object->trigger('onTestEvent'),
			$this->equalTo(
				array(
					'FOFUtilsObservableDispatcherMockFunction executed',
					''
				)
			)
		);

		// Let's check the existing event "onTestEvent" with parameters
		$this->assertThat(
			$this->object->trigger('onTestEvent', array('one', 'two')),
			$this->equalTo(
				array(
					'FOFUtilsObservableDispatcherMockFunction executed',
					'onetwo'
				)
			)
		);

		// We check a situation where the observer is broken. Joomla should handle this gracefully
		TestReflection::setValue($this->object, '_observers', array());

		$this->assertThat(
			$this->object->trigger('onTestEvent'),
			$this->equalTo(array())
		);
	}

	/**
	 * @group   FOFUtilsObservableDispatcher
	 * @covers  FOFUtilsObservableDispatcher::attach()
	 */
	public function testAttach()
	{
		// Let's test an invalid observer
		$observer = array();

		$this->object->attach($observer);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(array())
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(array())
		);

		// Let's test an uncallable observer
		$observer = array('handler' => 'fakefunction', 'event' => 'onTestEvent');

		$this->object->attach($observer);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(array())
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(array())
		);

		// Let's test a callable function observer
		$observer = array('handler' => 'FOFUtilsObservableEventMockFunction', 'event' => 'onTestEvent');
		$observers = array($observer);

		$this->object->attach($observer);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(
				array(
					'ontestevent' => array(0)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo($observers)
		);

		// Let's test that an observer is not attached twice
		$observer = array('handler' => 'FOFUtilsObservableEventMockFunction', 'event' => 'onTestEvent');
		$observers = array($observer);

		$this->object->attach($observer);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(
				array(
					'ontestevent' => array(0)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo($observers)
		);

		// Let's test an invalid object
		$observer = new stdClass;

		$this->object->attach($observer);

		$this->assertThat(
			TestReflection::getValue($this->object, '_methods'),
			$this->equalTo(
				array(
					'ontestevent' => array(0)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo($observers)
		);

		// Let's test a valid event object
		$observer = new FOFUtilsObservableEventInspector($this->object);
		$observers[] = $observer;

		$this->object->attach($observer);

        // Since I am using a super-class of FOFUtilsObservableEvent which extends FOFUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(0, 1)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo($observers)
		);

		// Let's test that an object observer is not attached twice
		$observer = new FOFUtilsObservableEventInspector($this->object);

		$this->object->attach($observer);

        // Since I am using a super-class of FOFUtilsObservableEvent which extends FOFUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(0, 1)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo($observers)
		);
	}

	/**
     * @group   FOFUtilsObservableDispatcher
     * @covers  FOFUtilsObservableDispatcher::detach()
	 */
	public function testDetach()
	{
		// Adding 3 events to detach later
		$observer1 = array('handler' => 'fakefunction', 'event' => 'onTestEvent');
		$observer2 = array('handler' => 'FOFUtilsObservableEventMockFunction', 'event' => 'onTestEvent');
		$this->object->attach($observer2);
		$observer3 = new FOFUtilsObservableEventInspector($this->object);
		$this->object->attach($observer3);

		// Test removing a non-existing observer

        // Since I am using a super-class of FOFUtilsObservableEvent which extends FOFUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(0, 1)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					$observer2,
					$observer3
				)
			)
		);

		$return = $this->object->detach($observer1);

		$this->assertFalse($return);

        // Since I am using a super-class of FOFUtilsObservableEvent which extends FOFUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(0, 1)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					$observer2,
					$observer3
				)
			)
		);

		// Test removing a functional observer
		$return = $this->object->detach($observer2);

		$this->assertTrue($return);

        // Since I am using a super-class of FOFUtilsObservableEvent which extends FOFUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array(1 => 1)
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(
				array(
					1 => $observer3
				)
			)
		);

		// Test removing an object observer with more than one event
		$return = $this->object->detach($observer3);

		$this->assertTrue($return);

        // Since I am using a super-class of FOFUtilsObservableEvent which extends FOFUtilsObject, I have to exclude
        // the methods of the last one
        $ignoreMethods = array('__construct', '__toString', 'def', 'get', 'getProperties', 'getError', 'getErrors',
            'set', 'setProperties', 'setError', 'update');

        $_methods = TestReflection::getValue($this->object, '_methods');

        foreach($ignoreMethods as $ignore)
        {
            if(isset($_methods[strtolower($ignore)]))
            {
                unset($_methods[strtolower($ignore)]);
            }
        }

		$this->assertThat(
			$_methods,
			$this->equalTo(
				array(
					'ontestevent' => array()
				)
			)
		);

		$this->assertThat(
			TestReflection::getValue($this->object, '_observers'),
			$this->equalTo(array())
		);
	}
}
