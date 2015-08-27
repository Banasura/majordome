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
__('Create a new form');

class newFormPage extends page
{
	function __construct($view)
	{
    	parent::__construct($view, 'newForm', 'Create a new form');
    	global $core;
    	
    	// Add Formbuilder dependencies
    	$this->view->addJs(dcPage::getPF('/majordome/vendor/vendor.js'));
    	$this->view->addJs(dcPage::getPF('/majordome/js/formbuilder/dist/formbuilder.js'));
    	// FIXME Find a proper way to inject the CSS
//     	$this->view->add_css(dcPage::getPF('/majordome/vendor/vendor.css'));
//     	$this->view->add_css(dcPage::getPF('/majordome/js/formbuilder/dist/formbuilder.css'));
		
    	// Translate the lib texts
    	$this->view->addHeader('<script>'.
    		'Formbuilder.options.dict = {' .
    			'SAVE_FORM: "' . __('Save form') . '",' .
    			'UNSAVED_CHANGES: "' . __('You have unsaved changes. If you leave this page, you will lose those changes!') . '"' .
    		'}'
    	);
    	
    	// Run Formbuilder
    	$this->view->addJs(dcPage::getPF('/majordome/js/majordome.newform.js'));
    	
    	// Handle the results if any
    	if (isset($_POST['mj_save_new_form'])) {
    		$this->saveForm();
    	}
	}
    
    public function content()
    {
    	global $core, $p_url;
        echo '<h3>', $this->title, '</h3>',
        	'<p>', __('Create a new form by entering its name and choosing its fields. You will then be able to add your brand new form wherever you want in your blog.'), '</p>',
        	'<form method="POST" id="mj_new_form" action="', $p_url, '&amp;page=', $this->id, '">',
        		$core->formNonce(),
        		'<div class="fieldset">',
        			'<h4>', __('Form options'), '</h4>',
        			'<p>',
        				'<label class="required" for="mj_form_name">',
        					'<abbr title="', __('Required field'), '">*</abbr>',
        					__('Form name'),
        				'</label>',
        				form::field('mj_form_name', 40, 50, ''),
        			'</p>',
        			'<p>',
        				'<label class="required" for="mj_form_action">',
        					'<abbr title="', __('Required field'), '">*</abbr>',
        					__('Data handling'),
        				'</label>',
        				form::combo('mj_form_action',majordome::getDataHandlerList()),
        			'</p>',
        		'</div>',
        		'<div class="fieldset">',
        			'<h4>', __('Form fields'), '</h4>',
					'<div id="newform-builder"></div>',
				'</div>',
				form::hidden('mj_form_content', ''),
				'<input type="submit" name="mj_save_new_form" id="mj_save_new_form" value="', __('Save'), '">',
			'</form>';
    }
    
    /**
     * Handle the results of the form creation
     */
    private function saveForm()
    {
    	global $core;
    	$title = null;
    	
    	// Form name check
    	if (empty($_POST['mj_form_name'])) {
    		$core->error->add(__('Please enter a form name'));
    	} elseif (strlen($_POST['mj_form_name']) > 50) {
    		$core->error->add(__('The form name is too long'));
    	} else {
    		$title = htmlentities($_POST['mj_form_name']);
    	}
    	
    	// Form data handler check
    	if (empty($_POST['mj_form_action'])) {
    		$core->error->add(__('Please choose a handler for the form results'));
    	} else if (!in_array($_POST['mj_form_action'], majordome::getDataHandlerList())) {
    		$core->error->add(__('The chosen data handler does not exists'));
    	}
    	
    	// Form fields check
    	if (empty($_POST['mj_form_content'])) {
    		$core->error->add(__('The form has no field'));
    	}

    	if ($core->error->flag() === false) {
    		// The form is valid, we store the result in the DB
    		$db =& $core->con;
    		
    		// TODO Implement save
    		
    		dcPage::addSuccessNotice(__('The form has been successfully created'));
    	}
    }
}
