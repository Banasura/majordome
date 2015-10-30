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

class majordome {
    static private $handlers = array();


	/**
	 * Returns the list of the data handlers registered in Majordome
	 * @return array
     */
	static public function getDataHandlerList ()
	{
		$hlist = array();
		foreach (self::$handlers as $id => $hclass)  {
            $hlist[$hclass::getHandlerName()] = $id;
        }
        
        return $hlist;
	}
    
    /**
     * Returns an instance of a registered handler, given its ID
     * @param   string  $id The ID of the handler
     */
    static public function getHandlerOfId ($id)
    {
        return self::$handlers[$id];
    }
	
	/**
	 * Register a new data handler in Majordome
     * @param string    $handler_class  The name of the class to register
     * @return void
	 */
	static public function registerDataHandler($handler_class)
	{
        $h =& self::$handlers;
        
		if (!is_subclass_of($handler_class, 'majordomeDataHandler')) {
            throw new InvalidArgumentException('Argument does not extend majordomeDataHandler.');
        }
        
        $h[$handler_class::getHandlerId()] = $handler_class;
	}
	
}
