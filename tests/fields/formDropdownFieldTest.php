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

class formDropdownFieldTest extends PHPUnit_Framework_TestCase
{
    public function testIsAnswerInOptionList()
    {
        $form_spec = json_decode('{
            "label": "Êtes-vous",
            "field_type": "dropdown",
            "required": true,
            "field_options": {
                "options": [
                    {
                        "label": "Un homme",
                        "checked": false
                    },
                    {
                        "label": "Une femme",
                        "checked": false
                    }
                ],
                "include_blank_option": true,
                "description": "Entrez votre sexe."
            },
            "cid": "c30"
        }');

        $field = new formDropdownField($form_spec);
        
        $this->assertNotEmpty($field->validate('Foo'));
        $this->assertNotEmpty($field->validate('Un homme'));
        $this->assertEmpty($field->validate('1'));
    }

    public function testIsFieldRequired()
    {
        $form_spec = json_decode('{
            "label": "Êtes-vous",
            "field_type": "dropdown",
            "required": true,
            "field_options": {
                "options": [
                    {
                        "label": "Un homme",
                        "checked": false
                    },
                    {
                        "label": "Une femme",
                        "checked": false
                    }
                ],
                "include_blank_option": true,
                "description": "Entrez votre sexe."
            },
            "cid": "c30"
        }');

        $field = new formDropdownField($form_spec);
        $this->assertNotEmpty($field->validate(''));
    }
    
    public function testIsFieldNotRequired()
    {
        $form_spec = json_decode('{
            "label": "Êtes-vous",
            "field_type": "dropdown",
            "required": false,
            "field_options": {
                "options": [
                    {
                        "label": "Un homme",
                        "checked": false
                    },
                    {
                        "label": "Une femme",
                        "checked": false
                    }
                ],
                "include_blank_option": true,
                "description": "Entrez votre sexe."
            },
            "cid": "c30"
        }');

        $field = new formDropdownField($form_spec);
        $this->assertEmpty($field->validate(''));
    }
}
