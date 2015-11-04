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

	function __construct($view)
	{
		global $core;

    	parent::__construct($view, 'edit', __('Create a new form'));

		// Check if we have an existing form to edit
		if (!empty($_GET['formid'])) {
			$form_data = majordomeDBHandler::getFormData($_GET['formid']);
			if ($form_data === false) {
				$core->error->add(__('Unable to edit the form.'));
			} else {
				$this->form_data = $form_data;
				$this->title = __(sprintf(__('Edit form “%s”'), $form_data->form_name));
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
			$this->view->addJs("dotclear.majordomeFormData = {$this->form_data->form_fields};", true);
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
				// Save the ID of the form to update
				(empty($this->form_data) ? '' : form::hidden('mj_form_id', $this->form_data->form_id)),
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
					'</p>';

					// If we are updating a form, we cannot change the handler (too complicated)
					if (empty($this->form_data)) {
						echo '<p>',
                            '<label class="required" for="mj_form_action">',
                                '<abbr title="', __('Required field'), '">*</abbr>',
                                __('Data handling'),
                            '</label>',
                            form::combo('mj_form_action', majordome::getDataHandlerList()),
						'</p>',
                        '</div>';
					} else {
						echo '</div>';
                        $handler = majordome::getHandlerOfId($this->form_data->form_handler);
						if (empty($handler)) {
							echo '<p class="error">', sprintf(__('Unknown data handler “%s”.'), $this->form_data->form_handler), '</p>';
						} else {
							$handlerOptions = $handler::getHandlerOptionPage();
							if (!empty($handlerOptions)) {
								// We display the handler's options instead
								echo '<div class="fieldset">',
								'<h4>', __('Data handling options'), '</h4>',
								$handlerOptions,
								'</div>';
							}
						}
					}
			echo '<div class="fieldset">',
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

		// Form ID check
		if (!empty($_POST['mj_form_id']) &&
			filter_var($_POST['mj_form_id'], FILTER_VALIDATE_INT) === false) {
			$core->error->add(sprintf(__('Unknown form of ID “%s”.'), $_POST['mj_form_id']));
		}

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

    	// Form data handler check, only if we are creating a new form
		if (empty($_POST['mj_form_id'])) {
			if (empty($_POST['mj_form_action'])) {
				$core->error->add(__('Please choose a handler for the form results.'));
			} else if (!in_array($_POST['mj_form_action'], majordome::getDataHandlerList())) {
				$core->error->add(__('The chosen data handler does not exists.'));
			}
		}

    	// Form fields check
    	if (empty($_POST['mj_form_content'])) {
    		$core->error->add(__('The form has no field.'));
    	}

    	if ($core->error->flag() === false) {
    		// The form is valid, we store the result in the DB

			if (empty($_POST['mj_form_id'])) {
				// We create a new form
				$success = majordomeDBHandler::insert($_POST['mj_form_name'], $_POST['mj_form_desc'], $_POST['mj_form_action'], $_POST['mj_form_content']);

				if ($success) {
					// The form has been successfully created, so we hide this page from the view
					dcPage::addSuccessNotice(__('The form has been successfully created.'));
					$this->hidden = true;
					return true;
				} else {
					$core->error->add(sprintf(__('An unknown error occurred. The form could not be created.'), html::escapeHTML($_POST['mj_form_name'])));
				}
			} else {
				// We update the existing form
				try {
					majordomeDBHandler::update($_POST['mj_form_id'], $_POST['mj_form_name'], $_POST['mj_form_desc'], $_POST['mj_form_content']);
				} catch (Exception $e) {
					$core->error->add(sprintf(__('An unknown error occurred: %s.'), $e->getMessage()));
				}

				// The form has been successfully created, so we hide this page from the view
				dcPage::addSuccessNotice(__('The form has been successfully updated.'));
				$this->hidden = true;
				return true;
			}
    	}

		if (!empty($_POST['mj_form_id'])) {
			// If there is an error during form update, we must pass the form ID again to the page
			$_GET['formid'] = $_POST['mj_form_id'];
		}

		return false;
	}
}
