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

class internalHandler implements majordomeDataHandler {
    private static $table_name = 'mj_storage';
    private static $display_field_blacklist = array(
        'reset',
        'submit',
        'captcha'
    );
    
    /**
     * Returns a string identifying this handler
     */
    static function getHandlerId ()
    {
        return 'internal';
    }
    
    /**
     * Returns the name of this handler
     */
    static function getHandlerName ()
    {
        return __('Internal storage');
    }
    
    /**
     * Tell if this handler can display the list of existing answers
     * to a form
     */
    static function hasAnAnswerPage ()
    {
        return true;
    }
    
    /**
     * Provides the HTML code to display in the configuration page of
     * the handler
     */
    static function getHandlerOptionPage ()
    {
        // There is no option yet
        return '';
    }
    
    /**
     * Display the page showing the answers to a form
     * @param   int $form_data    The form's schema of which display the answers
     */
    static function displayHandlerAnswerPage ($form_data)
    {
        if (empty($form_data->form_id) || filter_var($form_data->form_id, FILTER_VALIDATE_INT) === false) {
            throw new InvalidArgumentException('Cannot display the answers. Invalid or missing form ID.');
        }
        
        global $core, $p_url;
		$db =& $core->con;

        // Delete answers if needed
        if (isset($_POST['delete-answers'])) {
            self::deleteAnswers($form_data->form_id, $_POST['delete-answer']);
            // Redirect the page to avoid multiple deletions
            header('Location: ' . $p_url . '&page=answer&formid=' . $form_data->form_id);
        }
        
		$list = $db->select('SELECT `answer_id`, `answer` FROM ' . DC_DBPREFIX . self::$table_name
            . ' WHERE `form_id` = ' . $form_data->form_id);
        
        $form_content = json_decode($form_data->form_fields)->fields;
        
        if (empty($list->answer)) {
            echo '<p class="info">',
                __('There is no answer to this form yet.'),
            '</p>';
            return;
        }
        
        echo '<form method="POST" action="', $p_url, '&amp;page=answer&amp;formid=', $form_data->form_id, '">',
            $core->formNonce(),
            '<table>',
                '<thead>',
                    '<th></th>',
                    '<th>', __('Num'), '</th>';
                foreach ($form_content as $field) {
                    if (!in_array($field->field_type, self::$display_field_blacklist)) {
                        echo '<th>', html::escapeHTML($field->label), '</th>';
                    }
                }
                unset($field); // Remove this unused var
        echo '</thead>',
            '<tbody>';
                // Loop over each existing answer
                foreach ($list as $answer) {
                    $answer_entries = json_decode($answer->answer);
                    echo '<tr>',
                        '<td><input type="checkbox" name="delete-answer[]" value="', $answer->answer_id, '"></td>',
                        '<td>', $answer->answer_id, '</td>';
                    // Loop over each field of the answer
                    foreach ($form_content as $field) {
                        if (!in_array($field->field_type, self::$display_field_blacklist)) {
                            $answer_content = $answer_entries->{$field->cid};
                            $answer_string = self::serializeAnswer($answer_content, $field);
                            echo '<td>',
                            ((!isset($answer_string) || $answer_string === '')
                                ? '<em>' . __('unknown') . '</em>'
                                : $answer_string),
                            '</td>';
                        }
                    }
                    echo '</tr>';
                }
        echo '</tbody>',
            '</table>',
            '<input class="delete" type="submit" name="delete-answers" value="', __('Delete selected answers'), '">',
        '</form>';
    }

    /**
     * Give a literal representation of an answer corresponding to the type of
     * field
     * @param mixed     $answer       The answer given to the field
     * @param string    $field        The field's schema
     */
    private static function serializeAnswer ($answer, $field)
    {
        switch ($field->field_type) {
            case 'radio':
                if (isset($answer)) {
                    if (isset($answer->opt)) {
                        if ($answer->opt === 'other' && isset($answer->other)) {
                            return html::escapeHTML($answer->other);
                        } elseif (isset($field->field_options->options[$answer->opt])) {
                            return html::escapeHTML($field->field_options->options[$answer->opt]->label);
                        }
                    }
                }
                return null;
                break;

            case 'checkboxes':
                $res_string = null;

                if (isset($answer)) {
                    $res_string = array();
                    // Add each checked option in the array
                    foreach ($answer as $key => $opt) {
                        if ($key === 'other') {
                            $res_string[] = __('other: ') .
                                (isset($answer['other-value'])
                                ? html::escapeHTML($answer['other-value'])
                                : __('empty'));
                        } elseif ($key !== 'other-value') {
                            $res_string[] = $field->field_options->options[$opt]->label;
                        }
                    }
                    // Merge the array in a single string
                    $res_string = implode(', ', $res_string);
                }
                return $res_string;
                break;

            case 'dropdown':
                if (isset($answer) && isset($field->field_options->options[$answer])) {
                    return $field->field_options->options[$answer]->label;
                } else {
                    return null;
                }
                break;

            default:
                return is_string($answer) ? html::escapeHTML($answer) : null;
                break;
        }
    }
    
    /**
     * Validate the options chosen by the user in the handler option
     * page.
     * @return boolean  true if the options are valid, false elsewhere
     */
    static function areHandlerOptionsValid ()
    {
        // There is no option yet
        return true;
    }
    
    /**
     * Save the handler's options chosen by the user
     */
    static function saveHandlerOptions ()
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
    static function saveAnswer ($form_data, $answer)
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
		$res = $db->select('SELECT MAX(answer_id) as lastid FROM ' . DC_DBPREFIX . self::$table_name .
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

    /**
     * Delete answers of a form
     * @param int   $form   The form's ID
     * @param array $ids    The answers' IDs to delete
     */
    private static function deleteAnswers ($form_id, $ids)
    {
        global $core;

        if (empty($ids) || !is_array($ids) || $ids != array_filter($ids, 'is_numeric')) {
            return false;
        } elseif (!is_int($form_id)) {
            $form_id = (int) $form_id;
        }

        $db =& $core->con;
        $db->execute('DELETE FROM ' . DC_DBPREFIX . 'mj_storage WHERE form_id = ' . $form_id .
            ' AND answer_id IN (' . implode(',', $ids) . ');');
        return $db->changes() > 0;
    }
	
}
