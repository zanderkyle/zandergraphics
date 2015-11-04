<?php
defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>
<?php
	$doc = \GCore\Libs\Document::getInstance();
	$doc->_('jquery');
	$doc->_('jquery-ui');
	$doc->_('forms');
	$doc->addCssFile('extensions/chronocontact/assets/css/fixes.css');
	$doc->__('tabs', '#details-panel');
	
	$this->Toolbar->addButton('apply', 'index.php?option=com_chronocontact&act=save&save_act=apply', 'Apply', $this->Assets->image('apply', 'toolbar/'));
	$this->Toolbar->addButton('save', 'index.php?option=com_chronocontact&act=save', 'Save', $this->Assets->image('save', 'toolbar/'));
	$this->Toolbar->addButton('cancel', 'index.php?option=com_chronocontact', 'Cancel', $this->Assets->image('cancel', 'toolbar/'), 'link');
?>
<script>
	jQuery(document).ready(function($) {
		var email_count = $('#email_count').val();
		var action_count = $('#action_count').val();
		// tabs init with a custom tab template and an "add" callback filling in the content
		var $emails = $('#emails').tabs({
			tabTemplate: "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove</span></li>",
			add: function(event, ui){
				var tab_content = $('#email_generic_config').html().replace(/{N}/ig, action_count);//$tab_content_input.val() || "Tab " + email_count + " content.";
				$(ui.panel).append(tab_content);
			}
		});
		// modal dialog init: custom buttons and a "close" callback reseting the form inside
		var $add_email_dialog = $('#add_email_dialog').dialog({
			autoOpen: false,
			modal: true,
			buttons: {
				Add: function() {
					addEmail();
					$(this).dialog('close');
				}
			},
			close: function() {
				$('#email_label').val('');
			}
		});
		// actual addEmail function: adds new tab using the title input from the form above
		function addEmail(){
			var email_label = $('#email_label').val() || 'Email #' + email_count;
			$emails.tabs('add', '#emails-' + email_count, email_label);
			$('#configemail'+action_count+'action_label').val(email_label);
			email_count++;
			$('#email_count').val(email_count);
			action_count++;
			$('#action_count').val(action_count);
		}

		// addEmail button: just opens the dialog
		$('#add_email').button().click(function(){
			$add_email_dialog.dialog('open');
		});

		// close icon: removing the tab on click
		// note: closable tabs gonna be an option in the future - see http://dev.jqueryui.com/ticket/3924
		$('#emails span.ui-icon-close').live('click', function(){
			var index = $('li', $emails).index($(this).parent());
			$emails.tabs('remove', index);
		});
	});
