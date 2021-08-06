<?php

declare(strict_types=1);
/*
 * xSitemMap module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @package    module\Xsitemap\admin
 * @author     XOOPS Module Development Team
 * @copyright  XOOPS Project (https://xoops.org)
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @link       https://xoops.org XOOPS
 **/

use Xmf\Module\Admin;

if (isset($templateMain)) {
    $GLOBALS['xoopsTpl']->display("db:{$templateMain}");
}
xoops_cp_footer();
