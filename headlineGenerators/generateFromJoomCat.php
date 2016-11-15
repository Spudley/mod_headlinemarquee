<?php
/**
 * @package  mod_headlinemarquee
 *
 * @copyright   Copyright (C) 2016 Simon Champion.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once('generateFromNone.php');
require_once(JPATH_SITE."/components/com_content/models/category.php");

class generateFromJoomCat extends generateFromNone
{
    public function getHeadlines()
    {
        $cat = $this->params['joomCat'];
 
        $category = new ContentModelCategory();
        $category->hit($categoryId);
        $items = $category->getItems();

        $limit = $this->params['numberOfHeadlines'];
        $output = [];
        foreach ($items as $item) {
            $output[] = [$item->title, JRoute::_('index.php?option=com_content&view=article&id='.$item->id)];
            if ($limit > 0 && count($output) >= $limit) {
                break;
            }
        }
        return $output;
    }
}