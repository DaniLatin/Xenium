<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty replace modifier plugin
 *
 * Type:     modifier<br>
 * Name:     seolinker<br>
 * Purpose:  simple seolinker
 * @link http://smarty.php.net/manual/en/language.modifier.replace.php
 *          replace (Smarty online manual)
 * @author   Dani <monte at ohrt dot com>
 * @param string
 * @param string
 * @param string
 * @return string
 */





function smarty_modifier_seolinker($string)
{
	setlocale(LC_CTYPE, 'en_US.UTF8');
    $string = strip_tags($string);
    $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
    $string = preg_replace('/\\s+/', '-', $string);
    $string = str_replace('_', '-', $string);
    $string = str_replace('&Scaron;', 's', $string);
    $string = str_replace('&scaron;', 's', $string);
    $string = preg_replace("/[^a-z-\d ]/i", '', $string);
    return strtolower($string);
}




?>