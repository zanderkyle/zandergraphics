<?php
/**
* @version 1.0.0
* @copyright (C) 2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class RSPackage extends JObject
{
	protected $_options = array();
	protected $db = null;
	protected $input = null;
	protected $_folder = null;
	protected $_extractfolder = null;
	
	public function __construct($options = array()) {
		$this->db		= JFactory::getDBO();
		$this->input	= JFactory::getApplication()->input;
		$config			= JFactory::getConfig();		
		$this->_options = $options;
		
		$this->setFile();
		
		$tmp_path				= $config->get('tmp_path');
		$tmp_folder				= 'rsbackup_'.$this->getMD5();
		$extract_tmp_folder		= 'rsbackup_'.$this->getMD5File();
		$this->_folder			= JPath::clean($tmp_path.'/'.$tmp_folder);
		$this->_extractfolder	= JPath::clean($tmp_path.'/'.$extract_tmp_folder);
	}
	
	
	protected function setFile() {
		$file = $this->input->files->get('rspackage');
		
		if (!empty($file) && $file['error'] == 0)
			$this->_options['file'] = $file;
	}
	
	protected function getMD5() {
		$string = '';
		$queries = $this->getQueries();
		foreach ($queries as $query)
			$string .= $query['query'].';';
		
		return md5($string);
	}
	
	protected function getMD5File() {
		if (isset($this->_options['file'])) {
			return md5($this->_options['file']['name']);
		}
	}
	
	protected function getQueries() {
		if (isset($this->_options['queries'])) {
			return $this->_options['queries'];
		}
		
		return array();
	}
	
	protected function getLimit() {
		$default = 300;
		
		if (isset($this->_options['limit'])) {
			return (int) $this->_options['limit'] <= 0 ? $default : $this->_options['limit'];
		}
			
		return $default;
	}
	
	protected function getFolder() {
		return $this->_folder;
	}
	
	protected function getExtractFolder() {
		return $this->_extractfolder;
	}
	
	public function backup() {
		if ($this->_isRequest()) {
			$this->_parseRequest();
			return;
		}
		
		if ($this->_isDownload()) {
			$this->_startDownload();
			return;
		}
		
		$folder = $this->getFolder();
		if (JFolder::exists($folder)) {
			JFile::delete(JFolder::files($folder, '.xml$', 1, true));
			JFile::delete(JFolder::files($folder, '.tar.gz$', 1, true));
		}
		else
			JFolder::create($folder);
		
		$document = JFactory::getDocument();
		
		$script = array();
		$script[] = 'var rspackage_queries = new Array();';
		
		$uri		= JURI::getInstance();
		$url		= $uri->toString();
		$limit		= $this->getLimit();
		$queries	= $this->getQueries();
		
		foreach ($queries as $query) {
			$this->db->setQuery($query['query']);
			$results = $this->db->getNumRows($this->db->execute());
			$pages = ceil($results / $limit);
			
			for ($i=0; $i<$pages; $i++) {
				$page				= $i * $limit;
				$query['offset'] 	= $page; 
				$query['limit'] 	= $limit;
				$script[] = 'rspackage_queries.push("'.$this->encode($query).'");';
			}
		}
		
		$script[] = 'var rspackage_requests = new Array();';
		$script[] = 'var totalbackup = 0;';
		$script[] = 'var totalsofarbackup = 0;';
		$script[] = "\n";
		$script[] = 'function rspackage_backup() {';
		$script[] = "\t".'for (var i=0; i<rspackage_queries.length; i++) {';
		$script[] = "\t\t".'var rspackage_query = rspackage_queries[i];';
		$script[] = "\t\t".'var rspackage_request = new Request({url:"'.$url.'",method: "post", data: {query: rspackage_query, ajax: 1, type: "backup"}, onComplete: rspackage_next});';
		$script[] = "\t\t".'rspackage_requests.push(rspackage_request);';
		$script[] = "\t\t".'totalbackup++;';
		$script[] = "\t".'}';
		$script[] = '}';
		$script[] = "\n";
		$script[] = 'function rspackage_next(response) {';
		$script[] = "\t".'var comrsseobar = $("com-rsseo-bar");';
		$script[] = "\t".'var rspackage_progress_bar_unit = 100 / totalbackup;';
		$script[] = "\t".'if (rspackage_requests.length < 1) {';
		$script[] = "\t\t".'if (comrsseobar != undefined)';
		$script[] = "\t\t\t".'comrsseobar.innerHTML = comrsseobar.style.width = "100%";';
		$script[] = "\t\t".'rspackage_pack();';
		$script[] = "\t\t".'return;';
		$script[] = "\t".'}';
		$script[] = "\n";
		$script[] = "\t".'if (comrsseobar != undefined) {';
		$script[] = "\t\t".'totalsofarbackup += rspackage_progress_bar_unit;';
		$script[] = "\t\t".'comrsseobar.innerHTML = number_format(totalsofarbackup,2) + "%";';
		$script[] = "\t\t".'comrsseobar.style.width = number_format(totalsofarbackup,2) + "%";';
		$script[] = "\t".'}';
		$script[] = "\n";
		$script[] = "\t".'var rspackage_request = rspackage_requests[rspackage_requests.length - 1];';
		$script[] = "\t".'rspackage_requests.pop();';
		$script[] = "\t".'rspackage_request.send();';
		$script[] = '}';
		$script[] = "\n";
		$script[] = 'function rspackage_pack() {';
		$script[] = "\t".'var rspackage_request = new Request({url:"'.$url.'",method: "post", data: {ajax: 1, pack: 1}, onComplete: rspackage_download});';
		$script[] = "\t".'rspackage_request.send();';
		$script[] = '}';
		$script[] = "\n";
		$script[] = 'function rspackage_download() {';
		$script[] = "\t".'var form = document.createElement("form");';
		$script[] = "\t".'form.setAttribute("action", "'.$url.'");';
		$script[] = "\t".'form.setAttribute("method", "post");';
		$script[] = "\t".'var input = document.createElement("input");';
		$script[] = "\t".'input.setAttribute("type", "hidden");';
		$script[] = "\t".'input.setAttribute("name", "download");';
		$script[] = "\t".'input.setAttribute("value", "1");';
		$script[] = "\t".'form.appendChild(input);';
		$script[] = "\t".'var body = document.body.appendChild(form);';
		$script[] = "\t".'form.submit();';
		$script[] = '}';
		$script[] = "\n";
		$script[] = 'rspackage_backup();';
		$script[] = 'window.addEvent("domready", rspackage_next);';
		
		$document->addScriptDeclaration(implode("\n",$script));
	}
	
	public function restore() {
		$app = JFactory::getApplication();
		
		if ($this->_isRequest()) {
			$this->_parseRequest();
			return;
		}
		
		if (!isset($this->_options['file']) || $this->_options['file']['error'] != 0) 
			return;
		
		$db			= JFactory::getDBO();
		$document	= JFactory::getDocument();
		
		if (isset($this->_options['file']) && $this->_options['file']['error'] == 0) {
			$extract = $this->_extract();
			if ($extract == false) 
				$app->redirect('index.php?option=com_rsseo&view=backup&process=restore',JText::_('COM_RSSEO_RESTORE_ERROR'),'error');
		}
		
		
		$uri	= JFactory::getURI();
		$url	= $uri->toString();
		$files	= $this->_getFiles();
		$script = array();
		
		$script[] = 'var rspackage_files = new Array();'."\n";
		
		if(!empty($files)) {
			foreach ($files as $file) {
				$script[] = 'rspackage_files.push("'.urlencode($db->escape($file)).'");';
			}
		}
		
		$script[] = 'var rspackage_requests = new Array();';
		$script[] = 'var thetotal = 0;';
		$script[] = 'function rspackage_restore() {';
		$script[] = "\t".'for (var i=0; i<rspackage_files.length; i++) {';
		$script[] = "\t\t".'var rspackage_file = rspackage_files[i];';
		$script[] = "\t\t".'var rspackage_request = new Request({url:"'.$url.'" ,method: "post", data: {file: rspackage_file, ajax: 1, type: "restore", process: "restore"}, onComplete: rspackage_next});';
		$script[] = "\t\t".'rspackage_requests.push(rspackage_request);';
		$script[] = "\t\t".'thetotal++;';
		$script[] = "\t".'}';	
		$script[] = "\t".'var clear = new Request({url:"'.$url.'" ,method: "post", data: {ajax: 1, type: "clear", process: "restore"}, onComplete: rspackage_next});';
		$script[] = "\t".'rspackage_requests.push(clear);';
		$script[] = '}';
		$script[] = "\n";
		$script[] = 'var totalsofar = 0;';
		$script[] = 'function rspackage_next(response) {';
		$script[] = "\t".'var rspackage_progress_bar_unit = 100 / thetotal;';
		$script[] = "\t".'var comrsseobar = $("com-rsseo-bar");';
		$script[] = "\t".'if (rspackage_requests.length < 1) {';
		$script[] = "\t\t".'if (comrsseobar != undefined)';
		$script[] = "\t\t\t".'comrsseobar.innerHTML = comrsseobar.style.width = "100%";';
		$script[] = "\t\t".'document.location = "'.$this->getRedirect().'";';
		$script[] = "\t\t".'return;';
		$script[] = "\t".'}';
		$script[] = "\n";
		$script[] = "\t".'if (comrsseobar != undefined) {';
		$script[] = "\t\t".'totalsofar += rspackage_progress_bar_unit;';
		$script[] = "\t\t".'comrsseobar.innerHTML = number_format(totalsofar,2) + "%";';
		$script[] = "\t\t".'comrsseobar.style.width = number_format(totalsofar,2) + "%";';
		$script[] = "\t".'}';
		$script[] = "\n";
		$script[] = "\t".'var rspackage_request = rspackage_requests[rspackage_requests.length - 1];';
		$script[] = "\t".'rspackage_requests.pop();';
		$script[] = "\t".'rspackage_request.send();';
		$script[] = '}';
		$script[] = "\n";
		$script[] = 'rspackage_restore();';
		$script[] = 'window.addEvent("domready", rspackage_next);';
		
		$document->addScriptDeclaration(implode("\n",$script));
	}
	
	protected function getRedirect() {
		if (isset($this->_options['redirect']))
			return $this->_options['redirect'].'&delfolder='.base64_encode($this->getExtractFolder());
		
		$uri = JURI::getInstace();
		$url = $uri->toString();
		
		return $url;
	}
	
	protected function _extract() {
		$folder		= $this->getExtractFolder();		
		$file		= $folder.'/'.$this->_options['file']['name'];
		
		//check to see if its a gzip file
		if (!preg_match('#zip#is',$this->_options['file']['name'])) {
			return false;
		}
		
		//upload the file in the tmp folder
		if (!JFile::upload($this->_options['file']['tmp_name'],$file)) {
			return false;
		}
		
		//ectract the archive
		$extract = JArchive::extract($file,$folder);
		
		//delete the archive
		if($extract) 
			JFile::delete($file);
		
		return true;
	}
	
	protected function _getFiles() {
		$xmls = array();
		
		if(isset($this->_options['file']) && $this->_options['file']['error'] == 0) {
			$folder = $this->getExtractFolder();
			$xmls = JFolder::files($folder, '.xml$', 1, true);
		}
		
		return $xmls;
	}
	
	
	protected function _isDownload() {
		return $this->input->getInt('download',0);
	}
	
	protected function _startDownload() {
		$file = $this->getFolder().'/package.zip';
		$fsize = filesize($file);
		header("Cache-Control: public, must-revalidate");
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header("Pragma: no-cache");
		header("Expires: 0"); 
		header("Content-Description: File Transfer");
		header("Expires: Sat, 01 Jan 2000 01:00:00 GMT");
		header("Content-Type: application/octet-stream");
		header("Content-Length: ".(string) ($fsize));
		header('Content-Disposition: attachment; filename="backup_package_'.date('Y_m_d').'.zip"');
		header("Content-Transfer-Encoding: binary\n");
		@ob_end_flush();
		$this->readfile_chunked($file);
		exit();
	}
	
	protected function readfile_chunked($filename, $retbytes = true) {
		$chunksize = 1*(1024*1024); // how many bytes per chunk
		$buffer = '';
		$cnt =0;
		$handle = fopen($filename, 'rb');
		if ($handle === false) {
			return false;
		}
		while (!feof($handle)) {
			$buffer = fread($handle, $chunksize);
			echo $buffer;
			if ($retbytes) {
				$cnt += strlen($buffer);
			}
		}
		$status = fclose($handle);
		if ($retbytes && $status) {
			return $cnt; // return num. bytes delivered like readfile() does.
		}
		return $status;
	}
	
	protected function _isRequest() {
		return $this->input->getInt('ajax',0);
	}
	
	protected function _parseRequest() {
		$folder = $this->getFolder();
		$type	= $this->input->getString('type');
		
		if ($type) {
			$query	= $this->input->getBase64('query');
			$start	= $this->input->getInt('start',0);
			$num	= count(JFolder::files($folder, '.xml$', 1, false));
			
			switch ($type) 	{
				case 'clear':
					$this->db->setQuery("TRUNCATE TABLE #__rsseo_pages");
					$this->db->execute();
					$this->db->setQuery("TRUNCATE TABLE #__rsseo_redirects");
					$this->db->execute();
				break;
				
				case 'backup':				
					$buffer  = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
					$buffer .= '<query>'."\n";
					
					$query = $this->decode($query);
					$table = '';
					if (preg_match('# (\#__.*?) #is', $query['query'], $matches))
						$table = trim($matches[1]);
					
					$buffer .= $this->addTag('table', $table);
					
					$this->db->setQuery($query['query'], $query['offset'], $query['limit']);
					$results = $this->db->loadObjectList();
					
					$buffer .= '<rows>'."\n";
					foreach ($results as $result) {
						$buffer .= '<row>'."\n";
						foreach ($result as $key => $value) {
							if (isset($query['primary']) && $key == $query['primary'])
								continue;
								
							$buffer .= $this->addTag('column',$value,$key);
						}
						$buffer .= '</row>'."\n";
					}
					$buffer .= '</rows>';
					
					$buffer .= "\n".'</query>';
					JFile::write($folder.'/package'.$num.'.xml', $buffer);
				break;
				
				case 'restore':
					$file = urldecode($this->input->getString('file'));
					$xml = new SimpleXMLElement($file, null, true);
					
					$table = (string) $xml->table;
					$rows  = $xml->rows->children();
					
					$table_fields = $name = $data = array();
					$fields = $this->db->getTableColumns($table);
					foreach($fields as $field => $type)
						$table_fields[] = $this->db->quoteName($field);
					
					$thequery = $this->db->getQuery(true);
					
					if (!empty($rows)) {
						foreach ($rows as $row) {
							$sql = array();
							$columns = $row->children();
							
							foreach ($columns as $column) {
								$properties = $column->children();
								foreach($properties as $prop) {
									if ($prop->getName() == 'name') $name[] = $this->db->quoteName((string) $prop);
									if ($prop->getName() == 'value') $data[] = $this->db->quote((string) $prop);
								}							
							}
							
							foreach($name as $i => $val) {
								if (!in_array($val,$table_fields)) {
									unset($name[$i]);
									unset($data[$i]);
								}
							}
							
							if (!empty($name) && !empty($data)) {
								$thequery->clear();
								$thequery->insert($this->db->qn($table))->columns($name)->values(implode(',', $data));
								$this->db->setQuery($thequery);
								$this->db->execute();
								unset($name);unset($data);
							}
						}
					}
					
				break;
			}
		}
		
		$pack = $this->input->getInt('pack', 0);
		if ($pack) {
			$adapter = JArchive::getAdapter('zip');
			
			$archivefiles = array();
			$xmlfiles = JFolder::files($folder, '.xml$', 1, true);
			foreach($xmlfiles as $xmlfile) {
				$data = JFile::read($xmlfile);
				$archivefiles[] = array('name' => JFile::getName($xmlfile), 'data' => $data);
			}
			
			if (rsseoHelper::isJ3()) {
				if ($adapter->isSupported()) {
					$archive = new RSZip;
					$archive->create($folder.'/package.zip', $archivefiles);
				}
			} else {
				$adapter->create($folder.'/package.zip', $archivefiles);
			}
		}
		
		die();
	}
	
	protected function encode($array) {
		return base64_encode(serialize($array));
	}
	
	protected function decode($array) {
		return unserialize(base64_decode($array));
	}
	
	public function displayProgressBar() {
		return '<div id="com-rsseo-import-progress" class="com-rsseo-progress"><div style="width: 1%;" id="com-rsseo-bar" class="com-rsseo-bar">0%</div></div>';
	}
	
	protected function addTag($tag, $value, $name = null) {
		if (is_null($name)) {
			return "\t".'<'.$tag.'>'.$this->xmlentities($value).'</'.$tag.'>'."\n";
		} else {
			return "\t".'<'.$tag.'>'."\n"."\t\t".'<name>'.$this->xmlentities($name).'</name>'."\n\t\t".'<value>'.$this->xmlentities($value).'</value>'."\n\t".'</'.$tag.'>'."\n";
		}
	}
	
	protected function xmlentities($string, $quote_style=ENT_QUOTES) {
		return htmlspecialchars($string,$quote_style,'UTF-8');
	}
}