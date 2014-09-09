<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.translate.php
 * Type:     function
 * Name:     translate
 * Purpose:  output translated text in current language
 * -------------------------------------------------------------
 */

function smarty_function_translate($params, &$smarty)
{
    global $selected_language;
    
    if (isset($params['string']) && $params['string'] != '' && !empty($params['string']))
    {
        $translate_string = $params['string'];
        
        $translation = new Translations;
        $output = $translation->get_translation($translate_string, $selected_language);
        
        if (isset($params['replace']) && !empty($params['replace']))
        {
            $replace = $params['replace'];
            $replace_array = explode('|', $replace);
            foreach ($replace_array as $replacement)
            {
                $replacement_value = explode('=', $replacement);
                $output = str_replace($replacement_value[0], $replacement_value[1], $output);
            }
        }
        
        if (isset($params['modifiers']) && $params['modifiers'] != '' && !empty($params['modifiers']))
        {
            $modifiers = explode(', ', $params['modifiers']);
            foreach ($modifiers as $modifier)
            {
                $output = call_user_func($modifier, $output);
                //$output = Translations::get_modified_translation($translate_string, $selected_language, $modifier);
            }
        }
       
        return $output;
    }
    else
    {
        return '';
    }
}

?>