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

class formDropdownField extends formField
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
        $options = '';
        $has_a_selected_opt = false;

        foreach ($this->field->field_options->options as $num_opt => $option) {
            $options .= '<option value="' . $num_opt . '"';
            if (($option->checked && $fill === null) || ($fill !== null && ((int) $fill === $num_opt))) {
                $options .= ' selected';
                $has_a_selected_opt = true;
            }
            $options .= '>' . html::escapeHTML($option->label) . '</option>';
        }

        // Include a first blank option at the beginning if asked and if there is no option selected
        if ($this->field->field_options->include_blank_option && $has_a_selected_opt === false) {
            $options = '<option value="blank" disabled selected></option>' . $options;
        }

        return '<select id="' . $id . '" name="' . $id . '"' .
        ($this->field->required ? ' required' : '') . '>' .
            $options .
        '</select>';
    }

    /**
     * Validate the answer to a field against the specifications of the form
     * @param mixed $answer The user's answer to the field
     * @return array   The error messages explaining the problem, if any
     */
    public function validate($answer)
    {
        $error = parent::validate($answer);

        // Check if answer is in options list
        if (!empty($answer) && empty($this->field->field_options->options[$answer])) {
            $error[] = sprintf(__('Please choose a valid option in “%s”'), $this->renderLabel());
        }
        return $error;
    }
}