<?php
/**
 * @copyright	Copyright (C) 2011 Simplify Your Web, Inc. All rights reserved.
 * @license		GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class SYWCache {
			
	/**
	 * Get file's content
	 */
	public static function getFileContent($file) 
	{
		$content = '';
		
		if (function_exists('curl_version')) {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $file);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$content = curl_exec($curl);
			curl_close($curl);
			if (!$content) {				
				return false;
			}	
		} else if (ini_get('allow_url_fopen')) {
			$content = file_get_contents($file);
			if ($content === false) {
				return false;
			}
		} else {
			return false;
		}
		
		//$content = '<html><head><title>Error 403 - Forbidden</title><head><body><h1>Error 403 - Forbidden</h1><p>You don\'t have permission to access the requested resource. Please contact the web site owner for further assistance.</p></body></html>';
				
		// give feedback if there are access permission issues on the file
		if (strpos($content, 'Error 403') !== false) {
			$lang = JFactory::getLanguage();
			$lang->load('lib_syw.sys', JPATH_SITE);	

			$file_array = explode('?', $file);
			JFactory::getApplication()->enqueueMessage(JText::sprintf('LIB_SYW_ERROR_403', $file_array[0]), 'error');
			return false;
		}
		
		return $content;
	}
	
	/**
	 * Get the cached file
	 * 
	 * @param string $path common path to origin and target files (ex: modules/mod_latest_news/)
	 * @param string $file_origin (ex: style.css.php?x=3) - can be empty to create new file with just additional content
	 * @param string $file_target (ex: style.css)
	 * @param boolean $reset whether to reset the cached file or not
	 * @param string $additional_content string content to be appended to the file origin 
	 *  
	 * @return string filepath (the cached version if everything went well, the filepath origin otherwise)
	 */
	public static function getCachedFilePath($path, $file_origin, $file_target, $reset = true, $additional_content = '') {
				
		$trouble_in_paradise = false;
		
		$filepath_origin = $path.$file_origin;
		$filepath_target = $path.$file_target;
		
		if ($reset || !JFile::exists(JPATH_ROOT.'/'.$filepath_target)) {
		
			$content = "\n"; // if empty, does not work
			if (!empty($file_origin)) {
				$file = htmlspecialchars_decode(JURI::base().$filepath_origin); // replace &amp; with & if any
				$content = self::getFileContent($file);
			}
				
			if ($content != false) {
				$result = file_put_contents(JPATH_ROOT.'/'.$filepath_target, $content);
				if ($result === false) {
					$trouble_in_paradise = true;
				} else {
					if (!empty($additional_content)) {
						$result = file_put_contents(JPATH_ROOT.'/'.$filepath_target, $additional_content, FILE_APPEND);
						if ($result === false) {
							$trouble_in_paradise = true;
						}
					}
				}
			} else {
				$trouble_in_paradise = true;
			}
		
// 		} else {
// 			if (!JFile::exists(JPATH_ROOT.'/'.$filepath_target)) {
// 				$trouble_in_paradise = true;
// 			}
		}
		
		if ($trouble_in_paradise) {
			if (empty($file_origin)) {
				return '';
			}
			return JURI::base(true).'/'.$filepath_origin;
		} else {
			return JURI::base(true).'/'.$filepath_target;
		}
	}

	/**
	 * Tests if a folder is ready to be used
	 * If not, creates the proper folders, adding index.html files inside them
	 *
	 */
	public static function isFolderReady($root_path, $extra_path) 
	{		
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		
		$path = $root_path;		
		$folders = explode("/", $extra_path);
		
		foreach ($folders as $folder) {
			$path .= '/'.$folder;
			if (!JFolder::exists($path)) {
					
				if (JFolder::create($path)) {
						
					$src = JPATH_ROOT.'/libraries/syw/index.html';
					$dest = $path.'/index.html';
			
					if (!JFile::copy($src, $dest)) {
						return false;
					}
				} else {
					return false;
				}
			}
		}
		
		return true;
	}
	
}
?>
