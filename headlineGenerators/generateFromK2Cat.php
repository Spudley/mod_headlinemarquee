<?php
/**
 * @package  mod_headlinemarquee
 *
 * @copyright   Copyright (C) 2016 Simon Champion.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once('generateFromNone.php');

class generateFromK2Cat extends generateFromNone
{
    public function getHeadlines()
    {
        if (!JComponentHelper::getComponent('com_k2', true)->enabled) {
            return ['Module configuration error. K2 category selected but K2 not installed.'];
        }
        require_once(JPATH_SITE."/modules/mod_k2_content/helper.php");

        $items = modK2ContentHelper::getItems($this->k2params(), 'feed');
        $limit = $this->params['numberOfHeadlines'];
        $output = [];
        foreach ($items as $item) {
            $output[] = [$item->title, $item->link];
            if ($limit > 0 && count($output) >= $limit) {
                break;
            }
        }
        return $output;
    }

    private function k2params()
    {
        return new Joomla\Registry\Registry([
            'itemCount' => $this->params->get('numberOfHeadlines', 5),
            'category_id' => $this->params['headlines']->k2Cat,
            'catfilter' => true,
        ]);

    }
}