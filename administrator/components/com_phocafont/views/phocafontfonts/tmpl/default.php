<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
$class		= $this->t['n'] . 'RenderAdminViews';
$r 			=  new $class();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', $this->t['o']);
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option='.$this->t['o'].'&task='.$this->t['tasks'].'.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
$sortFields = $this->getSortFields();

echo $r->jsJorderTable($listOrder);

echo '<div class="clearfix"></div>';

echo $r->startForm($this->t['o'], $this->t['tasks'], 'adminForm');
echo $r->startFilter($this->t['l'].'_FILTER');
echo $r->selectFilterPublished('JOPTION_SELECT_PUBLISHED', $this->state->get('filter.state'));
//echo $r->selectFilterLanguage('JOPTION_SELECT_LANGUAGE', $this->state->get('filter.language'));
echo $r->endFilter();

echo $r->startMainContainer();
echo $r->startFilterBar();
echo $r->inputFilterSearch($this->t['l'].'_FILTER_SEARCH_LABEL', $this->t['l'].'_FILTER_SEARCH_DESC',
							$this->escape($this->state->get('filter.search')));
echo $r->inputFilterSearchClear('JSEARCH_FILTER_SUBMIT', 'JSEARCH_FILTER_CLEAR');
echo $r->inputFilterSearchLimit('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC', $this->pagination->getLimitBox());
echo $r->selectFilterDirection('JFIELD_ORDERING_DESC', 'JGLOBAL_ORDER_ASCENDING', 'JGLOBAL_ORDER_DESCENDING', $listDirn);
echo $r->selectFilterSortBy('JGLOBAL_SORT_BY', $sortFields, $listOrder);
echo $r->endFilterBar();		

echo $r->startTable('categoryList');

echo $r->startTblHeader();

echo $r->thOrdering('JGRID_HEADING_ORDERING', $listDirn, $listOrder);
echo $r->thCheck('JGLOBAL_CHECK_ALL');
echo '<th class="ph-title-short">'.JHTML::_('grid.sort',  	$this->t['l'].'_TITLE', 'a.title', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-published">'.JHTML::_('grid.sort',  $this->t['l'].'_PUBLISHED', 'a.published', $listDirn, $listOrder ).'</th>'."\n";	
echo '<th class="ph-format">'.JHTML::_('grid.sort',  	$this->t['l'].'_FORMAT', 'a.format', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-default">'.JText::_($this->t['l'].'_DEFAULT').'</th>'."\n";
//echo '<th class="ph-language">'.JHTML::_('grid.sort',  	'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.JHTML::_('grid.sort',  		$this->t['l'].'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();
			
echo '<tbody>'. "\n";

$originalOrders = array();	
$parentsStr 	= "";		
$j 				= 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
		//if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
			//$j++;

$urlEdit		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'].'.edit&id=';
$orderkey   	= array_search($item->id, $this->ordering[0]);		
$ordering		= ($listOrder == 'a.ordering');			
$canCreate		= $user->authorise('core.create', $this->t['o']);
$canEdit		= $user->authorise('core.edit', $this->t['o']);
$canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange		= $user->authorise('core.edit.state', $this->t['o']) && $canCheckin;
$linkEdit 		= JRoute::_( $urlEdit.(int) $item->id );


$iD = $i % 2;
echo "\n\n";
echo '<tr class="row'.$iD.'" sortable-group-id="0" item-id="'.$item->id.'" parents="0" level="0">'. "\n";

echo $r->tdOrder($canChange, $saveOrder, $orderkey);
echo $r->td(JHtml::_('grid.id', $i, $item->id), "small hidden-phone");						
$checkO = '';
if ($item->checked_out) {
	$checkO .= JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, $this->t['tasks'].'.', $canCheckin);
}
if ($canCreate || $canEdit) {
	$checkO .= '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->title).'</a>';
} else {
	$checkO .= $this->escape($item->title);
}
//$checkO .= ' <span class="smallsub">(<span>'.JText::_($this->t['l'].'_FIELD_ALIAS_LABEL').':</span>'. $this->escape($item->alias).')</span>';
echo $r->td($checkO, "small hidden-phone");
echo $r->td(JHtml::_('jgrid.published', $item->published, $i, $this->t['tasks'].'.', $canChange), "small hidden-phone");

echo $r->td($item->format, "small hidden-phone");

$defaultO = JHtml::_('jgrid.isdefault', $item->defaultfont, $i, 'phocafontfont.', $canChange && !$item->defaultfont);
echo $r->td($defaultO, "small hidden-phone");
						

//echo $r->tdLanguage($item->language, $item->language_title, $this->escape($item->language_title));
echo $r->td($item->id, "small hidden-phone");

echo '</tr>'. "\n";
						
		//}
	}
}
echo '</tbody>'. "\n";

