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
 */

/**
 * Module: xsitemap
 *
 * @author          XOOPS Module Development Team
 * @author          Urbanspaceman (https://www.takeaweb.it)
 * @copyright       Urbanspaceman (https://www.takeaweb.it)
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link            https://xoops.org XOOPS
 * @since           1.00
 */

use Xmf\Module\Admin;
use XoopsModules\Xsitemap\{
    Helper
};
/** @var Helper $helper */

include \dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName      = \basename(\dirname(__DIR__));
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');
$pathIcon32    = Admin::menuIconPath('');
$pathModIcon32 = XOOPS_URL . '/modules/' . $moduleDirName . '/assets/images/icons/32/';
if (is_object($helper->getModule()) && false !== $helper->getModule()->getInfo('modicons32')) {
    $pathModIcon32 = $helper->url($helper->getModule()->getInfo('modicons32'));
}
$adminmenu = [
    [
        'title' => _MI_XSITEMAP_MANAGER_INDEX,
        'link'  => 'admin/index.php',
        'icon'  => $pathIcon32 . '/home.png',
    ],
    [
        'title' => _MI_XSITEMAP_MANAGER_PLUGIN,
        'link'  => 'admin/plugin.php',
        'icon'  => 'assets/images/admin/plugin.png',
    ],
    [
        'title' => _MI_XSITEMAP_MANAGER_XML,
        'link'  => 'admin/xml.php',
        'icon'  => 'assets/images/admin/xml.png',
    ],
    [
        'title' => _MI_XSITEMAP_MANAGER_ABOUT,
        'link'  => 'admin/about.php',
        'icon'  => $pathIcon32 . '/about.png',
    ],
];
