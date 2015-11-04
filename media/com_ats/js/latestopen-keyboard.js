var ats_latestopen_active = 0;
var ats_latestopen_max = 10;
var ats_latestopen_id = 0;

akeeba.jQuery(document).ready(function($){
	function renderActiveReply()
	{
		$('.ats-latestopen-table tr').each(function(index, e){
			ats_latestopen_max = $(e).data('latestopensequence');

			if ($(e).data('latestopensequence') == ats_latestopen_active)
			{
				ats_latestopen_id = $(e).data('latestopenid');
                $(e).addClass('alert-success');
			}
			else
			{
                $(e).removeClass('alert-success');
			}
		});
	}

    $(document).keypress(function(event){
		if (event.which == 106)
		{
			// Try to go up
			if (ats_latestopen_active > 1)
			{
				event.preventDefault();

				ats_latestopen_active--;
				renderActiveReply();
			}
		}
		else if (event.which == 107)
		{
			// Try to go down
			if (ats_latestopen_active < ats_latestopen_max)
			{
				event.preventDefault();

				ats_latestopen_active++;
				renderActiveReply();
			}
		}
		else if (event.which == 13)
		{
			// ENTER key pressed, open the current ticket
			event.preventDefault();

			window.location = $('#ats-latestopenlink-' + ats_latestopen_active).attr('href');
		}
		//else { alert(event.which) }
	});

    $('#ats-cannedreplies').focus();
	renderActiveReply();
});