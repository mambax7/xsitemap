<?php declare(strict_types=1);
/**
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
 * @since      ::    1.00
 **/

use XoopsModules\Xsitemap\Utility;

$moduleDirName = basename(__DIR__);

//template assign
$GLOBALS['xoopsOption']['template_main'] = 'xsitemap_xml.tpl';
require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';

$xmlfile       = XOOPS_ROOT_PATH . '/xsitemap.xml';
$xsitemap_show = Utility::generateSitemap();
if (!empty($xsitemap_show)) {
    $retVal = Utility::saveSitemap($xsitemap_show);
    if (false !== $retVal) {
        $stat   = stat($xmlfile);
        $status = formatTimestamp($stat['mtime'], _DATESTRING);
    } else {
        $status = _AM_XSITEMAP_XML_ERROR_UPDATE;
    }
} else {
    $status = _AM_XSITEMAP_XML_ERROR_UPDATE;
}
$xoopsTpl->assign('lastmod', $status);
require_once XOOPS_ROOT_PATH . '/footer.php';
