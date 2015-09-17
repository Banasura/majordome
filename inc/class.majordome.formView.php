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

class formView extends dcUrlHandlers
{
    public static function handleURL($args)
    {
        global $core;

        // Get the context of the page, which allow to perform operations on it
        $_ctx =& $GLOBALS['_ctx'];

        // Get the form corresponding t
        $_ctx->formData = majordomeDBHandler::getFormData($args);

        if ($_ctx->formData === false) {
            // The form does not exists: fire a 404 error
            self::p404();
            return;
        }

        $core->tpl->setPath($core->tpl->getPath(), realpath(dirname(__FILE__) . '/../default-templates/'));

        self::serveDocument('form.html','text/html');
    }

    public static function formName()
    {
        return '<?php echo html::escapeHTML($_ctx->formData->form_name); ?>';
    }

    public static function formDescription()
    {
        return '<?php echo html::escapeHTML($_ctx->formData->form_desc); ?>';
    }

    /**
     * Display the form using PFBC
     * @param $attr     The attributes of the tag
     * @param $content  The existing content inside the tag
     * @return string   The form code
     */
    public static function formItems($attr, $content)
    {
        // TODO Implement!
        // Start a session if none is active
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        return '';
    }
}