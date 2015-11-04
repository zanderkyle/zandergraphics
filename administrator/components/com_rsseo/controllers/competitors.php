<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class rsseoControllerCompetitors extends JControllerAdmin
{
	protected $text_prefix = 'COM_RSSEO_COMPETITOR';
	
	/**
	 * Constructor.
	 *
	 * @param	array	$config	An optional associative array of configuration settings.

	 * @return	rsseoControllerCompetitors
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}
	
	/**
	 * Proxy for getModel.
	 *
	 * @param	string	$name	The name of the model.
	 * @param	string	$prefix	The prefix for the PHP class name.
	 *
	 * @return	JModel
	 * @since	1.6
	 */
	public function getModel($name = 'Competitor', $prefix = 'rsseoModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	/**
	 * Method to export competitors
	 *
	 * @return	FILE
	 */
	public function export() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$file	= 'rsseo_'.JFactory::getDate()->format('YmdHis').'.csv';
		$csv	= '';
		
		$csv .= '"Competitor URL","Google Page Rank","Google Pages","Google Backlinks","Bing Pages","Bing Backlinks","Alexa Rank","Tehnorati Rank","Dmoz"'."\n";
		
		$query->clear();
		$query->select('*')->from('`#__rsseo_competitors`')->where('`parent_id` = 0');
		$db->setQuery($query);
		if ($competitors = $db->loadObjectList()) {
			foreach($competitors as $competitor)
			$csv .='"'.$competitor->name.'","'.$competitor->pagerank.'","'.$competitor->googlep.'","'.$competitor->googleb.'","'.$competitor->bingp.'","'.$competitor->bingb.'","'.$competitor->alexa.'","'.$competitor->technorati.'","'.$competitor->dmoz.'"'."\n";
		}
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($csv));
		ob_clean();
		flush();
		echo $csv;
		
		JFactory::getApplication()->close();
	}
	
	/**
	 * Method to refresh a competitor
	 *
	 * @return	JSON
	 */
	public function refresh() {
		$db		= JFactory::getDBO();
		$query	= $db->getQuery(true);
		$id		= JFactory::getApplication()->input->getInt('id');
		$config	= rsseoHelper::getConfig();
		
		$query->clear();
		$query->select('`name`')->from('`#__rsseo_competitors`')->where('`id` = '.$id);
		$db->setQuery($query);
		$url = $db->loadResult();
		
		require_once JPATH_ADMINISTRATOR. '/components/com_rsseo/helpers/competitors.php';
		$competitor = competitorsHelper::getInstance($id, $url);
		$values = $competitor->check();
		$default= json_decode($values);
		$values = json_decode($values, true);
		
		foreach($values as $name => $value) {			
			if ($value == -1)
				$values[$name] = '-';
			if ($name == 'dmoz') {
				if ($value == -1) { 
					$values[$name] = '-';
					$values['dmozbadge'] = '';
				} else if ($value == 1) {
					$values[$name] = JText::_('JYES');
					$values['dmozbadge'] = 'success';
				} else if ($value == 0) {
					$values[$name] = JText::_('JNO');
					$values['dmozbadge'] = 'important';
				}
			}
		}
		
		// Get history
		$query->clear();
		$query->select('*')->from('`#__rsseo_competitors`')->where('`parent_id` = '.(int) $id)->order('`date` DESC');
		$db->setQuery($query,0,2);
		$history = $db->loadObjectList();
		
		if(isset($history[1])) {
			$compare = $history[1]; 
		} else $compare = $history[0];
		
		if (empty($compare)) {
			$compare = $default;
		}
		
		// Google page rank
		if ($config->enable_pr) {
			if ($compare->pagerank < $values['pagerank']) 
				$values['pagerankbadge'] = 'success';
			else if ($compare->pagerank > $values['pagerank'])
				$values['pagerankbadge'] = 'important';
			else if ($compare->pagerank == $values['pagerank'])
				$values['pagerankbadge'] = '';
		} else $values['pagerankbadge'] = '';
		
		// Google pages
		if ($config->enable_googlep) {
			if ($compare->googlep < $values['googlep']) 
				$values['googlepbadge'] = 'success';
			else if ($compare->googlep > $values['googlep'])
				$values['googlepbadge'] = 'important';
			else if ($compare->googlep == $values['googlep']) 
				$values['googlepbadge'] = '';
		} else $values['googlepbadge'] = '';
			
		// Google backlinks
		if ($config->enable_googleb) {
			if ($compare->googleb < $values['googleb']) 
				$values['googlebbadge'] = 'success';
			else if ($compare->googleb > $values['googleb'])
				$values['googlebbadge'] = 'important';
			else if ($compare->googleb == $values['googleb']) 
				$values['googlebbadge'] = '';
		} else $values['googlebbadge'] = '';
		
		// Bing pages
		if ($config->enable_bingp) {
			if ($compare->bingp < $values['bingp']) 
				$values['bingpbadge'] = 'success';
			else if ($compare->bingp > $values['bingp'])
				$values['bingpbadge'] = 'important';
			else if ($compare->bingp == $values['bingp']) 
				$values['bingpbadge'] = '';
		} else $values['bingpbadge'] = '';
		
		// Bing backlinks
		if ($config->enable_bingb) {
			if ($compare->bingb < $values['bingb']) 
				$values['bingbbadge'] = 'success';
			else if ($compare->bingb > $values['bingb'])
				$values['bingbbadge'] = 'important';
			else if ($compare->bingb == $values['bingb']) 
				$values['bingbbadge'] = '';
		} else $values['bingbbadge'] = '';
			
		// Alexa page rank
		if ($config->enable_alexa) {
			if ($compare->alexa < $values['alexa']) 
				$values['alexabadge'] = 'important';
			else if ($compare->alexa > $values['alexa'])
				$values['alexabadge'] = 'success';
			else if ($compare->alexa == $values['alexa']) 
				$values['alexabadge'] = '';
		} else $values['alexabadge'] = '';
		
		// Technorati rank
		if ($config->enable_tehnorati) {
			if ($compare->technorati < $values['technorati']) 
				$values['technoratibadge'] = 'success';
			else if ($compare->technorati > $values['technorati'])
				$values['technoratibadge'] = 'important';
			else if ($compare->technorati == $values['technorati']) 
				$values['technoratibadge'] = '';
		} else $values['technoratibadge'] = '';
		
		// Add date refreshed
		$values['date'] = JHtml::_('date', 'now', $config->global_dateformat);
		
		echo json_encode($values);
		JFactory::getApplication()->close();
	}
}