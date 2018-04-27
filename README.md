Headline Marquee
================

A Joomla! module to display a scrolling news feed on your site.

Written by Simon Champion, November 2016.

If you like this extension, please review it at the Joomla Extensions Directory: https://extensions.joomla.org/extensions/extension/news-display/headline-marquee

Introduction
------------

Headline Marquee is a Joomla! module that allows you to add a scrolling news-feed to your site. This news feed can be populated with headlines take from your Joomla site via a Joomla or K2 category, or from a external RSS feed. You can also add an additional message to the marquee text directly from within the module.

This module uses the jQuery-Marquee plugin. You can read about this plugin on its page at Github, here: https://github.com/aamirafridi/jQuery.Marquee


Version History
---------------

v2.3.0
* Added new options:
  - Open links in new tab: If set, then links in the marquee will open in new a new tab.
  - Allow HTML in Before/After text: If set, then you will be able to use HTML code in the Text Before and Text After fields.
  - Custom CSS class: Allows you to override the default class name for the container element for the marquee.

v2.2.0
* Rewrote the JoomCat generator to use Joomla's category model rather than its own DB query. This makes it a lot easier to filter the articles properly, eg only showing published articles.

v2.0.1
* Fixed bug in JoomCat generator when loaded on a page without anything else from com_content.

v2.0
* Made it modular. It is now possible to write additional headline classes for it as separate Joomla plugins.
* Rewrote the built-in class for Joomla categories, as it wasn't working properly (could still use some further attention though)

v1.0
* Initial release
* Headline Marquee module.
* Including built-in classes for Joomla Categories, K2 Categories and RSS feeds.


Dependencies
------------

* Joomla!
* The JSDeliver CDN being online and serving the jQuery Marquee library.

Note that this extension has only been tested against the current version of Joomla! (3.6.4 at the time of writing), and against currently supported PHP versions (PHP 5.6 and higher at the time of writing).


Installation
------------

This module should be installed via the Extensions manager in Joomla!'s admin panel.


Setup
-----

Once installed, go the configuration panel for the module by navigating Joomla!'s admin menu to Components / Modules. Then find it in the list of modules and click on it.

You will now get the config screen for the module, which contains the following fields:

* Scroll Direction
* Duration
* Pause on hover
* Open links in new tab
* Source -- followed by one of the following, depending on the selected value:
  - Joomla Category
  - K2 Category
  - RSS Feed URL
* Number of Headlines
* Separation between items
* Separator bullet
* Text Before
* Text After
* Allow HTML in Before/After text
* CSS Class

Help text is also available if you hover over the field captions.

The first few parameters map directly to options available in the jQuery-Marquee plugin that the module uses: Scroll direction allows you to specify left-to-right or right-to-left scrolling, as well as up or down; the duration field is used to control the speed of scrolling; and Pause on Hover tells the plugin to pause the scrolling when the user hovers the mouse pointer over the marquee.

The Source field is used to specify what headlines you want to be displayed in the marquee. You can chose from a Joomla category, a K2 category or an RSS feed. You can also set it to 'None' if you do not want any headlines to be displayed. In this case, the only text that will be scrolled will be the text in the 'Text Before' and 'Text After' fields. This may be useful if you want the module to display ad-hoc system messages rather than news items.

If you pick Joomla Category, K2 Category or RSS Feed, then the appropriate field will be shown below this field to allow you pick which category you want or to enter the URL of the RSS feed. Note that if you do not have K2 installed, then selecting the K2 option will result in an error message being displayed in the marquee.

The RSS feed will be cached, so your users should not generally notice a performance hit from it.

The 'Text Before' and 'Text After' fields allow you to specify some text to be shown in the scolling marquee that is entered directly here in the module config rather than coming from your article headlines. This may be useful if you want to use the marquee to display a fixed system message rather than any headlines, or if you want to add some explanatory text, etc. You have the option to allow HTML to be included in these fields or not. If you set this option to 'Yes', then you will need to ensure that the values in the two fields contain valid HTML. Note that even with the option set to 'Yes', only "safe" HTML is allowed; this is intended to allow simple markup for styling purposes, not for complex scripting, etc.

The final option is 'CSS Class'. This defaults to "headlineMarquee", which allows you to use CSS to style the marquee as you like. However you can override this class if you wish.

Once you've selected your options, set the status to 'Published', set the position and other config parameters as required to place the module where you want it in the site, as per the normal Joomla! process, and hit 'Save'.

Note that it is up to you to style the element.


Multiple Instances
------------------

As with all Joomla! modules, you can create duplicates of the module with different config parameters and different publishing positions, etc. You could, for example, have one marquee showing your site headlines and another for your favourite RSS feed.

Use the "Duplicate" button on the list of modules to achieve this. This is standard Joomla! functionality, so please see the Joomla! documentation for more details.


Plugins
-------

As of v2.0, This module can make use plugins for its news feeds. This means that additional news sources can be written without having to modify the core code for the module.

Please see the separate [plugins.md](plugins.md) documentation file for details of how to write a plugin.

If you write a plugin for this module please let me know, as I will be able to reference it and give you credit in this documentation. (Even if you don't plan to release it, it would be good to know how the module is being used)


Who wrote this?
---------------

This code was written by Simon Champion.

All code in this repository is released under the GPLv2 licence. The GPLv2 licence document should be included with the code.

Thanks go to those who have submitted bug reports and feature suggestions.

If you use this module and like it, please consider [writing a review of it in the JED](https://extensions.joomla.org/extensions/extension/news-display/headline-marquee/).


Support
-------

Please use the Github issues tracker to report any bugs or feature requests.

If the issue is with the jQuery-Marquee  itself, please report it directly to the author.


Caveats and known issues
------------------------

* The author of this module is not connected with the developers of the jQuery-Marquee plugin. If you wish to report a bug or make a feature request regarding this library, please contact the author directly.
* When using the RSS feed option, this module is obviously dependent on the selected feed remaining online.
* The jQuery-Marquee script is loaded from a third-party CDN (content delivery network). The module therefore relies on the CDN remaining online.


Todo
----

* Make the K2 category selection a drop-down list rather than a text field.
* Add more source types (as separate plugins)
  - Atom feeds
  - JEvents
  - ...other Joomla extensions?
* Add more options, including some from jQuery Marquee.


Trademarks and Licenses
-----------------------

* Joomla!® is a registered trademark of Open Source Matters, Inc.
* Joomla! is distributed under the GPLv2 licence.
* jQuery-Marquee is distributed under the ISC license. Licence document can be obtained from the author. Please see https://github.com/aamirafridi/jQuery.Marquee
* This package is distributed under the GPLv2 licence. The GPLv2 licence document should be included with the code.
