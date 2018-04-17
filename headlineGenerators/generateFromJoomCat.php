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
     * @todo: Add config for including subcats, only featured, sort order, etc.
     */
    public function getHeadlines()
    {
        $cat = (int)$this->params['headlines']->joomCat;
        $limit = (int)$this->params['numberOfHeadlines'];

        $model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
        $model->getState();

        //Set app parameters in model
        $app = JFactory::getApplication();
        $appParams = $app->getParams();
        $model->setState('params', $appParams);

        //Set the filters based on the module params
        $model->setState('list.start', 0);
        $model->setState('list.limit', $limit);
        $model->setState('filter.category_id', $cat);
        $model->setState('filter.published', 1);

        //User permissions
        $access = !JComponentHelper::getParams('com_content')->get('show_noauth');
        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
        $model->setState('filter.access', $access);

        $items = $model->getItems();

        $output = [];
        foreach ($items as $item) {
            $output[] = [$item->title, JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $cat))];
        }
        return $output;
    }
}
