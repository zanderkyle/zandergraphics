<?php
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$doc = \GCore\Libs\Document::getInstance();
	$doc->_('datatable');
	$doc->_('jquery');
	$doc->_('jquery-ui');
	$doc->_('forms');
	$this->Toolbar->setTitle(l_('Validate your ChronoContact install'));
	$this->Toolbar->addButton('add', 'index.php?option=com_chronocontact&act=validateinstall', 'Validate', $this->Assets->image('confirm', 'toolbar/'));
?>
<form action="index.php?option=com_chronocontact&act=validateinstall" method="post" name="admin_form" id="admin_form">
	<?php echo $this->Html->formStart(); ?>
	<?php echo $this->Html->formSecStart(); ?>
	<?php echo $this->Html->formLine('domain', array('type' => 'custom', 'label' => 'Domain', 'code' => $domain, 'sublabel' => 'The domain name, this domain MUST match the domain name used to generate the key on ChronoEngine.com')); ?>
	<?php echo $this->Html->formLine('domain_name', array('type' => 'hidden', 'value' => $domain)); ?>
	<?php echo $this->Html->formLine('license_key', array('type' => 'text', 'label' => 'Validation Key', 'class' => 'L', 'sublabel' => 'The short validation key which you should generate using your sale# on www.chronoengine.com')); ?>
	<?php echo $this->Html->formLine('pid', array('type' => 'dropdown', 'label' => 'Product', 'options' => array(22 => 'ChronoContact 1 validation key subscription', 23 => 'ChronoContact 3 validation keys subscription', 24 => 'ChronoContact 5 validation keys subscription'), 'sublabel' => 'Your subscription title')); ?>
	<?php echo $this->Html->formLine('instantcode', array('type' => 'text', 'label' => 'Instant Code', 'class' => 'XXL', 'sublabel' => 'In some situations you may need to provide the instant code, you do not need to enter this code unless you had instructions to do so.')); ?>
	<?php echo $this->Html->formSecEnd(); ?>
	<?php echo $this->Html->formEnd(); ?>
</form>