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
 * @license    GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link       https://xoops.org XOOPS
 * @since      1.00
 */

/**
 * Class Plugin
 */
class Plugin extends \XoopsObject
{
    private $plugin_id;
    private $plugin_name;
    private $plugin_mod_version;
    private $plugin_mod_table;
    private $plugin_cat_id;
    private $plugin_cat_pid;
    private $plugin_cat_name;
    private $plugin_weight;
    private $plugin_where;
    private $plugin_call;
    private $plugin_submitter;
    private $plugin_date_created;
    private $plugin_online;
    // to allow html
    private $dohtml;

    //Constructor
    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('plugin_id', \XOBJ_DTYPE_INT, null, false, 8);
        $this->initVar('plugin_name', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('plugin_mod_version', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('plugin_mod_table', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('plugin_cat_id', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('plugin_cat_pid', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('plugin_cat_name', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('plugin_weight', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('plugin_where', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('plugin_call', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('plugin_submitter', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('plugin_date_created', \XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('plugin_online', \XOBJ_DTYPE_INT, null, false, 1);
        // to allow html
        $this->initVar('dohtml', \XOBJ_DTYPE_INT, 1, false);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getVar('plugin_name');
    }

    /**
     * @param bool $action
     * @return \XoopsThemeForm
     */
    public function getForm(bool $action): \XoopsThemeForm
    {
        if (!$action) {
            $action = $_SERVER['REQUEST_URI'];
        }
        if ($this->isNew()) {
            $title               = \_AM_XSITEMAP_PLUGIN_ADD;
            $plugin_date_created = \time();
            $plugin_online       = 1;
        } else {
            $title               = \_AM_XSITEMAP_PLUGIN_EDIT;
            $plugin_date_created = $this->getVar('plugin_date_created');
            $plugin_online       = $this->getVar('plugin_online');
        }
        //            $title = $this->isNew() ? sprintf(_AM_XSITEMAP_PLUGIN_ADD) : sprintf(_AM_XSITEMAP_PLUGIN_EDIT);
        require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new \XoopsFormText(\_AM_XSITEMAP_PLUGIN_NAME, 'plugin_name', 50, 255, $this->getVar('plugin_name')), true);
        $form->addElement(new \XoopsFormText(\_AM_XSITEMAP_PLUGIN_MOD_VERSION, 'plugin_mod_version', 50, 255, $this->getVar('plugin_mod_version')), true);
        $form->addElement(new \XoopsFormText(\_AM_XSITEMAP_PLUGIN_MOD_TABLE, 'plugin_mod_table', 50, 255, $this->getVar('plugin_mod_table')), true);
        $form->addElement(new \XoopsFormText(\_AM_XSITEMAP_PLUGIN_CAT_ID, 'plugin_cat_id', 50, 255, $this->getVar('plugin_cat_id')), true);
        $form->addElement(new \XoopsFormText(\_AM_XSITEMAP_PLUGIN_CAT_PID, 'plugin_cat_pid', 50, 255, $this->getVar('plugin_cat_pid')), true);
        $form->addElement(new \XoopsFormText(\_AM_XSITEMAP_PLUGIN_CAT_NAME, 'plugin_cat_name', 50, 255, $this->getVar('plugin_cat_name')), true);
        $form->addElement(new \XoopsFormText(\_AM_XSITEMAP_PLUGIN_WEIGHT, 'plugin_weight', 50, 255, $this->getVar('plugin_weight')), true);
        $form->addElement(new \XoopsFormText(\_AM_XSITEMAP_PLUGIN_WHERE, 'plugin_where', 50, 255, $this->getVar('plugin_where')), false);
        $form->addElement(new \XoopsFormText(\_AM_XSITEMAP_PLUGIN_CALL, 'plugin_call', 50, 255, $this->getVar('plugin_call')), true);
        $form->addElement(new \XoopsFormSelectUser(\_AM_XSITEMAP_PLUGIN_SUBMITTER, 'plugin_submitter', false, $this->getVar('plugin_submitter'), 1, false), true);
        //            $plugin_date_created = $this->isNew() ? time() : $this->getVar("plugin_date_created");
        $form->addElement(new \XoopsFormTextDateSelect(\_AM_XSITEMAP_PLUGIN_DATE_CREATED, 'plugin_date_created', '', $plugin_date_created));
        //            $plugin_online = $this->isNew() ? 1 : $this->getVar("plugin_online");
        $check_plugin_online = new \XoopsFormCheckBox(\_AM_XSITEMAP_PLUGIN_ONLINE, 'plugin_online', $plugin_online);
        $check_plugin_online->addOption(1, ' ');
        $form->addElement($check_plugin_online);
        $form->addElement(new \XoopsFormHidden('op', 'save_plugin'));
        if (!$this->isNew()) {
            $form->addElement(new \XoopsFormHidden('plugin_id', $this->getVar('plugin_id')));
        }
        $form->addElement(new \XoopsFormButtonTray('submit', \_SUBMIT));

        return $form;
    }

    /**
     * Get Values
     * @param array|null  $keys
     * @param string|null $format
     * @param int|null    $maxDepth
     * @return array
     */
    public function getValuesPlugins(?array $keys = null, ?string $format = null, ?int $maxDepth = null): array
    {
        $ret                 = $this->getValues($keys, $format, $maxDepth);
        $ret['date_created'] = \formatTimestamp($this->getVar('plugin_date_created'), 'm');
        $ret['submitter']    = \XoopsUser::getUnameFromId($this->getVar('plugin_submitter'));

        return $ret;
    }
}
