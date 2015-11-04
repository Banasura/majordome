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

class formMailField extends formField
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

        return '<input type="email" id="' . $id . '" name="' . $id . '"' .
            ($this->field->required ? ' required' : '') .
            ($fill !== null ? ' value="' . html::escapeHTML($fill) . '"' : '') .
        ' placeholder="contact@example.com">';
    }

    /**
     * Validate the answer to a field against the specifications of the form
     * @param mixed $answer The user's answer to the field
     * @return string   An error message explaining the problem, if any
     */
    public function validate($answer)
    {
        $error = parent::validate($answer);

        // Check the mail pattern
        if (!empty($answer) && filter_var($answer, FILTER_VALIDATE_EMAIL) === false) {
            $error[] = sprintf(__('Please enter a valid email address in field “%s”'), $this->renderLabel());
        }

        return $error;
    }
}