echo $r->tblFoot($this->pagination->getListFooter(), 8);
echo $r->endTable();



echo $r->formInputs($listOrder, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();

echo '<div class="clearfix"></div>';
echo '<div class="span2"></div>';

echo '<div id="j-main-container" class="span10" style="margin-left: 0px;padding-right:5px;">';
echo '<div style="border-top:1px solid #eee"></div><p>&nbsp;</p>';
echo '<ul class="nav nav-tabs" id="configTabs">';
$label = JHTML::_( 'image', $this->t['i'].'icon-16-upload.png','') . '&nbsp;'.JText::_('COM_PHOCAFONT_UPLOAD_FONT_INSTALL_FILE');
echo '<li><a href="#upload" data-toggle="tab">'.$label.'</a></li>';
echo '</ul>';

echo '<div class="tab-content">'. "\n";
echo '<div class="tab-pane" id="upload">'. "\n";

?>
<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_phocafont&view=phocafontfonts'); ?>" method="post" name="uploadForm">

<?php   if ($this->ftp) { echo PhocaFontRender::renderFTPaccess();} 
/*
?>

<table class="adminform" border="0">
<tr>
	<td>&nbsp;</td>
	<td colspan="2"><b><?php echo JText::_( 'COM_PHOCAFONT_UPLOAD_FONT_INSTALL_FILE' ); ?></b></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td width="120">
		<label for="install_package"><?php echo JText::_( 'COM_PHOCAFONT_PACKAGE_FILE' ); ?>:</label>
	</td>
	<td>
		<input class="input_box" id="install_package" name="install_package" type="file" size="57" />
		<input class="button" type="button" value="<?php echo JText::_( 'COM_PHOCAFONT_UPLOAD_FILE' ); ?> &amp; <?php echo JText::_( 'COM_PHOCAFONT_INSTALL' ); ?>" onclick="submitbuttonupload()" />
	</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
</table> */ ?>

<h4><?php echo JText::_( 'COM_PHOCAFONT_PACKAGE_FILE' ); ?></h4>
<input type="file" id="sfile-upload" class="input" name="Filedata" />
<button class="btn btn-primary" id="upload-submit"><i class="icon-upload icon-white"></i><?php echo JText::_( 'COM_PHOCAFONT_UPLOAD_FILE' ); ?> &amp; <?php echo JText::_( 'COM_PHOCAFONT_INSTALL' ); ?></button>
	
<input type="hidden" name="type" value="" />
<input type="hidden" name="task" value="install" />
<input type="hidden" name="option" value="com_phocafont" />
<input type="hidden" name="task" value="phocafontfont.install" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php

echo '</div></div>';

echo '<div style="border-top:1px solid #eee"></div><p>&nbsp;</p>';

echo '<div class="btn-group" style="float:right;"><a class="btn btn-large btn-info" href="http://www.phoca.cz/phocafont-fonts" target="_blank"><i class="icon-share icon-white"></i>&nbsp;&nbsp;'.  JText::_($this->t['l'] . '_NEW_FONT_DOWNLOAD') .'</a></div>';
echo '<div class="clearfix"></div>';

echo '</div>';

//if ($this->t['tab'] != '') {$jsCt = 'a[href=#'.$this->t['tab'] .']';} else {$jsCt = 'a:first';}
$jsCt = 'a:first';
echo '<script type="text/javascript">';
echo '   jQuery(\'#configTabs '.$jsCt.'\').tab(\'show\');'; // Select first tab
echo '</script>';

