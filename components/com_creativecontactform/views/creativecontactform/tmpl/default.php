<?php
/**
 * Joomla! component Creative Contact Form
 *
 * @version $Id: 2012-04-05 14:30:25 svn $
 * @author creative-solutions.net
 * @package Creative Contact Form
 * @subpackage com_creativecontactform
 * @license GNU/GPL
 *
 */

// no direct access
defined('_JEXEC') or die('Restircted access');

$form_id = (int) $_REQUEST['form'];

$ccf_class = new CreativecontactformHelper;
$ccf_class->form_id = $form_id;
$ccf_class->type = 'component';
$ccf_class->class_suffix = '';
$ccf_class->module_id = 0;
echo $ccf_class->render_html();

?>