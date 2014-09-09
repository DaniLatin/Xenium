<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $id, $project_id, $editing, $populate_content, $content_title, $project_info, $save_function, $languages, $tab_counter;

?>

<div class="custom-container title-container">
    <h3>Editing "<?php echo $content_title; ?>"</h3>
</div>
<div class="row-fluid">
    <div class="span10">
        <div class="custom-container custom-container-content">
            <form id="EditOffer_<?php echo $editing; ?>_<?php echo $project_id; ?>_<?php echo $id; ?>">
                <input id="Slideshow_position_<?php echo $id . '_' . $project_id; ?>" name="position" type="hidden" value="<?php echo $populate_content[0]['position']; ?>" />
                <ul id="language-tabs" class="nav nav-tabs">
                    <?php foreach ($languages as $language){ ?>
                        <?php if (!$tab_counter){ ?>
                            <li class="active"><a data-toggle="tab" no-follow="true" href="#content_<?php echo $language['code']; ?>"><img src="/admin/theme/img/flags/32/<?php echo $language['icon']; ?>" /></a></li>
                        <?php } else { ?>
                            <li><a data-toggle="tab" no-follow="true" href="#content_<?php echo $language['code']; ?>"><img src="/admin/theme/img/flags/32/<?php echo $language['icon']; ?>" /></a></li>
                        <?php } ?>
                        
                        <?php $tab_counter++; ?>
                    <?php } ?>
                </ul>
                
                <div id="language-tabsContent" class="tab-content">
                    <?php $tab_counter = 0; ?>
                    <?php foreach ($languages as $language){ ?>
                        <?php if (!$tab_counter) $tab_class = 'tab-pane fade active in'; else $tab_class = 'tab-pane fade'; ?>
                        <div id="content_<?php echo $language['code']; ?>" class="<?php echo $tab_class; ?>">
                            
                            <label>Image:</label>
                            <textarea style="display: none;" id="Slideshow_image_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="image_<?php echo $language['code']; ?>"><?php echo $populate_content[0]['image_' . $language['code']]; ?></textarea>
                            <div id="Slideshow_image_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>-plupload" class="slideshow-image-block" language="<?php echo $language['code']; ?>">
                                <?php $images = str_replace('&quot;', '"', $populate_content[0]['image_' .$language['code']]); $images = json_decode($images, true); ?>
                                <?php if (count($images)){ ?>
                                <?php foreach ($images as $image){ ?>
                                <div class="slideshow-image" id="<?php echo $image['id']; ?>" year="<?php echo $image['fileYear']; ?>" month="<?php echo $image['fileMonth']; ?>" image="<?php echo $image['fileName']; ?>">
                                    <div class="img" style="background: url(/images/1000x200/<?php echo $image['fileYear']; ?>/<?php echo $image['fileMonth']; ?>/<?php echo $image['fileName']; ?>)"></div>
                                </div>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            
                            <div style="float: left;">
                                <label>Slideshow url:</label>
                                <input id="Slideshow_url_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="url_<?php echo $language['code']; ?>" class="input-xxlarge" type="text" value="<?php if (isset($populate_content[0]['url_' . $language['code']])) echo $populate_content[0]['url_' . $language['code']]; ?>" />
                            </div>
                            
                            <div style="float: left; margin-left: 20px;">
                                <label>Slideshow url opening mode:</label>
                                <select id="Slideshow_url_opening_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="url_opening_<?php echo $language['code']; ?>">
                                    <option value="_self" <?php if (!isset($populate_content[0]['url_opening_' . $language['code']]) || !$populate_content[0]['url_opening_' . $language['code']] || $populate_content[0]['url_opening_' . $language['code']] == '_self'){ ?> selected="selected" <?php } ?>>same window</option>
                                    <option value="_blank" <?php if (isset($populate_content[0]['url_opening_' . $language['code']]) && $populate_content[0]['url_opening_' . $language['code']] == '_blank'){ ?> selected="selected" <?php } ?> >new window</option>
                                </select>
                            </div>
                            
                            <div class="clear"></div>
                            
                            <label>Slideshow description:</label>
                            <textarea id="Slideshow_description_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="description_<?php echo $language['code']; ?>" class="span12 html_edit_advanced rows12" type="text" rows="12"><?php echo $populate_content[0]['description_' . $language['code']]; ?></textarea>
                            
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

<script type="text/javascript">
// Custom example logic
$(function() {
    $(".slideshow-image-block").each(function(){
        
        var target_textarea_id = $(this).attr('id').replace('-plupload', '');
        var drop_element = $(this).attr('id');
        var language = $(this).attr('language');
    
    	window['uploader_' + language] = new plupload.Uploader({
    		runtimes : 'html5',
    		//browse_button : 'pickfiles',
            drop_element : drop_element,
    		//container : 'container',
    		max_file_size : '10mb',
    		url : '/admin/system/admin.actions/admin.upload.php',
    		//flash_swf_url : '/plupload/js/plupload.flash.swf',
    		//silverlight_xap_url : '/plupload/js/plupload.silverlight.xap',
    		filters : [
    			{title : "Image files", extensions : "jpg,gif,png"},
    			{title : "Zip files", extensions : "zip"}
    		],
    		//resize : {width : 320, height : 240, quality : 90}
            multi_selection:false,
            max_file_count: 1
    	});
    
    	window['uploader_' + language].bind('Init', function(up, params) {
    		//$('#image-block').html("<div>Current runtime: " + params.runtime + "</div>");
    	});
        /*
    	$('#uploadfiles').click(function(e) {
    		uploader.start();
    		e.preventDefault();
    	});
        */
    	window['uploader_' + language].init();
    
    	window['uploader_' + language].bind('FilesAdded', function(up, files) {
    		$.each(files, function(i, file) {
    			//$('#image-block').append(
                $('#' + drop_element).html(
    				'<div class="slideshow-image" id="' + file.id + '">' +
    				//file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                    '<b></b>' +
    			'</div>');
    		});
    
    		up.refresh(); // Reposition Flash/Silverlight
            up.start();
    	});
    
    	window['uploader_' + language].bind('UploadProgress', function(up, file) {
    		$('#' + file.id + " b").html(file.percent + "%");
    	});
    
    	window['uploader_' + language].bind('Error', function(up, err) {
    		$('#filelist').append("<div>Error: " + err.code +
    			", Message: " + err.message +
    			(err.file ? ", File: " + err.file.name : "") +
    			"</div>"
    		);
    
    		up.refresh(); // Reposition Flash/Silverlight
    	});
    
    	window['uploader_' + language].bind('FileUploaded', function(up, file, response) {
    	   /*
           var images_text = $("#Slideshow_images_<?php echo $id . '_' . $project_id; ?>").val();
    	   
           var get_images = jQuery.parseJSON(images_text);
           if (get_images == null){
                var images = {"image_files" : []};
           } else {
                var images = {"image_files" : get_images};
           }
           */
           
           var images = {"image_files" : []};
           
    		var obj = jQuery.parseJSON(response.response);
            $('#' + file.id).html('<div class="img" style="background: url(/images/1000x200/' + obj.fileYear + '/' + obj.fileMonth + '/' + obj.fileName + ')"></div>');
            $('#' + file.id).attr('year', obj.fileYear);
            $('#' + file.id).attr('month', obj.fileMonth);
            $('#' + file.id).attr('image', obj.fileName);
            images.image_files.push( { "id":file.id, "fileName":obj.fileName, "fileYear":obj.fileYear, "fileMonth":obj.fileMonth } );
            var new_images = JSON.stringify(images.image_files);
            $("#" + target_textarea_id).val(new_images);
    	});
    
    });
});
/*
$("#image-block").sortable({
    axis: 'x',
    update : function () {
        reorder_images();
    }
});

$('.offer-image .delete').bind('click', function() {
    $(this).parent().fadeOut(500, function() { $(this).remove(); reorder_images(); });
});

function reorder_images(){
    var images = {"image_files" : []};
    window.new_images = "";
    
    $(".image-block .offer-image").each(function() {
        var id = $(this).attr('id');
        var name = $(this).attr('image');
        var year = $(this).attr('year');
        var month = $(this).attr('month');
        
        images.image_files.push( { "id":id, "fileName":name, "fileYear":year, "fileMonth":month } );
        window.new_images = JSON.stringify(images.image_files);
    });
    
    $("#Offer_images_<?php echo $id . '_' . $project_id; ?>").val(window.new_images);
}
*/
</script>