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
    	$this->view->addJs(dcPage::getPF('/majordome/js/vendor.js'));
    	$this->view->addJs(dcPage::getPF('/majordome/js/formbuilder/dist/formbuilder.js'));
    	$this->view->addCss(dcPage::getPF('majordome/js/formbuilder/dist/formbuilder.css'));

    	// Translate the lib texts
    	$this->view->addJs(
    		'Formbuilder.options.dict = {' .
    			'SAVE_FORM: "' . __('Save form') . '",' .
    			'UNSAVED_CHANGES: "' . __('You have unsaved changes. If you leave this page, you will lose those changes!') . '",' .
    			'NO_RESPONSE_FIELDS: "' . __('No response fields') . '",' .
    			'ADD_NEW_FIELD: "' . __('Add new field') . '",' .
    			'EDIT_FIELD: "' . __('Edit field') . '",' .
    			'DROPDOWN: "' . __('Dropdown') . '",' .
    			'EMAIL: "' . __('Email') . '",' .
    			'NUMBER: "' . __('Number') . '",' .
    			'PARAGRAPH: "' . __('Paragraph') . '",' .
    			'PRICE: "' . __('Price') . '",' .
    			'MULTIPLE_CHOICE: "' . __('Multiple choice') . '",' .
    			'SECTION_BREAK: "' . __('Section break') . '",' .
    			'TEXT: "' . __('Text') . '",' .
    			'TIME: "' . __('Time') . '",' .
    			'WEBSITE: "' . __('Website') . '",' .
    			'CHECKBOXES: "' . __('Checkboxes') . '",' .
    			'DATE: "' . __('Date') . '",' .
    			'ADDRESS: "' . __('Address') . '",' .
    			'ADD_A_LONGER_DESCRIPTION_TO_THIS_FIELD: "' . __('Add a longer description to this field') . '",' .
    			'LABEL: "' . __('Label') . '",' .
    			'INTEGER_ONLY: "' . __('Integer only') . '",' .
    			'ONLY_ACCEPT_INTEGERS: "' . __('Only accept integers') . '",' .
    			'REQUIRED: "' . __('Required') . '",' .
    			'MINIMUM_MAXIMUM: "' . __('Minimum / Maximum') . '",' .
    			'ABOVE: "' . __('Above') . '",' .
    			'BELOW: "' . __('Below') . '",' .
    			'LENGTH_LIMIT: "' . __('Length limit') . '",' .
    			'MIN: "' . __('Min') . '",' .
    			'MAX: "' . __('Max') . '",' .
    			'CHARACTERS: "' . __('characters') . '",' .
    			'WORDS: "' . __('words') . '",' .
    			'OPTIONS: "' . __('Options') . '",' .
    			'INCLUDE_BLANK: "' . __('Include blank') . '",' .
    			'INCLUDE_OTHER: "' . __('Include \"other\"') . '",' .
    			'ADD_OPTION: "' . __('Add option') . '",' .
    			'SIZE: "' . __('Size') . '",' .
    			'SMALL: "' . __('Small') . '",' .
    			'MEDIUM: "' . __('Medium') . '",' .
    			'LARGE: "' . __('Large') . '",' .
    			'UNITS: "' . __('Units') . '",' .
    			'DUPLICATE_FIELD: "' . __('Duplicate field') . '",' .
    			'REMOVE_FIELD: "' . __('Remove field') . '",' .
    			'REMOVE_OPTION: "' . __('Remove option') . '"' .
    		'};' .
    		'Formbuilder.options.mappings.LABEL = "' . __('Untitled') . '";',
    	true);

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
        					__('Form identifier'),
        				'</label>',
        				form::field('mj_form_name', 50, 50, ''),
        			'</p>',
        			'<p class="form-note">',
        				__('This field will allow you to identify your form later. Only use alphanumeric characters (a-zA-Z0-9) or dashes.'),
        			'</p>',

        			'<p>',
        				'<label for="mj_form_desc">',
        					__('Form description'),
        				'</label>',
        				form::field('mj_form_desc', 50, 255, ''),
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

    	// Form name check
    	if (empty($_POST['mj_form_name'])) {
    		$core->error->add(__('Please enter a form identifier.'));
    	} elseif (strlen($_POST['mj_form_name']) > 50) {
    		$core->error->add(__('The form identifier is too long.'));
    	} elseif (preg_match('/^[-_a-zA-Z0-9]+$/', $_POST['mj_form_name']) !== 1) {
			$core->error->add(__('The form identifier must contain only alphanumeric or dashes characters.'));
		}

    	// Form description check
    	if (!empty($_POST['mj_form_desc']) && strlen($_POST['mj_form_desc']) > 250) {
    		$core->error->add(__('The form description is too long.'));
    	}

    	// Form data handler check
    	if (empty($_POST['mj_form_action'])) {
    		$core->error->add(__('Please choose a handler for the form results.'));
    	} else if (!in_array($_POST['mj_form_action'], majordome::getDataHandlerList())) {
    		$core->error->add(__('The chosen data handler does not exists.'));
    	}

    	// Form fields check
    	if (empty($_POST['mj_form_content'])) {
    		$core->error->add(__('The form has no field.'));
    	}

    	if ($core->error->flag() === false) {
    		// The form is valid, we store the result in the DB
    		$db =& $core->con;
    		$success = majordomeDBHandler::insert($_POST['mj_form_name'], $_POST['mj_form_desc'], $_POST['mj_form_action'], $_POST['mj_form_content']);

    		if ($success) {
    			dcPage::addSuccessNotice(__('The form has been successfully created.'));
    		} else {
    			$core->error->add(sprintf(__('The form “%s” already exists. Please choose another name or edit the existing form.'), html::escapeHTML($_POST['mj_form_name'])));
    		}
    	}
    }
}
