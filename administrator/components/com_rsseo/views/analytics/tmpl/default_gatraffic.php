<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');?>
<table cellpadding="0" cellspacing="0" width="100%">
<?php 
	if (!empty($this->sources['details'])) {
		if (!empty($this->sources['details'][0]) && !empty($this->sources['details'][1]) && !empty($this->sources['details'][2])) {
			$dtraffic = $this->sources['details'][0];
			$straffic = $this->sources['details'][1];
			$rtraffoc = $this->sources['details'][2];
			$total = $this->sources['details'][0] + $this->sources['details'][1] + $this->sources['details'][2];
		} else { 
			$total = 1; 
			$dtraffic = 1; 	
			$straffic = 1; 
			$rtraffoc = 1; 
		}
		
		$direct = number_format((($dtraffic * 100)/$total) , 2);
		$reffer = number_format((($rtraffoc * 100)/$total) , 2);
		$search = number_format((($straffic * 100)/$total) , 2);
?>
	<tr style="text-align: center;">
		<td align="right" style="width:45%;">
			<table>
				<tr>
					<td align="right"><b><?php echo JText::_('COM_RSSEO_GRAPH_DIRECT_TRAFFIC'); ?></b></td>
					<td><?php echo $direct; ?> % <span class="rss_color" style="background:#dc3912"></span></td>
				</tr>
				<tr>
					<td align="right"><b><?php echo JText::_('COM_RSSEO_GRAPH_REFERRING_SITES'); ?></b></td>
					<td><?php echo $reffer; ?> % <span class="rss_color" style="background:#3366cc"></span></td>
				</tr>
				<tr>
					<td align="right"><b><?php echo JText::_('COM_RSSEO_GRAPH_SEARCH_ENGINES'); ?></b></td>
					<td><?php echo $search; ?> % <span class="rss_color" style="background:#ff9900"></span></td>
				</tr>
			</table>
		</td>
		<td align="left"><div id="rss_pie" style="clear: both;"></div></td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="2">
			<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/rssloader.gif" id="imgsources" alt="" style="display:none;" />
			<span id="gasources"></span>
		</td>
	</tr>
</table>