<?php
/**
 * @package  mod_headlinemarquee
 *
 * @copyright   Copyright (C) 2016 Simon Champion.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class generateFromNone
{
    protected $params;
    protected $module;

    /**
     * Leave set to true if the main module class needs to call htmlentities() on the headline text.
     * Set to false if your class does its own escaping. (this may be useful if you want to output HTML code in your news items)
     */
    protected $needsEscaping = true;

    public function __construct($params, $module)
    {
        $this->params = $params;
        $this->module = $module;
    }

    private function getHeadlines()
    {
        return [];
    }

    public function getNeedsEscaping()
    {
        return $this->needsEscaping;
    }
}