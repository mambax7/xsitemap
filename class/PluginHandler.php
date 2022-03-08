<?php declare(strict_types=1);

namespace XoopsModules\Xsitemap;

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
 * @author     XOOPS Module Development Team
 * @author     Urbanspaceman (https://www.takeaweb.it)
 * @copyright  Urbanspaceman (https://www.takeaweb.it)
 * @copyright  XOOPS Project (https://xoops.org)
 * @license    https://www.fsf.org/copyleft/gpl.html GNU public license
 * @link       https://xoops.org XOOPS
 * @since      1.00
 */

/**
 * Class PluginHandler
 */
class PluginHandler extends \XoopsPersistableObjectHandler
{
    /**
     * PluginHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'xsitemap_plugin', Plugin::class, 'plugin_id', 'plugin_name');
    }
}
