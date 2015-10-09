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

class formNumberField extends formField
{
    /**
     * Render the HTML of the field
     * @return string           The generated HTML
     */
    public function renderField()
    {
        $id = $this->getFieldId();

        return '<input type="number" id="' . $id . '" name="' . $id . '"' .
            ($this->field->required ? ' required' : '') .
            (empty($this->field->field_options->min)
                ? ''
                : ' min="' . $this->field->field_options->min . '"') .
            (empty($this->field->field_options->max)
                ? ''
                : ' max="' . $this->field->field_options->max . '"') .
            (empty($this->field->field_options->integer_only) || $this->field->field_options->integer_only === false
                ? ' step="0.1"'
                : ' step="1"') .
        '>' .
        (empty($this->field->field_options->units)
            ? ''
            : html::escapeHTML($this->field->field_options->units));
    }

    /**
     * Validate the answer to a field against the specifications of the form
     * @param mixed $answer The user's answer to the field
     * @return string   An error message explaining the problem, if any
     */
    public function validate($answer)
    {
        $error = parent::validate($answer);

        // Check the number format
        if (empty($error)) {
            $error = array();

            // If the number must be an integer
            if ($this->field->field_options->integer_only && filter_var($answer, FILTER_VALIDATE_INT) === false) {
                $error[] = sprintf(__('Please enter an integer number in the field “%s”'), $this->renderLabel());
            } elseif (filter_var($answer, FILTER_VALIDATE_FLOAT) === false) {
                $error[] = sprintf(__('Please enter a decimal number in the field “%s”'), $this->renderLabel());
            }

            // Minimum/Maximum
            if (!empty($this->field->field_options->min) && ($answer < $this->field->field_options->min)) {
                $error[] = sprintf(__('Please enter a number above %s in the field “%s”'), $this->field->field_options->min, $this->renderLabel());
            }

            if (!empty($this->field->field_options->max) && ($answer > $this->field->field_options->max)) {
                $error[] = sprintf(__('Please enter a number under %s in the field “%s”'), $this->field->field_options->max, $this->renderLabel());
            }
        }

        return $error;
    }
}