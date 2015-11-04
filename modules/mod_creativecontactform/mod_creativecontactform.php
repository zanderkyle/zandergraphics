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

// get a parameter from the module's configuration
$module_id = $module->id;
$form_id = $params->get('form_id',1);
//$class_suffix = $params->get('class_suffix','');

//include helper class
require_once JPATH_SITE.'/components/com_creativecontactform/helpers/helper.php';

$ccf_class = new CreativecontactformHelper;
$ccf_class->form_id = $form_id;
$ccf_class->type = 'module';
$ccf_class->class_suffix = '';
$ccf_class->module_id = $module_id;
echo $ccf_class->render_html();

?>