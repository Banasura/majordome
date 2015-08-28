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
    
    /**
     * Custom header to inject in the view
     * @var unknown
     */
    private $header;
    
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

        echo '<html>',
                '<head>',
                	dcPage::jsPageTabs($this->current);
        
			        // Add the CSS files
			        foreach ($this->css as $key => $file) {
			        	echo '<link rel="stylesheet" type="text/css" href="', $file, '">';
			        }
        
       echo	        '<title>Majordome</title>',
       				$this->header,
                '</head>',
                '<body>',
                    dcPage::breadcrumb(array(__('Plugins') => '', 'Majordome' => ''));

			        // Display the tabs
			        foreach ($this->pages as $id_page => $page) {
			           	echo '<div class="multi-part" id="', $id_page, '" title="', $page->title, '">';
			           			$page->content();
			           		echo '</div>';
			        }
			        
			        // Add the JS files
			        foreach ($this->js as $key => $file) {
			        	echo '<script src="', $file, '"></script>';
			        }

        echo	'</body>',
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
     * @param string $path	The path to the file
     * @return void
     */
    public function addJs($path)
    {
    	// Remove backslashes if any and replace them by slashes
    	$this->js[] = str_replace("\\", '/', $path);
    }
    
    /**
     * Inject an HTML string into the <head> tag of the page
     * @param string $html The string to inject
     * @return void
     */
    public function addHeader($html)
    {
    	$this->header .= $html;
    }
    
    /**
     * Change the page to display by default
     * @param unknown $id	The ID to display
     */
    public function setCurrent($id) {
    	$this->current = $id;
    }
    

    /**
     * Register a new page to be displayed. The page must be a subclass of
     * "view".
     * @method register
     * @param  view     $page_class     The class to be added
     * @param  mixed	$params			An additional parameter to pass to the constructor
     * @return page						The instance newly created
     */
    public function register($page_class, $params = null)
    {
    	$page = null;
    	
        if (!is_subclass_of($page_class, 'page')) {
            throw new InvalidArgumentException('Argument does not extend page.');
        }
		
        if (empty($params)) {
	        $page = new $page_class($this);
        } else {
	        $page = new $page_class($this, $params);
        }

        if (empty($page->id)) {
            throw new InvalidArgumentException('Given class "' . $page_class . '" does not have a unique identifier: attribute "id" is missing.');
        }
        
        $this->pages[$page->id] = $page;
        return $page;
    }
}
