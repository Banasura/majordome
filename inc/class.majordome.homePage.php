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
__('My forms');

class homePage extends page
{
	function __construct($view)
	{
    	parent::__construct($view, 'home', 'My forms');
    	$this->view->addCss(dcPage::getPF('majordome/css/admin.css'));
    	
    	// Handle form operations
    	if (!empty($_POST['delete'])) {
    		$this->deleteForm();
    	} elseif (!empty($_POST['edit'])) {
    		// Add a new specific page to the view, and pass the form ID as parameter
    		$form_ids = array_keys($_POST['edit']);
    		$page = $this->view->register('editFormPage', $form_ids[0]);
    		$this->view->setCurrent($page->id);
    	}
	}

    public function content()
    {
        global $p_url, $core;
        $form_list = majordomeDBHandler::getFormList();

        echo '<h3 class="no-margin">', $this->title, '</h3>',
            '<p class="right"><a class="button add" href="#newForm">', __('Create a form'), '</a></p>',

            // Display the current form list
            '<form method="POST" action="', $p_url, '&amp;page=', $this->id, '">',
            	$core->formNonce(),
	            '<table>',
	            	'<thead>',
	            		'<th>', __('Name'), '</th>',
	            		'<th class="nowrap">', __('Handler'), '</th>',
	            		'<th class="nowrap">', __('Action'), '</th>',
	            	'</thead>',
	            	'<tbody>';
	        
				        foreach($form_list as $key => $form)
				        {
				        	echo '<tr>',
				        			'<td class="maximal">', $form->name, '</td>',
				        			'<td class="nowrap">', $form->handler, '</td>',
				        			'<td class="module-actions nowrap">',
				        				'<input class="button" type="submit" name="edit[', $form->name, ']" value="', __('Edit'), '"> ',
				        				'<input class="delete" type="submit" name="delete[', $form->name, ']" value="', __('Delete'), '">',
				        			'</td>',
				        		'</tr>';
				        }
	            		
	
	            	echo '</tbody>',
	            '</table>',
			'</form>';
    }
    
    /**
     * Delete a form
     */
    private function deleteForm()
    {
    	global $core;
    	$success = null;
    	
    	try {
	    	foreach($_POST['delete'] as $form_id => $v) {
	    		$success = $success || majordomeDBHandler::delete($form_id);
	    	}
    	} catch (InvalidArgumentException $e) {
    		dcPage::addErrorNotice(__('Unable to delete the form: unknown form identifier'));
    		return;
    	}
    	
    	if ($success) {
    		dcPage::addSuccessNotice(__('The form has been successfully deleted'));
    	} else {
    		$core->error->add(__('Unable to delete the form'));
    	}
    }
}
