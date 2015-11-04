objSeparator = {
	separator:'---------------'
};
objCannedReplyButton = {
	name		: 'CannedReply',
	className	: 'canned',
	key			: 'K',
	replaceWith:function(markitup) {
		SqueezeBox.fromElement(document.getElementById('atsCannedReplyDialog'), {
			parse: 'rel'
		});
		return false;
	}
}
myBBCodeSettings.markupSet.push(objSeparator);
myBBCodeSettings.markupSet.push(objCannedReplyButton);