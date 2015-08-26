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

class view
{
    /**
     * The list of the different pages
     * @var array
     */
    private $pages;

    /**
     * The default page to display
     * @var view
     */
    private $home;
    
    /**
     * The id of the current page displayed
     * @var string
     */
    public $current;
    
    /**
     * List of the different Js to include ot the page
     * @var array
     */
    private $js;
    
	function __construct()
	{
		$this->pages = array();
		$this->home = null;
		$this->current = isset($_GET['page']) ? $_GET['page'] : 'home';
		$this->js = array();
	}

    /**
     * Display the page including the givent content.
     * @method display
     * @param  string  $content The content of the page, added in the <body> tag
     * @return void
     */
    public function display()
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
        foreach ($this->pages as $id_page => $page) {
           	// Remove the link and only keep the tab
           	echo '<div class="multi-part" id="', $id_page, '" title="', $page->title, '">',
           			$page->content(),
           		'</div>';
        }

        echo	'</body>',
            '</html>';
    }
    
    public function add_js($path)
    {
    	$this->js[] = $path;
    }

    /**
     * Register a new page to be displayed. The page must be a subclass of
     * "view".
     * @method register
     * @param  view     $page_class     The class to be added
     * @param  boolean  $default        Use this page as default
     * @return void
     */
    public function register($page_class, $default = false)
    {
        if (!is_subclass_of($page_class, 'page')) {
            throw new InvalidArgumentException('Argument does not extend page.');
        }

        $page = new $page_class($this);

        if (empty($page->id)) {
            throw new InvalidArgumentException('Given class "' . $page_class . '" does not have a unique identifier: attribute "id" is missing.');
        }
        
        $this->pages[$page->id] = $page;
        if ($default === true) $this->home = $page;
    }
}
