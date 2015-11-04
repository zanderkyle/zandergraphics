objSeparator = {
	separator:'---------------'
};
objBucketsButton = {
	name		: 'Buckets',
	className	: 'bucket',
	replaceWith:function(markitup) {
		SqueezeBox.fromElement(document.getElementById('atsBucketsDialog'), {
			parse: 'rel'
		});
		return false;
	}
}
myBBCodeSettings.markupSet.push(objSeparator);
myBBCodeSettings.markupSet.push(objBucketsButton);