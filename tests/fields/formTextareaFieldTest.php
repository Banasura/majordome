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
class formTextareaFieldTest extends PHPUnit_Framework_TestCase
{
    public function testIsFieldRequired()
    {
        $form_spec = json_decode('{
            "label": "Blabla",
            "field_type": "paragraph",
            "required": true,
            "field_options": {
                "size": "small",
                "description": "Veuillez entrer votre biographie ici.",
                "minlength": "0",
                "maxlength": "250",
                "min_max_length_units": "characters"
            },
            "cid": "c22"
        }');

        $field = new formTextareaField($form_spec);
        $this->assertNotEmpty($field->validate(''));
    }

    public function testIsFieldNotRequired()
    {
        $form_spec = json_decode('{
            "label": "Blabla",
            "field_type": "paragraph",
            "required": false,
            "field_options": {
                "size": "small",
                "description": "Veuillez entrer votre biographie ici.",
                "minlength": "0",
                "maxlength": "250",
                "min_max_length_units": "characters"
            },
            "cid": "c22"
        }');

        $field = new formTextareaField($form_spec);
        $this->assertEmpty($field->validate(''));
    }

    public function testMinimumMaximumLength()
    {
        $form_spec = json_decode('{
            "label": "Champ texte",
            "field_type": "text",
            "required": true,
            "field_options": {
                "size": "small",
                "description": "Ceci est un champ texte requis entre 0 et 10 caractères.",
                "minlength": "1",
                "maxlength": "20",
                "min_max_length_units": "characters"
            },
            "cid": "c2"
        }');

        $field = new formTextareaField($form_spec);
        $this->assertNotEmpty($field->validate('Lorem ipsum dolor sit'));
        $this->assertNotEmpty($field->validate(''));
        $this->assertEmpty($field->validate('Lorem ipsum dolor si'));
        $this->assertEmpty($field->validate('Y'));
    }
}
