<?php
/**
 * @author     Daniel Dimitrov <daniel@compojoom.com>
 * @date       09.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

// Load Compojoom library
require_once JPATH_LIBRARIES . '/compojoom/include.php';

require_once JPATH_SITE . '/modules/mod_cmc/helper.php';

$user = JFactory::getUser();
$form = modCMCHelper::getForm($module->id, $params);

$layout = $params->get("layout", "default");

if (!$user->guest)
{
	$status = modCMCHelper::getNewsletterStatus($params->get('listid'));

	if ($status)
	{
		if ($layout == 'default' && ($status->status == 'applied' || $status->status == 'pending'))
		{
			$layout = 'applied';
		}
	}
}

if (!isset($status))
{
	$status = new stdClass;
	$status->status = '';
}

if (!$form)
{
	$layout = 'error';
}

require JModuleHelper::getLayoutPath('mod_cmc', $layout);
