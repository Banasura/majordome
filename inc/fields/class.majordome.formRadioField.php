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
 * *
 *
 * Handling of text fields
 *
 ******************************************************************************/

class formRadioField extends formField
{
    /**
     * @override
     * Render the HTML of the field
     * @param   mixed   $fill   An optional value to use in the field
     * @return string           The generated HTML
     */
    public function renderField ($fill = null)
    {
        $id = $this->getFieldId();
        $html = '';

        foreach ($this->field->field_options->options as $num_opt => $option) {
            $html .= '<label class="radio-option" for="' . $id . '-' . $num_opt . '">' .
                '<input type="radio" name="' . $id . '[opt]" id="' . $id . '-' . $num_opt . '" value="' . $num_opt .'"' .
                (($option->checked && $fill === null) || ($fill !== null && isset($fill['opt']) && ((int) $fill['opt'] === $num_opt))
                    ? ' checked' : '') .
                ($this->field->required ? ' required' : '') .
                '>' . html::escapeHTML($option->label) . '</label>';
        }

        // Include 'other' field if necessary
        if ($this->field->field_options->include_other_option) {
            $html .= '<label class="radio-option" for="' . $id . '-other">' .
                '<input type="radio" name="' . $id . '[opt]" id="' . $id . '-other" value="other"' .
                ($this->field->required ? ' required' : '') .
                (($fill !== null && isset($fill['opt']) && ((int) $fill['opt'] === $num_opt)) ? ' checked' : '') .
                '>' .
                __('Other') . '</label>' .
                '<input type="text" name="' . $id . '[other]" id="' . $id . '-other-value"' .
                (($fill !== null && !empty($fill['other'])) ? ' value="' . html::escapeHTML($fill['other']) . '"' : '') .
                '>';
        }

        return $html;
    }

    /**
     * Validate the answer to a field against the specifications of the form
     * @param mixed $answer The user's answer to the field
     * @return string   An error message explaining the problem, if any
     */
    public function validate($answer)
    {
        $error = array();
        $is_empty = !is_array($answer) || !isset($answer['opt']);

        if ($this->field->required && $is_empty) {
            $error[] = sprintf(__('Please fill in the field “%s”'), $this->renderLabel());
        }

        // Check the answer's validity
        if (!$is_empty) {
            // Check if the text field is filled if the box 'other' is checked
            if ($answer['opt'] === 'other') {
                if (empty($answer['other'])) {
                    $error[] = sprintf(__('Please fill in the “other” option in the field “%s”'), $this->renderLabel());
                }
            } else {
                // Check if the value is a real option index
                if (filter_var($answer['opt'], FILTER_VALIDATE_INT) === false || empty($this->field->field_options->options[$answer['opt']])) {
                    // Check if the answer is in the option list
                    $error[] = sprintf(__('Please choose a valid option in the field “%s”'), $this->renderLabel());
                }
            }
        }

        return $error;
    }
}