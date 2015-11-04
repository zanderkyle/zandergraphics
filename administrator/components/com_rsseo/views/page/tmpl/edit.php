<?php
/**
* @version 1.0.0
* @package RSSEO! 1.0.0
* @copyright (C) 2009-2012 www.rsjoomla.com
* @license GPL, http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'page.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_rsseo&view=page&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" class="form-validate form-horizontal">
	<div class="row-fluid">
		<div class="span7">
			<?php $extra = $this->item->id ? '<a target="_blank" href="'.JURI::root().$this->item->url.'"><img src="'.JURI::root().'administrator/components/com_rsseo/assets/images/external-link.png" alt=""></a>' : ''; ?>
			<?php echo JHtml::_('rsfieldset.start', 'adminform'); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('url'), $this->form->getInput('url').$extra); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('title'), $this->form->getInput('title')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('keywords'), $this->form->getInput('keywords')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('keywordsdensity'), $this->form->getInput('keywordsdensity')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('description'), $this->form->getInput('description')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('canonical'), $this->form->getInput('canonical').'<div class="clr"></div><div id="rss_results"><ul id="rsResultsUl"></ul></div>'); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('frequency'), $this->form->getInput('frequency')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('priority'), $this->form->getInput('priority')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('level'), $this->form->getInput('level')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('original'), $this->form->getInput('original')); ?>
			<?php echo JHtml::_('rsfieldset.element', $this->form->getLabel('published'), $this->form->getInput('published')); ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
			
			<?php echo JHtml::_('rsfieldset.start', 'adminform', JText::_('COM_RSSEO_PAGE_ROBOTS')); ?>
			<?php foreach($this->form->getGroup('robots') as $field) { ?>
			<?php echo JHtml::_('rsfieldset.element', $field->label, $field->input); ?>
			<?php } ?>
			<?php echo JHtml::_('rsfieldset.end'); ?>
		</div>
		
		<?php if ($this->item->id && $this->item->crawled) { ?>
		<div class="span5">
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<?php echo JText::_('COM_RSSEO_PAGE_SEO_GRADE'); ?> 
						<?php $grade = ($this->item->grade <= 0) ? 0 : ceil($this->item->grade); ?>
					</h5>
					<div class="rsj-progress">
						<span class="<?php echo $this->item->color; ?>" style="width: <?php echo $grade; ?>%;">
							<span><?php echo $grade; ?>%</span>
						</span>
					</div>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<table class="table table-striped">
							<tbody>
								<?php if ($this->config->crawler_sef) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_SEF'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_SEFCHECK" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $url_sef = $this->item->params['url_sef'] == 1; ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $url_sef ? 'ok' : 'notok'; ?>.png" alt="" />
									</td>
									<td>
										<?php echo $url_sef ? JText::_('COM_RSSEO_CHECKPAGE_URL_SEF_YES') : JText::_('COM_RSSEO_CHECKPAGE_URL_SEF_NO'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_title_duplicate) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_DUPLICATE_PAGE_TITLES'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_TITLE_DUPLICATE" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $duplicate_title = $this->item->params['duplicate_title'] > 1; ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $duplicate_title ? 'notok' : 'ok'; ?>.png" alt="" />
									</td>
									<td>
										<?php echo $duplicate_title ? JText::sprintf('COM_RSSEO_CHECKPAGE_METATITLE_DUPLICATE_YES', ($this->item->params['duplicate_title'] - 1), md5($this->item->title)) : JText::_('COM_RSSEO_CHECKPAGE_METATITLE_DUPLICATE_NO'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_title_length) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_TITLE_LENGTH'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_TITLE_LENGTH" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $tlength = $this->item->params['title_length']; ?>
										<?php $titlelength = ($tlength == 0 || $tlength > 70 || $tlength < 10); ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $titlelength ? 'notok' : 'ok'; ?>.png" alt="" />
									</td>
									<td>
										<?php 
											if ($tlength == 0) 
												echo JText::_('COM_RSSEO_CHECKPAGE_METATITLE_LENGTH_0');
											else if ($tlength < 10)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METATITLE_LENGTH_SHORT',$tlength);
											else if ($tlength > 70)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METATITLE_LENGTH_LONG',$tlength);
											else echo JText::sprintf('COM_RSSEO_CHECKPAGE_METATITLE_LENGTH_OK',$tlength);
										?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_description_duplicate) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_DUPLICATE_PAGE_DESCRIPTION'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_DESCRIPTION_DUPLICATE" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $duplicate_desc = $this->item->params['duplicate_desc'] > 1; ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $duplicate_desc ? 'notok' : 'ok'; ?>.png" alt="" />
									</td>
									<td>
										<?php echo $duplicate_desc ? JText::sprintf('COM_RSSEO_CHECKPAGE_METADESC_DUPLICATE_YES', ($this->item->params['duplicate_desc'] - 1), md5($this->item->description)) : JText::_('COM_RSSEO_CHECKPAGE_METADESC_DUPLICATE_NO'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_description_length) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_DESCRIPTION_LENGTH'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_DESCRIPTION_LENGTH" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $dlength = $this->item->params['description_length']; ?>
										<?php $descrlength = ($dlength == 0 || $dlength > 150 || $dlength < 70); ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $descrlength ? 'notok' : 'ok'; ?>.png" alt="" />
									</td>
									<td>
										<?php 
											if ($dlength == 0) 
												echo JText::_('COM_RSSEO_CHECKPAGE_METADESC_LENGTH_0');
											else if ($dlength < 70)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METADESC_LENGTH_SHORT',$dlength);
											else if ($dlength > 150)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METADESC_LENGTH_LONG',$dlength);
											else echo JText::sprintf('COM_RSSEO_CHECKPAGE_METADESC_LENGTH_OK',$dlength);
										?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_keywords) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_KEYWORDS'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_KEYWORD_COUNT" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $keywordsnr = $this->item->params['keywords']; ?>
										<?php $keywords = $keywordsnr > 10 || $keywordsnr < 10; ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $keywords ? 'notok' : 'ok'; ?>.png" alt="" />
									</td>
									<td>
										<?php 
											if ($keywordsnr == 0)
												echo JText::_('COM_RSSEO_CHECKPAGE_METAKEYWORDS_0');
											else if ($keywordsnr < 10)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METAKEYWORDS_SMALL', $keywordsnr);
											else if ($keywordsnr > 10)
												echo JText::sprintf('COM_RSSEO_CHECKPAGE_METAKEYWORDS_BIG', $keywordsnr);
											else echo JText::_('COM_RSSEO_CHECKPAGE_METAKEYWORDS_OK');
										?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_headings) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_HEADINGS'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_HEADINGS" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $headings = $this->item->params['headings'] <= 0; ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $headings ? 'notok' : 'ok'; ?>.png" alt="" />
									</td>
									<td>
										<?php echo $headings ? JText::_('COM_RSSEO_CHECKPAGE_HEADINGS_ERROR') : JText::sprintf('COM_RSSEO_CHECKPAGE_HEADINGS_OK',$this->item->params['headings']); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_intext_links) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_IE_LINKS'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_IELINKS" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $ielinks = $this->item->params['links'] > 100; ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $ielinks ? 'notok' : 'ok'; ?>.png" alt="" />
									</td>
									<td>
										<?php echo $ielinks ? JText::_('COM_RSSEO_CHECKPAGE_IE_LINKS_ERROR') : JText::_('COM_RSSEO_CHECKPAGE_IE_LINKS_OK'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_images) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_IMAGES'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_IMG" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $images = $this->item->params['images'] > 10; ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $images ? 'notok' : 'ok'; ?>.png" alt="" />
									</td>
									<td>
										<?php echo $images ? JText::sprintf('COM_RSSEO_CHECKPAGE_IMAGES_ERROR',$this->item->params['images']) : JText::sprintf('COM_RSSEO_CHECKPAGE_IMAGES_OK',$this->item->params['images']); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_images_alt) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_IMAGES_W_ALT'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_IMG_ALT" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $images_alt = $this->item->params['images_wo_alt'] > 0; ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $images_alt ? 'notok' : 'ok'; ?>.png" alt="" />
									</td>
									<td>
										<?php echo $images_alt ? JText::sprintf('COM_RSSEO_CHECKPAGE_IMAGES_WO_ALT_ERROR',$this->item->params['images_wo_alt']) : JText::_('COM_RSSEO_CHECKPAGE_IMAGES_WO_ALT_OK'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<?php if ($this->config->crawler_images_hw) { ?>
								<tr>
									<td colspan="2">
										<strong><?php echo JText::_('COM_RSSEO_CHECK_FOR_PAGE_IMAGES_W_HW'); ?></strong>
									</td>
								</tr>
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_IMG_RESIZE" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
										<?php $images_hw = $this->item->params['images_wo_hw'] > 0; ?>
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/<?php echo $images_hw ? 'notok' : 'ok'; ?>.png" alt="" />
									</td>
									<td>
										<?php echo $images_hw ? JText::sprintf('COM_RSSEO_CHECKPAGE_IMAGES_WO_HW_ERROR',$this->item->params['images_wo_hw']) : JText::_('COM_RSSEO_CHECKPAGE_IMAGES_WO_HW_OK'); ?>
									</td>
								</tr>
								<?php } ?>
								
								<tr>
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_IMG_NAMES" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
									</td>
									<td>
										<?php echo JText::_('COM_RSSEO_CHECKPAGE_IMAGES_NAMES_DESC'); ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<?php if ($this->config->crawler_images_alt && !empty($this->item->imagesnoalt)) { ?>
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<?php echo JText::_('COM_RSSEO_PAGE_IMAGES_WITHOUT_ALT'); ?> 
					</h5>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<table class="table table-striped">
							<?php foreach ($this->item->imagesnoalt as $image) { ?>
							<tr>
								<td><?php echo $image; ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php if ($this->config->crawler_images_hw && !empty($this->item->imagesnowh)) { ?>
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<?php echo JText::_('COM_RSSEO_PAGE_IMAGES_WITHOUT_WH'); ?> 
					</h5>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<table class="table table-striped">
							<?php foreach ($this->item->imagesnowh as $image) { ?>
							<tr>
								<td><?php echo $image; ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php if ($this->config->keyword_density_enable) { ?>
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<?php echo JText::_('COM_RSSEO_PAGE_KEYWORD_DENSITY'); ?> 
					</h5>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<?php if (!empty($this->item->densityparams)) { ?>
						<table class="table table-striped">
							<?php foreach ($this->item->densityparams as $keyword => $value) { ?>
							<tr>
								<td style="vertical-align:middle;" width="6%">
									<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_DENSITY" target="_blank">
										<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
									</a>
								</td>
								<td><?php echo $keyword; ?></td>
								<td><?php echo $value; ?></td>
							</tr>
							<?php } ?>
						</table>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php if ($this->item->id) { ?>
			<div class="rsj-block">
				<div class="rsj-head">
					<h5>
						<a href="javascript:void(0)" onclick="rss_pagecheck(<?php echo $this->item->id; ?>);">
							<?php echo JText::_('COM_RSSEO_PAGE_CHECK_LOAD_SIZE'); ?> 
						</a>
						<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/loader.gif" alt="" id="loader" style="display:none; vertical-align: bottom;" />
					</h5>
				</div>
				<div class="rsj-content">
					<div class="rsj-box">
						<table class="table table-striped">
							<tbody>
								<tr id="pageloadtr" style="display:none;">
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_PAGELOAD" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
									</td>
									<td><?php echo JText::_('COM_RSSEO_CHECKPAGE_TOTAL_PAGE_DESCR'); ?></td>
									<td><span id="pageload"></span></td>
								</tr>
								<tr id="pagesizetr" style="display:none;">
									<td style="vertical-align:middle;" width="6%">
										<a href="http://www.rsjoomla.com/index.php?option=com_rsfirewall_kb&task=redirect&code=SEO_PAGESIZE" target="_blank">
											<img src="<?php echo JURI::root(); ?>administrator/components/com_rsseo/assets/images/icons/help.png" alt="" />
										</a>
									</td>
									<td><?php echo JText::_('COM_RSSEO_CHECKPAGE_PAGE_SIZE'); ?></td>
									<td><span id="pagesize"></span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>

	<?php echo JHTML::_('form.token'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('id'); ?>
	<?php echo JHTML::_('behavior.keepalive'); ?>
</form>

<script type="text/javascript">
var browser=navigator.appName;
if (browser == 'Opera')
	document.onkeyup = checkKeycode;
else document.onkeydown = checkKeycode;

$('jform_canonical').onkeyup = generateRSResults;
var xml = null;
var rs_results = null;
</script>