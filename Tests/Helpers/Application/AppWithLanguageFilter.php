<?php

namespace FOF30\Tests\Helpers\Application;

class AppWithLanguageFilter
{
    protected $languageFilter;

    public function __construct($filter)
    {
        $this->languageFilter = $filter;
    }

    public function getLanguageFilter()
    {
        return $this->languageFilter;
    }
}
