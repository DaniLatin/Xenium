<?php

/**
 * @author Danijel
 * @copyright 2013
 */

//global $first_page, $project_id;

?>

<div class="custom-container title-container">
    <h3>Editing first page "<?php echo $project_info['project_name']; ?>"</h3>
</div>
<div class="row-fluid">
    <div class="span10">
        <div class="custom-container custom-container-content">
            <?php 
            echo Form::generate_admin_form($fields, $populate_content, $editing, $project_id, $first_page_id);
            ?>
        </div>
    </div>
    <div class="span2">
        <div class="right-toolbar-positioner">
            <div class="right-toolbar-included right-editor">
                <div class="custom-container main-tools-toolbar">
                    <p class="toolbar-title">Tools:</p>
                    <hr />
                    <p><button class="btn btn-primary save-btn" onclick="save_content('<?php echo $first_page_id; ?>', '<?php echo $project_id; ?>', '<?php echo $save_function; ?>')" href="/admin/interface/contents/" title="Save"><i class="icon-save icon-2x"></i> <span class="save-btn-txt">Save</span></button></p>
                    <p><a class="btn back-btn follow" href="/admin/interface/contents/" title="Go back"><i class="icon-double-angle-left"></i> Back</a></p>
                </div>
                <?php self::load_text_editor($project_info['id']); ?>
            </div>
        </div>
    </div>
</div>

