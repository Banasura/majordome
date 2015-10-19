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

class formDateFieldTest extends PHPUnit_Framework_TestCase
{
    public function testIsPatternRestricted()
    {
        $form_spec = json_decode('{
            "label": "Date de naissance",
            "field_type": "date",
            "required": true,
            "field_options": {
                "description": "Merci de renseigner votre date de naissance"
            },
            "cid": "c10"
        }');

        $field = new formDateField($form_spec);
        
        $this->assertNotEmpty($field->validate('1/20/1971'));
        $this->assertNotEmpty($field->validate('01201971'));
        $this->assertNotEmpty($field->validate('01-02-1971'));
        $this->assertNotEmpty($field->validate('32/01/1971'));
            
        $this->assertEmpty($field->validate('1/1/1971'));
        $this->assertEmpty($field->validate('01/01/1971'));
    }

    public function testIsFieldRequired()
    {
        $form_spec = json_decode('{
            "label": "Date de naissance",
            "field_type": "date",
            "required": true,
            "field_options": {
                "description": "Merci de renseigner votre date de naissance"
            },
            "cid": "c10"
        }');

        $field = new formDateField($form_spec);
        $this->assertNotEmpty($field->validate(''));
    }

    public function testIsFieldNotRequired()
    {
        $form_spec = json_decode('{
            "label": "Date de naissance",
            "field_type": "date",
            "required": false,
            "field_options": {
                "description": "Merci de renseigner votre date de naissance"
            },
            "cid": "c10"
        }');

        $field = new formDateField($form_spec);
        $this->assertEmpty($field->validate(''));
    }
}
