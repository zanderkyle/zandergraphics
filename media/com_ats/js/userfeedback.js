function closeModal()
{
	SqueezeBox.close();
}

function submitFeedback()
{
	var active = akeeba.jQuery('.btn-group .active');
	if(active.length > 0)
	{
		var url = akeeba.jQuery('.user-feedback').data('submiturl');
		url += '&rating=' + akeeba.jQuery(active).data('rating');

		window.location = url;
	}

}

akeeba.jQuery(document).ready(function($){

	SqueezeBox.assign($$('a[rel=boxed][href^=#]'), {
		size: {x: 500, y: 320}
	});
});
