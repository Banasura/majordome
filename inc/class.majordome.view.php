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
     * The id of the current page displayed
     * @var string
     */
    public $current;
    
    /**
     * List of the different Js to include ot the page
     * @var array
     */
    private $js;
    
    /**
     * List of the different CSS to include ot the page
     * @var array
     */
    private $css;
    
	function __construct()
	{
		$this->pages = array();
		$this->current = isset($_GET['page']) ? $_GET['page'] : 'home';
		$this->js = array();
		$this->css = array();
	}

    /**
     * Display the page including the givent content.
     * @method display
     * @param  string  $content The content of the page, added in the <body> tag
     * @return void
     */
    public function display()
    {
    	global $p_url;

        echo '<!doctype html>',
        	'<html>',
                '<head>',
                	dcPage::jsPageTabs($this->current);
        
			        // Add the CSS files
			        foreach ($this->css as $key => $file) {
			        	echo '<link rel="stylesheet" type="text/css" href="', $file, '"/>';
			        }
        
       echo	        '<title>Majordome</title>',
                '</head>',
                '<body>',
                    dcPage::breadcrumb(array(__('Plugins') => '', 'Majordome' => '')),
                    dcPage::notices();

			        // Display the tabs
			        foreach ($this->pages as $id_page => $page) {
			           	echo '<div class="multi-part" id="', $id_page, '" title="', $page->title, '">';
			           			$page->content();
			           		echo '</div>';
			        }
			        
			        // Add the JS files
			   		echo implode('', $this->js),
        		'</body>',
            '</html>';
    }
    
    /**
     * Add a CSS file to the page.
     * @param string $path	The path to the file
     * @return void
     */
    public function addCss($path)
    {
    	// Remove backslashes if any and replace them by slashes
    	$this->css[] = str_replace("\\", '/', $path);
    }
    
    /**
     * Add a Javascript file to the page.
     * @param string $path	The path to the file or its content if $inline is set to true
     * @param boolean $inline Is it an inline script?
     * @return void
     */
    public function addJs($path, $inline = false)
    {
    	$this->js[] = $inline 	? '<script>' . $path . '</script>'
    							: '<script src="' . $path . '" type="text/javascript"></script>';
    }
    
    /**
     * Change the page to display by default
     * @param unknown $id	The ID to display
     */
    public function setCurrent($id) {
    	$this->current = $id;
    }

    /**
     * Display the given page if available
     * @param $page_name    The page name, found in the URL
     */
    public function showPage($page_name = null)
    {
        if (empty($page_name)) return;

        if (empty($this->pages[$page_name])) {
            // The page is not already added to the view: we add it
            try {
                $page = $this->register($page_name . 'Page');
            } catch (Exception $e) {
                // The page does not exist: we display the default page
                return;
            }
            $this->current = $page->id;
        }
    }
    

    /**
     * Register a new page to be displayed. The page must be a subclass of
     * "view".
     * @method register
     * @param  view     $page_class, ...    The class to be added,
     *                                      followed by the params to pass to the constructor
     * @return page						    The instance newly created
     */
    public function register($page_class)
    {
    	$page = null;
    	
        if (!is_subclass_of($page_class, 'page')) {
            throw new InvalidArgumentException('Argument does not extend page.');
        }
		
        if (func_num_args() > 1) {
            // Call the constructor with the additional parameters
            $reflect  = new ReflectionClass($page_class);
            $args = func_get_args();
            $args[0] = $this; // replace the first arg by the right one
            $page = $reflect->newInstanceArgs($args);
        } else {
	        $page = new $page_class($this);
        }

        if (empty($page->id)) {
            throw new InvalidArgumentException('Given class "' . $page_class . '" does not have a unique identifier: attribute "id" is missing.');
        }
        
        $this->pages[$page->id] = $page;
        return $page;
    }
}
