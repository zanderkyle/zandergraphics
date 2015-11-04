objSeparator = {
	separator:'---------------'
};
objPreviewButton = {
	name		: 'Preview',
	className	: 'atspreview',
	key			: 'P',
	replaceWith:function(markitup)
    {
        akeeba.jQuery.ajax(ATS_ROOT_URL + 'index.php?option=com_ats&view=Posts&task=parsebbcode&format=json', {
            cache : false,
            dataType : 'json',
            method : 'POST',
            data : {
                'content' : akeeba.jQuery(markitup.textarea).val()
            },
            beforeSend : function(){
                akeeba.jQuery('#atsPreviewHolder').hide();
                akeeba.jQuery('#atsPreviewLoading').show();
                akeeba.jQuery('#atsPreviewHtml').html('');
            },
            success : function(responseJSON){
                akeeba.jQuery('#atsPreviewLoading').hide();
                akeeba.jQuery('#atsPreviewHtml').html(responseJSON);
                akeeba.jQuery('#atsPreviewHolder').show();
            }
        });
		return false;
	}
};
myBBCodeSettings.markupSet.push(objSeparator);
myBBCodeSettings.markupSet.push(objPreviewButton);