<?php
namespace FOF30\Tests\DataModel;

use FOF30\Model\DataModel\Behaviour\Tags;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once 'TagsDataprovider.php';

/**
 * @covers      FOF30\Model\DataModel\Behaviour\Tags::<protected>
 * @covers      FOF30\Model\DataModel\Behaviour\Tags::<private>
 * @package     FOF30\Tests\DataModel\Behaviour\Tags
 */
class TagsTest extends DatabaseTest
{
    /**
     * @group           Behaviour
     * @group           TagsOnBeforeCreate
     * @covers          FOF30\Model\DataModel\Behaviour\Tags::onBeforeCreate
     */
    public function testOnBeforeCreate()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model      = new DataModelStub(static::$container, $config);
        $dispatcher = $model->getBehavioursDispatcher();
        $tags       = new Tags($dispatcher);
        $data       = (object) array('tags' => 'foobar');

        $tags->onBeforeCreate($model, $data);

        $this->assertObjectNotHasAttribute('tags', $data);
    }

    /**
     * @group           Behaviour
     * @group           TagsOnBeforeUpdate
     * @covers          FOF30\Model\DataModel\Behaviour\Tags::onBeforeUpdate
     */
    public function testOnBeforeUpdate()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model      = new DataModelStub(static::$container, $config);
        $dispatcher = $model->getBehavioursDispatcher();
        $tags       = new Tags($dispatcher);
        $data       = (object) array('tags' => 'foobar');

        $tags->onBeforeCreate($model, $data);

        $this->assertObjectNotHasAttribute('tags', $data);
    }

    /**
     * @group           Behaviour
     * @group           TagsOnAfterBind
     * @covers          FOF30\Model\DataModel\Behaviour\Tags::onAfterBind
     * @dataProvider    TagsDataprovider::getTestOnAfterBind
     */
    /*public function testOnAfterBind($test, $check)
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('FOF30\Tests\Stubs\Model\DataModelStub', array('getContentType', 'addKnownField'), array(static::$container, $config));
        $model->expects($check['contentType'] ? $this->once() : $this->never())->method('getContentType')->willReturn('com_foftest.foobars');
        $model->expects($check['addKnown'] ? $this->once() : $this->never())->method('addKnownField');

        if($test['load'])
        {
            $model->find($test['load']);
        }

        if($test['tags'])
        {
            $model->tags = $test['tags'];
        }

        $dispatcher = $model->getBehavioursDispatcher();
        $tags       = new Tags($dispatcher);
        $dummy      = array();

        $tags->onAfterBind($model, $dummy);

        $this->assertEquals($check['result'], $model->tags);
    }*/

    /**
     * @group           Behaviour
     * @group           TagsOnAfterPublish
     * @covers          FOF30\Model\DataModel\Behaviour\Tags::onAfterPublish
     */
    public function testOnAfterPublish()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('FOF30\Tests\Stubs\Model\DataModelStub', array('updateUcmContent'), array(static::$container, $config));
        $model->expects($this->once())->method('updateUcmContent');

        $dispatcher = $model->getBehavioursDispatcher();
        $tags       = new Tags($dispatcher);

        $tags->onAfterPublish($model);
    }

    /**
     * @group           Behaviour
     * @group           TagsOnAfterUnpublish
     * @covers          FOF30\Model\DataModel\Behaviour\Tags::onAfterUnpublish
     */
    public function testOnAfterUnpublish()
    {
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $model = $this->getMock('FOF30\Tests\Stubs\Model\DataModelStub', array('updateUcmContent'), array(static::$container, $config));
        $model->expects($this->once())->method('updateUcmContent');

        $dispatcher = $model->getBehavioursDispatcher();
        $tags       = new Tags($dispatcher);

        $tags->onAfterUnpublish($model);
    }
}
