akeeba.jQuery(document).ready(function($){
    akeeba.jQuery('.select-status li a').click(function(){
        var image = akeeba.jQuery(this).parents('div.select-status').parent().find('img');

        var self = this;
        akeeba.jQuery.ajax(ATS_ROOT_URL + 'index.php?option=com_ats&view=Tickets&task=ajax_set_status&format=json&'+jQuery('#token').attr('name')+'=1',{
            type : 'POST',
            dataType : 'json',
            data : {
                'id' 	 : akeeba.jQuery('input[name="ats_ticket_id"]').val(),
                'status' : akeeba.jQuery(this).data('status')
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
                    var label = akeeba.jQuery(self).parents('div.select-status').parent().find('span[class*="label-"]');
                    label.attr('class', 'label pull-right ' + responseJSON.ats_class).html(responseJSON.msg);
                }
            }
        })
    });

    function atsAssignmentClick()
    {
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
                'id'   : $('input[name="ats_ticket_id"]').val(),
                'assigned_to' : assign_to
            },
            beforeSend: function() {
                var wait = $(this._rootElement).parents('.assign-wrapper').find('.loading');
                wait.css('display','inline').find('i').css('display', 'none');
                wait.find('img').css('display', 'inline-block');
            },
            success: function(responseJSON)
            {
                var assigned = $(this._rootElement).parents('.assign-wrapper').find('#ats-assigned-to');
                var unassign = $(this._rootElement).hasClass('unassign');

                if(responseJSON.result == true){
                    assigned.html(responseJSON.assigned);
                    unassign ? assigned.removeClass('badge-info') : assigned.addClass('badge-info');
                    for (var i = 0; i < hide.length; i++)
                    {
                        var elementDefinition = hide[i];
                        $(this._rootElement).parents('.assign-wrapper').find(elementDefinition).css('display', 'none');
                    }
                    for (var i = 0; i < show.length; i++)
                    {
                        var elementDefinition = show[i];
                        $(this._rootElement).parents('.assign-wrapper').find(elementDefinition).css('display', 'inline-block');
                    }
                }
                else
                {
                    var wait = $(this._rootElement).parents('.assign-wrapper').find('.loading');
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
});