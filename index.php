<?php
/*
 * ****************************************************************************
 * xsitemap - MODULE FOR XOOPS CMS
 * Copyright (c) Urbanspaceman (http://www.takeaweb.it)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * @package    module\xsitemap\frontside
 * @author     Urbanspaceman (http://www.takeaweb.it)
 * @copyright  Urbanspaceman (http://www.takeaweb.it)
 * @copyright  XOOPS Project
 * @license    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @link       http://xoops.org XOOPS
 * @since      ::    1.00
 **/

$moduleDirName = basename(__DIR__);
require_once __DIR__ . '/../../mainfile.php';

//template assign
$GLOBALS['xoopsOption']['template_main'] = 'xsitemap_index.tpl';

include_once $GLOBALS['xoops']->path('header.php');
include_once $GLOBALS['xoops']->path('class/tree.php');
include_once $GLOBALS['xoops']->path('modules/xsitemap/class/plugin.php');
include_once $GLOBALS['xoops']->path('modules/xsitemap/include/functions.php');
include_once $GLOBALS['xoops']->path('modules/xsitemap/class/dummy.php');

$xsitemap_configs = $GLOBALS['xoopsModuleConfig'];

$xsitemap_show = xsitemapGenerateSitemap();

$GLOBALS['xoTheme']->addStylesheet($GLOBALS['xoops']->url("browse.php?modules/{$moduleDirName}/assets/css/style.css"));
$GLOBALS['xoopsTpl']->assign(array(
                                 'xsitemap'           => $xsitemap_show,
                                 'num_col'            => $xsitemap_configs['columns_number'],
                                 'show_sublink'       => $xsitemap_configs['show_sublink'],
                                 'show_subcategories' => $xsitemap_configs['show_subcategories']
                             ));

include_once $GLOBALS['xoops']->path('footer.php');