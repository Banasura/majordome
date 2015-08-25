<?php

/*******************************************************************************
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 William Hiver
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 ******************************************************************************/

abstract class view
{
    /**
     * The list of the different pages
     * @var array
     */
    public static $pages = array();

    /**
     * The default page to display
     * @var view
     */
    public static $home = null;
    
    /**
     * The unique identifier of the page
     * @var string
     */
    public $id = null;

    /**
     * The title of the page displayed by the current view
     * @var string
     */
    public $title;
    
    /**
     * Returns the HTML content of the current page
     * @method render
     * @return string   the HTML content of the page
     */
    abstract public function content();

    /**
     * Display the page including the givent content.
     * @method display
     * @param  string  $content The content of the page, added in the <body> tag
     * @return void
     */
    static public function display()
    {
    	global $p_url, $default_tab;
    	
    	// TODO Find a way to use self::id to get the current id & not those from parent

        echo '<html>',
                '<head>',
        	        '<title>Majordome</title>',
                    dcPage::jsPageTabs($default_tab),
                '</head>',
                '<body>',
                    dcPage::breadcrumb(array(__('Plugins') => '', 'Majordome' => ''));

        // Display the tabs
        foreach (self::$pages as $id_page => $page) {
           	// Remove the link and only keep the tab
           	echo '<div class="multi-part" id="', $id_page, '" title="', $page->title, '">',
           			$page->content(),
           		'</div>';
        }

        echo	'</body>',
            '</html>';
    }

    /**
     * Returns an instance of the view corresponding to the page id.
     * @method getPage
     * @param  string  $page_id The id of the page (givent in the URL)
     * @return view             An instance of the view handling this page
     */
    public static function getPage($page_id)
    {
        if (isset(self::$pages[$page_id])) return self::$pages[$page_id];
        else {
            // Return the default page
            if (self::$home !== null) return self::$home;
            else {
                throw new Exception('No page to display.');
            }
        }
    }

    /**
     * Register a new page to be displayed. The page must be a subclass of
     * "view".
     * @method register
     * @param  view     $view_class     The class to be added
     * @param  boolean  $default        Use this page as default
     * @return void
     */
    public static function register($view_class, $default = false)
    {
        if (!is_subclass_of($view_class, __CLASS__)) {
            throw new InvalidArgumentException('Argument does not extend view.');
        }

        if (!property_exists ($view_class, 'id')) {
            throw new InvalidArgumentException('Given class does not have a unique identifier: attribute "id" is missing.');
        }

        $page = new $view_class();
        self::$pages[$page->id] = $page;
        if ($default === true) self::$home = $page;
    }
}
