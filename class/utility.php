<?php
/*
 Xsitemap Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Module:  xSitemap
 *
 * @package      \module\xsitemap\class
 * @license      http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright    https://xoops.org 2001-2017 &copy; XOOPS Project
 * @author       ZySpec <owners@zyspec.com>
 * @author       Mamba <mambax7@gmail.com>
 * @since        File available since version 1.54
 */

/**
 * XsitemapUtility
 *
 * Static utility class to provide common functionality
 *
 */
class XsitemapUtility extends XoopsObject
{
    /**
     *
     * Verifies XOOPS version meets minimum requirements for this module
     * @static
     * @param XoopsModule $module
     *
     * @param null        $requiredVer
     * @return bool true if meets requirements, false if not
     */
    public static function checkVerXoops(XoopsModule $module = null, $requiredVer = null)
    {
        $moduleDirName = basename(dirname(__DIR__));
        if (null === $module) {
            $module = XoopsModule::getByDirname($moduleDirName);
        }
        xoops_loadLanguage('admin', $moduleDirName);

        //check for minimum XOOPS version
        $currentVer = substr(XOOPS_VERSION, 6); // get the numeric part of string
        if (null === $requiredVer) {
            $requiredVer = '' . $module->getInfo('min_xoops'); //making sure it's a string
        }
        $success     = true;

        if (version_compare($currentVer, $requiredVer, '<')){
            $success     = false;
            $module->setErrors(sprintf(_AM_XSITEMAP_ERROR_BAD_XOOPS, $requiredVer, $currentVer));
        }

        return $success;
    }

    /**
     *
     * Verifies PHP version meets minimum requirements for this module
     * @static
     * @param XoopsModule $module
     *
     * @return bool true if meets requirements, false if not
     */
    public static function checkVerPhp(XoopsModule $module)
    {
        xoops_loadLanguage('admin', $module->dirname());
        // check for minimum PHP version
        $success = true;
        $verNum  = PHP_VERSION;
        $reqVer  =& $module->getInfo('min_php');
        if ((false !== $reqVer) && ('' !== $reqVer)) {
            if (version_compare($verNum, (string)$reqVer, '<')) {
                $module->setErrors(sprintf(_AM_XSITEMAP_ERROR_BAD_PHP, $reqVer, $verNum));
                $success = false;
            }
        }

        return $success;
    }

    /**
     *
     * Remove files and (sub)directories
     *
     * @param string $src source directory to delete
     *
     * @uses \Xmf\Module\Helper::getHelper()
     * @uses \Xmf\Module\Helper::isUserAdmin()
     *
     * @return bool true on success
     */
    public static function deleteDirectory($src)
    {
        // Only continue if user is a 'global' Admin
        if (!($GLOBALS['xoopsUser'] instanceof XoopsUser) || !$GLOBALS['xoopsUser']->isAdmin()) {
            return false;
        }

        $success = true;
        // remove old files
        $dirInfo = new SplFileInfo($src);
        // validate is a directory
        if ($dirInfo->isDir()) {
            $fileList = array_diff(scandir($src, SCANDIR_SORT_NONE), ['..', '.']);
            foreach ($fileList as $k => $v) {
                $fileInfo = new SplFileInfo("{$src}/{$v}");
                if ($fileInfo->isDir()) {
                    // recursively handle subdirectories
                    if (!$success = self::deleteDirectory($fileInfo->getRealPath())) {
                        break;
                    }
                } else {
                    // delete the file
                    if (!($success = unlink($fileInfo->getRealPath()))) {
                        break;
                    }
                }
            }
            // now delete this (sub)directory if all the files are gone
            if ($success) {
                $success = rmdir($dirInfo->getRealPath());
            }
        } else {
            // input is not a valid directory
            $success = false;
        }
        return $success;
    }

    /**
     *
     * Recursively remove directory
     *
     * @todo currently won't remove directories with hidden files, should it?
     *
     * @param string $src directory to remove (delete)
     *
     * @return bool true on success
     */
    public static function rrmdir($src)
    {
        // Only continue if user is a 'global' Admin
        if (!($GLOBALS['xoopsUser'] instanceof XoopsUser) || !$GLOBALS['xoopsUser']->isAdmin()) {
            return false;
        }

        // If source is not a directory stop processing
        if (!is_dir($src)) {
            return false;
        }

        $success = true;

        // Open the source directory to read in files
        $iterator = new DirectoryIterator($src);
        foreach ($iterator as $fObj) {
            if ($fObj->isFile()) {
                $filename = $fObj->getPathname();
                $fObj     = null; // clear this iterator object to close the file
                if (!unlink($filename)) {
                    return false; // couldn't delete the file
                }
            } elseif (!$fObj->isDot() && $fObj->isDir()) {
                // Try recursively on directory
                self::rrmdir($fObj->getPathname());
            }
        }
        $iterator = null;   // clear iterator Obj to close file/directory
        return rmdir($src); // remove the directory & return results
    }

    /**
     * Recursively move files from one directory to another
     *
     * @param string $src  - Source of files being moved
     * @param string $dest - Destination of files being moved
     *
     * @return bool true on success
     */
    public static function rmove($src, $dest)
    {
        // Only continue if user is a 'global' Admin
        if (!($GLOBALS['xoopsUser'] instanceof XoopsUser) || !$GLOBALS['xoopsUser']->isAdmin()) {
            return false;
        }

        // If source is not a directory stop processing
        if (!is_dir($src)) {
            return false;
        }

        // If the destination directory does not exist and could not be created stop processing
        if (!is_dir($dest) && !mkdir($dest, 0755)) {
            return false;
        }

        // Open the source directory to read in files
        $iterator = new DirectoryIterator($src);
        foreach ($iterator as $fObj) {
            if ($fObj->isFile()) {
                rename($fObj->getPathname(), "{$dest}/" . $fObj->getFilename());
            } elseif (!$fObj->isDot() && $fObj->isDir()) {
                // Try recursively on directory
                self::rmove($fObj->getPathname(), "{$dest}/" . $fObj->getFilename());
                //                rmdir($fObj->getPath()); // now delete the directory
            }
        }
        $iterator = null;   // clear iterator Obj to close file/directory
        return rmdir($src); // remove the directory & return results
    }

    /**
     * Recursively copy directories and files from one directory to another
     *
     * @param string $src  - Source of files being moved
     * @param string $dest - Destination of files being moved
     *
     * @uses \Xmf\Module\Helper::getHelper()
     * @uses \Xmf\Module\Helper::isUserAdmin()
     *
     * @return bool true on success
     */
    public static function rcopy($src, $dest)
    {
        // Only continue if user is a 'global' Admin
        if (!($GLOBALS['xoopsUser'] instanceof XoopsUser) || !$GLOBALS['xoopsUser']->isAdmin()) {
            return false;
        }

        // If source is not a directory stop processing
        if (!is_dir($src)) {
            return false;
        }

        // If the destination directory does not exist and could not be created stop processing
        if (!is_dir($dest) && !mkdir($dest, 0755)) {
            return false;
        }

        // Open the source directory to read in files
        $iterator = new DirectoryIterator($src);
        foreach ($iterator as $fObj) {
            if ($fObj->isFile()) {
                copy($fObj->getPathname(), "{$dest}/" . $fObj->getFilename());
            } elseif (!$fObj->isDot() && $fObj->isDir()) {
                self::rcopy($fObj->getPathname(), "{$dest}/" . $fObj->getFilename());
            }
        }
        return true;
    }
}
