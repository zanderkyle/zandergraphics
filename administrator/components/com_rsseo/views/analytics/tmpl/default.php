<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
?>

<?php if (!empty($this->config->ga_account)) { ?>
<?php $count = count($this->visits); ?>
<script style="text/javascript">
	google.load('visualization', '1', {packages: ['corechart','corechart']});
	google.setOnLoadCallback(drawVisualization);
	
	function drawVisualization() {
	// Create and populate the data table.
	var data = new google.visualization.DataTable();

	data.addColumn('string', '<?php echo JText::_('COM_RSSEO_GA_CHART_DATE',true); ?>');
	data.addColumn('number', '<?php echo JText::_('COM_RSSEO_GA_CHART_VISITS',true); ?>');
	data.addRows(<?php echo $count; ?>);

	<?php $i = 0; ?>
	<?php if (!empty($this->visits)) { ?>
	<?php foreach ($this->visits as $date => $visit) { ?>
			data.setCell(<?php echo $i; ?>, 0, '<?php echo @date('l, F d, Y',$date); ?>');
			data.setCell(<?php echo $i; ?>, 1, <?php echo $visit->visits; ?>);
	<?php	
			if ($i > $count) break;
			$i++;
		}}
	?>

	var areaOptions = {
		'legend': 'none',
		'height': '250',
		'width': '100%',
		'hAxis': {'textPosition': 'none'},
		'pointSize': '6',
		'title': '<?php echo JText::_('COM_RSSEO_GA_CHART_VISITS',true); ?>',
		'backgroundColor': {stroke:'#666', fill:'#FFFFFF', strokeSize: 1}
	};
	
	// Create and draw the visualization.
	var chart = new google.visualization.AreaChart($('rss_visualization'));
	chart.draw(data, areaOptions);
	
	window.addEvent('resize', function() {
		chart.draw(data, areaOptions);
	});
	
	<?php if (!empty($this->sources['details'])) { ?>
	// Create and populate the data table.
	var Pie = new google.visualization.DataTable();
	Pie.addColumn('string', '<?php echo JText::_('COM_RSSEO_GRAPH_SOURCE',true); ?>');
	Pie.addColumn('number', '<?php echo JText::_('COM_RSSEO_GA_CHART_VISITS',true); ?>');
	Pie.addRows(3);
	Pie.setValue(0, 0, '<?php echo JText::_('COM_RSSEO_GRAPH_REFERRING_SITES',true); ?>');
	Pie.setValue(0, 1, <?php echo $this->sources['details'][2]; ?>);
	Pie.setValue(1, 0, '<?php echo JText::_('COM_RSSEO_GRAPH_DIRECT_TRAFFIC',true); ?>');
	Pie.setValue(1, 1, <?php echo $this->sources['details'][0]; ?>);
	Pie.setValue(2, 0, '<?php echo JText::_('COM_RSSEO_GRAPH_SEARCH_ENGINES',true); ?>');
	Pie.setValue(2, 1, <?php echo $this->sources['details'][1]; ?>);
	
	var pieOptions = {
		'legend': 'none',
		'legendFontSize': 12,
		'pieSliceText': 'none',
		'height': '250',
		'width': '100%',
		'backgroundColor': {stroke:'#666', fill:'#FFFFFF', strokeSize: 1}
	}
	
	// Create and draw the visualization.
	var pie = new google.visualization.PieChart($('rss_pie'));
	pie.draw(Pie, pieOptions);
	
	window.addEvent('resize', function() {
		pie.draw(Pie, pieOptions);
	});
	<?php } ?>
}
</script>
<?php } ?>


<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=analytics');?>" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
	<div class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div class="span10">
		<div id="filter-bar" class="btn-toolbar">
			<div class="btn-group pull-right">
				<button class="btn btn-info button" type="button" onclick="Joomla.submitbutton('analytics.save')"><?php echo JText::_('COM_RSSEO_GLOBAL_UPDATE'); ?></button>
			</div>
			<div class="btn-group pull-right">&nbsp;</div>
			<div class="btn-group pull-right">
				<?php echo JHTML::_('calendar', $this->rsend, 'rsend', 'rsend', '%Y-%m-%d' , array('class' => 'input-small')); ?>
			</div>
			<div class="btn-group pull-right">&nbsp;</div>
			<div class="btn-group pull-right">
				<?php echo JHTML::_('calendar', $this->rsstart, 'rsstart', 'rsstart', '%Y-%m-%d' , array('class' => 'input-small')); ?>
			</div>
			<div class="btn-group pull-right">&nbsp;</div>
			<div class="btn-group pull-right">
				<select id="account" class="inputbox" size="1" name="account">
					<?php echo JHtml::_('select.options', $this->acc, 'value', 'text', rsseoHelper::getConfig('ga_account'));?>
				</select>
			</div>
			
			<div class="clearfix"> </div>
		</div>
		<div class="clr"> </div>
		
		<?php if (!empty($this->config->ga_account)) { ?>
		<?php $this->tabs->title('COM_RSSEO_GA_VISITORS_LBL','gavisitors'); ?>
		<?php $this->tabs->title('COM_RSSEO_GA_TRAFFIC_LBL','gatraffic'); ?>
		<?php $this->tabs->title('COM_RSSEO_GA_CONTENT_LBL','gacontent'); ?>
		<?php $this->tabs->content($this->loadTemplate('gavisitors')); ?>
		<?php $this->tabs->content($this->loadTemplate('gatraffic')); ?>
		<?php $this->tabs->content($this->loadTemplate('gacontent')); ?>
		<?php echo $this->tabs->render(); ?>
		<?php } else { ?>
		<div class="ga_no_account">
			<h3>
				<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/info.png" alt="" />
				<?php echo JText::_('COM_RSSEO_SELECT_VALID_ACCOUNT'); ?>
			</h3>
		</div>
		<?php } ?>
		
		
	</div>
</div>

<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="task" value="" />
</form>

<?php if (!empty($this->config->ga_account)) { ?>
<script type="text/javascript">
rsseo_analytics('general');
rsseo_analytics('newreturning');
rsseo_analytics('visits');
rsseo_analytics('browsers');
rsseo_analytics('mobiles');
rsseo_analytics('sources');
rsseo_analytics('content');
</script>
<?php } ?>