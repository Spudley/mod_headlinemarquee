Writing data source plugins for HeadlineMarquee
===============================================

This document provides instructions for writing a Joomla! plugin to provide a data source for the HeadlineMarquee module.

You only need to read this document if you intend to write your own plugins. For help using existing plugins, please refer to the documentation provided with those plugins.


Example
-------

A working example plugin has been written as a demonstration, and can be found in its own repository: https://github.com/Spudley/plg_headlinemarqueetest.

When writing your own plugin, you should consider taking this example plugin as a starting point.


Before You Begin
----------------

Before starting, please note that this document assumes that you already know how to:
* Use the mod_headlinemarquee module.
* Write modern object-oriented PHP code.
* Create a Joomla! plugin, and indeed, you have an understanding of coding for Joomla in general.

It is not the intention of this document to teach you how to write a Joomla plugin in general; other guides already exist for this.

You probably should also have a good idea of what kind of data source you want to load using your plugin, and how your code will go about doing that.


The Basics
----------

Let's begin by looking at the example plugin.

Look at the files and directories it contains:

* language
* generatefromheadlinemarqueetest.php
* headlinemarqueetest.php
* headlinemarqueetest.xml
* index.html
* LICENSE
* README.md

Starting with the ones we don't need to worry too much about, we can ignore the index.html, LICENSE and README.md files for now. index.html is just a blank file, and while the license and documentation are important, they're not relevant for discussing how to write a plugin.

We can also ignore the language folder. Obviously this is also actually a useful part of the plugin, but it is also very much a standard part of writing a Joomla! extension and is thus outside the scope of this document. You can write your own plugins as translatable or not, depending on your own needs.

That leaves just three files that are significant here: `generatefromheadlinemarqueetest.php`, `headlinemarqueetest.php` and `headlinemarqueetest.xml`.


The Plugin Definition XML File
------------------------------

So the first file we'll look at that is important to discuss is the XML file.

If you already know how a Joomla! plugin works, you will know that this XML file relates to the plugin's internal configuration. It defines the plugin's structure, name, version number, what files it includes, and also the sets up the fields that will be available in the plugin's configuration screen once its installed. This particular XML file is unusually simple, as the plugin has no configuration fields at all (other than a single placeholder field to state that fact).

Aside from the files list, which should be self-explanatory, the key thing you need to note from the XML file is that you need to specify `group="headline"` as an attribute in the main `extension` element. This defines the plugin as belonging to the HeadlineMarquee module. If you get this group attribute wrong, the plugin's events will never be fired and the plugin will never actually do anything. You also need to pay attention to the plugin name which is defined in our example with the `plugin="headlinemarqueetest"` attribute in the `filename` element relating to the main plugin class file. You can name your plugin whatever you like, but remember that this name will be used elsewhere in the plugin for filenames and class names.

As noted above, the example plugin has no configuration fields defined in its XML file. This is worth explaining here, because most plugins do have at least some degree of configuration. However in our case, we will be deferring any configurable features of the plugin to the main HeadlineMarquee module. The plugin itself will not be configurable, but the module will have configuration fields from the plugin injected into it. The reason for doing this rather than simply configuring the plugin is that it is possible to have multiple instances of the module, whereas a plugin can only be installed once. If you have more than one instance of the module, it is highly likely that you would want to configure them differently. Delegating the configuration fields to the module allows us to do this, albeit at the expense of making the plugin code a little more complex.

You are, of course, free to specify some configuration fields for your plugin if you feel that you need them. But in most cases it is expected that delegating the configuration to the module will provide more flexibility.


The PHP Code
------------

And that brings us neatly on to talking about the PHP code. There are two PHP files that are important here. In the example plugin, they are named `headlinemarqueetest.php` and `generatefromheadlinemarqueetest.php`.

The first of these -- headlinemarqueetest.php -- is the standard PHP class file that you would expect to have for any Joomla! plugin. As with any Joomla! plugin, its filename should match the plugin name, as defined in the XML file.

The other PHP file from the example is `generatefromheadlinemarqueetest.php`. When writing your own plugins, you will need a file like this, named `generatefrom<pluginname>`. This should be all-lower-case, and `<pluginname>` should be the name of the plugin as for the main plugin file.

Both PHP files should contain a PHP class. Detailed definitions of the requirements for these classes can be found below.


The Main Plugin Class
---------------------

