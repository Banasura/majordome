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
		$id = 0;
		$db =& $core->con;
		$dataSet = $db->openCursor(self::getFullTableName());
		
		// Look for the last form ID
		$res = $db->select('SELECT MAX(form_id) as lastid FROM ' . self::getFullTableName() . ';');

		if (!$res->isEmpty()) {
			// We use the next id, which should be available
			$id = $res->lastid + 1;
		}

		// Create a unique form URL
		$res = $db->select('SELECT COUNT(form_name) AS nbf FROM ' . self::getFullTableName() .
							' WHERE form_name = \'' . $db->escape($name) . '\';');

		$url = html::escapeURL($name);

		if ($res->nbf > 0) {
			// At least one other form with the same name exists: we add a number
			// at the end of the URL to avoid conflicts
			$url .= $res->nbf + 1;
		}

		$dataSet->form_id = $id;
		$dataSet->form_url = $url;
		$dataSet->form_name = $name;
		$dataSet->form_desc = $desc;
		$dataSet->form_handler = $handler;
		$dataSet->form_fields = $fields;
		return $dataSet->insert();				
	}
	
	/**
	 * Delete a form given its name
	 * @param int $form_id	the identifier of the form
	 */
	static public function delete($form_id)
	{
		global $core;
		
		if (!isset($form_id)) {
			throw new InvalidArgumentException('Form ID is null');
		} elseif (!is_int($form_id)) {
			$form_id = (int) $form_id;
		}

		$db =& $core->con;
		$db->execute('DELETE FROM ' . self::getFullTableName() . ' WHERE form_id = ' . $form_id . ';');
		return $db->changes() > 0;
	}
	
	/**
	 * Returns the list of the registered forms
	 */
	static public function getFormList()
	{
		global $core;
		$db =& $core->con;
		$list = $db->select('SELECT `form_id`, `form_url`, `form_name`, `form_handler` FROM ' . self::getFullTableName());
		return $list;
	}

	/**
	 * Returns the content of a form
	 * @param int $url The form URL
	 * @return DBResult|boolean the form or false if no form found
	 */
	static public function getFormData($url)
	{
		global $core;
		$db =& $core->con;

		$data = $db->select('SELECT * FROM ' . self::getFullTableName()
							. ' WHERE form_url = \'' . $db->escape($url) . '\';');

		return $data->isEmpty() ? false : $data;
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