</script>
<script>
	jQuery(document).ready(function($) {
		var fields_count = <?php echo (!empty($wizard_fields) ? max(array_keys($wizard_fields)) + 1 : 1); ?>;
		var $add_field_dialog = $('#add_field_dialog').dialog({
			autoOpen: false,
			modal: true,
			buttons: {
				'Insert': function() {
					addField();
					$(this).dialog('close');
				}
			},
			close: function() {
				$('#email_label').val('');
			}
		});
		$('#add_new_field').button().click(function(){
			$add_field_dialog.dialog('open');
		});
		$('#preview').sortable({
			deactivate: function(){
				$(this).find('.onhover').remove();
			}
		});
		//var $wizard = $('#wizard-area').tabs();
		function addField(){
			var type = $('#field_type_selector').val();
			
			var $new_element = $('#'+type+'_origin').clone().removeAttr('id');
			$new_element.html($new_element.html().replace(/{N}/ig, fields_count));
			
			var $new_element_config = $('#'+type+'_origin_config').clone().removeAttr('id').addClass('config_box');
			$new_element_config.html($new_element_config.html().replace(/{N}/ig, fields_count));
			
			
			//$new_element_config.wrap($('<td/>', {css:{'display':'none'}}));//.wrap($('<tr/>'));
			$new_element_config.css({'display':'none'});
			
			$new_element.css('width', '100%');
			$new_element.find('td').attr('style', 'width: auto !important');
			//$new_element.find('tr').append($new_element_config.parent());
			var $new_element_box = $('<div/>', {'class':'element_box'});
			
			addLinks($new_element);
			
			$new_element_box.append($new_element);
			$new_element_box.append($new_element_config);
			
			$('#preview').append($new_element_box);
			fields_count++;
		}
		
		function addLinks(Element){
			Element.on('mouseenter', function(){
				var editLink = $('<a/>', {
					text:'Edit',
					href:'javascript:void(null);',
					css:{'padding-left':'10px','cursor':'pointer','font-weight':'bold','color':'blue'}
				}).on('click', function(){
					var config_box = Element.parent().find('.config_box').clone().dialog({
						autoOpen: false,
						modal: true,
						height: 400,
						width: 550,
						title : 'Edit Element Settings',
						buttons: {
							'Save & Close': function() {
								$('#preview').append($('<tr id="loading_gif"><td><img src="<?php echo $this->Assets->image('loading.gif'); ?>" /></td></tr>'));
								var obj = {};
								$(this).find(':input').each(function(i, inp){
									var inpData = $(inp).val();
									if($(inp).attr('alt') == 'options'){
										inpData = {};
										var lines = $(inp).val().split("\n");
										$.each(lines, function(ln, ld){
											var opts = ld.split('=');
											inpData[opts[0]] = opts[1];
										});
									}
									if($(inp).attr('alt') == 'multiline'){
										inpData = {};
										var lines = $(inp).val().split("\n");
										inpData = lines;
									}
									obj[$(inp).attr('name')] = inpData;
								});
								$.ajax({
									url: 'index.php?option=com_chronocontact&act=render_field&tvout=ajax',
									data: obj
								}).done(function(msg){
									$('#loading_gif').remove();
									var $newElem = $(msg);
									$newElem.find('td').attr('style', 'width: auto !important');
									Element.replaceWith($newElem);
									Element = $newElem.css('width', '100%').removeAttr('id');
									addLinks(Element);
								});
								$(this).dialog('close');
								$(this).parent().addClass('remove_dialog');
								$(this).css({'display':'none'});
								//Element.parent().find('.config_box').replaceWith($(this));
								$(this).find(':input').each(function(i, inp){
									var propelem = Element.parent().find(':input[name="'+$(inp).attr('name')+'"]');
									if(propelem.get(0).nodeName.toLowerCase() == 'textarea'){
										propelem.text($(inp).val());
									}else if(propelem.get(0).nodeName.toLowerCase() == 'select'){
										propelem.find('option').each(function(){$(this).attr('selected', ($(this).val() == $(inp).val()));});
									}else{
										propelem.val($(inp).val());
									}
								});
								$('.remove_dialog').remove();
							},
							'Cancel': function(){$(this).dialog('close');}
						},
					});
					config_box.dialog('open');
				}).wrap($('<td/>', {'class':'onhover', css:{'display':'inline-block','vertical-align':'top','padding-top':'7px'}}));
				
				var deleteLink = $('<a/>', {
					text:'Delete',
					href:'javascript:void(null);',
					css:{'padding-left':'100px','cursor':'pointer','font-weight':'bold','color':'red'}
				}).on('click', function(){
					$(this).closest('.element_box').remove();
				}).wrap($('<td/>', {'class':'onhover', css:{'display':'inline-block','vertical-align':'top','padding-top':'7px'}}));
				
				var sortLink = $('<a/>', {
					text:'Drag me',
					'title':'asd asdasd asd',
					href:'javascript:void(null);',
					css:{'padding-left':'10px','cursor':'pointer','font-weight':'bold','color':'green'}
				}).on('click', function(){
					
				}).wrap($('<td/>', {'class':'onhover', css:{'display':'inline-block','vertical-align':'top','padding-top':'7px'}}));
				
				Element.find('tr:first').append(editLink.parent());				
				Element.find('tr:first').append(sortLink.parent());
				Element.find('tr:first').append(deleteLink.parent());
				Element.css('background-color', '#D0F5A9');
			});
			Element.on('mouseleave', function(){
				Element.find('.onhover').remove();
				Element.css('background-color', 'transparent');
			});
		}
		//fix old fields
		$('.element_box .original_element_conifg').css('display', 'none').addClass('config_box').removeAttr('id');
		$('.element_box .original_element').css('width', '100%').removeAttr('id');
		$('.element_box .original_element').each(function(i, El){
			$(El).find('td').attr('style', 'width: auto !important');
			addLinks($(El));
		});
		//sortable
	});
