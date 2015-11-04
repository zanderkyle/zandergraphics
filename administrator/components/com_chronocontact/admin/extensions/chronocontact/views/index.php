<?php
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$doc = \GCore\Libs\Document::getInstance();
	$doc->_('datatable');
	$doc->_('jquery');
	$doc->_('jquery-ui');
	$this->Toolbar->setTitle(l_('FORMS_MANAGER'));
	$this->Toolbar->addButton('add', 'index.php?option=com_chronocontact&act=edit', 'New', $this->Assets->image('add', 'toolbar/'));
	//$this->Toolbar->addButton('save_list', 'index.php?option=com_chronocontact&act=save_list', 'Update List', $this->Assets->image('save_list', 'toolbar/'), 'submit_selectors');
	$this->Toolbar->addButton('remove', 'index.php?option=com_chronocontact&act=delete', 'Delete', $this->Assets->image('remove', 'toolbar/'), 'submit_selectors');
?>
<form action="index.php?option=com_chronocontact" method="post" name="admin_form" id="admin_form">
	<?php
		echo $this->DataTable->headerPanel($this->DataTable->_l($this->Paginator->getList()));
		$this->DataTable->create();
		$this->DataTable->header(
			array(
				'CHECK' => $this->Toolbar->selectAll(),
				'Form.name' => $this->Sorter->link('Form Name', 'Form.name'),
				'Form.view' => 'Front View',
				'Form.published' => l_('PUBLISHED'),
				'Form.id' => $this->Sorter->link('Form ID', 'Form.id')
			)
		);
		$this->DataTable->cells($forms, array(
			'CHECK' => array(
				'style' => array('width' => '5%'),
				'html' => $this->Toolbar->selector('{Form.id}')
			),
			'Form.name' => array(
				'link' => 'index.php?option=com_chronocontact&act=edit&id={Form.id}',
				'style' => array('text-align' => 'left')
			),
			'Form.view' => array(
				'html' => '<a href="'.GCORE_ROOT_URL.'index.php?option=com_chronocontact&ccfname={Form.name}" target="_blank">View Form</a>',
			),
			'Form.published' => array(
				'link' => array('index.php?option=com_chronocontact&act=toggle&gcb={Form.id}&val=1&fld=published', 'index.php?option=com_chronocontact&act=toggle&gcb={Form.id}&val=0&fld=published'),
				'image' => array($this->Assets->image('disabled.png'), $this->Assets->image('enabled.png')),
				'style' => array('width' => '15%'),
			),
			'Form.id' => array(
				'style' => array('width' => '15%'),
			)
		));
		echo $this->DataTable->build();
		echo $this->DataTable->footerPanel($this->DataTable->_l($this->Paginator->getInfo()).$this->DataTable->_r($this->Paginator->getNav()));
	?>
</form>