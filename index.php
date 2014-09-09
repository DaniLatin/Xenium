<?php

/**
 * @author Danijel
 * @copyright 2012
 */


require ($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');
//echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//xMemcache::flush_memcache();
//$time_start = microtime_float();

$project_info = Projects::get_domain_project_info();

//$project_id = $project_info['project_id'];
$project_id = $project_info['project_id'];
$project_slug = $project_info['project_slug'];
$project_name = $project_info['project_name'];
//$project_template = Projects::get_project_template($project_id);
$project_template = Projects::get_template($project_info['template_slug']);



$project_settings = json_decode($project_info['project_settings'], true);

//@unlink($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/790ec46d3a132b1729e13c5c1924a04dc4531158^de4b34ba130b855ca0fecf6322255c037d5ea306.index.tpl.php');
@unlink($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/ghost_file.txt');
/*
$files = glob($_SERVER['DOCUMENT_ROOT'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/cache/*.index.tpl.php');
                                    
                                    foreach ($files as $file)
                                    {
                                        //echo $file;
                                        echo $file . '
                                        ';
                                        //unlink($file);
                                    }*/

/*
$project_id = Projects::get_project_id();
$project_slug = Projects::get_project_slug();
$project_name = Projects::get_project_name();
$project_template = Projects::get_project_template();
$project_settings = Projects::get_project_settings();
*/

$selected_language = Languages::get_current_language($project_id);
//$selected_language = 'sl';





Languages::language_redirect($selected_language);
//$translations = Translations::get_all_translations(); // REMOVE!!!

//$modified_translations = Translations::get_all_modified_translations();

//$xSmarty = new xSmarty($project_slug, $project_template, 0, true);   // no caching
$xSmarty = new xSmarty($project_slug, $project_template);   // caching

$xSmarty->load_commands($project_slug);

//$xSmarty->smarty_display('index.tpl');
/* OUTPUT DATA LOADING TIME*/
/*$time_end = microtime_float();
$time = $time_end - $time_start;

echo "Whole in $time seconds\n";*/



$xSmarty->smarty_display($project_settings);



?>