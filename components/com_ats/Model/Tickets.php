<?php
/**
 * @package   AkeebaTicketSystem
 * @copyright Copyright (c)2011-2015 Nicholas K. Dionysopoulos
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\TicketSystem\Site\Model;

use Akeeba\TicketSystem\Admin\Helper\Permissions;
use JText;

defined('_JEXEC') or die;

/**
 * Class Tickets
 *
 * @method  $this   frontendfilter($bool)   Enable frontend filtering
 * @method  $this   filterNewest($bool)     Order by newest ticket
 * @method  $this   assignedtome($bool)     Only tickets assigned to me
 * @method  $this   categories($cats)       Filter by categories (array)
 *
 * @package Akeeba\TicketSystem\Site\Model
 */
class Tickets extends \Akeeba\TicketSystem\Admin\Model\Tickets
{
    public function buildQuery($override = false)
    {
        $db      = $this->getDbo();
        $user_id = $this->container->platform->getUser()->id;

        // On frontend we use category instead of catid for filtering
        $this->setState('catid', $this->getState('category'));

        $query = parent::buildQuery($override);

        // Filter only public tickets and tickets visible to me
        if($this->getState('frontendfilter') == 1)
        {
            $category = $this->getState('category');

            $isManager = Permissions::isManager($category);

            if(!$isManager && ($user_id > 0))
            {
                // I am not a manager and I have a user ID
                $query->where(
                '('.
                    $db->qn('public').' = '.$db->q(1).
                    ' OR ('.$db->qn('public').' = '.$db->q(0).' AND '.$db->qn('created_by').' = '.$db->q($user_id).')'.
                ')'
                );
            }
            elseif(!$isManager)
            {
                // I am a guest
                $query->where($db->qn('public').' = '.$db->q(1));
            }
        }

        // Filter by category
        if($categories = $this->getState('categories', array(), 'array'))
        {
            $categories = array_map(array($db, 'quote'), $categories);

            $query->where($db->qn('catid').' IN ('.implode(',', $categories).')');
        }

        // Only tickets assigned to me
        if($this->getState('assignedtome', 0, 'int') == 1)
        {
            if($user_id > 0)
            {
                $query->where($db->qn('assigned_to').' = '.$db->q($user_id));
            }
        }

        // Apply custom ordering
        $query->clear('order');

        $dir = strtoupper($this->getState('filter_order_Dir', 'DESC', 'cmd'));

        if (!in_array($dir,array('ASC','DESC')))
        {
            $dir = 'ASC';
        }

        $filterNewest = $this->getState('filterNewest', false, 'bool');

        if ($filterNewest)
        {
            // The modified_on field is always filled thanks to our custom behavior
            $order = 'modified_on';
        }
        else
        {
            $order = $this->getState('filter_order', 'ats_ticket_id', 'cmd');

            if (!in_array($order, array_keys($this->knownFields)))
            {
                $order = 'ats_ticket_id';
            }
        }

        $query->order($order.' '.$dir);

        return $query;
    }

    /**
     * Loads and decode ticket custom fields data, returning an array conataining labels and values
     *
     * @return   array   Array list of array following this logic:
     *                   array('label' => field label, 'value' => field value)
     */
    public function loadCustomFields()
    {
        $customFields = array();

        $ticket_id = $this->getId();

        // Got no ticket id, return an empty set
        if(!$ticket_id)
        {
            return $customFields;
        }

        // No fields to decode
        if(!$this->params)
        {
            return $customFields;
        }

        /** @var CustomFields $customModel */
        $customModel = $this->container->factory->model('CustomFields')->tmpInstance();
        $fields = $customModel->enabled(true)->get(true);

        // Organize the fields
        /** @var CustomFields $field */
        foreach($fields as $field)
        {
            $decoded[$field->slug] = $field;
        }

        foreach ($this->params as $slug => $value)
        {
            if(!isset($decoded[$slug]))
            {
                continue;
            }

            switch ($decoded[$slug]->type)
            {
                // Display a "yes" or "no" string
                case 'checkbox':
                    $value = strtolower($value) == 'on' ? JText::_('JYES') : JText::_('JNO');
                    break;
                // Display the value label, not the value itself
                case 'dropdown':
                case 'list':
                case 'radio':
                    //Use of non-capturing group: end of line OR new line
                    preg_match('/'.$value.'=(.*?)(?:$|\n)/i', $decoded[$slug]->options, $matches);

                    if(isset($matches[1]))
                    {
                        $value = $matches[1];
                    }

                    break;
                case 'multiselect':

                    $values = array();

                    foreach($value as $selected)
                    {
                        preg_match('/'.$selected.'=(.*?)(?:$|\n)/i', $decoded[$slug]->options, $matches);
                        if(isset($matches[1]))
                        {
                            $values[] = trim($matches[1]);
                        }
                    }

                    $value = implode(', ', $values);

                    break;
                default:
                    ;
                    break;
            }

            $customFields[] = array(
                'label' => $decoded[$slug]->title,
                'value' => $value);
        }

        return $customFields;
    }
}