</script>
<form action="index.php?option=com_chronocontact&act=save" method="post" name="admin_form" id="admin_form">
	<?php echo $this->Html->input('id', array('type' => 'hidden')); ?>
	
	<div id="details-panel">
		<ul>
			<li><a href="#general">General Settings</a></li>
			<li><a href="#layout-wizard">Designer</a></li>
			<li><a href="#form-code">Code</a></li>
			<li><a href="#data-processing">Data Processing</a></li>
			<li><a href="#emails-panel">Set up Emails</a></li>
			<li><a href="#thanks-message">Thanks Message</a></li>
			<li><a href="#anti-spam">Anti Spam</a></li>
			<li><a href="#uploads">Uploads</a></li>
			<li><a href="#validation">Validation</a></li>
			<li><a href="#after-submit">After Submit</a></li>
		</ul>
		<div id="general">
			<?php echo $this->Html->formStart(); ?>
			<?php echo $this->Html->formSecStart(); ?>
			<?php echo $this->Html->formLine('name', array('type' => 'text', 'id' => 'gform_name', 'label' => 'Form name', 'class' => 'XL', 'sublabel' => 'Unique form name without spaces or any special characters, underscores _ or dashes -')); ?>
			<?php echo $this->Html->formLine('published', array('type' => 'dropdown', 'label' => 'Published', 'options' => array(0 => l_('NO'), 1 => l_('YES')), 'values' => 1)); ?>
			<?php echo $this->Html->formLine('params[form_method]', array('type' => 'dropdown', 'label' => 'Form method', 'options' => array('post' => 'Post', 'get' => 'Get', 'file' => 'File'), 'default' => 'post', 'sublabel' => 'Choose your form method, File is necessary to get file uploads working.')); ?>
			<?php echo $this->Html->formLine('params[action_url]', array('type' => 'text', 'label' => 'Submit URL', 'class' => 'XL', 'sublabel' => 'Adding a submit URL will disable all the form auto functions like emails...etc')); ?>
			<?php echo $this->Html->formLine('params[form_class]', array('type' => 'text', 'label' => 'Form Class', 'value' => 'cc_form', 'sublabel' => 'The class attribute value for the generated form tag.')); ?>
			<?php echo $this->Html->formLine('params[form_tag_attach]', array('type' => 'text', 'label' => 'Form tag attachment', 'class' => 'XL', 'value' => htmlspecialchars($params->get('form_tag_attach', '')), 'sublabel' => 'Extra attributes to be added to the <form ... > tag, e.g: onsubmit="someJSFunction();"')); ?>
			<?php echo $this->Html->formSecEnd(); ?>
			<?php echo $this->Html->formEnd(); ?>
		</div>
		<div id="form-code">
			<?php echo $this->Html->formStart(); ?>
			<?php echo $this->Html->formSecStart(); ?>
			<?php echo $this->Html->formLine('form_type', array('type' => 'dropdown', 'label' => 'Form type', 'options' => array(1 => 'Wizard Designer', 0 => 'Custom Code'), 'sublabel' => 'If Form Type is set to Custom Code then the Designer fields will be ignored.')); ?>
			<?php echo $this->Html->formLine('content', array('type' => 'textarea', 'label' => 'HTML code', 'rows' => 20, 'cols' => 100, 'sublabel' => 'May contain PHP code with tags', 'value' => (!empty($form['content']) ? htmlspecialchars($form['content']) : ''))); ?>
			<?php echo $this->Html->formLine('extras[css]', array('type' => 'textarea', 'label' => 'CSS code', 'rows' => 10, 'cols' => 100, 'sublabel' => 'NO Style tags, May contain PHP code with tags', 'value' => (!empty($form['extras']) ? htmlspecialchars($form['extras']['css']) : ''))); ?>
			<?php echo $this->Html->formLine('extras[js]', array('type' => 'textarea', 'label' => 'JS code', 'rows' => 10, 'cols' => 100, 'sublabel' => 'NO Script tags, May contain PHP code with tags', 'value' => (!empty($form['extras']) ? htmlspecialchars($form['extras']['js']) : ''))); ?>
			<?php echo $this->Html->formSecEnd(); ?>
			<?php echo $this->Html->formEnd(); ?>
		</div>
		<div id="layout-wizard">
			<div id="add_field_dialog" class="add_action_dialog" title="Insert New Field">
				<?php echo $this->Html->formSecStart(); ?>
				<?php
					$foptions = array();
					foreach($fields_types as $type){
						$class = '\GCore\Admin\Extensions\Chronocontact\Fields\\'.\GCore\Libs\Str::camilize($type);
						$foptions[$type] = $class::$title;
					}
				?>
				<?php echo $this->Html->formLine('field_type_selector', array('type' => 'dropdown', 'id' => 'field_type_selector', 'label' => 'Field Type', 'options' => $foptions)); ?>
				<?php echo $this->Html->formSecEnd(); ?>
			</div>
			
			<div id="wizard-area" class="actions_tabs">
				<!--<ul>
					<li><a href="#preview">Preview</a></li>
				</ul>-->
				<div id="preview">
					<?php
						if(!empty($wizard_fields)){
							foreach($wizard_fields as $k => $wizard_field){
								$type = isset($wizard_field['render_type']) ? $wizard_field['render_type'] : $wizard_field['type'];
								$class = '\GCore\Admin\Extensions\Chronocontact\Fields\\'.\GCore\Libs\Str::camilize($type);
								echo '<div class="element_box">';
								$element_info = $wizard_field;
								if(isset($element_info['options'])){
									$options = array();
									if(!empty($element_info['options'])){
										$lines = explode("\n", $element_info['options']);
										foreach($lines as $line){
											$opts = explode("=", $line);
											$options[$opts[0]] = $opts[1];
										}
									}
									$element_info['options'] = $options;
								}
								if(isset($element_info['values'])){
									$values = array();
									if(!empty($element_info['values'])){
										$values = explode("\n", $element_info['values']);
									}
									$element_info['values'] = $values;
								}
								$class::element($element_info);
								$class::config($wizard_field, $k);
								echo '</div>';
							}
						}
					?>
				</div>
				<br />
				<a id="add_new_field" href="#" onClick="return false;" style="margin:0 40% 0 40%">Add New Field</a>
			</div>
			<div id="origins" style="display:none;">
				<?php
					foreach($fields_types as $type){
						$class = '\GCore\Admin\Extensions\Chronocontact\Fields\\'.\GCore\Libs\Str::camilize($type);
						$class::element();
						$class::config();
					}
				?>
			</div>
		</div>
		<div id="data-processing">
			<?php $this->FormsConfig->load_config('handle_arrays', 108, (array)$form['config']); ?>
		</div>
		<div id="emails-panel">
			<div id="add_email_dialog" class="add_action_dialog" title="Email Label">
				<?php echo $this->Html->formSecStart(); ?>
				<?php echo $this->Html->formLine('email_label', array('type' => 'text', 'id' => 'email_label', 'label' => 'Email Label', 'sublabel' => 'A label identifier for your email in the wizard.')); ?>
				<?php echo $this->Html->formSecEnd(); ?>
			</div>
			<a id="add_email" href="#" onClick="return false;">Add Email</a>

			<div id="emails" class="actions_tabs">
				<?php if(!empty($form['config']['email'])): ?>
					<ul>
						<?php foreach($form['config']['email'] as $k => $vs): ?>
							<li><a href="#emails-<?php echo $k; ?>"><?php echo !empty($form['config']['email'][$k]['action_label']) ? $form['config']['email'][$k]['action_label'] : "Email #".$k; ?></a> <span class="ui-icon ui-icon-close">Remove Tab</span></li>
						<?php endforeach; ?>
					</ul>
					<?php foreach($form['config']['email'] as $k => $vs): ?>
						<div id="emails-<?php echo $k; ?>">
							<?php $this->FormsConfig->load_config('email', $k, (array)$form['config']); ?>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<ul>
						<li><a href="#emails-1">Email #1</a> <span class="ui-icon ui-icon-close">Remove Tab</span></li>
					</ul>
					<div id="emails-1">
						<?php $this->FormsConfig->load_config('email', 1, (array)$form['config']); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<div id="thanks-message">
			<?php $this->FormsConfig->load_config('thanks_message', 101, (array)$form['config']); ?>
		</div>
		<div id="anti-spam">
			<?php $this->FormsConfig->load_config('check_captcha', 103, (array)$form['config']); ?>
			<?php $this->FormsConfig->load_config('load_captcha', 102, (array)$form['config']); ?>
		</div>
		<div id="uploads">
			<?php $this->FormsConfig->load_config('file_upload', 104, (array)$form['config']); ?>
		</div>
		<div id="validation">
			<?php $this->FormsConfig->load_config('validation', 105, (array)$form['config']); ?>
		</div>
		<div id="after-submit">
			<?php $this->FormsConfig->load_config('custom_code', 106, (array)$form['config'], array('label' => "Just after submit")); ?>
			<?php $this->FormsConfig->load_config('custom_code', 107, (array)$form['config'], array('label' => "End of submit routine")); ?>
		</div>
	</div>
</form>
<div id="email_generic_config" class="generic_action_config">
	<?php $this->FormsConfig->load_config('email'); ?>
</div>
<?php
	$max_action_count = 2;
	if(!empty($form['config']['email'])){
		foreach($form['config']['email'] as $c => $d){
			if($c > $max_action_count){
				$max_action_count = $c;
			}
		}
	}
?>
<input name="action_count" id="action_count" value="<?php echo !empty($form['config']['email']) ? count($form['config']['email']) + 1 : 2; ?>" type="hidden" />
<input name="email_count" id="email_count" value="<?php echo !empty($form['config']['email']) ? count($form['config']['email']) + 1 : 2; ?>" type="hidden" />