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
****
*
* Handle database operations
*
******************************************************************************/

class majordomeDBHandler {
	/**
	 * The Majordome's database table name
	 * @var string
	 */
	private static $TABLE_NAME = 'mj_forms';
	
	/**
	 * Insert a new form in the database
	 * @param string 	$name
	 * @param string 	$desc
	 * @param string 	$handler
	 * @param string 	$fields
	 * @return boolean	true if the insertion succeed, false if the form name already exists
	 */
	static public function insert($name, $desc, $handler, $fields)
	{
		global $core;
		$db =& $core->con;
		$dataSet = $db->openCursor(self::getFullTableName());
		
		// Look for an already existing form of this name
		$res = $db->select('SELECT name FROM ' . self::getFullTableName() .
						' WHERE name = \'' . $db->escape($name) . '\'');
		
		if (!$res->isEmpty()) {
			// The form already exists
			return false;
		}
		
		$dataSet->name = $name;
		$dataSet->desc = $desc;
		$dataSet->handler = $handler;
		$dataSet->fields = $fields;
		return $dataSet->insert();				
	}
	
	/**
	 * Delete a form given its name
	 * @param int $form_id	the identifier of the form
	 */
	static public function delete($form_id)
	{
		global $core;
		
		if (empty($form_id)) {
			throw new InvalidArgumentException('Form ID is null');
		}

		$db =& $core->con;
		$db->execute('DELETE FROM ' . self::getFullTableName() . ' WHERE name = \'' . $db->escape($form_id) . '\'');
		return $db->changes() > 0;
	}
	
	/**
	 * Returns the list of the registered forms
	 */
	static public function getFormList()
	{
		global $core;
		$db =& $core->con;
		$list = $db->select('SELECT `name`, `desc`, `handler` FROM ' . self::getFullTableName());
		return $list;
	}
	
	static public function getTableName()
	{
		return self::$TABLE_NAME;
	}

	static public function getFullTableName()
	{
		return DC_DBPREFIX . self::$TABLE_NAME;
	}
}
