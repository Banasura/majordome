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
 * Handling of submit buttons
 *
 ******************************************************************************/

class formSubmitField extends formField
{
    /**
     * Constructor
     * @param $field_content    The schema of this field
     */
    public function __construct($field_content)
    {
        parent::__construct($field_content);

        // We don't keep this field in the answer
        $this->saveAnswer = false;
    }

    /**
     * @override
     * Render the HTML of the field
     * @param   mixed   $fill   An optional value to use in the field
     * @return string           The generated HTML
     */
    public function renderField($fill = null)
    {
        $id = $this->getFieldId();

        return '<input type="submit" id="' . $id . '" name="' . $id . '"' .
        ' value="' . parent::renderLabel() . '">';
    }

    /**
     * Render the HTML of the field's label
     * @param $field_content    The parameters of the field
     * @return string           The generated HTML
     */
    public function renderLabel()
    {
        // The label already appears as the value of the button
        return '';
    }

    /**
     * Validate the answer to a field against the specifications of the form
     * @param mixed $answer The user's answer to the field
     * @return string   An error message explaining the problem, if any
     */
    public function validate($answer)
    {
        // This field is always valid
        return array();
    }
}