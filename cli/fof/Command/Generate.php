<?php

namespace FOF30\Generator\Command;

class Generate extends Command
{
    public function execute($composer, $input)
    {
        // Run some checks
        $this->doChecks($input);

        // Ok, I have to generate something, but... what?
        $class = $this->getClass($input);

        /** @var Command $generator */
        $generator = new $class();
        $generator->execute($composer, $input);
    }

    /**
     * @param   \JInput $input
     */
    protected function doChecks($input)
    {
        // Do I have a name of the view?
        if(!$input->getString('name'))
        {
            throw new \RuntimeException("You have to specify the name of the view that will be used to generate FOF objects");
        }

        // Ok, did the user told me WHAT he wants?
        if($input->get('layout'))
        {
            return;
        }

        if($input->get('controller'))
        {
            return;
        }

        if($input->get('model'))
        {
            return;
        }

        if($input->get('view'))
        {
            return;
        }

        if($input->get('mvc'))
        {
            return;
        }

        throw new \RuntimeException("You have to specify **what** you want to create: model, view, controller or layout");
    }

    protected function getClass($input)
    {
        $class  = 'FOF30\\Generator\\Command\\Generate';
        $return = '';

        $layout = strtolower($input->get('layout'));

        if(in_array($layout, array("1", 'item', 'default', 'form'), true))
        {
            $class .= '\\Layout';

            if($layout === "1")
            {
                $return = $class.'\\Layouts';
            }
        }

        if(!$return || !class_exists($return))
        {
            throw new \RuntimeException("Can not understand which object to generate. Please consult the documentation");
        }

        return $return;
    }
}