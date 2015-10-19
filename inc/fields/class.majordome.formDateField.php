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

class formDateField extends formField
{
    /**
     * Render the HTML of the field
     * @return string           The generated HTML
     */
    public function renderField()
    {
        $id = $this->getFieldId();

        // TODO Polyfill the field for Firefox with a pattern attribute
        return '<input type="date" id="' . $id . '" name="' . $id . '"' .
            ($this->field->required ? ' required' : '') .
        '>';
    }

    /**
     * Validate the answer to a field against the specifications of the form
     * @param mixed $answer The user's answer to the field
     * @return array   The error messages explaining the problem, if any
     */
    public function validate($answer)
    {
        $error = parent::validate($answer);

        // Date format verification
        if (!empty($answer)) {
            // FIXME The only supported pattern is currently the French date format (DD/MM/YYYY)
            $date_items = array();
            // Check if preg_match equals 0 or false: do NOT replace == with === !
            if (preg_match('/^(0?[1-9]|[12]\d|3[01])\/(0?[1-9]|1[12])\/(\d{4})$/', $answer, $date_items) == 0) {
                $error[] = sprintf(__('Please enter a valid date format (DD/MM/YYYY) in “%s”'), $this->renderLabel());
            } elseif (checkdate($date_items[2], $date_items[1], $date_items[3]) === false) {
                $error[] = sprintf(__('Please enter a valid date in “%s”'), $this->renderLabel());
            }
        }
        return $error;
    }
}