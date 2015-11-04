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
 * Handling of checkboxes
 *
 ******************************************************************************/

class formCheckboxField extends formField
{
    /**
     * @override
     * Render the HTML of the field
     * @param   mixed   $fill   An optional value to use in the field
     * @return string           The generated HTML
     */
    public function renderField($fill = null)
    {
        $id = $this->getFieldId();
        $html = '';

        foreach ($this->field->field_options->options as $num_opt => $option) {
            $html .= '<input type="checkbox" name="' . $id . '[]" id="' . $id . '-' . $num_opt . '" value="' . $num_opt .'"' .
                (($option->checked && $fill === null) || ($fill !== null && in_array($num_opt.'', $fill)) ? ' checked' : '') .
                '><label for="' . $id . '-' . $num_opt . '">' . html::escapeHTML($option->label) . '</label>';
        }

        // Include 'other' field if necessary
        if ($this->field->field_options->include_other_option) {
            $html .= '<input type="checkbox" name="' . $id . '[other]" id="' . $id . '-other" ' .
                (($fill !== null && !empty($fill['other'])) ? 'checked' : '') .
                '>' .
                '<label for="' . $id . '-other">' . __('Other') . '</label>' .
                '<input type="text" name="' . $id . '[other-value]" id="' . $id . '-other-value" ' .
                (($fill !== null && !empty($fill['other-value'])) ? 'value="' . html::escapeHTML($fill['other-value']) . '"' : '') .
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
        $error = parent::validate($answer);

        if (!empty($answer)) {
            // Check if the text field is filled if the box 'other' is checked
            if (!empty($answer['other']) && empty($answer['other-value'])) {
                $error[] = sprintf(__('Please fill in the “other” option in the field “%s”'), $this->renderLabel());
            }

            // Check if the answers are in the option values
            foreach($answer as $optId => $value) {
                if (empty($this->field->field_options->options[$value]) && $optId !== 'other-value' && $optId !== 'other') {
                    $error[] = sprintf(__('Please choose only possible answers in “%s”'), $this->renderLabel());
                }
            }
        }

        return $error;
    }


}