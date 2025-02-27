<?php declare(strict_types=1);

namespace XoopsModules\Xsitemap\Common;

/*
 Utility Class Definition

 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 *
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    https://xoops.org 2000-2020 &copy; XOOPS Project
 * @author       ZySpec <zyspec@yahoo.com>
 * @author       Mamba <mambax7@gmail.com>
 */

use XoopsModules\Xsitemap\Helper;

/** @var Helper $helper */

/**
 * Class SysUtility
 */
class SysUtility
{
    use VersionChecks;

    //checkVerXoops, checkVerPhp Traits

    use ServerStats;

    // getServerStats Trait

    use FilesManagement;

    // Files Management Trait

    /**
     * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
     * www.gsdesign.ro/blog/cut-html-string-without-breaking-the-tags
     * www.cakephp.org
     *
     * @param string $text         String to truncate.
     * @param int    $length       Length of returned string, including ellipsis.
     * @param string $ending       Ending to be appended to the trimmed string.
     * @param bool   $exact        If false, $text will not be cut mid-word
     * @param bool   $considerHtml If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    public static function truncateHtml(string $text, int $length = 100, string $ending = '...', bool $exact = false, bool $considerHtml = true): string
    {
        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (mb_strlen(\preg_replace('/<.*?' . '>/', '', $text)) <= $length) {
                return $text;
            }
            // splits all html-tags to scanable lines
            \preg_match_all('/(<.+?' . '>)?([^<>]*)/s', $text, $lines, \PREG_SET_ORDER);
            $total_length = \mb_strlen($ending);
            $open_tags    = [];
            $truncate     = '';
            foreach ($lines as $lineMatchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($lineMatchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (\preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $lineMatchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } elseif (\preg_match('/^<\s*\/(\S+?)\s*>$/s', $lineMatchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = \array_search($tag_matchings[1], $open_tags, true);
                        if (false !== $pos) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } elseif (\preg_match('/^<\s*([^\s>!]+).*?' . '>$/s', $lineMatchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        \array_unshift($open_tags, \mb_strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $lineMatchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = \mb_strlen(\preg_replace('/&[0-9a-z]{2,8};|&#\d{1,7};|[0-9a-f]{1,6};/i', ' ', $lineMatchings[2]));
                if ($total_length + $content_length > $length) {
                    // the number of characters which are left
                    $left            = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (\preg_match_all('/&[0-9a-z]{2,8};|&#\d{1,7};|[0-9a-f]{1,6};/i', $lineMatchings[2], $entities, \PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($left >= $entity[1] + 1 - $entities_length) {
                                $left--;
                                $entities_length += \mb_strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= mb_substr($lineMatchings[2], 0, $left + $entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                }
                $truncate     .= $lineMatchings[2];
                $total_length += $content_length;
                // if the maximum length is reached, get off the loop
                if ($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            }
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if ($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }

    /**
     * @param \Xmf\Module\Helper|null $helper
     * @param array|null              $options
     * @return \XoopsFormDhtmlTextArea|\XoopsFormEditor
     */
    public static function getEditor(?\Xmf\Module\Helper $helper = null, ?array $options = null)
    {
        if (null === $options) {
            $options           = [];
            $options['name']   = 'Editor';
            $options['value']  = 'Editor';
            $options['rows']   = 10;
            $options['cols']   = '100%';
            $options['width']  = '100%';
            $options['height'] = '400px';
        }
        if (null === $helper) {
            $helper = Helper::getInstance();
        }
        $isAdmin = $helper->isUserAdmin();
        if (\class_exists('XoopsFormEditor')) {
            if ($isAdmin) {
                $descEditor = new \XoopsFormEditor(\ucfirst((string) $options['name']), $helper->getConfig('editorAdmin'), $options, $nohtml = false, $onfailure = 'textarea');
            } else {
                $descEditor = new \XoopsFormEditor(\ucfirst((string) $options['name']), $helper->getConfig('editorUser'), $options, $nohtml = false, $onfailure = 'textarea');
            }
        } else {
            $descEditor = new \XoopsFormDhtmlTextArea(\ucfirst((string) $options['name']), $options['name'], $options['value']);
        }
        //        $form->addElement($descEditor);
        return $descEditor;
    }

