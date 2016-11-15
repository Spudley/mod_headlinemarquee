<?php
/**
 * @package  mod_headlinemarquee
 *
 * @copyright   Copyright (C) 2016 Simon Champion.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

class generateFromNone
{
    protected $params;
    protected $module;

    public function __construct($params, $module)
    {
        $this->params = $params;
        $this->module = $module;
    }

    private function getHeadlines()
    {
        return [];
    }
}