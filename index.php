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
 * @author     Urbanspaceman (https://www.takeaweb.it)
 * @copyright  Urbanspaceman (https://www.takeaweb.it)
 * @copyright  XOOPS Project
 * @license    GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link       https://xoops.org XOOPS
 * @since      1.00
 **/

use XoopsModules\Xsitemap\Utility;

/** @var Utility $utility */
require_once __DIR__ . '/header.php';
$moduleDirName = basename(__DIR__);
$utility       = new Utility();

//template assign
$GLOBALS['xoopsOption']['template_main'] = 'xsitemap_index.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once $GLOBALS['xoops']->path('class/tree.php');

$xsitemap_configs = $GLOBALS['xoopsModuleConfig'];
$xsitemap_show    = $utility::generateSitemap();
$GLOBALS['xoTheme']->addStylesheet('modules/' . $moduleDirName . '/assets/css/style.css');
$GLOBALS['xoopsTpl']->assign(
    [
        'xsitemap'           => $xsitemap_show,
        'num_col'            => $xsitemap_configs['columns_number'],
        'show_sublink'       => $xsitemap_configs['show_sublink'],
        'show_subcategories' => $xsitemap_configs['show_subcategories'],
    ]
);
require_once XOOPS_ROOT_PATH . '/footer.php';
