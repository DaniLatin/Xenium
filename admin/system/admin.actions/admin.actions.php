<?php

/**
 * @author Danijel
 * @copyright 2013
 */

require($_SERVER['DOCUMENT_ROOT'] . '/admin/system/settings/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/admin/theme/js/jAPI-CORE.php');

if (!isset($_SESSION['admin_user']))
{
    die('<script type="text/javascript"> window.location = "/admin/login/"; </script>');
}

if (isset($_POST['ArgOne'])){
    $id = $_POST['ArgOne'];
    //echo $project_id;
}

if (isset($_POST['ArgTwo'])){
    $project_id = $_POST['ArgTwo'];
    //echo $project_id;
}

//echo json_encode($_POST);
//$project_id = $_POST['ArgTwo'];

class AdminAction
{
    private $methods = array();
    private $plugins = array();
    
    public $prop;
    public $projects = array();
    //public $projects = Projects::get_all_projects();
    
    public function __construct()
    {
        $this->prop = 'neki';
        $this->projects = Projects::get_all_projects();
        $this->load_plugins();
    }
    
    protected function load_plugins()
    {
        $base = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/plugins/';
        $plugins = glob($base . '*.php');
        foreach($plugins as $plugin)
        {
            include_once $plugin;
            $name = basename($plugin, '.php');
            $className = $name;
            $obj = new $className();
            $this->plugins[$name] = $obj;

            foreach (get_class_methods($obj) as $method )
                 $this->methods[$method] = $name;
            
            foreach (get_object_vars($obj) as $var_name => $var_value )
            {
                if (property_exists(get_class($this), $var_name))
                {
                    if (is_array($this->{$var_name}))
                    {
                        $this->{$var_name} = array_merge_recursive($this->{$var_name}, $var_value);
                    }
                    else
                    {
                        $this->{$var_name} = $this->{$var_name} . $var_value;
                    }
                    
                }
                else
                {
                    $this->{$var_name} = $var_value;
                }
            }
        }
    }
    
    public function __call($method, $args)
    {
        if(! key_exists($method, $this->methods))
           throw new Exception ("Call to undefined method: " . $method);
 
           array_unshift($args, $this);
           return call_user_func_array(array($this->plugins[$this->methods[$method]], $method), $args);
    }
    
