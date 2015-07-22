<?php

/**
 * This class if used when we have to test the loading of classes that do not use the autoloader
 */
class Standalone
{
    /**
     * This method is used in {@link CallbackTest::testGetCallbackResults()} to test the callback
     * to a class method
     *
     * @param $data
     *
     * @return array
     */
    public static function formCallback($data)
    {
        return $data;
    }
}