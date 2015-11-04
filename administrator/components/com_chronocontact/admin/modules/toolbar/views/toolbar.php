<?php
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$doc = \GCore\Libs\Document::getInstance();
	$doc->_('jquery');
	$doc->_('jquery-ui');
	$doc->addJsFile($this->Assets->js('toolbar', ''));
	
	$doc->addCssFile('modules/toolbar/assets/css/toolbar.css');
	$buttons = $this->Toolbar->getButtons();
	$form_id = $this->Toolbar->getFormID();
?>
<?php
	$title = $this->Toolbar->getTitle();
	if(!empty($title)){
		echo '<h1 class="page_title">'.$title.'</h1>';
	}
?>
<?php if(!empty($buttons) AND !empty($form_id)): ?>
	<?php ob_start(); ?>
		jQuery(function() {
			<?php foreach($buttons as $id => $button): ?>
				jQuery("#toolbar-button-<?php echo $id; ?>").button({});		
			<?php endforeach; ?>
		});
		jQuery(document).ready(function($) {
			<?php foreach($buttons as $id => $button): ?>
				<?php if(!empty($button['image'])): ?>
					jQuery("#toolbar-button-<?php echo $id; ?>").css('background', 'url("<?php echo $button['image']; ?>") no-repeat top center #F6F6F6');
				<?php endif; ?>
				jQuery("#toolbar-button-<?php echo $id; ?>").on('click',
					function(){
						<?php if($button['type'] == 'link'): ?>
							window.location = '<?php echo $button['link']; ?>';
						<?php elseif($button['type'] == 'submit'):; ?>
							jQuery('#<?php echo $form_id; ?>').attr('action', '<?php echo $button['link']; ?>');
							jQuery('#<?php echo $form_id; ?>').submit();
						<?php elseif($button['type'] == 'submit_selectors'):; ?>
							if(jQuery('#<?php echo $form_id; ?> input.gc_selector:checked').length > 0){
								jQuery('#<?php echo $form_id; ?>').attr('action', '<?php echo $button['link']; ?>');
								jQuery('#<?php echo $form_id; ?>').submit();
							}else{
								alert("<?php echo $button['alert']; ?>");
							}
						<?php elseif($button['type'] == 'false'):; ?>
							return false;
						<?php else: ?>
							<?php echo $button['type']; ?>();
						<?php endif; ?>
					}
				);
			<?php endforeach; ?>
		});
	<?php
		$buffer = ob_get_clean();
		$doc->addJsCode($buffer);
	?>

	<div class="gcore-toolbar-box">
		<span class="ui-widget-header ui-corner-all gcore-toolbar">
			<?php foreach($buttons as $id => $button): ?>
			<button id="toolbar-button-<?php echo $id; ?>" class="toolbar-button"><?php echo $button['text']; ?></button>
			<?php endforeach; ?>
		</span>
	</div>
<?php endif; ?>