    public function load_prop($prop_name)
    {
        global $project_id, $additional_menu, $additional_text_editor, $additional_text_editor_modal;
        $projects = Projects::get_all_projects();
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function load_text_editor_prop($prop_name, $project_id)
    {
        global $additional_menu, $additional_text_editor, $additional_text_editor_modal;
        $projects = Projects::get_all_projects();
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    /*
    public function load_modal_prop($prop_name, $project_id)
    {
        global $additional_menu, $additional_text_editor, $additional_text_editor_modal;
        $projects = Projects::get_all_projects();
        $current_project_offers = Offer::offers_get_by_project($project_id);
        $project_info = Projects::get_project_info_by_id($project_id);
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    */
    public function load_modal_prop($prop_name, $project_data)
    {
        global $additional_menu, $additional_text_editor, $additional_text_editor_modal;
        
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function load_first_page_prop($prop_name, $project_id)
    {
        $fp = new FirstPage;
        $editing = 'FirstPage';
        $first_page = $fp->first_page_get_by_project_id($project_id);
        //$first_page[0] = $first_page[1]; //print_r($first_page);
        //print_r($first_page);
        $first_page_id = $first_page[0]['id'];
        $fields = $fp->fields;
        $populate_content = $fp->first_page_get_by_project_id($project_id); //print_r($populate_content);
        $project_info = Projects::get_project_info_by_id($project_id);
        $save_function = 'AdminAction.save_first_page';
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function load_project_prop($prop_name, $project_id)
    {
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function load_static_content_prop($prop_name, $id, $project_id)
    {
        $GLOBALS['project_id'] = $project_id;
        
        $sc = new StaticContent;
        $editing = 'StaticContent';
        $fields = $sc->fields;
        $populate_content = $sc->static_contents_get_by_id($id);
        $content_title = $populate_content[0]['title'];
        $project_info = Projects::get_project_info_by_id($project_id);
        $save_function = 'AdminAction.save_static_content';
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function load_blog_prop($prop_name, $id, $project_id)
    {
        $GLOBALS['project_id'] = $project_id;
        
        $blog = new Blog;
        $editing = 'Blog';
        $fields = $blog->fields;
        $populate_content = $blog->get_by_id($id);
        $content_title = $populate_content[0]['title'];
        $project_info = Projects::get_project_info_by_id($project_id);
        $save_function = 'AdminAction.save_blog';
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function load_text_block_prop($prop_name, $id, $project_id)
    {
        $GLOBALS['project_id'] = $project_id;
        
        $tb = new TextBlock;
        $editing = 'TextBlock';
        $fields = $tb->fields;
        $populate_content = $tb->get_by_id($id);
        $content_title = $populate_content[0]['title'];
        $project_info = Projects::get_project_info_by_id($project_id);
        $save_function = 'AdminAction.save_text_block';
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function load_email_prop($prop_name, $id, $project_id)
    {
        $xMail_id = new Emails;
        $editing = 'Emails';
        $fields = $xMail_id->emails_fields;
        $populate_content = $xMail_id->email_get_by_id($id);
        $content_title = $populate_content[0]['title'];
        $project_info = Projects::get_project_info_by_id($project_id);
        $save_function = 'AdminAction.save_email';
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function load_text_editor($project_id = 'all')
    {
        global $additional_text_editor, $additional_text_editor_modal;
        $additional_text_editor = array();
        $additional_text_editor_modal = array();
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/class.*.php';
        foreach (glob($filename) as $filefound)
        {
            //echo "$filefound size " . filesize($filefound) . "\n";
            $tokens = token_get_all(file_get_contents($filefound));
            $comments = array();
            $counter = 0;
            foreach($tokens as $token) {
                if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
                    $comments[] = $token[1];
                    $module_name_pattern = "/@moduleName (.*?)\n/";
                    $module_text_editor_pattern = "/@moduleTextEditor (.*?)\n/";
                    $module_text_editor_modal_pattern = "/@moduleTextEditorModal (.*?)\n/";
                    //preg_match($module_category_pattern, $token[1], $category_matches);
                    preg_match($module_name_pattern, $token[1], $name_matches);
                    preg_match($module_text_editor_pattern, $token[1], $text_editor_matches);
                    preg_match($module_text_editor_modal_pattern, $token[1], $text_editor_modal_matches);
                    //print_r($name_matches);
                    //print_r($text_editor_matches);
                    
                    
                    
                    if (isset($text_editor_matches[1]) && preg_replace('/\s+/', '', $text_editor_matches[1]) == 'true')
                    {
                        $class_name = preg_replace('/\s+/', '', $name_matches[1]);
                        
                        if ((double)phpversion() >= 5.3)
                        {
                            $additional_text_editor[$class_name] = call_user_func($class_name .'::' . preg_replace('/\s+/', '', strtolower($name_matches[1])) . '_text_editor_add');
                        }
                        else
                        {
                            $additional_text_editor[$counter] = call_user_func(array($class_name, preg_replace('/\s+/', '', strtolower($name_matches[1])) . '_text_editor_add'));
                        }
                    }
                    
                    if (isset($text_editor_modal_matches[1]) && preg_replace('/\s+/', '', $text_editor_modal_matches[1]) == 'true')
                    {
                        if ((double)phpversion() >= 5.3)
                        {
                            $additional_text_editor_modal[$class_name] = call_user_func_array($class_name .'::' . preg_replace('/\s+/', '', strtolower($name_matches[1])) . '_text_editor_modal_add', array($project_id));
                        }
                        else
                        {
                            $additional_text_editor_modal[$counter] = call_user_func_array(array($class_name, preg_replace('/\s+/', '', strtolower($name_matches[1])) . '_text_editor_modal_add'), array($project_id));
                        }
                    }
                    
                    $counter++;
                }
            }
        }
        //print_r($additional_text_editor);
        echo self::load_text_editor_prop('text_editor', $project_id);
    }
    
    public function load_left_menu($menu_name)
    {
        // bof get all dynamic contents
        global $additional_menu;
        $additional_menu = array();
        $filename = $_SERVER['DOCUMENT_ROOT'] . '/admin/system/classes.modules/class.*.php';
        foreach (glob($filename) as $filefound)
        {
            //echo "$filefound size " . filesize($filefound) . "\n";
            $tokens = token_get_all(file_get_contents($filefound));
            $comments = array();
            foreach($tokens as $token) {
                if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT) {
                    $comments[] = $token[1];
                    $module_category_pattern = "/@moduleCategory (.*?)\n/";
                    $module_name_pattern = "/@moduleName (.*?)\n/";
                    preg_match($module_category_pattern, $token[1], $category_matches);
                    preg_match($module_name_pattern, $token[1], $name_matches);
                    //print_r($matches);
                    if (isset($category_matches[1])){
                        if (isset($name_matches[1])) $left_menu_additional[$category_matches[1]] = array($name_matches[1]);
                        //print_r($left_menu_additional);
                        if (!isset($additional_menu[preg_replace('/\s+/', '', $category_matches[1])]))
                        {
                            if ((double)phpversion() >= 5.3)
                            {
                                $additional_menu[preg_replace('/\s+/', '', $category_matches[1])] = call_user_func(preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($name_matches[1])) . '_menu_add');
                            }
                            else
                            {
                                $additional_menu[preg_replace('/\s+/', '', $category_matches[1])] = call_user_func(array(preg_replace('/\s+/', '', $name_matches[1]), preg_replace('/\s+/', '', strtolower($name_matches[1])) . '_menu_add'));
                            }
                            //$additional_menu[preg_replace('/\s+/', '', $category_matches[1])] = call_user_func(preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($name_matches[1])) . '_menu_add');
                            
                        }
                        else
                        {
                            if ((double)phpversion() >= 5.3)
                            {
                                $additional_menu[preg_replace('/\s+/', '', $category_matches[1])] .= call_user_func(preg_replace('/\s+/', '', $name_matches[1]) .'::' . preg_replace('/\s+/', '', strtolower($name_matches[1])) . '_menu_add');
                            }
                            else
                            {
                                $additional_menu[preg_replace('/\s+/', '', $category_matches[1])] .= call_user_func(array(preg_replace('/\s+/', '', $name_matches[1]), preg_replace('/\s+/', '', strtolower($name_matches[1])) . '_menu_add'));
                            }
                        }
                        
                        //$additional_menu[preg_replace('/\s+/', '', $category_matches[1])] = '';
                        //$new_class = new preg_replace('/\s+/', '', $name_matches[1]);
                        //$additional_menu[preg_replace('/\s+/', '', $category_matches[1])] .= call_user_func(array($offer, preg_replace('/\s+/', '', strtolower($name_matches[1])) . '_menu_add'));
                        //echo str_replace(' ', '', $category_matches[1]);
                        //echo $additional_menu[preg_replace('/\s+/', '', $category_matches[1])];
                    } 
                    //if (isset($name_matches[1])) echo str_replace(' ', '', $name_matches[1]);
                }
            }
            //print_r($comments);
        }
        // eof get all dynamic contents
        
        echo self::load_prop('left_menu_' . $menu_name);
    }
    
    public function contents_default()
    {
        global $projects;
        $projects = Projects::get_all_projects();
        echo self::load_prop('main_container_contents_default');
    }
    
    public function dashboard_default()
    {
        echo self::load_prop('main_container_dashboard_default');
    }
    
    public function applications_default($period)
    {
        global $set_period, $payment_stats, $today_count, $today_amount, $week_count, $week_amount, $month_count, $month_amount;
        
        if ($period == 'undefined')
        {
            $set_period = 'day';
        }
        else
        {
            $set_period = $period;
        }
        
        $payment_stats = Payment::get_payment_stats($set_period);
        
        echo self::load_prop('main_container_applications_default');
    }
    
    public function users_default($limit_start, $limit, $search_type, $search_term)
    {
        global $users, $projects, $items_count, $page_limit_start, $page_limit, $search_type_load, $search_term_load;
        
        $search_type_load = $search_type;
        $search_term_load = $search_term;
        
        if ($limit_start == 'undefined')
        {
            $limit_start = 0;
        }
        if ($limit == 'undefined')
        {
            $limit = 10;
        }
        
        $page_limit_start = $limit_start;
        $page_limit = $limit;
        
        //$offers = Offer::offers_get_all();
        if ($search_type == 'undefined')
        {
            $items_count = Users::users_get_count();
        }
        elseif ($search_type == 'search-simple')
        {
            $items_count = Users::users_get_simple_search_count($search_term);
        }
        elseif ($search_type == 'search-advanced')
        {
            $items_count = Users::users_get_advanced_search_count($search_term);
        }
        
        $users = Users::users_get_all_limit_search($limit_start, $limit, $search_type, $search_term);
        $projects = Projects::get_all_projects();
        echo self::load_prop('main_container_users_default');
    }
    
    public function users_stats($period)
    {
        global $set_period, $users_stats, $today_count, $today_activated, $week_count, $week_activated, $month_count, $month_activated;
        
        if ($period == 'undefined')
        {
            $set_period = 'day';
        }
        else
        {
            $set_period = $period;
        }
        
        $users_stats = Users::get_users_stats($set_period);
        
        echo self::load_prop('main_container_users_stats');
    }
    
    public function multimedia_default()
    {
        echo self::load_prop('main_container_multimedia_default');
    }
    
    public function settings_default()
    {
        echo self::load_prop('main_container_settings_default');
    }
    
    public function edit_first_page($project_id)
    {
        echo self::load_first_page_prop('main_container_edit_first_page', $project_id);
    }
    
    public function save_first_page($id, $project_id, $new_values)
    {
        FirstPage::first_page_save($id, $project_id, $new_values);
    }
    
    public function static_contents_default()
    {
        global $static_contents;
        $static_contents = StaticContent::static_contents_get_all();
        echo self::load_prop('main_container_static_contents_default');
    }
    
    public function blog_default()
    {
        global $blog_posts;
        $blog_posts = Blog::get_all();
        echo self::load_prop('main_container_blog_default');
    }
    
    public function textblock_default()
    {
        global $text_blocks;
        $text_blocks = TextBlock::get_all();
        echo self::load_prop('main_container_text_blocks_default');
    }
    
    public function slideshow_default()
    {
        global $slideshows;
        $slideshows = Slideshow::slideshow_get_all();
        echo self::load_prop('main_container_slideshow_default');
    }
    
    public function add_new_slideshow($title, $project_id)
    {
        $new_id = Slideshow::slideshow_insert_new($title, $project_id);
        echo '<a id="redirect" class="follow" style="display: none;" href="/admin/interface/contents/edit_slideshow/' . $new_id . '_' . $project_id . '/"></a><script type="text/javascript"> $("#redirect").trigger("click"); </script>';
    }
    
    public function edit_slideshow($send_id, $send_project_id)
    {
        global $id, $project_id, $editing, $populate_content, $content_title, $project_info, $save_function, $languages, $tab_counter;
        $id = $send_id;
        $project_id = $send_project_id;
        $slide = new Slideshow;
        $editing = 'Slideshow';
        $fields = $slide->fields;
        $populate_content = $slide->slideshow_get_by_id($id);
        $content_title = $populate_content[0]['title'];
        $project_info = Projects::get_project_info_by_id($project_id);
        $save_function = 'AdminAction.save_slideshow';
        $languages = Languages::get_all_www_languages($project_id);
        $tab_counter = 0;
        echo self::load_prop('main_container_slideshow_edit');
    }
    
    public function save_slideshow($id, $project_id, $new_values)
    {
        Slideshow::slideshow_save($id, $project_id, $new_values);
    }
    
    public function trash_slideshow($id)
    {
        Slideshow::slideshow_to_trash($id);
    }
    
    public function order_slideshow($order)
    {
        Slideshow::slideshow_set_order($order);
    }
    
    public function add_new_static_content($title, $project_id)
    {
        $new_id = StaticContent::static_contents_insert_new($title, $project_id);
        echo '<a id="redirect" class="follow" style="display: none;" href="/admin/interface/contents/edit_static_content/' . $new_id . '_' . $project_id . '/"></a><script type="text/javascript"> $("#redirect").trigger("click"); </script>';
    }
    
    public function add_new_blog($title, $project_id)
    {
        $new_id = Blog::insert_new($title, $project_id);
        echo '<a id="redirect" class="follow" style="display: none;" href="/admin/interface/contents/edit_blog/' . $new_id . '_' . $project_id . '/"></a><script type="text/javascript"> $("#redirect").trigger("click"); </script>';
    }
    
    public function edit_static_content($id, $the_project_id)
    {
        //echo self::load_prop('main_container_edit_static_content');
        //echo 'sm tle ;)';
        
        //$GLOBALS['project_id'] = $project_id;
        global $project_id;
        $project_id = $the_project_id;
        
        echo self::load_static_content_prop('default_content_edit_form', $id, $project_id);
    }
    
    public function edit_blog($id, $the_project_id)
    {
        //echo self::load_prop('main_container_edit_static_content');
        //echo 'sm tle ;)';
        
        //$GLOBALS['project_id'] = $project_id;
        global $project_id;
        $project_id = $the_project_id;
        
        echo self::load_blog_prop('default_content_edit_form', $id, $project_id);
    }
    
    public function trash_static_content($id)
    {
        StaticContent::static_contents_to_trash($id);
    }
    
    public function trash_blog($id)
    {
        Blog::to_trash($id);
    }
    
    public function save_static_content($id, $project_id, $new_values)
    {
        $GLOBALS['project_id'] = $project_id;
        
        StaticContent::static_contents_save($id, $project_id, $new_values);
    }
    
    public function save_blog($id, $project_id, $new_values)
    {
        $GLOBALS['project_id'] = $project_id;
        
        Blog::save($id, $project_id, $new_values);
    }
    
    public function add_new_text_block($title, $project_id)
    {
        $new_id = TextBlock::insert_new($title, $project_id);
        echo '<a id="redirect" class="follow" style="display: none;" href="/admin/interface/contents/edit_text_block/' . $new_id . '_' . $project_id . '/"></a><script type="text/javascript"> $("#redirect").trigger("click"); </script>';
    }
    
    public function edit_text_block($id, $the_project_id)
    {
        //echo self::load_prop('main_container_edit_static_content');
        //echo 'sm tle ;)';
        
        //$GLOBALS['project_id'] = $project_id;
        global $project_id;
        $project_id = $the_project_id;
        
        echo self::load_text_block_prop('default_content_edit_form', $id, $project_id);
    }
    
    public function trash_text_block($id)
    {
        TextBlock::to_trash($id);
    }
    
    public function save_text_block($id, $project_id, $new_values)
    {
        $GLOBALS['project_id'] = $project_id;
        
        TextBlock::save($id, $project_id, $new_values);
    }
    
    public function emails_default()
    {
        global $emails;
        $emails = Emails::emails_get_all();
        echo self::load_prop('main_container_emails_default');
    }
    
    public function add_new_email($title, $project_id)
    {
        $new_id = Emails::email_insert_new($title, $project_id);
        echo '<a id="redirect" class="follow" style="display: none;" href="/admin/interface/contents/edit_email/' . $new_id . '_' . $project_id . '/"></a><script type="text/javascript"> $("#redirect").trigger("click"); </script>';
    }
    
    public function edit_email($id, $project_id)
    {
        echo self::load_email_prop('default_email_edit_form', $id, $project_id);
    }
    
    public function trash_email($id)
    {
        Emails::emails_to_trash($id);
    }
    
    public function save_email($id, $project_id, $new_values)
    {
        Emails::emails_save($id, $project_id, $new_values);
    }
    
    public function offers_default($limit_start, $limit, $search_type, $search_term)
    {
        global $offers, $projects, $items_count, $page_limit_start, $page_limit, $search_type_load, $search_term_load;
        
        $search_type_load = $search_type;
        $search_term_load = $search_term;
        
        if ($limit_start == 'undefined')
        {
            $limit_start = 0;
        }
        if ($limit == 'undefined')
        {
            $limit = 10;
        }
        
        $page_limit_start = $limit_start;
        $page_limit = $limit;
        
        //$offers = Offer::offers_get_all();
        if ($search_type == 'undefined')
        {
            $items_count = Offer::offers_get_count();
        }
        elseif ($search_type == 'search-simple')
        {
            $items_count = Offer::offers_get_simple_search_count($search_term);
        }
        elseif ($search_type == 'search-advanced')
        {
            $items_count = Offer::offers_get_advanced_search_count($search_term);
        }
        
        $offers = Offer::offers_get_all_limit_search($limit_start, $limit, $search_type, $search_term);
        $projects = Projects::get_all_projects();
        echo self::load_prop('main_container_offers_default');
    }
    
    public function offers_trashed($limit_start, $limit, $search_type, $search_term)
    {
        global $offers, $projects, $items_count, $page_limit_start, $page_limit, $search_type_load, $search_term_load;
        
        $search_type_load = $search_type;
        $search_term_load = $search_term;
        
        if ($limit_start == 'undefined')
        {
            $limit_start = 0;
        }
        if ($limit == 'undefined')
        {
            $limit = 10;
        }
        
        $page_limit_start = $limit_start;
        $page_limit = $limit;
        
        //$offers = Offer::offers_get_all();
        if ($search_type == 'undefined')
        {
            $items_count = Offer::offers_get_count();
        }
        elseif ($search_type == 'search-simple')
        {
            $items_count = Offer::offers_get_simple_search_count_trashed($search_term);
        }
        elseif ($search_type == 'search-advanced')
        {
            $items_count = Offer::offers_get_advanced_search_count($search_term);
        }
        
        $offers = Offer::offers_get_all_limit_search_trashed($limit_start, $limit, $search_type, $search_term);
        $projects = Projects::get_all_projects();
        echo self::load_prop('main_container_offers_trashed');
    }
    
    public function add_new_offer($title, $project_id)
    {
        $new_id = Offer::offer_insert_new($title, $project_id);
        echo '<a id="redirect" class="follow" style="display: none;" href="/admin/interface/contents/edit_offer/' . $new_id . '_' . $project_id . '"></a><script type="text/javascript"> $("#redirect").trigger("click"); </script>';
    }
    
    public function duplicate_offer($title, $new_title, $project_id)
    {
        if (Offer::offer_title_exists($new_title))
        {
            echo 'title exists';
        }
        else
        {
            $new_id = Offer::offer_duplicate($title, $new_title, $project_id);
            echo '<a id="redirect" class="follow" style="display: none;" href="/admin/interface/contents/edit_offer/' . $new_id . '_' . $project_id . '"></a><script type="text/javascript"> $("#redirect").trigger("click"); </script>';
        }
    }
    
    public function load_offer_prop($prop_name, $id, $get_project_id)
    {
        global $project_id;
        $project_id = $get_project_id;
        
        $offer = new Offer;
        $editing = 'Offer';
        $fields = $offer->fields;
        $populate_content = $offer->offer_get_by_id($id);
        $content_title = $populate_content[0]['title'];
        $offer_content = $populate_content[0];
        $images = str_replace('&quot;', '"', $populate_content[0]['images']);
        $images = json_decode($images, true);
        if (isset($populate_content[0]['seller_logo']))
        {
            $logo = str_replace('&quot;', '"', $populate_content[0]['seller_logo']);
            $logo = json_decode($logo, true);
        }
        $save_function = 'AdminAction.save_offer';
        $languages = Languages::get_all_www_languages($project_id);
        $countries_currency_own = Countries::get_all_own_currency_countries($project_id);
        $tab_counter = 0;
        $main_categories = Offer::get_all_offer_categories($project_id);
        $project_info = Projects::get_project_info_by_id($project_id);
        $voucher_persons_options = $offer->offers_get_voucher_persons_options();
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/admin/system/admin.actions/props/' . $prop_name . '.php');
        $prop = ob_get_contents();
        ob_end_clean();
        return $prop;
    }
    
    public function edit_offer($id, $project_id)
    {
        echo self::load_offer_prop('offer_content_edit_form', $id, $project_id);
    }
    
    public function save_offer($id, $project_id, $new_values)
    {
        Offer::offer_save($id, $project_id, $new_values);
    }
    
    public function trash_offer($id)
    {
        Offer::offer_to_trash($id);
    }
    
    public function renew_offer($id)
    {
        Offer::offer_to_renewed($id);
    }
    
    public function new_offer_category($project_id, $parent_id, $position, $title, $relation)
    {
        $last_id = Offer::offer_insert_new_category($project_id, $parent_id, $position, $title, $relation);
        echo $last_id;
    }
    
    public function rename_offer_category($project_id, $id, $new_title)
    {
        Offer::offer_rename_category($project_id, $id, $new_title);
    }
    
    public function delete_offer_category($id)
    {
        Offer::offer_delete_category($id);
    }
    
    public function get_offer_for_text($offer_id, $offer_size)
    {
        $offer = Offer::offer_get_by_id($offer_id);
        $images = json_decode(str_replace('&quot;', '"', $offer[0]['images']), true);
        $offer[0]['view_images'] = $images;
        echo '<div class="alohablock offer col' . $offer_size . '"><span class="hide">' . $offer_id . '</span><span class="hide link">/admin/interface/contents/edit_offer/' . $offer_id . '_' . $offer[0]['project_id'] . '/</span><span class="aloha-block-handle aloha-block-draghandle aloha-block-draghandle-blocklevel"></span><span class="title">' . $offer[0]['title'] . '</span><span class="image" style="background-image: url(/uploads/image/' . $offer[0]['view_images'][0]['fileYear'] . '/' . $offer[0]['view_images'][0]['fileMonth'] . '/' . $offer[0]['view_images'][0]['fileName'] . ');"></span></div>';
    }
    
    public function get_blog_for_text($blog_id, $blog_size)
    {
        $blog = Blog::get_by_id($blog_id);
        $images = json_decode(str_replace('&quot;', '"', $blog[0]['image']), true);
        $blog[0]['view_image'] = $images;
        echo '<div class="alohablock blog col' . $blog_size . '"><span class="hide">' . $blog_id . '</span><span class="hide link">/admin/interface/contents/edit_blog/' . $blog_id . '_' . $blog[0]['project_id'] . '/</span><span class="aloha-block-handle aloha-block-draghandle aloha-block-draghandle-blocklevel"></span><span class="title">' . $blog[0]['title'] . '</span><span class="image" style="background-image: url(/uploads/image/' . $blog[0]['view_image'][0]['fileYear'] . '/' . $blog[0]['view_image'][0]['fileMonth'] . '/' . $blog[0]['view_image'][0]['fileName'] . ');"></span></div>';
    }
    
    public function get_textblock_for_text($textblock_id, $textblock_size)
    {
        $textblock = TextBlock::get_by_id($textblock_id);
        //$images = json_decode(str_replace('&quot;', '"', $offer[0]['images']), true);
        //$offer[0]['view_images'] = $images;
        echo '<div class="alohablock textblock col' . $textblock_size . '"><span class="hide">' . $textblock_id . '</span><span class="hide link">/admin/interface/contents/edit_text_block/' . $textblock_id . '_' . $textblock[0]['project_id'] . '/</span><span class="aloha-block-handle aloha-block-draghandle aloha-block-draghandle-blocklevel"></span><span class="title">' . $textblock[0]['title'] . '</span><span class="description">' . html_entity_decode($textblock[0]['description_en'], ENT_QUOTES, 'UTF-8') . '</span></div>';
    }
    
    public function translations_default($language_from, $language_to)
    {
        global $translations, $language_from_code, $language_to_code, $language_from_name, $language_to_name, $all_languages;
        
        $all_languages = Languages::get_all_www_languages_list();
        
        if (isset($language_from) && $language_from != 'undefined')
        {
            $language_from_code = $language_from;
        }
        else
        {
            $language_from_code = 'en';
        }
        
        if (!isset($language_to) || $language_to == 'undefined')
        {
            foreach ($all_languages as $language)
            {
                if ($language['code'] != 'en')
                {
                    $language_to_code = $language['code'];
                    break;
                }
            }
        }
        else
        {
            $language_to_code = $language_to;
        }
        $language_from_name = Languages::get_language_name($language_from_code);
        $language_to_name = Languages::get_language_name($language_to_code);
        $translations = Translations::get_all_translations_admin();
        echo self::load_prop('main_container_translations_default');
    }
    
    public function translations_save($language_to, $new_values)
    {
        $new_values = str_replace('ANDPARAMETER', '&', $new_values);
        $new_values = str_replace('%3Cbr+style%3D%22%22%3E', '', $new_values);
        $new_values = str_replace('%0D%0A%3C%2Fp%3E%3Cbr+style%3D%22%22+class%3D%22aloha-cleanme%22%3E', '', $new_values);
        $new_values = str_replace('<br style="">', '', $new_values);
        $new_values = str_replace('<br style="" class="aloha-cleanme">', '', $new_values);
        //echo $new_values;
        //Translations::translations_save($language_to, $new_values);
        $translations = new Translations;
        $translations->translations_save($language_to, $new_values);
    }
    
    public function purchased_subscriptions($limit_start, $limit, $search_type, $search_term)
    {
        global $payments, $projects, $items_count, $page_limit_start, $page_limit, $search_type_load, $search_term_load, $payments_confirmed_overall;
        
        $search_type_load = $search_type;
        $search_term_load = $search_term;
        
        if ($limit_start == 'undefined')
        {
            $limit_start = 0;
        }
        if ($limit == 'undefined')
        {
            $limit = 10;
        }
        
        $page_limit_start = $limit_start;
        $page_limit = $limit;
        
        //$offers = Offer::offers_get_all();
        if ($search_type == 'undefined')
        {
            $items_count = Payment::payments_get_count();
        }
        elseif ($search_type == 'search-simple')
        {
            $items_count = Payment::payments_get_simple_search_count($search_term);
        }
        elseif ($search_type == 'search-advanced')
        {
            $items_count = Users::users_get_advanced_search_count($search_term);
        }
        
        $payments_confirmed_overall = Payment::get_overall_confirmed();
        $payments = Payment::payments_get_all_limit_search($limit_start, $limit, $search_type, $search_term);
        $projects = Projects::get_all_projects();
        echo self::load_prop('main_container_subscription_purchases');
    }
    
    public function subscription_packages()
    {
        global $packages;
        $packages = Subscription::packages_get_all();
        echo self::load_prop('main_container_subscription_packages');
    }
    
    public function add_new_subscription_package($title, $project_id)
    {
        $new_id = Subscription::package_insert_new($title, $project_id);
        echo '<a id="redirect" class="follow" style="display: none;" href="/admin/interface/applications/edit_subscription_package/' . $new_id . '_' . $project_id . '/"></a><script type="text/javascript"> $("#redirect").trigger("click"); </script>';
    }
    
    public function edit_subscription_package($load_id, $load_project_id)
    {
        global $id, $project_id, $editing, $package, $content_title, $save_function, $countries_currency_own, $project_info, $languages;
        
        $id = $load_id;
        $project_id = $load_project_id;
        $editing = 'subscription-package';
        $package = Subscription::package_get_by_id($id);
        $content_title = $package['title'];
        $save_function = 'AdminAction.save_subscription_package';
        $languages = Languages::get_all_www_languages($project_id);
        $countries_currency_own = Countries::get_all_own_currency_countries($project_id);
        $project_info = Projects::get_project_info_by_id($project_id);
        echo self::load_prop('main_container_edit_subscription_package');
    }
    
    public function save_subscription_package($id, $project_id, $new_values)
    {
        Subscription::package_save($id, $project_id, $new_values);
    }
    
    public function trash_subscription_package($id)
    {
        Subscription::package_to_trash($id);
    }
    
    public function confirm_payment($id)
    {
        $result = Payment::confirm_payment_offline($id);
        if ($result)
        {
            $answer = json_encode(array('result' => 1));
        }
        else
        {
            $answer = json_encode(array('result' => 0));
        }
        
        echo $answer;
    }
    
    public function applications_exports()
    {
        echo self::load_prop('main_container_applications_exports');
    }
    
    public function user_activate($user_id)
    {
        echo Users::admin_activate_user($user_id);
    }
    
    public function category_edit($category_id, $proj_id)
    {
        global $id, $project_id, $category_content, $content_title, $save_function, $languages, $project_info, $editing;
        
        $id = $category_id;
        $project_id = $proj_id;
        
        $offer = new Offer;
        $editing = 'Category';
        $fields = $offer->category_fields;
        $populate_content = $offer->category_get_by_id($category_id);
        $content_title = $populate_content[0]['title'];
        $category_content = $populate_content[0];
        
        $save_function = 'AdminAction.save_category';
        $languages = Languages::get_all_www_languages($project_id);
        $tab_counter = 0;
        $project_info = Projects::get_project_info_by_id($project_id);
        
        echo self::load_prop('main_container_edit_category');
    }
    
    public function save_category($id, $project_id, $new_values)
    {
        Offer::category_save($id, $project_id, $new_values);
    }
    
    public function logoff_user()
    {
        unset($_SESSION['admin_user']);
    }
}

//xMemcache::get_memcache_settings();
//echo $project_id;


/*if (isset($project_id))
{
    //echo $project_id;
}*/
//$project_id = 1;



new jAPIBaseClass('AdminAction');
//xMemcache::ajax_write_memcache_settings();
if (isset($project_id))
{
    //echo $project_id;
    xMemcache::write_memcache_settings();
} else {
    $projects = Projects::get_all_projects();
    
    foreach ($projects as $project)
    {
        $project_id = $project['id'];
        xMemcache::write_memcache_settings();
    }
}

?>