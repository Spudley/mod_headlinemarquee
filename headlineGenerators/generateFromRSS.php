<?php
/**
 * @package  mod_headlinemarquee
 *
 * @copyright   Copyright (C) 2016 Simon Champion.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once('generateFromNone.php');

class generateFromRSS extends generateFromNone
{
    const cacheRef = 'headlineMarqueeRSS';
    const cacheLifetime = 86400;    //24 hours

    private $cacheDir;

    public function getHeadlines()
    {
        try {
            $url = $this->params['headlines']->rssFeed;
            if (!$url) {
                throw new HeadlineMarqueeRSSException("RSS feed URL not defined");
            }
            $feed = $this->readURL($url);
            $xml = $this->loadFeed($feed);
            $output = $this->processFeed($xml);
        } catch(HeadlineMarqueeRSSException $e) {
            $output = [[$e->getMessage(), '']];
        }
        return $output;
    }

	function readURL($url) {
        $cache = JFactory::getCache(self::cacheRef, '');
        $cache->setCaching(true);
        $cache->setLifeTime(self::cacheLifetime);
        $cacheID = self::cacheRef.'_'.md5($url);
        $cachedFeed = $cache->get($cacheID);
        if (!empty($cachedFeed)) {
            $feed = $cachedFeed;
        }else{
            $feed = JFile::read($url);
            $cache->store($feed, $cacheID);
        }
        return $feed;
	}

    private function loadFeed($feed)
    {
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $xml = simplexml_load_string($feed);
        $error = libxml_get_last_error();
        if ($error) {
            throw new HeadlineMarqueeRSSException($error->message);
        }
        if (!$xml) {
            throw new HeadlineMarqueeRSSException('Not a valid XML document');
        }
        return $xml;
    }

    private function processFeed($xml)
    {
        $items = $xml->xpath("/rss/channel/item");
        if ($items === false) {
            throw new headlineMarqueeRSSException('Not a valid RSS feed.');
        }

        $limit = $this->params['numberOfHeadlines'];
        $output = [];
        foreach($items as $item){
            $output[] = [$item->title, $item->link];
            if ($limit > 0 && count($output) >= $limit) {
                break;
            }
        }
        return $output;
    }
}

class headlineMarqueeRSSException extends Exception
{
    public function __construct($message = "", $code = 0, $previous = NULL)
    {
        parent::__construct('Error loading RSS feed: '.$message, $code, $previous);
    }
}
