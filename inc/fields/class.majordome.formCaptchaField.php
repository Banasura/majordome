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
 * Handling of captcha fields
 *
 ******************************************************************************/

class formCaptchaField extends formField
{
    /**
     * @override
     * Render the HTML of the field
     * @param   mixed   $fill   An optional value to use in the field
     * @return string           The generated HTML
     */
    public function renderField ($fill = null)
    {
        global $core;
        $id = $this->getFieldId();

        // We are using AccessibleCaptcha
        if ($core->plugins->moduleExists('accessibleCaptcha')) {
            $captcha = new AccessibleCaptcha();
            $question = $captcha->getRandomQuestionAndHash($core->blog->id);

            return '<p class="mj-captcha-lib">' .
            html::escapeHTML($question['question']) .
            '</p><input type="text" id="' . $id . '" name="' . $id . '[answer]" required>' .
            '<input type="hidden" name="' . $id . '[ref]" value="' . $question['hash'] . '">';
        } else {
            throw new Exception('Cannot display a captcha field if AccessibleCaptcha is not installed or enabled.');
        }
    }

    /**
     * Validate the answer to a field against the specifications of the form
     * @param mixed $answer The user's answer to the field
     * @return string   An error message explaining the problem, if any
     */
    public function validate($answer)
    {
        global $core;

        if ($core->plugins->moduleExists('accessibleCaptcha')) {
            $error = array();

            // We are using AccessibleCaptcha
            if (!isset($answer) || !isset($answer['answer']) || !isset($answer['ref'])) {
                $error[] = sprintf(__('Please answer the question “%s”.'), $this->renderLabel());
            } else {
                $captcha = new AccessibleCaptcha();
                if ($captcha->isAnswerCorrectForHash($answer['ref'], $answer['answer']) === false) {
                    $error[] = sprintf(__('The answer to “%s” is wrong. Would you be a robot?'), $this->renderLabel());
                }
            }
            return $error;
        } else {
                throw new Exception('Cannot display a captcha field if AccessibleCaptcha is not installed or enabled.');
        }
    }
}