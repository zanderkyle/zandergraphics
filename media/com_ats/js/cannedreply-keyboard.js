var ats_cannedreply_active = 1;
var ats_cannedreply_max = 10;
var ats_cannedreply_id = 0;

akeeba.jQuery(document).ready(function($){
	function renderActiveReply()
	{
        akeeba.jQuery('tr.ats-cannedreply-row').each(function(index, e){
			ats_cannedreply_max = akeeba.jQuery(e).data('cannedreplysequence');
			
			if (akeeba.jQuery(e).data('cannedreplysequence') == ats_cannedreply_active)
			{
				ats_cannedreply_id = akeeba.jQuery(e).data('cannedreplyid');
                akeeba.jQuery(e).addClass('success');
			}
			else
			{
                akeeba.jQuery(e).removeClass('success');
			}
		});
	}

    akeeba.jQuery(document).keypress(function(event){
		if (event.which == 106)
		{
			// Try to go up
			if (ats_cannedreply_active > 1)
			{
				event.preventDefault();
				
				ats_cannedreply_active--;
				renderActiveReply();
			}
		}
		else if (event.which == 107)
		{
			// Try to go down
			if (ats_cannedreply_active < ats_cannedreply_max)
			{
				event.preventDefault();
				
				ats_cannedreply_active++;
				renderActiveReply();
			}
		}	
		else if ((event.which == 43) || (event.which == 45) || (event.which == 61))
		{
			// Plus, minus or equals key was pressed, expand the currently selected reply
			event.preventDefault();
			atsExpandReply(ats_cannedreply_id);
		}
		else if (event.which == 13)
		{
			// ENTER key pressed, use the current reply
			event.preventDefault();
			atsUseReply(ats_cannedreply_id);
		}
		//else { alert(event.which) }
	})

    akeeba.jQuery('#ats-cannedreplies').focus();
	renderActiveReply();
});