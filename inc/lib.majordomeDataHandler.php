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

interface majordomeDataHandler {
    
    /**
     * Returns a string identifying this handler
     */
    static function getHandlerId ();
    
    /**
     * Returns the name of this handler
     */
    static function getHandlerName ();
    
    /**
     * Tell if this handler can display the list of existing answers
     * to a form
     */
    static function hasAnAnswerPage ();
    
    /**
     * Provides the HTML code to display in the configuration page of
     * the handler
     */
    static function getHandlerOptionPage ();
    
    /**
     * Display the page showing the answers to a form
     * @param   int $form_data    The form's schema of which display the answers
     */
    static function displayHandlerAnswerPage ($form_data);
    
    /**
     * Validate the options chosen by the user in the handler option
     * page.
     * @return boolean  true if the options are valid, false elsewhere
     */
    static function areHandlerOptionsValid ();
    
    /**
     * Save the handler's options chosen by the user
     */
    static function saveHandlerOptions ();
    
    /**
     * Save an answer to a form
     * @param   array   $form_data  The form's schema
     * @param   array   $answer     The answer to the form to save
     */
    static function saveAnswer ($form_data, $answer);
	
}
