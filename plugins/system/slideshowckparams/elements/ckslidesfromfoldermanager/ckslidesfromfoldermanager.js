/**
 * @copyright	Copyright (C) 2013 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Slideshow CK
 * @license		GNU/GPL
 * */
 
function importfromfolderck(imgs) {

	clearallimgs = confirm(Joomla.JText._('MOD_SLIDESHOWCK_CLEARALLIMGS','Clear all images before importing ? WARNING : This will erase all your existing slides !'));
	if (clearallimgs) document.id('ckslideslist').getElements('.ckslide').destroy();

	var myurl = "index.php?option=com_ajax&format=raw&plugin=Slideshowckparams&group=system";
	jQuery.ajax({
		type: "POST",
		url: myurl,
		data: {
			method: 'AjaxImportfromfolderck',
			folder: jQuery('#jform_params_fromfoldername').val()
		}
	}).done(function(response) {
		// imgs = response.replace(/\|qq\|/g,"\"");
		imgs = JSON.decode(response);

		for (i=0; i<imgs.length; i++) {
			addslideck(imgs[i], '', JURI+imgs[i]);
		}
	}).fail(function() {
		alert('Failed');
	});
}

function showfolderslistck(button) {
	jQuery('#cklistfolders').show();
}

function selectfolderck(folder, field) {
	jQuery('#' + field).val(jQuery(folder).attr('data-foldername'));
	jQuery('#cklistfolders').hide();
}
