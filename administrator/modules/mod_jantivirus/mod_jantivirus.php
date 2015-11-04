<?php
/**
 * @package	Antivirus
 * @copyright	Copyright (C) 2014 SiteGuarding.com. All rights reserved.
 * @license	GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

$file = JPATH_ADMINISTRATOR."/components/com_jantivirus/classes/sgantivirus.class.php";
if (file_exists($file)) require_once($file);
else return;


$session = JFactory::getSession();

$license_info = $session->get('jantivirus_license_info');

if (!is_array($license_info))
{
	// Check license info
	$params = JComponentHelper::getParams('com_jantivirus'); 
	if (trim($params->get('access_key')) == '') return;


	// Prepare data for Last Scan Results
	$avp_alert_main = 0;
	if (count($license_info['last_scan_files']['main']))
	{
		foreach ($license_info['last_scan_files']['main'] as $k => $tmp_file)
		{
			if (file_exists(JPATH_SITE.'/'.$tmp_file)) $avp_alert_main++;
			else unset($license_info['last_scan_files']['main'][$k]); 
		}
	}
	if ($license_info['membership'] != 'pro') $avp_alert_main = $license_info['last_scan_files_counters']['main'];
	
	$avp_alert_heuristic = 0;
	if (count($license_info['last_scan_files']['heuristic']))
	{
		foreach ($license_info['last_scan_files']['heuristic'] as $k => $tmp_file)
		{
			if (file_exists(JPATH_SITE.'/'.$tmp_file)) $avp_alert_heuristic++;
			else unset($license_info['last_scan_files']['heuristic'][$k]);
		}
	}
	if ($license_info['membership'] != 'pro') $avp_alert_heuristic = $license_info['last_scan_files_counters']['heuristic'];
	
	$license_info['last_scan_files_counters']['main'] = $avp_alert_main;
	$license_info['last_scan_files_counters']['heuristic'] = $avp_alert_heuristic;
	
	$session->set('jantivirus_license_info', $license_info);
}	



if ($license_info['last_scan_files_counters']['main'] > 0 || $license_info['last_scan_files_counters']['heuristic'] > 0)
{
	$alert_counter = ' ['.$license_info['last_scan_files_counters']['main'].'/'.$license_info['last_scan_files_counters']['heuristic'].']';
	$block_class = 'class="jantivirus_alert"';
}
else {
	$alert_counter = '';
	$block_class = '';
}

?>
<style>
#module-status .jantivirus {
	background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAk9JREFUeNqUU0tIG1EUPS8z+RojJDRQa6mNqKmU0BBTilSzMJBs0oUEyaYIbgzdzM6NrrPIyp1gliJuFCoULAiVxEBLbSiUIl3UZKEoAcMUk4mZyWfa94qTpA2UntkM795z7rnzzhBVVZFOp9ELmXpGrdarCFvCpFc9EAiApy/cA04182a01BYuy5fIFrI4KhyholRY4wF3oPru+xB4GMCwfRhGzohao0ZLhFAHwVRQLZaL+B8MmAaQfZUlOiYD8k9CU21CUiTUm3VAbZ8zAbXzpAfkhgwrb8XS0yW477i7+vlOB7SgNBQYOMOv5YhGtugtWAmuIDQWgu2TDfn3eU2EOegz9LEDnvDw3PUwu/TbUHK/oR+JcALh8TD2v+1jM7eJZqsJE29qCwzaBlFVqog8imDn5Q6EKQHijQi72Y6N6AZmR2ex93UPiXcJiDWRuaM1bQU69fD0EJlCBrnzHOJTcRj1RridbviGfNj+vI1kOsmujiMcExhxjLQdTA5NwqAzoFgpYvXtKk6KJ4h6ovDe82Irt4X1D+tsHUq+BRXWBKRTiUy7psHreOTFPJbfLOPsxxl2v+wimUniSrqC7veNs+kTzgk4RSfRVqBY9C/i+PwYkiwxEeG1gOv6NZSmok2mZDok9iQGlDpyQFH+XibxZ3HWRKddVC5QkStdZIq5x3Owl+ykK0i3cEkusuBbYM3aQ4hGDo2HMKOf6Yqt7s/U+eEnwnOhK0xUaN4zj4g18lfm+V7RHb0ZJWsv1tTUxxTkuoyYNwZHydHzh/kpwAAyo9lB4+SHmQAAAABJRU5ErkJggg==') 3px 3px no-repeat;
}
.jantivirus_alert{color:#DD3146!important;font-weight: bold;}
</style>
<span class="jantivirus"><a <?php echo $block_class; ?> href="<?php echo JRoute::_('index.php?option=com_jantivirus'); ?>">Antivirus<?php echo $alert_counter; ?></a></span>
<?php

?>
