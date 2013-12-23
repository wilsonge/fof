<?php

class privateDataprovider
{
    public static function getTestOnAfterBuildQuery()
    {
        $db = JFactory::getDbo();

        // On frontend no filtering
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'frontend' => false,
                'user'     => 42,
                'query'    => $test
            ),
            array('query' => $check)
        );

        // Apply filtering
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')->where($db->qn('created_by').' = '.$db->q(42));
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'frontend' => true,
                'user'     => 42,
                'query'    => $test
            ),
            array('query' => $check)
        );

        // Apply filtering with table alias
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')
                     ->where($db->qn('dummy').'.'.$db->qn('created_by').' = '.$db->q(42));
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'frontend'    => true,
                'user'        => 42,
                'table_alias' => 'dummy',
                'query'       => $test
            ),
            array('query' => $check)
        );

        // Table with column alias
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')->where($db->qn('fo_created_by').' = '.$db->q(42));
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'frontend'  => true,
                'user'      => 42,
                'query'     => $test,
                'aliases'   => array(
                    'created_by' => 'fo_created_by'
                )
            ),
            array('query' => $check)
        );

        // Table with column alias and table alias
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')
                     ->where($db->qn('dummy').'.'.$db->qn('fo_created_by').' = '.$db->q(42));
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'frontend'    => true,
                'user'        => 42,
                'table_alias' => 'dummy',
                'query'       => $test,
                'aliases'     => array(
                    'created_by' => 'fo_created_by'
                )
            ),
            array('query' => $check)
        );

        // No table support
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $data[] = array(
            array('name' => 'bares'),
            array(
                'frontend' => true,
                'user'     => 42,
                'query'    => $test
            ),
            array('query' => $check)
        );

        return $data;
    }

    public static function getTestOnAfterGetItem()
    {
        // Record enabled
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'loadid' => 1
            ),
            array('nullify' => false)
        );

        // Record NOT enabled
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'loadid' => 2
            ),
            array('nullify' => true)
        );

        // Record enabled - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'loadid' => 1,
                'aliases' => array(
                    'tbl_key'  => 'id_foobar_aliases',
                    'enabled'  => 'fo_enabled'
                )
            ),
            array('nullify' => false)
        );

        // Record NOT enabled - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'loadid' => 2,
                'aliases' => array(
                    'tbl_key'  => 'id_foobar_aliases',
                    'enabled'  => 'fo_enabled'
                )
            ),
            array('nullify' => true)
        );

        // Table with no enabled support
        $data[] = array(
            array('name' => 'bares'),
            array(
                'loadid' => 1,
            ),
            array('nullify' => false)
        );

        return $data;
    }
}