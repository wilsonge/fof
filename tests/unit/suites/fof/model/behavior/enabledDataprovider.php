<?php

class enabledDataprovider
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
                'query'    => $test
            ),
            array('query' => $check)
        );

        // Apply filtering
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')->where($db->qn('enabled').' = '.$db->q(1));
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'frontend' => true,
                'query'    => $test
            ),
            array('query' => $check)
        );

        // Table with alias
        $test   = $db->getQuery(true)->select('*')->from('#__foftestfoobars');
        $check  = $db->getQuery(true)->select('*')->from('#__foftestfoobars')->where($db->qn('fo_enabled').' = '.$db->q(1));
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'frontend' => true,
                'query'    => $test,
                'aliases'  => array(
                    'enabled' => 'fo_enabled'
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
                'query'    => $test
            ),
            array('query' => $check)
        );

        return $data;
    }

    public static function getTestOnAfterGetItem()
    {
        // User has access
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'loadid' => 1,
                'views'  => array(1, 2)
            ),
            array('nullify' => false)
        );

        // User has NO access
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'loadid' => 1,
                'views'  => array(1)
            ),
            array('nullify' => true)
        );

        // User has access - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'loadid' => 1,
                'views'  => array(1, 2),
                'aliases' => array(
                    'tbl_key' => 'id_foobar_aliases',
                    'access'  => 'fo_access'
                )
            ),
            array('nullify' => false)
        );

        // User has NO access - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'loadid' => 1,
                'views'  => array(1),
                'aliases' => array(
                    'tbl_key' => 'id_foobar_aliases',
                    'access'  => 'fo_access'
                )
            ),
            array('nullify' => true)
        );

        // Table with no access support
        $data[] = array(
            array('name' => 'bares'),
            array(
                'loadid' => 1,
                'views'  => array()
            ),
            array('nullify' => false)
        );

        return $data;
    }
}