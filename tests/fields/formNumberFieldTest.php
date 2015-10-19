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

class formNumberFieldTest extends PHPUnit_Framework_TestCase
{
    public function testIsPatternRestrictedToInteger()
    {
        $form_spec = json_decode('{
            "label": "Combien avez-vous sur votre compte bancaire ?",
            "field_type": "number",
            "required": true,
            "field_options": {
                "description": "Entrez le solde de votre compte",
                "max": "50",
                "min": "0",
                "units": "€",
                "integer_only": true
            },
            "cid": "c34"
        }');

        $field = new formNumberField($form_spec);
        $this->assertNotEmpty($field->validate('4 2'));
        $this->assertNotEmpty($field->validate('4,2'));
        $this->assertNotEmpty($field->validate('4.2'));
        $this->assertEmpty($field->validate('42'));
    }
    
    public function testFloatAreAllowed()
    {
        $form_spec = json_decode('{
            "label": "Combien avez-vous sur votre compte bancaire ?",
            "field_type": "number",
            "required": true,
            "field_options": {
                "description": "Entrez le solde de votre compte",
                "max": "50",
                "min": "0",
                "units": "€",
                "integer_only": false
            },
            "cid": "c34"
        }');

        $field = new formNumberField($form_spec);
        $this->assertNotEmpty($field->validate('4 2'));
        $this->assertNotEmpty($field->validate('4,2'));
        $this->assertEmpty($field->validate('4.2'));
        $this->assertEmpty($field->validate('42'));
    }

    public function testIsFieldRequired()
    {
        $form_spec = json_decode('{
            "label": "Combien avez-vous sur votre compte bancaire ?",
            "field_type": "number",
            "required": true,
            "field_options": {
                "description": "Entrez le solde de votre compte",
                "max": "50",
                "min": "0",
                "units": "€",
                "integer_only": false
            },
            "cid": "c34"
        }');

        $field = new formNumberField($form_spec);
        $this->assertNotEmpty($field->validate(''));
    }

    public function testIsFieldNotRequired()
    {
        $form_spec = json_decode('{
            "label": "Combien avez-vous sur votre compte bancaire ?",
            "field_type": "number",
            "required": false,
            "field_options": {
                "description": "Entrez le solde de votre compte",
                "max": "0",
                "min": "0",
                "units": "€",
                "integer_only": false
            },
            "cid": "c34"
        }');

        $field = new formNumberField($form_spec);
        $this->assertEmpty($field->validate(''));
    }

    public function testMinimumMaximumValue()
    {
        $form_spec = json_decode('{
            "label": "Combien avez-vous sur votre compte bancaire ?",
            "field_type": "number",
            "required": false,
            "field_options": {
                "description": "Entrez le solde de votre compte",
                "max": "100",
                "min": "50",
                "units": "€",
                "integer_only": false
            },
            "cid": "c34"
        }');

        $field = new formNumberField($form_spec);
        $this->assertNotEmpty($field->validate('42'));
        $this->assertNotEmpty($field->validate('102'));
        $this->assertEmpty($field->validate('50'));
        $this->assertEmpty($field->validate('100'));
    }
}
