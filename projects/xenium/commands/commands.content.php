<?php

/**
 * @author Danijel
 * @copyright 2013
 */

spl_autoload_register('class_autoloader');
// remove
//Payment::check_payment_plugins();
// end remove

if (isset($_GET['module']))
{
    $module_content = sanitize($_GET['module']);
    if (preg_match('/[^a-zA-Z]/', $module_content)) die();
}
else
{
    $module_content = 'FirstPage';
}

$exploded_domain = explode('.', $_SERVER['HTTP_HOST']);
$returned_domain = implode('.', array_slice($exploded_domain, -2, 2));

$this->smarty_assign('main_project_domain', $returned_domain);

//$project_domain = 'http://' . $_SERVER['HTTP_HOST'];
$project_domain = 'http://' . $returned_domain;
$smarty_cache_id['domain'] = $project_domain;
$this->smarty_assign('project_domain', $project_domain);

$smarty_cache_id['module_content'] = $module_content;
$this->smarty_assign('page_content', $module_content);
$this->smarty_assign('selected_language', $selected_language);

if (isset($_GET['sub']))
{
    $module_sub = sanitize($_GET['sub']);
    if (preg_match('/[^0-9a-zA-Z_-]/', $module_sub)) die();
    $smarty_cache_id['module_sub'] = $module_sub;
    $this->smarty_assign('page_sub_content', $module_sub);
}
else
{
    $module_sub = 'none';
    $smarty_cache_id['module_sub'] = $module_sub;
    $this->smarty_assign('page_sub_content', $module_sub);
}

$add_js = 'window.onload = function () {';

$add_headers = '';

if ($exploded_domain[0] == 'blog')
{
    $html_page_title = 'Xenium Blog';
}
else
{
    $html_page_title = 'Xenium';
}

$this->smarty_assign('main_page_title', $html_page_title);

$add_html_page_title = '';

$security_token = Users::get_security_token();
$this->smarty_assign('security_token', $security_token);

//if (!isset($_SESSION['search_security_token']))
//{
    //$search_key = rand() . '*/&*' . rand() . '#%$#' . rand();
    //$_SESSION['search_security_token'] = sha1($search_key);
//}


