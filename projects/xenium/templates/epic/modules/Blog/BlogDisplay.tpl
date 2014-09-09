{strip}
<div class="row hide-for-small-not">
    <div class="twelve columns title-contain">
        {*<div class="heading_dots">*}<h1 class="heading_supersize" style="margin-bottom:10px"><span class="heading_center_bg">News / Blog</span></h1>{*</div>*}
    </div>
</div>
<div class="content-container">
    <div class="content">
        {*$StaticContent.dynamic_description*}
        <div class="blog-left nine columns">
        {nocache}
        {if count($blog_posts)}
            {foreach from=$blog_posts item=blog_post}
                {*<img src="{$project_domain}/images/1000x450/{$slideshow.dynamic_image.fileYear}/{$slideshow.dynamic_image.fileMonth}/{$slideshow.dynamic_image.fileName}" alt="desc" />*}
                <div class="blog-post">
                    {if isset($blog_post.main_image)}
                        <a href="http://blog.{$main_project_domain}/{$selected_language}/{$blog_post.dynamic_slug}/">
                            <img src="{$project_domain}/images/250x150/{$blog_post.main_image.fileYear}/{$blog_post.main_image.fileMonth}/{$blog_post.main_image.fileName}" />
                        </a>
                    {/if}
                    <a href="http://blog.{$main_project_domain}/{$selected_language}/{$blog_post.dynamic_slug}/">
                        <h2>{$blog_post.dynamic_title}</h2>
                    </a>
                    <p>{$blog_post.dynamic_intro}</p>
                    <a class="read-more" href="http://blog.{$main_project_domain}/{$selected_language}/{$blog_post.dynamic_slug}/">Read more <i class="iconblock" data-fip-value="57650" data-icomoon=""></i></a>
                    <div class="clear"></div>
                </div>
            {/foreach}
        {/if}
        {/nocache}
        </div>
        <div class="blog-right three columns">
            {include 'modules/Blog/BlogAuthor.tpl'}
        </div>
        <div class="clear"></div>
    </div>
</div>
{/strip}