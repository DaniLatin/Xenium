{*if count($slideshows) && ($page_content == "FirstPage" || ($page_content == "Offer" && ($page_sub_content != 'single_view' || ($page_sub_content == 'single_view' && !$user_logged_in))))*}
{if count($slideshows)}
<div class="show-for-large-up-now">
    <div class="row">
        <div class="twelve columns">
            <div id="featured">
                {foreach from=$slideshows item=slideshow}
                    {*<img src="{$project_domain}/images/1000x450/{$slideshow.dynamic_image.fileYear}/{$slideshow.dynamic_image.fileMonth}/{$slideshow.dynamic_image.fileName}" alt="desc" />*}
                    <div class="slide">{$slideshow.dynamic_description}</div>
                {/foreach}
                {*<img src="{$project_domain}/{$project_template_folder}/attributes/images/demo1.jpg" alt="desc" /> 
                <img src="{$project_domain}/{$project_template_folder}/attributes/images/demo2.jpg" alt="desc" /> 
                <img src="{$project_domain}/{$project_template_folder}/attributes/images/demo3.jpg" alt="desc" />*}
            </div>
        </div>
    </div>
</div>
{/if}