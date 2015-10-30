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
 **
 * 
 * Display the existing answers to a form
 * 
 ******************************************************************************/

class answerPage extends page
{
	/**
	 * Contains the handler class of the form
	 * @var	object
	 */
	private $handler;
    
    /**
     * Contains the data of the form of which we show the answers
     */
    private $form_data;

	function __construct($view)
	{
		global $core;
        
    	parent::__construct($view, 'answer', 'Answers');
        
        // Load the form data
        $error = false;
		if (!empty($_GET['formid'])) {
			$form_data = majordomeDBHandler::getFormData($_GET['formid']);
			if ($form_data === false) {
				$core->error->add(__('Unable to find the form ' . html::escapeHTML($_GET['formid']) . '.'));
			} else {
				$this->form_data = $form_data;
				$this->title = __(sprintf(__('Answers to “%s”'), $form_data->form_name));
                
                // Find the right handler
                $this->handler = majordome::getHandlerOfId($this->form_data->form_handler);
			}
		} else {
            $core->error->add(__('Unable to show the answers: no form given.'));
        }
	}

    public function content()
    {
    	global $core, $p_url;
        $h =& $this->handler;
        
        if (empty($this->form_data) || empty($this->handler)) return;

        echo '<h3>', $this->title, '</h3>';
	
	if ($h::hasAnAnswerPage()) {
            $h::displayHandlerAnswerPage($this->form_data);
	} else {
            echo '<p class="warn">',
		__('The answer handler of this form does not allow you to see the existing answers.'),
		'</p>';
	}
    }
}
