akeeba.jQuery(document).ready(function($){
	function atsAssignmentClick()
	{
		var parent 	  = $(this).parent('td');
		var id	   	  = $(this).parents('td').find('input.ticket_id').val();
		var hide   	  = ['.loading img', '.loading .icon-warning-sign'];
		var show   	  = ['.loading .icon-ok'];
		var assign_to = 0;

		if($(this).hasClass('assignme'))
		{
			assign_to = $('#user').val();
		}
		else if($(this).parent('.assignto'))
		{
			assign_to = $(this).parent().find('input.assignto').val();
		}

		if(this.hasClass('unassign')){
			hide.push('.unassign');
			show.push('.assignme');
		}
		else{
			hide.push('.assignme');
			show.push('.unassign');
		}

		var structure = {
			_rootElement: this,
			type: "POST",
			dataType: 'json',
			url : 'index.php?option=com_ats&view=Tickets&format=json&' + $('#token').attr('name') + '=1',
			data: {
				'task' : 'assign',
				'id'   : id,
				'assigned_to' : assign_to
			},
			beforeSend: function() {
				var wait = $(this._rootElement).parents('td').find('.loading');
				wait.css('display','inline').find('i').css('display', 'none');
				wait.find('img').css('display', 'inline-block');
			},
			success: function(responseJSON)
			{
				var assigned = $(this._rootElement).parents('td').find('.assigned_to');
				var unassign = $(this._rootElement).hasClass('unassign');

				if(responseJSON.result == true){
					assigned.html(responseJSON.assigned);
					unassign ? assigned.removeClass('badge-info') : assigned.addClass('badge-info');
					for (var i = 0; i < hide.length; i++)
					{
						var elementDefinition = hide[i];
                        $(this._rootElement).parents('td').find(elementDefinition).css('display', 'none');
					}
					for (var i = 0; i < show.length; i++)
					{
						var elementDefinition = show[i];
                        $(this._rootElement).parents('td').find(elementDefinition).css('display', 'inline-block');
					}
				}
				else
				{
					var wait = $(this._rootElement).parents('td').find('.loading');
					wait.find('.icon-ok,img').css('display', 'none');
					wait.find('.icon-warning-sign').show('fast');
				}
			}
		};

        $.ajax( structure );
	}

    $('.unassign a').click(atsAssignmentClick);
    $('.assignme a').click(atsAssignmentClick);
    $('.assignto li a').click(atsAssignmentClick);

	$('.select-status li a').click(function(){
		var image = $(this).parents('td').find('img');

		var self = this;
        $.ajax('index.php?option=com_ats&view=Tickets&task=ajax_set_status&format=json&'+$('#token').attr('name')+'=1',{
			type : 'POST',
			dataType : 'json',
			data : {
				'id' 	 : $(this).parents('tr').find('input[name="cid[]"]').val(),
				'status' : $(this).data('status')
			},
			beforeSend : function(){
				image.show();
			},
			success : function(responseJSON){
				image.hide();

				if(responseJSON.err){
					alert(responseJSON.err);
				}
				else{
					var label = $(self).parents('td').find('[class*="ats-ticket-status-"]');
					label.attr('class', responseJSON.ats_class).html(responseJSON.msg);
				}
			}
		})
	});

    $('#bucketadd').click(function(){
        if(document.adminForm.boxchecked.value == 0)
        {
            alert($('#warnmessage').val());
            return false;
        }

        var href = $('#bucketadd').attr('href');

        $('input[name="cid[]"]:checked').each(function(i, item){
            href += '&ats_ticket_id[]=' + $(item).val();
        });

        // Inject selected tickets into the url
        $('#bucketadd').attr('href', href);

        SqueezeBox.open(this, {'handler' : 'iframe', 'size' : {'x' : 1050, 'y' : 550}});

        return false;
    });
});
