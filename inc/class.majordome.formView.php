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

__('The form has been successfully sent. Thank you for your participation.');

class formView extends dcUrlHandlers
{
    public static function handleURL($args)
    {
        global $core;

        // Get the context of the page, which allow to perform operations on it
        $_ctx =& $GLOBALS['_ctx'];

        $_ctx->pageArgs = $args;

        // First redirect the page to avoid multiple form submission
        session_start();
        if (isset($_POST['mj_fid'])) {
            $_SESSION['form'] = $_POST ;
            header('Location: ' . $core->blog->url . $core->url->getBase('majordome_view') . '/' . $_ctx->pageArgs);
            exit;
        } elseif (isset($_SESSION['form'])) {
            // Put back the data in the POST variable to ease processing
            $_POST = $_SESSION['form'];
            unset($_SESSION['form']);
        }

        // Get the form corresponding to the URL
        $_ctx->formData = majordomeDBHandler::getFormData($args);
        $_ctx->formData->content = json_decode($_ctx->formData->form_fields)->fields;

        if ($_ctx->formData === false) {
            // The form does not exists: fire a 404 error
            self::p404();
            return;
        }

        /* Form answers: we look for the nonce sent with the form, if found, we
        trigger the form validation & save */
        if (!empty($_POST['mj_fid'])) {
            self::validateForm();
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
        return '<?php echo $_ctx->formData->form_desc; ?>';
    }

    public static function formURL()
    {
        return '<?php
            global $core;
            $_ctx =& $GLOBALS[\'_ctx\'];
            echo $core->blog->url, $core->url->getBase(\'majordome_view\'), \'/\', $_ctx->pageArgs;
            ?>';
    }

    /**
     * Display the form
     * @param array     $attr     The attributes of the tag
     * @param string    $content  The existing content inside the tag
     * @return string   The form code
     */
    public static function formItems($attr, $content)
    {
        return '<?php
        global $core;
        $_ctx =& $GLOBALS[\'_ctx\'];
        echo \'<input type="hidden" name="mj_fid" value="\', $_ctx->formData->form_id, \'">\';
        foreach ($_ctx->formData->content as $f):
            $renderer = formField::getField($f);
            if (empty($renderer)) {
                throw new Exception(\'Unknown field "\' . $f->field_type . \'"\');
            }
        ?>'
            .$content.
        '<?php endforeach; ?>';
    }

    /**
     * Display the label of a field
     * @return string   The field's HTML
     */
    public static function formItemLabel()
    {
        /* We should have the $f variable defined in this function, if the tag
         * has been correctly added inside a <tpl:Form> tag. The variable
         * represents the current field to display
         */
        return '<?php if (!empty($f)) echo $renderer->renderLabel(); ?>';
    }

    /**
     * Display the label of a field
     * @return string   The field's HTML
     */
    public static function formItemField()
    {
        return '<?php if (!empty($f)) {
        $id = $renderer->getFieldId();
        echo $renderer->renderField(array_key_exists($id, $_POST) ? $_POST[$id] : null);
       } ?>';
    }

    /**
     * Display the label of a field
     * @return string   The field's HTML
     */
    public static function formItemDescription()
    {
        return '<?php if (!empty($f)) echo $renderer->renderDescription(); ?>';
    }

    /**
     * Display the ID of a field, used for example in the inputs
     * @return string   The field's HTML
     */
    public static function formItemId()
    {
        return '<?php if (!empty($f)) echo $renderer->getFieldId(); ?>';
    }

    /**
     * Display the type of a field
     * @return string   The field's HTML
     */
    public static function formItemType()
    {
        return '<?php if (!empty($f)) echo html::escapeHTML($f->field_type); ?>';
    }

    /**
     * Run a test against an item, and display the content if the test succeeds.
     * Must be called inside a <tpl:FormItems> loop.
     * @param array $attr     The value to test. A "!" as first char of an attr invert
     *                          the test. Can be:
     *                              type        The field type
     *                              id          The field's ID property
     *                              label       The field label
     *                              operator    The operator used to combine tests (default: and)
     * @param string $content  The existing content inside the tag
     * @return string   The form code
     */
    public static function formItemIf($attr, $content)
    {
        $if = array();
        $operator = '&&';

        if (isset($attr['operator'])) {
            switch ($attr['operator']) {
                case 'or':
                case '||':
                    $operator = '||';
                    break;
                default:
                    // Let the and operator
                    break;
            }
        }

        // Run the ID test
        if (isset($attr['id'])) {
            $id = addslashes(trim($attr['id']));
            if (substr($id,0,1) === '!') {
                $id = substr($id, 1);
                $if[] = '(\'' . $id . '\' !== $renderer->getFieldId())';
            } else {
                $if[] = '(\'' . $attr['id'] . '\' === $renderer->getFieldId())';
            }
        }

        // Run the label test
        if (isset($attr['label'])) {
            $label = addslashes(trim($attr['label']));
            if (substr($label,0,1) === '!') {
                $label = substr($label, 1);
                $if[] = '(\'' . $label . '\' !== $f->label)';
            } else {
                $if[] = '(\'' . $attr['label'] . '\' === $f->label)';
            }
        }

        // Run the type test
        if (isset($attr['type'])) {
            $type = addslashes(trim($attr['type']));
            if (substr($type,0,1) === '!') {
                $type = substr($type, 1);
                $if[] = '(\'' . $type . '\' !== $f->field_type)';
            } else {
                $if[] = '(\'' . $attr['type'] . '\' === $f->field_type)';
            }
        }

        if (empty($if)) return $content;
        else {
            // We ensure that the block is inside a <tpl:FormItems> loop
            return '<?php if(isset($f) && (' . implode(' ' . $operator . ' ', $if) . ')): ?>' .
                $content .
            '<?php endif; ?>';
        }
    }

    /**
     * Display a message explaining the errors in the form
     * @return string   The field's HTML
     */
    public static function formErrorMsg()
    {
        return '<?php
            $_ctx =& $GLOBALS[\'_ctx\'];
            if (!empty($_ctx->formData->errorMsg)) {
                echo \'<ul><li>\', implode(\'</li><li>\', $_ctx->formData->errorMsg), \'</li></ul>\';
            }
        ?>';
    }

    /**
     * Display a message notifying the success of the operation
     * @return string   The field's HTML
     */
    public static function formSuccessMsg()
    {
        return '<?php
            $_ctx =& $GLOBALS[\'_ctx\'];
            if (empty($_ctx->formData->errorMsg) && !empty($_POST[\'mj_fid\'])) {
                echo __(\'The form has been successfully sent. Thank you for your participation.\');
            }
        ?>';
    }

    /**
     * Validate form answers in POST data against the specification given in
     * parameter
     * @return bool the result of the validation
     * @throws Exception
     */
    public static function validateForm()
    {
        $_ctx =& $GLOBALS['_ctx'];
        $error_msg = array();
        $answer_list = array();
        
        foreach ($_ctx->formData->content as $f) {
            $renderer = formField::getField($f);
            $fid = $renderer->getFieldId();
            $answer = array_key_exists($fid, $_POST) ? $_POST[$fid] : null;

            if (empty($renderer)) {
                throw new Exception('Unknown field "' . $f->field_type . '"');
            }
            $error_msg = array_merge($error_msg, $renderer->validate($answer));
            $answer_list[$f->cid] = $answer;
        }

        if (!empty($error_msg)) {
            // The validation failed, we display the form again
            $_ctx->formData->errorMsg = $error_msg;
            return false;
        }
        
        // The answers are valid, we store the results and wipe the validated data
        $data_handler = majordome::getHandlerOfId($_ctx->formData->form_handler);
        try {
            $data_handler::saveAnswer($_ctx->formData, $answer_list);
        } catch (Exception $e) {
            $error_msg[] = __('An error occurred while saving your answers. Sorry for the inconvenience.');
        }
        unset($_POST);
        $_POST = array();

        return true;
    }
}
