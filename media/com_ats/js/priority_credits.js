akeeba.jQuery(document).ready(function($){
	akeeba.jQuery('#privateticket, #ticketpriority').change(function(){

		akeeba.jQuery.ajax(ATS_ROOT_URL + 'index.php?option=com_ats&view=newticket&task=getcredits&format=json', {
			dataType : 'json',
			data : {
				'public'   : akeeba.jQuery('#privateticket').val(),
				'catid'    : akeeba.jQuery('#ticket_catid').val(),
				'priority' : akeeba.jQuery('#ticketpriority').val()
			},
			beforeSend : function(){
                akeeba.jQuery('#loading').show();
                akeeba.jQuery('#ats-newticket-pubnote-private').hide();
                akeeba.jQuery('#ats-newticket-pubnote-public').hide();
			},
			success : function(responseJSON){
                akeeba.jQuery('#loading').hide();
				var selectPriv = akeeba.jQuery('#privateticket');
				var inpPriv    = akeeba.jQuery('input[name="ticket[public]"]');

				if((selectPriv.length && selectPriv.val() == 0) || (!selectPriv.length && inpPriv.val() == 0)){
                    akeeba.jQuery('#ats-newticket-pubnote-private .credits').html(responseJSON.credits);
                    akeeba.jQuery('#ats-newticket-pubnote-private').show();
				}
				else{
                    akeeba.jQuery('#ats-newticket-pubnote-public .credits').html(responseJSON.credits);
                    akeeba.jQuery('#ats-newticket-pubnote-public').show();
				}
			}
		});
	});
})