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
 ******************************************************************************/

class formRadioFieldTest extends PHPUnit_Framework_TestCase
{
    public function testIsAnswerInOptionList()
    {
        $form_spec = json_decode('{
            "label": "Qu\'aimez-vous dans la vie ?",
            "field_type": "radio",
            "required": true,
            "field_options": {
                "options": [
                    {
                        "label": "Lire",
                        "checked": false
                    },
                    {
                        "label": "Chanter",
                        "checked": false
                    }
                ],
                "description": "Renseignez vos loisirs ici.",
                "include_other_option": true
            },
            "cid": "c26"
        }');

        $field = new formRadioField($form_spec);
        
        $this->assertNotEmpty($field->validate(array('opt' => 'Foo')));
        $this->assertNotEmpty($field->validate(array('opt' => 'Lire')));
        $this->assertEmpty($field->validate(array('opt' => '1')));
    }

    public function testIsFieldRequired()
    {
        $form_spec = json_decode('{
            "label": "Qu\'aimez-vous dans la vie ?",
            "field_type": "radio",
            "required": true,
            "field_options": {
                "options": [
                    {
                        "label": "Lire",
                        "checked": false
                    },
                    {
                        "label": "Chanter",
                        "checked": false
                    }
                ],
                "description": "Renseignez vos loisirs ici.",
                "include_other_option": true
            },
            "cid": "c26"
        }');

        $field = new formRadioField($form_spec);
        $this->assertNotEmpty($field->validate(array('other' => 'foobar')));
    }
    
    public function testIsFieldNotRequired()
    {
        $form_spec = json_decode('{
            "label": "Qu\'aimez-vous dans la vie ?",
            "field_type": "radio",
            "required": false,
            "field_options": {
                "options": [
                    {
                        "label": "Lire",
                        "checked": false
                    },
                    {
                        "label": "Dormir",
                        "checked": false
                    }
                ],
                "description": "Renseignez vos loisirs ici.",
                "include_other_option": true
            },
            "cid": "c26"
        }');

        $field = new formRadioField($form_spec);
        $this->assertEmpty($field->validate(''));
    }
}
