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
* Maintenance script, handling updates & installation
* 
******************************************************************************/

if (!defined('DC_CONTEXT_ADMIN')) { return; }
global $core;

// Check the current version against those installed
$current_version 	= $core->plugins->moduleInfo('majordome','version');
$existing_version 	= $core->getVersion('majordome');

// If the existing version is geq than the current, there is nothing to do
if (version_compare($existing_version, $current_version, '>=')) {
	return;
}

// Now install the script
$core->setVersion('majordome', $current_version);

// Update the DB
$s = new dbStruct($core->con,$core->prefix);
$new_table = $s->table('mj_forms');
$new_table->form_id('integer', null, false)
		->form_name('varchar', 50, false)
		->form_url('varchar', 100, false)
		->form_desc('text', 0, true)
		->form_handler('varchar', 50, false)
		->form_fields('text', 0, false)
		->primary('pk_mj_forms', 'form_id')
		->index('idx_mj_forms_url','btree','form_url');

$sync = new dbStruct($core->con, $core->prefix);
$changes = $sync->synchronize($s);

// Update the DB for the internal storage handler
$new_table = $s->table('mj_storage');
$new_table->form_id('integer', null, false)
		->answer_id('integer', null, false)
        ->answer('text', 0, false)
        ->primary('pk_mj_storage', 'form_id', 'answer_id');

$changes = $sync->synchronize($s);

return true;