For the main plugin class, the classname should follow the standard Joomla naming convention: `class plg<PluginGroup><PluginName> extends JPlugin`. In this case, `<PluginGroup>` is `Headline` (the group that you specified in the XML file as mentioned earlier), and `<PluginName>` should be the name of the plugin (also from the XML file). Classnames in PHP are  case-insensitive, so the classname can be capitalised as you see fit, but by convention we leave `plg` as lower case, and put the rest in camel-case.

Joomla! plugin classes may contain methods for any event that is triggered for the plugin group. In the case of the `headline` group only one event is triggered. The event name is `onGetConfigFields`, and thus you should have a method named `onGetConfigFields()`. The method should not take any arguments.

The event that calls this method is triggered in the admin panel, when you go to the configuration screen for the HeadlineMarquee module.

This method is optional: Its purpose is to define parameter fields that your plugin will inject into HeadlineMarquee's module config form. It is possible that your plugin will not need any configuration beyond being selected as the data source, in which case you may omit this method entirely, or write it as an empty function that just returns an blank string.

Assuming you do write the method it must return a string in the form of an XML fragment, composed of `<field>` elements of the same specification as per a Joomla! extension XML configuration file.

The example plugin does this very simply by outputting a string that is hard-wired directly into the code. You may take this approach if you wish, or you may load it from an external XML file, or compose it using PHP's XML building classes. Whatever method you use, do note that the output should be an XML fragement, not a well-formed XML file. It should only consist of the `<field>` elements.

If you look at XML elements that are output by the example plugin, you will notice that they specify a `showon="source:<pluginname>"` attribute (where `<pluginname>` is the name of the plugin, matching the name from the plugin's main XML file). This `showon` attribute is important as it tells HeadlineMarquee to only display the fields in the module config if you select the relevant plugin as the data source. This avoid clutter in the UI for the module admin.


The 'GenerateFrom' Class
------------------------

And finally, we get to the code that actually does the work.

Everything we've looked at so far above has just been dealing with the configuration of the plugin and the module. But this is the class that is called when the end user loads your site and views a page that includes your HeadlineMarquee.

The goal of this class is to load the data that you want to display in your marquee, and return it to the main HeadlineMarquee module.

You'll notice that in the example code, the class `extends generateFromNone`. The `generateFromNone` class is part of the main module, and as the name suggests, it returns an empty data set. Your own code does not have to extend this, but it is recommended. If you choose not to, you will probably have to duplicate most of the code in `generateFromNone` anyway. This documentation assumes that you are going to extend `generateFromNone`.

###The `getHeadlines()` method

In most cases, your class will only need a single public method, named `getHeadlines()`. This method does not take any arguments, and should return an array containing a further two-element array for each item loaded from your data source. Each of these two-element arrays should consist of two strings; the first being the text description for your item, and the second being a URL for it to link to. The second element can be skipped if your do not have a URL (or do not want your marquee to be clickable).

An example output array might be structured like this:

    array(
        array('Item #1 title', 'http://example.com/item1/'),
        array('Item #2 title', 'http://example.com/item2/'),
        array('Item #3 title'),
    )

Your code can reference `$this->params` to access the module configuration. The parameters that you will be most interested in will be the config fields specific to your plugin. These which can be accessed via `$this->params->get('headlines')->fieldname` or `$this->params['headlines']->fieldname`.

The other config parameters for the module are also available, but less likely to be needed. The one that you may want to use is `numberOfHeadlines` (`$this->params['numberOfHeadlines']`), as this may be useful for example if you want to specify a limit for a SQL query. However, even if you don't use it, the module itself will enforce it, and will truncate the data if your class provides more items than specified.

If you need to reference the module object, this is also available to your class via `$this->module`. Note that none of the plugins written so far have had used this, but it is provided in case you need it.

For performance reasons, it is strongly recommended that your class should cache its results, particularly if it is querying an external resource.

By default, the main module will pass the item titles and URLs through `htmlentities()`. to ensure that they are sanitised for display in the marquee. It is possible that you do not want this; for example, if your want more complex content in your marquee than just plain text, your plugin may return fully formed HTML. In this case, you can switch off the escaping of the item title by setting the `needsEscaping` property in your class to `false`, like so:

    protected $needsEscaping = false;

However, it is important to note that if you do this, your plugin must take responsibility for ensuring that it properly escapes its output.

Also note that setting this property will only affect the escaping of the item title. The URL will still be escaped regardless.


Digging Further
---------------

Hopfully the information given above will be enough to help you write your own plugins for HeadlineMarquee.

If you need further examples, you could look at the data generator classes that are provided as built-in options for the module. These classes are all more complex than the trivial one provided in the example.
