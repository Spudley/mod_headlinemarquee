<?php
/**
 * @package  mod_headlinemarquee
 *
 * @copyright   Copyright (C) 2016 Simon Champion.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.form.form');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('subform');

class JFormFieldHeadlines extends JFormFieldSubform {
    protected $type = 'Headlines';
    protected $renderLayout = 'headlinefields';

    protected function getLayoutPaths()
    {
        $dummyRenderer = new JLayoutFile('default');
        $defaultPaths = $dummyRenderer->getDefaultIncludePaths();
        $layoutPaths = [JPATH_ROOT.'/modules/mod_headlinemarquee/layout/'];
        return array_merge($layoutPaths, $defaultPaths);
    }

    public function getInput() {
        //override $this->formsource that the main Subform class uses.
        $this->formsource = $this->buildSubformFromPlugins();
        return parent::getInput();
    }
    
    private function buildSubformFromPlugins()
    {
        //trigger plugin event to return field data
        JPluginHelper::importPlugin('headline');
        $dispatcher = JEventDispatcher::getInstance();

        //results = array of XML strings containing form fields.
        $results = $dispatcher->trigger('onGetConfigFields', []);
        $xmlString = implode("\n", $results);

        //build the full XML form string from field data
        //and add a hidden spacer at the end so that there is always at least one field, even if no plugins.
        return <<<eof
<?xml version="1.0" encoding="UTF-8"?>
<form>
<field name="source" type="plugins" folder="headline" description="MOD_HEADLINEMARQUEE_SOURCE_DESC" label="MOD_HEADLINEMARQUEE_SOURCE_LABEL" default="">
    <option value="None">MOD_HEADLINEMARQUEE_SOURCE_NONE</option>
    <option value="JoomCat">MOD_HEADLINEMARQUEE_SOURCE_JOOMLA</option>
    <option value="K2Cat">MOD_HEADLINEMARQUEE_SOURCE_K2</option>
    <option value="RSS">MOD_HEADLINEMARQUEE_SOURCE_RSS</option>
</field>
<field
    name="rssFeed"
    type="URL"
    showon="source:RSS"
    label="MOD_HEADLINEMARQUEE_RSSFEED_LABEL"
    description="MOD_HEADLINEMARQUEE_RSSFEED_DESC"
    default="" />
<field
    name="joomCat"
    type="category"
    extension="com_content"
    showon="source:JoomCat"
    label="MOD_HEADLINEMARQUEE_JOOMCAT_LABEL"
    description="MOD_HEADLINEMARQUEE_JOOMCAT_DESC"
    default="" />
<field
    name="k2Cat"
    type="text"
    extension="com_k2"
    showon="source:K2Cat"
    label="MOD_HEADLINEMARQUEE_K2CAT_LABEL"
    description="MOD_HEADLINEMARQUEE_K2CAT_DESC"
    default="" />
{$xmlString}
</form>
eof;
    }
}
