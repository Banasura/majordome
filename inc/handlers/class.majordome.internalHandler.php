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
* Store the answers to a form in an internal database.
*
******************************************************************************/

class internalHandler extends majordomeDataHandler {
    private static $table_name = DC_DBPREFIX . 'mj_storage';
    
    /**
     * Returns a string identifying this handler
     */
    function getHandlerId ()
    {
        return 'internal';
    }
    
    /**
     * Returns the name of this handler
     */
    function getHandlerName ()
    {
        return __('Internal storage');
    }
    
    /**
     * Tell if this handler can display the list of existing answers
     * to a form
     */
    function hasAnAnswerPage ()
    {
        return true;
    }
    
    /**
     * Provides the HTML code to display in the configuration page of
     * the handler
     */
    function getHandlerOptionPage ()
    {
        // There is no option yet
        return '';
    }
    
    /**
     * Display the page showing the answers to a form
     * @param   int $form_data    The form's schema of which display the answers
     */
    function displayHandlerAnswerPage ($form_data)
    {
        if (empty($form_data->form_id) || filter_var($form_data->form_id, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('Cannot display the answers. Invalid or missing form ID.');
        }
        
        global $core;
		$db =& $core->con;
        
		$list = $db->select('SELECT `answer_id`, `answer` FROM ' . self::$table_name
            . ' WHERE `form_id` = ' . $form_data->form_id);
        
        $form_content = json_decode($form_data->form_fields)->fields;
        
        if (empty($list->answer)) {
            echo '<p class="info">',
                __('There is no answer to this form yet.'),
            '</p>';
            return;
        }
        
        echo '<table>',
            '<thead>',
                '<th>', __('Num'), '</th>';
                foreach ($form_content as $field) {
                    echo '<th>', html::escapeHTML($field->label), '</th>';
                }
                unset($field); // Remove this unused var
        echo '</thead>',
            '<tbody>';
                // Loop over each existing answer
                foreach ($list as $answer) {
                    $answer_entries = json_decode($answer->answer);
                    echo '<td>', $answer->answer_id, '</td>';
                    // Loop over each field of the answer
                    foreach ($form_content as $field) {
                        $answer_content = $answer_entries->{$field->cid};
                        echo '<td>', 
                            (empty($answer_content)
                            ? '<em>' . __('empty') . '</em>'
                            : html::escapeHTML($answer_content)),
                        '</td>';
                    }
                }
        echo '</tbody>',
        '</table>';
    }
    
    /**
     * Validate the options chosen by the user in the handler option
     * page.
     * @return boolean  true if the options are valid, false elsewhere
     */
    function areHandlerOptionsValid ()
    {
        // There is no option yet
        return true;
    }
    
    /**
     * Save the handler's options chosen by the user
     */
    function saveHandlerOptions ()
    {
        // There is no option yet
        return;
    }
    
    /**
     * Save an answer to a form
     * @param   array   $form_data  The form's schema
     * @param   array   $answer     The answers to the form, of the form
     *                                  field_id -> field_response
     */
    function saveAnswer ($form_data, $answer)
    {
        global $core;
        
        /* For now, the answers are serialized under a JSON format, to
         * reduce the amount of rows in the database, we will see later
         * if it is a good idea
         */
         
        $bundle = json_encode($answer);
		$id = 0;
		$db =& $core->con;
		$dataSet = $db->openCursor(DC_DBPREFIX . 'mj_storage');
		
		// Look for the last form ID
		$res = $db->select('SELECT MAX(answer_id) as lastid FROM ' . self::$table_name .
            ' WHERE form_id = ' . ((int) $form_data->form_id) . ';');

		if (!$res->isEmpty()) {
			// We use the next id, which should be available
			$id = $res->lastid + 1;
		}

		$dataSet->form_id = $form_data->form_id;
		$dataSet->answer_id = $id;
		$dataSet->answer = $bundle;
		return $dataSet->insert();		
    }
	
}
