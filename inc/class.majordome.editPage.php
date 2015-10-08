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

class editPage extends page
{
	/**
	 * Contains the already existing data of the form
	 * @var	object
	 */
	private $form_data;

	function __construct($view, $id = 'newForm')
	{
		global $core;

    	parent::__construct($view, $id, __('Create a new form'));

		// Check if we have an existing form to edit
		if (!empty($_POST['edit'])) {
			$form_ids = array_keys($_POST['edit']);
			$form_data = majordomeDBHandler::getFormData($form_ids[0]);
			if ($form_data === false) {
				$core->error->add(__('Unable to edit the form.'));
			} else {
				$this->form_data = $form_data;
			}
		}

		// Do we have to trigger form registration?
		$this->resultSaved = true;

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
    			'UNITS: "' . __('Units') . '",' .
    			'DUPLICATE_FIELD: "' . __('Duplicate field') . '",' .
    			'REMOVE_FIELD: "' . __('Remove field') . '",' .
    			'REMOVE_OPTION: "' . __('Remove option') . '",' .
    			'RESET: "' . __('Reset') . '",' .
    			'SUBMIT: "' . __('Submit') . '"' .
    		'};',
    	true);

		// Load the form fields if any
		if (!empty($this->form_data)) {
			$this->view->addJs("dotclear.majordomeFormData = '{$this->form_data->form_fields}';", true);
		}

    	// Run Formbuilder
    	$this->view->addJs(dcPage::getPF('/majordome/js/majordome.newform.js'));

    	// Handle the results if any
    	if (isset($_POST['mj_save_new_form'])) {
    		$this->resultSaved = $this->saveForm();
    	}
	}

    public function content()
    {
    	global $core, $p_url;
		$title = '';
		$desc = '';


		// We retrieve the informations already entered if the save failed
		if ($this->resultSaved === false) {
			$title = empty($_POST['mj_form_name']) ? '' : html::escapeHTML($_POST['mj_form_name']);
			$desc = empty($_POST['mj_form_desc']) ? '' : html::escapeHTML($_POST['mj_form_desc']);
		} elseif (!empty($this->form_data)) {
			// We fill in the existing data if any
			$title = html::escapeHTML($this->form_data->form_name);
			$desc = html::escapeHTML($this->form_data->form_desc);
		}

		echo '<h3>', $this->title, '</h3>',
        	'<form method="POST" id="mj_new_form" action="', $p_url, '&amp;page=', $this->id, '">',
        		$core->formNonce(),
        		'<div class="fieldset">',
        			'<h4>', __('Form options'), '</h4>',
        			'<p>',
        				'<label class="required" for="mj_form_name">',
        					'<abbr title="', __('Required field'), '">*</abbr>',
        					__('Form name'),
        				'</label>',
        				form::field('mj_form_name', 50, 50, $title),
        			'</p>',
        			'<p class="form-note">',
        				__('The form name must not exceed 50 characters.'),
        			'</p>',

        			'<p>',
        				'<label for="mj_form_desc">',
        					__('Form description'),
        				'</label>',
						form::textarea('mj_form_desc', 50, 5, $desc, NULL, NULL, false, 'maxlength="250"'),
        			'</p>',
					'<p class="form-note">',
					__('The form description must not exceed 250 characters.'),
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
    		$core->error->add(__('Please enter a form name.'));
    	} elseif (strlen($_POST['mj_form_name']) > 50) {
    		$core->error->add(__('The form name is too long.'));
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
    		$success = majordomeDBHandler::insert($_POST['mj_form_name'], $_POST['mj_form_desc'], $_POST['mj_form_action'], $_POST['mj_form_content']);

    		if ($success) {
    			dcPage::addSuccessNotice(__('The form has been successfully created.'));
				return true;
    		} else {
    			$core->error->add(sprintf(__('The form “%s” already exists. Please choose another name or edit the existing form.'), html::escapeHTML($_POST['mj_form_name'])));
    		}
    	}

		return false;
	}
}
