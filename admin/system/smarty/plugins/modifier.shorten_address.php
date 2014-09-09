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





function smarty_modifier_shorten_address($string)
{
	$exploded = explode(',', $string);
    if (isset($exploded[0]))
    {
        $just_address = trim($exploded[0]);
    }
    else
    {
        $just_address = '';
    }
    
    if (isset($exploded[1]))
    {
        $just_city = trim(str_replace(range(0,9),'',$exploded[1]));
    }
    else
    {
        $just_city = '';
    }
    
    if ($just_address != '' && $just_city != '')
    {
        return $just_address . ', ' . $just_city;
    }
    elseif ($just_address != '' && $just_city == '')
    {
        return $just_address;
    }
    elseif ($just_address == '' && $just_city != '')
    {
        return $just_city;
    }
    else
    {
        return '';
    }
}




?>