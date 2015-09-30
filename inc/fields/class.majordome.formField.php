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
 * ***
 *
 * Main class to get the HTML code corresponding to a form field
 *
 ******************************************************************************/

abstract class formField
{
    /**
     * formField constructor.
     */
    public function __construct($field_content)
    {
        //TODO add some verification
        $this->field = $field_content;
    }

    /**
     * @override
     * Render the HTML of the field
     * @return string           The generated HTML
     */
    abstract public function renderField();

    /**
     * Render the HTML of the field's label
     * @param $field_content    The parameters of the field
     * @return string           The generated HTML
     */
    public function renderLabel()
    {
        if (empty($this->field->field_options->label)) {
            return '';
        } else {
            return html::escapeHTML($this->field->label);
        }
    }

    /**
     * Render the HTML of the field's description
     * @return string           The generated HTML
     */
    public function renderDescription()
    {
        if (empty($this->field->field_options->description)) {
            return '';
        } else {
            return html::escapeHTML($this->field->field_options->description);
        }
    }

    /**
     * Returns the unique identifier of a field
     * @return string The identifier
     */
    public function getFieldId()
    {
        return 'fid-' . $this->field->cid;
    }

    /********************* Class methods & properties *************************/

    /**
     * Give the associated class for each field type
     * @var array
     */
    private static $fields = array(
        'text'          => 'formTextField',
        'checkboxes'    => 'formCheckboxField',
        'date'          => 'formDateField',
        'time'          => 'formTimeField',
        'website'       => 'formWebsiteField',
        'paragraph'     => 'formTextareaField',
        'radio'         => 'formRadioField',
        'dropdown'      => 'formDropdownField',
        'number'        => 'formNumberField',
        'email'         => 'formMailField'
    );

    /**
     * Returns the class corresponding to the given field's type
     * @param  object       $field_content  The field data
     * @return formField                    The corresponding class
     */
    public static function getField($field_content)
    {
        return is_object($field_content) && isset(self::$fields[$field_content->field_type])
            ? new self::$fields[$field_content->field_type]($field_content)
            : null;
    }
}