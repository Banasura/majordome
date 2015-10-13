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

class formTimeFieldTest extends PHPUnit_Framework_TestCase
{
    public function testIsPatternRestricted()
    {
        $form_spec = json_decode('{
          "label": "Aimez-vous les pizzas ?",
          "field_type": "checkboxes",
          "required": true,
          "field_options": {
            "options": [
              {
                "label": "Oui",
                "checked": true
              },
              {
                "label": "Non",
                "checked": false
              }
            ],
            "description": "Répondez à cette question requise",
            "include_other_option": true
          },
          "cid": "c6"
        }');

        $field = new formTimeField($form_spec);
        
        $this->assertNotEmpty($field->validate(array(
            'fid-c6' => '1h20')));
        $this->assertNotEmpty($field->validate(array(
            'fid-c6' => '1')));
        $this->assertNotEmpty($field->validate(array(
            'fid-c6' => '24:30')));
        $this->assertNotEmpty($field->validate(array(
            'fid-c6' => '23:60')));
            
        $this->assertEmpty($field->validate(array(
            'fid-c6' => '0:0')));
        $this->assertEmpty($field->validate(array(
            'fid-c6' => '1:1')));
        $this->assertEmpty($field->validate(array(
            'fid-c6' => '01:01')));
    }

    public function testIsFieldRequired()
    {
        $form_spec = json_decode('{
          "label": "Aimez-vous les pizzas ?",
          "field_type": "checkboxes",
          "required": false,
          "field_options": {
            "options": [
              {
                "label": "Oui",
                "checked": true
              },
              {
                "label": "Non",
                "checked": false
              }
            ],
            "description": "Répondez à cette question requise",
            "include_other_option": true
          },
          "cid": "c6"
        }');

        $field = new formDateField($form_spec);
        $this->assertNotEmpty($field->validate(array(
            'fid-c6' => ''
        )));
    }
}
