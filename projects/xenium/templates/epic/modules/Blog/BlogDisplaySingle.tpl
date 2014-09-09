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
            {*<img src="{$project_domain}/images/1000x450/{$slideshow.dynamic_image.fileYear}/{$slideshow.dynamic_image.fileMonth}/{$slideshow.dynamic_image.fileName}" alt="desc" />*}
            <div class="blog-post-single">
                <h2><strong>{$blog_post.dynamic_title}</strong></h2>
                {if isset($blog_post.main_image)}
                    <img class="main_image" src="{$project_domain}/images/823x420/{$blog_post.main_image.fileYear}/{$blog_post.main_image.fileMonth}/{$blog_post.main_image.fileName}" />
                {/if}
                
                <p class="intro">{$blog_post.dynamic_intro}</p>
                <p>{$blog_post.dynamic_description}</p>
                <div class="clear"></div>
                <h3>Liked this post?</h3>
                <div class="fb-like" data-href="{$page_url}" data-layout="box_count" data-action="like" data-show-faces="true" data-share="false"></div>
                <div class="g-plusone" data-size="tall" data-href="{$page_url}" style="margin-left: 30px;"></div>
                <a href="https://twitter.com/share" class="twitter-share-button" data-dnt="true" data-count="vertical">Tweet</a>
                <script type="IN/Share" data-counter="top"></script>
                {literal}<script id='fb6o45y'>(function(i){var f,s=document.getElementById(i);f=document.createElement('iframe');f.src='//api.flattr.com/button/view/?uid=danilatin&url='+encodeURIComponent(document.URL);f.title='Flattr';f.height=62;f.width=55;f.style.borderWidth=0;s.parentNode.insertBefore(f,s);})('fb6o45y');</script>{/literal}
                <hr />
                <h3>Leave a comment:</h3>
                <div class="fb-comments" data-href="{$page_url}" data-width="823" data-numposts="15" data-colorscheme="light"></div>
                
            </div>
        </div>
        <div class="blog-right three columns">
            {include 'modules/Blog/BlogAuthor.tpl'}
        </div>
        <div class="clear"></div>
    </div>
</div>
{literal}
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/platform.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<script src="//platform.linkedin.com/in.js" type="text/javascript">
  lang: en_US
</script>
{/literal}
{/strip}