<?php
/**
* ChronoCMS version 1.0
* Copyright (c) 2012 ChronoCMS.com, All rights reserved.
* Author: (ChronoCMS.com Team)
* license: Please read LICENSE.txt
* Visit http://www.ChronoCMS.com for regular updates and information.
**/
namespace GCore\Extensions\Chronocontact\Actions\FileUpload;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
Class FileUpload {
	var $events = array('success' => 0, 'fail' => 0);
	var $fail = 'load';
	var $stop = array('fail');
	var $defaults = array(
		'files' => '',
		'array_fields' => '',
		'upload_path' => '',
		'forced_file_name' => '',
		'max_size' => '100',
		'min_size' => '0',
		'enabled' => 1,
		'safe_file_name' => 1,
		'max_error' => 'Sorry, Your uploaded file size exceeds the allowed limit.',
		'min_error' => 'Sorry, Your uploaded file size is less than the minimum limit.',
		'type_error' => 'Sorry, Your uploaded file type is not allowed.',
	);
	
	function execute(&$form, $config = array(), $action_id = null){
		$this->config = $config = new \GCore\Libs\Parameter($config);
		if((bool)$config->get('enabled', 0) === false){
			return;
		}
		$upload_path = $config->get('upload_path', '');
		if(!empty($upload_path)){
			$upload_path = str_replace(array("/", "\\"), DS, $upload_path);
			if(substr($upload_path, -1) == DS){
				$upload_path = substr_replace($upload_path, '', -1);
			}
			$config->set('upload_path', $upload_path);
		}else{
			$upload_path = GCORE_FRONT_PATH.'uploads'.DS.$form->name.DS;
		}
		$this->upload_path = $upload_path;
		//check path is correct
		if(!is_dir($this->upload_path) OR !is_writable(realpath($this->upload_path))){
			//$form->errors[] = "Unable to write to upload directory.";
			//$this->events['fail'] = 1;
			//return;
		}
		if(!file_exists($this->upload_path.DS.'index.html')){
			if(!\GCore\Libs\Folder::create($this->upload_path)){
				$form->errors[] = "Couldn't create upload directroy: ".$this->upload_path;
				$this->events['fail'] = 1;
				return;
			}
			$dummy_c = '<html><body bgcolor="#ffffff"></body></html>';
			if(!\GCore\Libs\File::write($this->upload_path.DS.'index.html', $dummy_c)){
				$form->errors[] = "Couldn't create upload directroy index file.";
				$this->events['fail'] = 1;
				return;
			}
		}
		$files_array = explode(',', trim($config->get('files', '')));
		//get array fields
		$array_fields = array();
		if(trim($config->get('array_fields', ''))){
			$array_fields = explode(',', trim($config->get('array_fields', '')));				
		}

		foreach($files_array as $file_string){
			if(strpos($file_string, ':') !== false){
				$file_data = explode(':', trim($file_string));
				$file_extensions = explode('-', $file_data[1]);
				//convert all extensions to lower case
				foreach($file_extensions as $k => $file_extension){
					$file_extensions[$k] = strtolower($file_extension);
				}
				//get the posted file details
				$field_name = $file_data[0];
				if(empty($_FILES[$field_name])){
					continue;
				}
				$file_post = $_FILES[$field_name];
				if(in_array($field_name, $array_fields) AND !empty($file_post['name']) AND ($file_post['name'] === array_values($file_post['name']))){
					foreach($file_post['name'] as $k => $v){
						$uploaded_file_data = $this->processUpload($form, array('name' => $file_post['name'][$k], 'tmp_name' => $file_post['tmp_name'][$k], 'error' => $file_post['error'][$k], 'size' => $file_post['size'][$k]), $file_data[0], $file_extensions);
						if(is_array($uploaded_file_data)){
							$form->files[$field_name][] = $uploaded_file_data;
							$form->data[$field_name][] = $uploaded_file_data['name'];
						}elseif($uploaded_file_data === false){
							return false;
						}
					}
				}else{
					$uploaded_file_data = $this->processUpload($form, $file_post, $field_name, $file_extensions);
					if(is_array($uploaded_file_data)){
						$form->files[$field_name] = $uploaded_file_data;
						$form->data[$field_name] = $uploaded_file_data['name'];
					}elseif($uploaded_file_data === false){
						return false;
					}
				}
			}				
		}
	}
	
	function processUpload(&$form, $file_post = array(), $field_name, $file_extensions){
		//check valid file
		if(!\GCore\Libs\Upload::valid($file_post)){
			return false;
		}
		//check not empty file upload
		if(!\GCore\Libs\Upload::not_empty($file_post)){
			return false;
		}
		//check errors
		if(!isset($file_post['tmp_name']) OR !is_uploaded_file($file_post['tmp_name'])){
			if(!empty($file_post['error']) AND $file_post['error'] !== UPLOAD_ERR_OK){
				$form->debug[] = 'PHP returned this error for file upload by : '.$field_name.', PHP error is: '.$file_post['error'];
				$form->errors[$field_name] = $file_post['error'];
			}
			$this->events['fail'] = 1;
			return false;
		}else{
			$form->debug[] = 'Upload routine started for file upload by : '.$field_name;
		}
		if((bool)$this->config->get('safe_file_name', 1) === true){
			$file_name = \GCore\Libs\File::makeSafe($file_post['name']);
		}else{
			$file_name = utf8_decode($file_post['name']);
		}
		$real_file_name = $file_name;
		$file_tmp_name = $file_post['tmp_name'];
		$file_info = pathinfo($file_name);
		//mask the file name
		if(strlen($this->config->get('forced_file_name', '')) > 0){
			$file_name = str_replace('FILE_NAME', $file_name, $this->config->get('forced_file_name', ''));
		}else{
			$file_name = date('YmdHis').'_'.$file_name;
		}
		//check the file size
		if($file_tmp_name){
			//check max size
			if($file_post['error'] === UPLOAD_ERR_INI_SIZE){
				$form->debug[] = 'File : '.$field_name.' size is over the max PHP configured limit.';
				$form->errors[$field_name] = $this->config->get('max_error', 'Sorry, Your uploaded file size ('.($file_post["size"] / 1024).' KB) exceeds the allowed limit.');
				$this->events['fail'] = 1;
				return false;
			}elseif(($file_post["size"] / 1024) > (int)$this->config->get('max_size', 100)){
				$form->debug[] = 'File : '.$field_name.' size is over the max limit.';
				$form->errors[$field_name] = $this->config->get('max_error', 'Sorry, Your uploaded file size ('.($file_post["size"] / 1024).' KB) exceeds the allowed limit.');
				$this->events['fail'] = 1;
				return false;
			}elseif(($file_post["size"] / 1024) < (int)$this->config->get('min_size', 0)){
				$form->debug[] = 'File : '.$field_name.' size is less than the minimum limit.';
				$form->errors[$field_name] = $this->config->get('min_error', 'Sorry, Your uploaded file size ('.($file_post["size"] / 1024).' KB) is less than the minimum limit.');
				$this->events['fail'] = 1;
				return false;
			}elseif(!in_array(strtolower($file_info['extension']), $file_extensions)){
				$form->debug[] = 'File : '.$field_name.' extension is not allowed.';
				$form->errors[$field_name] = $this->config->get('type_error', 'Sorry, Your uploaded file type is not allowed.');
				$this->events['fail'] = 1;
				return false;
			}else{
				$uploaded_file = \GCore\Libs\Upload::save($file_tmp_name, $this->upload_path.$file_name);
				if($uploaded_file){
					$uploaded_file_data = array();
					$uploaded_file_data = array('name' => $file_name, 'original_name' => $real_file_name, 'path' => $this->upload_path.$file_name, 'size' => $file_post["size"]);
					//Try to generate an auto file link
					$site_link = GCORE_FRONT_URL;
					if(substr($site_link, -1) == "/"){
						$site_link = substr_replace($site_link, '', -1);
					}
					$uploaded_file_data['link'] = str_replace(array(GCORE_FRONT_PATH, DS), array($site_link, "/"), $this->upload_path.$file_name);
					//$form->data[$field_name] = $file_name;
					$form->debug[] = $this->upload_path.$file_name.' has been uploaded successfully.';
					$this->events['success'] = 1;
					return $uploaded_file_data;
				}else{
					$form->debug[] = $this->upload_path.$file_name.' could not be uploaded!!';
					$this->events['fail'] = 1;
					return false;
				}
			}
		}
	}
	
	function config(){
		echo \GCore\Helpers\Html::formStart();
		echo \GCore\Helpers\Html::formSecStart();
		echo \GCore\Helpers\Html::formLine('config[file_upload][{N}][enabled]', array('type' => 'dropdown', 'label' => 'Enabled', 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'sublabel' => 'Enable the files upload feature.'));
		echo \GCore\Helpers\Html::formLine('config[file_upload][{N}][files]', array('type' => 'text', 'label' => 'Files Config', 'class' => 'XL', 'sublabel' => 'Config string, e.g: field1:jpg-png-gif,field2:zip-rar,field3:doc-docx-pdf'));
		echo \GCore\Helpers\Html::formLine('config[file_upload][{N}][upload_path]', array('type' => 'text', 'label' => 'Upload Path', 'class' => 'XL', 'sublabel' => 'Absolute server path to the upload directory.'));
		echo \GCore\Helpers\Html::formLine('config[file_upload][{N}][max_size]', array('type' => 'text', 'label' => 'Max. file size', 'sublabel' => 'Maximum accepted file size in KB.'));
		echo \GCore\Helpers\Html::formLine('config[file_upload][{N}][min_size]', array('type' => 'text', 'label' => 'Min. file size', 'sublabel' => 'Minimum accepted file size in KB.'));
		echo \GCore\Helpers\Html::formLine('config[file_upload][{N}][max_error]', array('type' => 'text', 'label' => 'Max error', 'class' => 'XL', 'sublabel' => 'Error displayed on when maximum size is exceeded.'));
		echo \GCore\Helpers\Html::formLine('config[file_upload][{N}][min_error]', array('type' => 'text', 'label' => 'Min error', 'class' => 'XL', 'sublabel' => 'Error displayed when uploaded file size is less than the minimum accepted.'));
		echo \GCore\Helpers\Html::formLine('config[file_upload][{N}][type_error]', array('type' => 'text', 'label' => 'Type error', 'class' => 'XL', 'sublabel' => 'Error displayed when the file extension is not allowed.'));
		echo \GCore\Helpers\Html::formSecEnd();
		echo \GCore\Helpers\Html::formEnd();
	}
}