    /**
     * @param $fieldname
     * @param $table
     *
     * @return bool
     */
    public static function fieldExists(string $fieldname, string $table): bool
    {
        global $xoopsDB;
        $sql    = "SHOW COLUMNS FROM   $table LIKE '$fieldname'";
        $result = self::queryFAndCheck($xoopsDB, $sql);

        return ($xoopsDB->getRowsNum($result) > 0);
    }

    /**
     * @param array|string $tableName
     * @param string       $idField
     * @param int          $id
     *
     * @return int|bool
     */
    public static function cloneRecord($tableName, string $idField, int $id)
    {
        $new_id = false;
        $table  = $GLOBALS['xoopsDB']->prefix($tableName);
        // copy content of the record you wish to clone
        $tempTable = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->query("SELECT * FROM $table WHERE $idField='$id' "), \MYSQLI_ASSOC) or exit('Could not select record');
        // set the auto-incremented id's value to blank.
        unset($tempTable[$idField]);
        // insert cloned copy of the original  record
        $result = $GLOBALS['xoopsDB']->queryF("INSERT INTO $table (" . \implode(', ', \array_keys($tempTable)) . ") VALUES ('" . \implode("', '", $tempTable) . "')") or exit($GLOBALS['xoopsDB']->error());

        if ($result) {
            // Return the new id
            $new_id = $GLOBALS['xoopsDB']->getInsertId();
        }

        return $new_id;
    }

    /**
     * Function responsible for checking if a directory exists, we can also write in and create an index.html file
     *
     * @param string $folder The full path of the directory to check
     */
    public static function prepareFolder(string $folder): void
    {
        try {
            if (!\is_dir($folder) && !\mkdir($folder) && !\is_dir($folder)) {
                throw new \RuntimeException(\sprintf('Unable to create the %s directory', $folder));
            }
            file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
        } catch (\Throwable $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n", '<br>';
        }
    }

    /**
     * @param string $tablename
     *
     * @return bool
     */

    public static function tableExists(string $tablename): bool
    {
        $ret   = false;
        $trace = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        \trigger_error(__FUNCTION__ . " is deprecated, called from {$trace[0]['file']}line {$trace[0]['line']}", E_USER_DEPRECATED);
        $GLOBALS['xoopsLogger']->addDeprecated(
            \basename(\dirname(__DIR__, 2)) . ' Module: ' . __FUNCTION__ . ' function is deprecated, please use Xmf\Database\Tables method(s) instead.' . " Called from {$trace[0]['file']}line {$trace[0]['line']}"
        );
        $sql    = "SHOW TABLES LIKE '$tablename'";
        $result = self::queryFAndCheck($GLOBALS['xoopsDB'], $sql);

        return $GLOBALS['xoopsDB']->getRowsNum($result) > 0;
    }

    /**
     * @param $field
     * @param $table
     * @return mixed
     */
    public static function addField($field, $table)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field;");

        return $result;
    }

    /**
     * Query and check if the result is a valid result set
     *
     * @param \XoopsMySQLDatabase $xoopsDB XOOPS Database
     * @param string              $sql     a valid MySQL query
     * @param int                 $limit   number of records to return
     * @param int                 $start   offset of first record to return
     *
     * @return \mysqli_result query result
     */
    public static function queryAndCheck(\XoopsMySQLDatabase $xoopsDB, string $sql, $limit = 0, $start = 0): \mysqli_result
    {
        $result = $xoopsDB->query($sql, $limit, $start);

        if (!$xoopsDB->isResultSet($result)) {
            throw new \RuntimeException(
                \sprintf(\_DB_QUERY_ERROR, $sql) . $xoopsDB->error(), \E_USER_ERROR
            );
        }

        return $result;
    }

    /**
     * QueryF and check if the result is a valid result set
     *
     * @param \XoopsMySQLDatabase $xoopsDB XOOPS Database
     * @param string              $sql     a valid MySQL query
     * @param int                 $limit   number of records to return
     * @param int                 $start   offset of first record to return
     *
     * @return \mysqli_result query result
     */
    public static function queryFAndCheck(\XoopsMySQLDatabase $xoopsDB, string $sql, $limit = 0, $start = 0): \mysqli_result
    {
        $result = $xoopsDB->queryF($sql, $limit, $start);

        if (!$xoopsDB->isResultSet($result)) {
            throw new \RuntimeException(
                \sprintf(\_DB_QUERY_ERROR, $sql) . $xoopsDB->error(), \E_USER_ERROR
            );
        }

        return $result;
    }
}
