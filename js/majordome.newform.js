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

// Init the form builder page
;(function () {
	// FIXME Find a proper way to inject the CSS in the plugin page
	$(document.head).append('<link rel="stylesheet" href="../plugins/majordome/js/formbuilder/dist/formbuilder.css">')
					.append('<link rel="stylesheet" href="../plugins/majordome/vendor/vendor.css">')
					.append('<link rel="stylesheet" href="../plugins/majordome/css/admin.css">');
	
	Formbuilder.options.AUTOSAVE = false;
	
	var formbuilder = new Formbuilder({
		selector: '#newform-builder',
	}),
		lastSaved	= null;

	// Log JSON save
	formbuilder.on("save", function (payload) {
		lastSaved = payload;
		console.log(payload);
	});
	
	// Save the form content on submit
	$("#mj_new_form").on("submit", function (ev) {
		// Get the json
		formbuilder.mainView.saveForm();
		$("#mj_form_content").val(lastSaved);
	});
})();