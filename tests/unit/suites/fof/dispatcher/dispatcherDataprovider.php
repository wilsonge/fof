<?php

class DispatcherDataprovider
{
    public static function getTestGetTask()
    {
        $message = 'Incorrect task';

        // Should we test for ids on other cases, too?
        $data[] = array(new FOFInput(array('ids' => array(999))), 'foobar' , true,  'GET' 	 , 'read'  , $message);
        $data[] = array(new FOFInput(array('ids' => array(999))), 'foobar' , false,  'GET' 	 , 'edit'  , $message);
        $data[] = array(new FOFInput(array('id' => 999)), 'foobar' , true,  'GET' 	 , 'read'  , $message);
        $data[] = array(new FOFInput(array('id' => 999)), 'foobar' , false, 'GET' 	 , 'edit'  , $message);
        $data[] = array(new FOFInput(array())           , 'foobar' , true,  'GET'  	 , 'add'   , $message);
        $data[] = array(new FOFInput(array('id' => 999)), 'foobar' , true,  'POST'	 , 'save'  , $message);
        $data[] = array(new FOFInput(array())           , 'foobar' , true,  'POST'	 , 'edit'  , $message);
        $data[] = array(new FOFInput(array('id' => 999)), 'foobar' , true,  'PUT' 	 , 'save'  , $message);
        $data[] = array(new FOFInput(array())           , 'foobar' , true,  'PUT' 	 , 'edit'  , $message);
        $data[] = array(new FOFInput(array('id' => 999)), 'foobar' , true,  'DELETE' , 'delete'  , $message);
        $data[] = array(new FOFInput(array())           , 'foobar' , true,  'DELETE' , 'edit'  , $message);
        $data[] = array(new FOFInput(array('id' => 999)), 'foobars', true,  'GET' 	 , 'browse', $message);
        $data[] = array(new FOFInput(array())           , 'foobars', true,  'GET' 	 , 'browse', $message);
        $data[] = array(new FOFInput(array('id' => 999)), 'foobars', true,  'POST'	 , 'save'  , $message);
        $data[] = array(new FOFInput(array())           , 'foobars', true,  'POST'	 , 'browse', $message);

        return $data;
    }
}