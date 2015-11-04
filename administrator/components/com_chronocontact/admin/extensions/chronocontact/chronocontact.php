<?php
/**
* COMPONENT FILE HEADER
**/
namespace GCore\Admin\Extensions\Chronocontact;
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
class Chronocontact extends \GCore\Libs\GController {
	var $models = array('\GCore\Admin\Extensions\Chronocontact\Models\Form');
	var $libs = array('\GCore\Libs\Request');
	var $helpers= array(
		'\GCore\Helpers\DataTable', 
		'\GCore\Helpers\Assets', 
		'\GCore\Helpers\Html', 
		'\GCore\Admin\Extensions\Chronocontact\Helpers\FormsConfig', 
		'\GCore\Helpers\Toolbar', 
		'\GCore\Helpers\Tasks', 
		'\GCore\Helpers\Paginator', 
		'\GCore\Helpers\Sorter'
	);
	
	function index(){
		$this->_sortable();
		$this->_paginate();
		$forms = $this->Form->find('all');
		$this->set('forms', $forms);
		if($this->_validated() === false){
			$session = \GCore\Libs\Base::getSession();
			$domain = str_replace(array('http://', 'https://'), '', \GCore\Libs\Url::domain());
			$session->setFlash('error', "Your ChronoContact installation on <strong>".$domain."</strong> is NOT validated.");
		}
	}
	
	function toggle(){
		parent::_toggle();
		$this->redirect('index.php?option=com_chronocontact');
	}
	
	//data reading
	function edit(){
		$id = $this->Request->data('id', null);
		$this->Form->id = $id;
		$form = $this->Form->load();
		if(!empty($form)){
			$form = $form['Form'];
			$form['config'] = unserialize(base64_decode($form['config']));
			
			$params = new \GCore\Libs\Parameter($form['params']);
			if(!empty($form['wizardcode'])){
				$this->set('wizard_fields', unserialize($form['wizardcode']));
			}
			$this->data = $form;
		}else{
			$form = array('config' => array());
			$params = new \GCore\Libs\Parameter('');
		}
		$this->set(array('form' => $form, 'params' => $params));
		//get fields types
		$fields_types = $fields_files = \GCore\Libs\Folder::getFiles(dirname(__FILE__).DS.'fields'.DS);
		$fields_types2 = $fields_files2 = array();
		foreach($fields_types as $k => $type){
			if(strpos($type, '.html') !== false){
				continue;
			}
			$fields_types2[$k] = str_replace(dirname(__FILE__).DS.'fields'.DS, '', $type);
			$fields_types2[$k] = str_replace('.php', '', $fields_types2[$k]);
			$fields_files2 = $fields_files[$k];
		}
		$this->set('fields_types', $fields_types2);
		$this->set('fields_files', $fields_files);
	}
	
	function save(){
		$result = parent::_save();
		if($result){
			if($this->Request->get('save_act') == 'apply'){
				$this->redirect('index.php?option=com_chronocontact&act=edit&id='.$this->Form->id);
			}else{
				$this->redirect('index.php?option=com_chronocontact');
			}
		}else{
			$this->edit();
			$this->view = 'edit';
			$session = \GCore\Libs\Base::getSession();
			$session->setFlash('error', \GCore\Libs\Arr::flatten($this->Form->errors));
		}
	}
	
	function save_list(){
		parent::_save_list();
		$this->redirect('index.php?option=com_chronocontact');
	}
	
	function delete(){
		parent::_delete();
		$this->redirect('index.php?option=com_chronocontact');
	}
	
	function page_settings(){
		
	}
	
	function render_field(){
		$config = array_values($this->data['fields_config']);
		$this->set('fdata', $config[0]);
	}
	
	function _validated(){
		parent::_settings('chronocontact');
		if((int)$this->data['Chronocontact']['validated'] == 1){
			return true;
		}
		return false;
	}
	
	function settings(){
		parent::_settings('chronocontact');
	}
	
	function save_settings(){
		$result = parent::_save_settings('chronocontact');
		$session = \GCore\Libs\Base::getSession();
		if($result){
			$session->setFlash('success', l_('SAVE_SUCCESS'));
		}else{
			$session->setFlash('error', l_('SAVE_ERROR'));
		}
		$this->redirect('index.php?option=com_chronocontact&act=settings');
	}
	
	function validateinstall(){
		$domain = str_replace(array('http://', 'https://'), '', \GCore\Libs\Url::domain());
		$this->set('domain', $domain);
		if(!empty($this->data['license_key'])){
			$session = \GCore\Libs\Base::getSession();
			$fields = ''; 
			$ch = curl_init();
			//$postfields = array();
			foreach($this->data as $key => $value){
				$fields .= "$key=".urlencode($value)."&";
			}
			curl_setopt($ch, CURLOPT_URL, 'http://www.chronoengine.com/index.php?option=com_chronocontact&task=extra&chronoformname=validateLicense');
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($fields, "& "));
			$output = curl_exec($ch);
			curl_close($ch);
			$validstatus = $output;
			
			if($validstatus == 'valid'){
				parent::_settings('chronocontact');
				$this->data['Chronocontact']['validated'] = 1;
				$result = parent::_save_settings('chronocontact');
				if($result){
					$session->setFlash('success', l_('Validated successflly.'));
					$this->redirect('index.php?option=com_chronocontact');
				}else{
					$session->setFlash('error', l_('Validation error.'));
				}
			}else if($validstatus == 'invalid'){
				parent::_settings('chronocontact');
				$this->data['Chronocontact']['validated'] = 0;
				$result = parent::_save_settings('chronocontact');
				$session->setFlash('error', l_('Validation error, you have provided incorrect data.'));
				$this->redirect('index.php?option=com_chronocontact');
			}else{
				if(!empty($this->data['instantcode'])){
					$step1 = base64_decode(trim($this->data['instantcode']));
					$step2 = str_replace(substr(md5(str_replace('www.', '', strtolower($matches[2]))), 0, 7), '', $step1);
					$step3 = str_replace(substr(md5(str_replace('www.', '', strtolower($matches[2]))), - strlen(md5(str_replace('www.', '', strtolower($matches[2])))) + 7), '', $step2);
					$step4 = str_replace(substr($this->data['license_key'], 0, 10), '', $step3);
					$step5 = str_replace(substr($this->data['license_key'], - strlen($this->data['license_key']) + 10), '', $step4);
					//echo (int)$step5;return;
					//if((((int)$step5 + (24 * 60 * 60)) > strtotime(date('d-m-Y H:i:s')))||(((int)$step5 - (24 * 60 * 60)) < strtotime(date('d-m-Y H:i:s')))){
					if(((int)$step5 < (strtotime("now") + (24 * 60 * 60))) AND ((int)$step5 > (strtotime("now") - (24 * 60 * 60)))){
						parent::_settings('chronocontact');
						$this->data['Chronocontact']['validated'] = 1;
						$result = parent::_save_settings('chronocontact');
						if($result){
							$session->setFlash('success', l_('Validated successflly.'));
							$this->redirect('index.php?option=com_chronocontact');
						}else{
							$session->setFlash('error', l_('Validation error.'));
						}
					}else{
						$session->setFlash('error', l_('Validation error, Invalid instant code provided.'));
						$this->redirect('index.php?option=com_chronocontact');
					}
				}else{
					$session->setFlash('error', l_('Validation error, your server does NOT have the CURL function enabled, please ask your host admin to enable the CURL, or please try again using the Instant Code, or please contact us on www.chronoengine.com'));
					$this->redirect('index.php?option=com_chronocontact');
				}
			}
		}
	}
}
?>