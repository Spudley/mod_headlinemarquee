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

    public function getDivID()
    {
        return "headlineMarquee_".$this->module->id;
    }

    public function getMarqueeText()
    {
        $headlines = $this->getHeadlines();
        $output = [];
        foreach ($headlines as $headline) {
            list($text, $url) = $headline;
            if($url) {
                $text = "<a href='{$url}'>{$text}</a>";
            }
            $output[] = "<span class='marqueeItem'>{$text}</span>";
        }

        $separation = (int)$this->params->get('separation', 10);
        $sepPixels = $separation ? (int)($separation / 2) : 0;
        $sepStyle = $sepPixels ? "style='padding-left:{$sepPixels}px; padding-right:{$sepPixels}px;'" : '';
        $bullet = $this->params->get('separatorBullet', 1) ? '&bull;' : '';

        return implode("<span class='marqueeSeparator' {$sepStyle}>{$bullet}</span>",$output);
    }
    
    private function getHeadlines()
    {
        $headlines = [];
        $pluginPath = JPATH_ROOT."/plugins/headline/".strtolower($this->params['headlines']->source)."/";
        $headlineClass = "generateFrom{$this->params['headlines']->source}";

        if (file_exists($this->generatorPath . $headlineClass . ".php")) {
            require_once($this->generatorPath . $headlineClass . ".php");
            $headlines = (new $headlineClass($this->params, $this->module))->getHeadlines();
        } elseif (file_exists($pluginPath . strtolower($headlineClass) . ".php")) {
            require_once($pluginPath . strtolower($headlineClass) . ".php");
            $headlines = (new $headlineClass($this->params, $this->module))->getHeadlines();
        }

        if ($this->params['textBeforeSource']) {
            array_unshift($headlines, [$this->params['textBeforeSource'], '']);
        }
        if ($this->params['textAfterSource']) {
            $headlines[] = [$this->params['textAfterSource'], ''];
        }
        return $headlines;
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
