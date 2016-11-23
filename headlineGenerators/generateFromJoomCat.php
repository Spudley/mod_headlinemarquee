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
    /**
     * @todo: Doesn't take permissions, etc into account; just lists all articles straight from the DB.
     */
    public function getHeadlines()
    {
        $cat = (int)$this->params['headlines']->joomCat;

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__content');
        $query->where('catid="'.$cat.'"');

        $db->setQuery((string)$query);
        $items = $db->loadObjectList();
 
        $output = [];
        foreach ($items as $item) {
            $output[] = [$item->title, JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $cat))];
        }
        return $output;
    }
}
