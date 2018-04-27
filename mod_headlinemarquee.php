<?php
/**
 * @package  mod_headlinemarquee
 *
 * @copyright   Copyright (C) 2016 Simon Champion.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
$document = JFactory::getDocument();
$document->addScript('//cdn.jsdelivr.net/jquery.marquee/1.4.0/jquery.marquee.min.js" type="text/javascript');

$marquee = new headlineMarqueeProcessor($params, $module);

require JModuleHelper::getLayoutPath('mod_headlinemarquee', 'default');

//--------------------------

class headlineMarqueeProcessor
{
    private $generatorPath;
    private $params;
    private $module;

    public function __construct($params, $module)
    {
        $this->params = $params;
        $this->module = $module;
        $this->generatorPath =  __DIR__."/headlineGenerators/";
    }

    public function getClassName()
    {
        return $this->params->get('cssClass');
    }
    
    public function getDivID()
    {
        return "headlineMarquee_".$this->module->id;
    }

    public function getMarqueeText()
    {
        $headlineObj = $this->getHeadlineObject();
        $headlines = $this->getHeadlines($headlineObj);
        $target = $this->getLinkTarget();
        $output = [];
        foreach ($headlines as $headline) {
            list($text, $url) = $headline;
            if($url) {
                $text = "<a href='{$url}' {$target}>{$text}</a>";
            }
            $output[] = "<span class='marqueeItem'>{$text}</span>";
        }

        $separation = (int)$this->params->get('separation', 10);
        $sepPixels = $separation ? (int)($separation / 2) : 0;
        $sepStyle = $sepPixels ? "style='padding-left:{$sepPixels}px; padding-right:{$sepPixels}px;'" : '';
        $bullet = $this->params->get('separatorBullet', 1) ? '&bull;' : '';

        return implode("<span class='marqueeSeparator' {$sepStyle}>{$bullet}</span>",$output);
    }

    private function getLinkTarget()
    {
        $newTab = ($this->params->get('linksOpenInNewTab')==1);
        return $newTab ? "target='_blank'" : '';
    }

    private function getHeadlineObject()
    {
        $pluginPath = JPATH_ROOT."/plugins/headline/".strtolower($this->params['headlines']->source)."/";
        $headlineClass = "generateFrom{$this->params['headlines']->source}";

        $fullPaths = [
            $this->generatorPath . $headlineClass . ".php",
            $pluginPath . strtolower($headlineClass) . ".php",
        ];
        foreach ($fullPaths as $filepath) {
            $headlineObj = $this->loadObject($filepath, $headlineClass);
            if ($headlineObj) {
                return $headlineObj;
            }
        }
        return $this->loadObject($this->generatorPath . "generateFromNone.php", "generateFromNone");
    }

    private function loadObject($filepath, $headlineClass)
    {
        if (file_exists($filepath)) {
            require_once($filepath);
            return new $headlineClass($this->params, $this->module);
        }
    }

    private function getHeadlines($headlineObj)
    {
        $headlines = $headlineObj->getHeadlines();
        foreach(array_keys($headlines) as $id) {
            if ($headlineObj->getNeedsEscaping()) {
                $headlines[$id][0] = htmlentities($headlines[$id][0]);
            }
            if (isset($headlines[$id][1])) {
                $headlines[$id][1] = htmlentities($headlines[$id][1]);
            }
        }

        if ($this->params['textBeforeSource']) {
            array_unshift($headlines, [$this->escapeBeforeAfter($this->params['textBeforeSource']), '']);
        }
        if ($this->params['textAfterSource']) {
            $headlines[] = [$this->escapeBeforeAfter($this->params['textAfterSource']), ''];
        }
        return $headlines;
    }

    private function escapeBeforeAfter($value)
    {
        return $this->params['beforeAndAfterHTML'] ? $value : htmlentities($value);
    }

    public function getScriptJSON()
    {
        return [
            'duration' => $this->params['duration'],
            'direction' => $this->params['scrollDirection'],
            'pauseOnHover' => ($this->params['pauseOnHover']==1),
            'startVisible' => false
        ];
    }
}