function get_basic_data($fSmarty)
{
    global $project_id, $selected_language, $project_domain, $translations;
    
    //$translations = Translations::get_all_translations();
    
    // get categories
    //$fSmarty->smarty_assign('categories', array_reverse(Offer::get_all_offer_categories($project_id)));
    //$fSmarty->smarty_assign('categories', Offer::get_all_offer_categories($project_id));
    
    //$currency = Countries::get_country_currency();
    //$fSmarty->smarty_assign('currency', $currency);
    
    $slideshows = Slideshow::slideshow_www_get_all();
    $fSmarty->smarty_assign('slideshows', $slideshows);
    /*
    $first_slide = reset($slideshows);
    //$slide_image_url = 'http://' . $_SERVER['HTTP_HOST'] . '/images/1920x350/' . $first_slide['dynamic_image']['fileYear'] . '/' . $first_slide['dynamic_image']['fileMonth'] . '/' . $first_slide['dynamic_image']['fileName'];
    $slide_image_url = 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/image/' . $first_slide['dynamic_image']['fileYear'] . '/' . $first_slide['dynamic_image']['fileMonth'] . '/1920x350_ZEBRA_IMAGE_CROP_CENTER/' . $first_slide['dynamic_image']['fileName'];
    $fSmarty->smarty_assign('slide_first_image', $slide_image_url);
    */
    $fSmarty->smarty_assign('page_url', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    
    //$offers_count = Offer::offers_www_get_count($project_id, $selected_language);
    //$fSmarty->smarty_assign('offers_count', $offers_count);
    
    //$offers_cities = Offer::offers_get_cities($project_id);
    //$fSmarty->smarty_assign('offers_cities', $offers_cities);
    
    // get some static contents
    /*$static_content = new StaticContent;
    $membership_benefits = $static_content->static_contents_get_by_title('Membership benefits');
    $privacy_policy = $static_content->static_contents_get_by_title('Privacy policy');
    $terms_and_conditions = $static_content->static_contents_get_by_title('Terms and conditions');
    
    $fSmarty->smarty_assign('membership_benefits', $membership_benefits);
    
    $privacy_policy_url = '/' . $selected_language . '/' . $privacy_policy['dynamic_slug'] . '/';
    $fSmarty->smarty_assign('privacy_policy_url', $privacy_policy_url);
    
    $terms_and_conditions_url = '/' . $selected_language . '/' . $terms_and_conditions['dynamic_slug'] . '/';
    $fSmarty->smarty_assign('terms_and_conditions_url', $terms_and_conditions_url);*/
}

switch ($module_content)
{
    case 'FirstPage':
        //$xSmarty->smarty_assign('FirstPage', $get_static_content);
        //$currency = Countries::get_country_currency();
        
        if (!$this->smarty_cached('index.tpl', sha1(implode($smarty_cache_id))))
        {
            get_basic_data($this);
            $get_first_page_content = FirstPage::first_page_www_get_by_project_id($project_id);
            //print_r($get_first_page_content);
            $this->smarty_assign('FirstPage', $get_first_page_content);
            
            $add_html_page_title = $get_first_page_content['dynamic_title'] . ' - ';
            
            $this->smarty_assign('seo_description', $get_first_page_content['dynamic_seo_description']);
            $this->smarty_assign('seo_keywords', $get_first_page_content['dynamic_seo_keywords']);
            $this->smarty_assign('content_include', 'modules/FirstPage/FirstPageDisplay.tpl');
        }
        break;
    case 'Blog':
        $slug = '';
        if (isset($_GET['slug']))
        {
            $slug = sanitize($_GET['slug']);
            $smarty_cache_id['blog'] = $slug;
        }
        
        if (!$slug)
        {
            if (!$this->smarty_cached('index.tpl', sha1(implode($smarty_cache_id))))
            {
                get_basic_data($this);
            }
            
            $blog = new Blog;
            $get_blog_content = $blog->get_by_10();
            $this->smarty_assign('blog_posts', $get_blog_content);
            $this->smarty_assign('content_include', 'modules/Blog/BlogDisplay.tpl');
        }
        else
        {
            if (!$this->smarty_cached('index.tpl', sha1(implode($smarty_cache_id))))
            {
                get_basic_data($this);
                $blog = new Blog;
                $get_blog_post = $blog->get_by_slug($slug);
                
                $add_html_page_title = $get_blog_post['dynamic_title'] . ' - ';
                
                $this->smarty_assign('seo_title', $get_blog_post['dynamic_title']);
                $this->smarty_assign('seo_description', $get_blog_post['dynamic_seo_description']);
                $this->smarty_assign('seo_keywords', $get_blog_post['dynamic_seo_keywords']);
                $this->smarty_assign('og_image', $project_domain . '/images/600x315/' . $get_blog_post['main_image']['fileYear'] . '/' . $get_blog_post['main_image']['fileMonth'] . '/' . $get_blog_post['main_image']['fileName']);
                $this->smarty_assign('blog_post', $get_blog_post);
                $this->smarty_assign('content_include', 'modules/Blog/BlogDisplaySingle.tpl');
            }
        }
        break;
    case 'StaticContent':
        $smarty_cache_id['static_content'] = sanitize($_GET['slug']);
        if (!$this->smarty_cached('index.tpl', sha1(implode($smarty_cache_id))))
        {
            get_basic_data($this);
            $static_content = new StaticContent;
            $get_static_content = $static_content->static_contents_get_by_slug(sanitize($_GET['slug']));
            
            $add_html_page_title = $get_static_content['dynamic_title'] . ' - ';
            
            $this->smarty_assign('seo_title', $get_static_content['dynamic_title']);
            $this->smarty_assign('seo_description', $get_static_content['dynamic_seo_description']);
            $this->smarty_assign('seo_keywords', $get_static_content['dynamic_seo_keywords']);
            $this->smarty_assign('StaticContent', $get_static_content);
            $this->smarty_assign('content_include', 'modules/StaticContent/StaticContentDisplay.tpl');
        }
        break;
}


/*
$currency = Countries::get_country_currency();
$this->smarty_assign('currency', $currency);

$offer_categories = Offer::get_all_offer_categories_cached($project_id);
$this->smarty_assign('offer_categories', $offer_categories);

if (isset($_GET['category_id']))
{
    $add_js .= ' $("#category_" + ' . $_GET['category_id'] . ').addClass("active"); ';
}
if (isset($_GET['subcategory_id']))
{
    $add_js .= ' $("#category_" + ' . $_GET['subcategory_id'] . ').addClass("active"); ';
}
*/

if (isset($_SERVER['REQUEST_URI'])) $request_uri = $_SERVER['REQUEST_URI']; else $request_uri = '/';
if (isset($_SERVER['HTTP_REFERER'])) $referer = $_SERVER['HTTP_REFERER']; else $referer = '';
$add_js .= ' AjaxCommand.pageview_tracking("' . $request_uri . '", "' . $add_html_page_title . $html_page_title . '", "' . $referer . '"); ';



$add_js .= ' };';
$this->smarty_assign('add_js', $add_js);
$this->smarty_assign('add_headers', $add_headers);

if ($module_content == 'FirstPage')
{
    $this->smarty_assign('page_title', 'Xenium - The new upcoming opensource CMS');
}
else
{
    if ($add_html_page_title)
    {
        $this->smarty_assign('page_title', $add_html_page_title . $html_page_title);
    }
    else
    {
        $this->smarty_assign('page_title', $html_page_title);
    }
}

?>