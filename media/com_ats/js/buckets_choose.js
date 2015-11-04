akeeba.jQuery(document).ready(function($){
    $('#addTickets').click(function(){
		var cbx = $('input[name="cid[]"]:checked');

		if(cbx.length == 0)
		{
			alert(Joomla.JText._('COM_ATS_BUCKETS_CHOOSE_ONE'));
			return false;
		}

		if(cbx.length > 1)
		{
            alert(Joomla.JText._('COM_ATS_BUCKETS_CHOOSE_ONLY_ONE'));
			return false;
		}

		// Ajax request
		var structure = {
			type: "POST",
			url: ATS_ROOT_URL + 'index.php?option=com_ats&view=Buckets&task=addtickets&format=json',
			data : {
				'cid' : cbx[0].value,
				'ats_ticket_id' : $('#ats_ticket_id').val()
			},
			beforeSend: function() {
                $('#loading').show('fast');
			},
			success: function(responseJSON)
			{
                var data   = responseJSON.match(/###(.*?)###/);
                var result = false;

                if(data.length == 2)
                {
                    result = data[1];
                }

                $('#loading').hide('fast');

				if(result == 'true')
				{
                    $('#saveok').show();
                    $('#saveko').hide();

					setTimeout(function(){parent.SqueezeBox.close()}, 500);
				}
				else
				{
                    $('#saveok').hide();
                    $('#saveko').show();
				}
			}
		};

        $.ajax( structure );
	});
});