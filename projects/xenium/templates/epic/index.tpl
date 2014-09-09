{strip}
{assign var="site_version" value="1-0-99"}
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>
    <meta charset="utf-8" />
    
    <!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="width=device-width" />
    
    <title>{$page_title}</title>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
    <meta name="description" content="{if isset($seo_description)}{$seo_description}{/if}">
    <meta name="keywords" content="{if isset($seo_keywords)}{$seo_keywords}{/if}">
    <meta property="og:title" content="{$page_title}"/>
    <meta property="og:description" content="{if isset($seo_description)}{$seo_description}{/if}"/>
    <meta property="og:site_name" content="Xenium"/>
    <meta property="og:url" content="{$page_url}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:image" content="{if isset($og_image)}{$og_image}{else}{$project_domain}/{$project_template_folder}/attributes/images/Xenium-logo-share.jpg{/if}"/>
    
    <!-- Included CSS Files (Uncompressed) -->
    <!--
    <link rel="stylesheet" href="stylesheets/foundation.css">
    -->
    
    {* Included CSS Files (Compressed) *}
    {*}
    <link rel="stylesheet" href="{$project_domain}/{$project_template_folder}/attributes/stylesheets/foundation.min.css" />
    <link rel="stylesheet" href="{$project_domain}/{$project_template_folder}/attributes/stylesheets/main.css" />
    <link rel="stylesheet" href="{$project_domain}/{$project_template_folder}/attributes/stylesheets/app.css" />
    <link rel="stylesheet" href="{$project_domain}/{$project_template_folder}/attributes/stylesheets/social.css" />
    <link rel="stylesheet" href="{$project_domain}/{$project_template_folder}/attributes/stylesheets/custom.css" />
    <link rel="stylesheet" type="text/css" href="{$project_domain}/{$project_template_folder}/attributes/font/icomoon/style.css" />
    
    <!-- Google fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300|Playfair+Display:400italic' rel='stylesheet' type='text/css' />
    {*}
    
    {*}<link rel="stylesheet" type="text/css" href="{$project_domain}/{$project_template_folder}/attributes/stylesheets/header.css.php?v={$site_version}" />{*}
    <link rel="stylesheet" type="text/css" href="{$project_domain}/{$project_template_folder}/attributes/stylesheets/cssheader_{$site_version}.css" />
    
    
    {* Included JS Files *}
    
    {*}<script src="{$project_domain}/{$project_template_folder}/attributes/javascripts/modernizr.foundation.js"></script>{*}
    
    {*}<script type="text/javascript" src="{$project_domain}/{$project_template_folder}/attributes/javascripts/header.js.php?v={$site_version}"></script>{*}
    {* HEADER JS - return maybe *}
    {*}<script type="text/javascript" src="{$project_domain}/{$project_template_folder}/jsheader_{$site_version}.js"></script>{*}
  
    

    <!-- IE Fix for HTML5 Tags -->
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>
<body>
    {if $main_page_title == 'Xenium Blog'}
    <div id="fb-root"></div>
    {literal}
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/sl_SI/sdk.js#xfbml=1&appId=700378900035779&version=v2.0";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    {/literal}
    {/if}
        <div class="row page_wrap">
    <!-- page wrap -->
        <div class="twelve columns no-padding">
      <!-- page wrap -->
            {include 'elements/modals.tpl' assign='element_modals'}{$element_modals|strip}
            {include 'elements/header.tpl' assign='element_header'}{$element_header|strip}
            {include 'elements/slideshow.tpl' assign='element_slideshow'}{$element_slideshow|strip}
            {*include 'elements/title.tpl' assign='element_title'}{$element_title|strip*}
            {*include 'elements/content.tpl' assign='element_content'}{$element_content*}
            {include $content_include assign='element_content'}{$element_content}
            {include 'elements/footer.tpl' assign='element_footer'}{$element_footer|strip}
        </div>

    </div><!-- end page wrap) -->
    <!-- Included JS Files (Compressed) -->
    {*}
    <script src="{$project_domain}/{$project_template_folder}/attributes/javascripts/foundation.min.js" type="text/javascript"></script> <!-- Initialize JS Plugins -->
    <script src="{$project_domain}/{$project_template_folder}/attributes/javascripts/app.js" type="text/javascript"></script>
    <script src="{$project_domain}/{$project_template_folder}/attributes/javascripts/custom.js" type="text/javascript"></script>
    {*}
    {*}<script src="{$project_domain}/{$project_template_folder}/attributes/javascripts/footer.js.php?v={$site_version}&language={$selected_language}" type="text/javascript"></script>{*}
    <script src="{$project_domain}/{$project_template_folder}/jsfooter_{$site_version}_{$selected_language}.js" type="text/javascript"></script>
    
    <script type="text/javascript">{nocache}{$add_js}{/nocache}</script>
    
    <script type="text/javascript">{literal}
          //<![CDATA[
          //$('ul#menu3').nav-bar();
          //]]>
    {/literal}</script>
</body>
</html>
{/strip}