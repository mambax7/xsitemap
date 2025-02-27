<?php declare(strict_types=1);
/*
 * ****************************************************************************
 * xsitemap - MODULE FOR XOOPS CMS
 * Copyright (c) Urbanspaceman (https://www.takeaweb.it)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * ****************************************************************************
 */

/**
 * Module: xsitemap
 *
 * @author     XOOPS Module Development Team
 * @author     Urbanspaceman (https://www.takeaweb.it)
 * @copyright  Urbanspaceman (https://www.takeaweb.it)
 * @copyright  XOOPS Project (https://xoops.org)
 * @license    GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link       https://xoops.org XOOPS
 * @since      1.00
 *
 * @uses       Xmf\Module\Admin
 */

use Xmf\Module\Admin;
use XoopsModules\Xsitemap\{
    Helper,
    Plugin,
    PluginHandler,
    Utility
};
/** @var Helper $helper */
/** @var PluginHandler $pluginHandler */
/** @var Plugin $offlineObj */
/** @var Utility $utility */
require_once __DIR__ . '/admin_header.php';
// Display Admin header
xoops_cp_header();
$adminObject  = Admin::getInstance();
$templateMain = 'xsitemap_admin_index.tpl';
// Get online plugin info
//$countPlugins       = $pluginHandler->getCount();
$criteria           = new \Criteria('plugin_online', 1);
$pluginHandler      = $helper->getHandler('Plugin');
$onlinePluginObjs   = [];
$onlinePluginObjs   = $pluginHandler->getAll($criteria);
$countPluginsOnline = !empty($onlinePluginObjs) ? count($onlinePluginObjs) : 0;
$onlinePluginArray  = [];
/** @var \XoopsObject $onlineObj */
foreach ($onlinePluginObjs as $onlineObj) {
    $onlinePluginArray[] = $onlineObj->getVar('plugin_name');
}
natsort($onlinePluginArray);
$onlinePluginNames = implode(', ', $onlinePluginArray);
// get offline plugin info
$criteria            = new \Criteria('plugin_online', 0);
$offlinePluginObjs   = $pluginHandler->getAll($criteria);
$countPluginsOffline = !empty($offlinePluginObjs) ? count($offlinePluginObjs) : 0;
$offlinePluginArray  = [];
foreach ($offlinePluginObjs as $offlineObj) {
    $offlinePluginArray[] = $offlineObj->getVar('plugin_name');
}
natsort($offlinePluginArray);
$offlinePluginNames = implode(', ', $offlinePluginArray);
$adminObject->addInfoBox(_AM_XSITEMAP_MANAGER_INDEX);
// display number of plugins online
$adminObject->addInfoBoxLine(sprintf(_AM_XSITEMAP_THEREARE_PLUGIN_ONLINE, $countPluginsOnline), '', 'green');
// display number of plugins offline
$adminObject->addInfoBoxLine(sprintf(_AM_XSITEMAP_THEREARE_PLUGIN_OFFLINE, $countPluginsOffline), '', 'green');
// display total number of plugins
$adminObject->addInfoBoxLine(sprintf(_AM_XSITEMAP_THEREARE_PLUGIN, $countPluginsOnline + $countPluginsOffline), '', 'green');
$adminObject->addConfigBoxLine(sprintf(_AM_XSITEMAP_PLUGIN_ONLINE_NAMES, $onlinePluginNames), 'information');
$adminObject->addConfigBoxLine(sprintf(_AM_XSITEMAP_PLUGIN_OFFLINE_NAMES, $offlinePluginNames), 'information');
$adminObject->displayNavigation(basename(__FILE__));
//$adminObject->displayIndex();
$GLOBALS['xoopsTpl']->assign('index', $adminObject->displayIndex());
$GLOBALS['xoopsTpl']->assign('serverstats', $utility::getServerStats());
//echo $utility::getServerStats();
require_once __DIR__ . '/admin_footer.php';
