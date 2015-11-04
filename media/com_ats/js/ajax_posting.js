akeeba.jQuery(document).ready(function($)
{
	var blockOptions = {
		message : akeeba.jQuery('#ajaxFormFeedback'),
        blockMsgClass : 'akeeba-bootstrap blockMsg',
		css : {
			padding : '20px',
			width   : '400px',
			cursor  : 'default'
		}
	};

    var options = {
        dataType : 'json',
        url : ATS_ROOT_URL + 'index.php?option=com_ats&view=Post&task=save&tmpl=component&format=json',
        beforeSubmit : function(){
            ats_ticket_cansubmit = true;
            ticketBeforeSubmit();
            akeeba.jQuery('.blockUI .bar').css('width', '0%');
            akeeba.jQuery('.closeUpload').show();
            akeeba.jQuery('.reloadTicketList').hide();
            akeeba.jQuery.blockUI(blockOptions);
            akeeba.jQuery('.blockUI .messageHolder').hide();
        },
	    uploadProgress : function(e, position, total, perc){
            akeeba.jQuery('.blockUI .bar').css('width', perc+'%');
	    },
        success : function(json){
            akeeba.jQuery('.blockUI .bar').css('width', '100%');

	        if(!json.result)
	        {
                akeeba.jQuery('.blockUI .message').html(json.error);
                akeeba.jQuery('.blockUI .messageHolder').show();

                if(json.forceReload)
                {
                    akeeba.jQuery('.closeUpload').hide();
                    akeeba.jQuery('.reloadTicketList').show();
                    akeeba.jQuery('input[name="last_ats_post_id"]').val(json.lastPost);
                }
	        }
	        else
	        {
                akeeba.jQuery('input[name="last_ats_post_id"]').val(json.id);
                akeeba.jQuery('#bbcode').val('');
                var clone = akeeba.jQuery('.attachmentWrapper').first().clone();
                clone.children('input').val('');
                clone.children('a').hide();
                akeeba.jQuery('.attachmentHolder').empty();
                clone.appendTo('.attachmentHolder');
                akeeba.jQuery('#ats-timespent').val('');

                akeeba.jQuery.ajax(ATS_ROOT_URL + 'index.php?option=com_ats&view=Post&task=read&layout=single&tmpl=component&render.toolbar=0', {
                    cache : false,
			        data : {
				        id : json.id,
				        format : 'raw',
				        bootstrap : false,
						attachmentErrors: json.attachment_errors,
				        returnurl : akeeba.jQuery('input[name="returnurl"]').val()
			        },
			        success : function(html){
				        var newpost = akeeba.jQuery(html);
                        akeeba.jQuery.unblockUI();
				        newpost.appendTo('#atsPostList').hide().show();
                        akeeba.jQuery('html, body').animate({
					        scrollTop: akeeba.jQuery('#p'+json.id).offset().top
				        }, 400);
			        }
		        });
	        }
        }
    };

    akeeba.jQuery('.closeUpload').click(function(){
        akeeba.jQuery.unblockUI();
    });

    akeeba.jQuery('.reloadTicketList').click(function()
    {
        akeeba.jQuery('.blockUI .message').html(Joomla.JText._('COM_ATS_COMMON_RELOADING'));
        akeeba.jQuery('.reloadTicketList').hide();

        akeeba.jQuery.ajax(ATS_ROOT_URL + 'index.php?option=com_ats&view=Posts&layout=threaded&tmpl=component&render.toolbar=0', {
            cache : false,
            data  : {
                format        : 'raw',
                ats_ticket_id : akeeba.jQuery('input[name="ats_ticket_id"]').val(),
                category_id   : akeeba.jQuery('#category_id').val(),
                bootstrap     : false,
                returnurl     : akeeba.jQuery('input[name="returnurl"]').val()
            },
            success : function(html){
                akeeba.jQuery('#atsPostList .akeeba-bootstrap').empty().html(html);
                akeeba.jQuery.unblockUI();
                akeeba.jQuery('html, body').animate({
                    scrollTop: akeeba.jQuery('#atsPostList .ats-post:last').offset().top
                }, 400);
            }
        });
    });

    akeeba.jQuery('form[name="ats_reply_form"]').ajaxForm(options);
});