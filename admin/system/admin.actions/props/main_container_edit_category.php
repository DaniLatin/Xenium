<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $id, $project_id, $category_content, $content_title, $save_function, $languages, $project_info, $editing;

?>

<div class="custom-container title-container">
    <h3>Editing category "<?php echo $content_title; ?>"</h3>
</div>
<div class="row-fluid">
    <div class="span10">
        <div class="custom-container custom-container-content">
            <form id="EditContent_<?php echo $editing; ?>_<?php echo $project_id; ?>_<?php echo $id; ?>">
                <ul id="language-tabs" class="nav nav-tabs">
                    <?php $tab_counter = 0; ?>
                    <?php foreach ($languages as $language){ ?>
                        <li <?php if (!$tab_counter) echo 'class="active"'; ?> ><a data-toggle="tab" no-follow="true" href="#content_<?php echo $language['code']; ?>"><img src="/admin/theme/img/flags/32/<?php echo $language['icon']; ?>" /></a></li>
                    <?php $tab_counter++; ?>
                    <?php } ?>
                </ul>
                
                <div id="language-tabsContent" class="tab-content">
                    <?php $tab_counter = 0; ?>
                    <?php foreach ($languages as $language){ ?>
                        <?php if (!$tab_counter) $tab_class = 'tab-pane fade active in'; else $tab_class = 'tab-pane fade'; ?>
                        <div id="content_<?php echo $language['code']; ?>" class="<?php echo $tab_class; ?>">
                            <label>Category description:</label>
                            <textarea id="Category_description_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="description_<?php echo $language['code']; ?>" class="span12 html_edit_advanced rows12" type="text" rows="12" style="height: 600px;"><?php if (isset($category_content['description_' . $language['code']])) echo $category_content['description_' . $language['code']]; ?></textarea>
                            
                            <label>Category SEO description:</label>
                            <textarea id="Offer_seo_description_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="seo_description_<?php echo $language['code']; ?>" class="span12" type="text" rows="2"><?php if (isset($category_content['seo_description_' . $language['code']])) echo $category_content['seo_description_' . $language['code']]; ?></textarea>
                            
                            <label>Category SEO keywords:</label>
                            <textarea id="Category_seo_keywords_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="seo_keywords_<?php echo $language['code']; ?>" class="span12" type="text" rows="2"><?php if (isset($category_content['seo_keywords_' . $language['code']])) echo $category_content['seo_keywords_' . $language['code']]; ?></textarea>
                        </div>
                        
                        <?php $tab_counter++; ?>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
    <div class="span2">
        <div class="right-toolbar-positioner">
            <div class="right-toolbar-included right-editor">
                <div class="custom-container main-tools-toolbar">
                    <p class="toolbar-title">Tools:</p>
                    <hr />
                    <p><button class="btn btn-primary save-btn" onclick="save_content('<?php echo $id; ?>', '<?php echo $project_id; ?>', '<?php echo $save_function; ?>')" href="/admin/interface/contents/" title="Save"><i class="icon-save icon-2x"></i> <span class="save-btn-txt">Save</span></button></p>
                    <p><a class="btn back-btn follow" href="<?php if (!isset($_SERVER['HTTP_REFERER'])) echo '/admin/interface/contents/offers_default/'; else echo $_SERVER['HTTP_REFERER']; ?>" title="Go back"><i class="icon-double-angle-left"></i> Back</a></p>
                </div>
                <?php //echo self::load_prop('text_editor'); ?>
                <?php self::load_text_editor($project_info['id']); ?>
            </div>
        </div>
    </div>
</div>
