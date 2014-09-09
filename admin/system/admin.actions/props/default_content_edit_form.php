<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $additional_text_editor, $additional_text_editor_modal;

?>

<div class="custom-container title-container">
    <h3>Editing "<?php echo $content_title; ?>"</h3>
</div>
<div class="row-fluid">
    <div class="span10">
        <div class="custom-container custom-container-content">
            <?php 
            echo Form::generate_admin_form($fields, $populate_content, $editing, $project_id, $id);
            ?>
        </div>
    </div>
    <div class="span2">
        <div class="right-toolbar-positioner">
            <div class="right-toolbar-included right-editor">
                <div class="custom-container main-tools-toolbar">
                    <p class="toolbar-title">Tools:</p>
                    <hr />
                    <p><button class="btn btn-primary save-btn" onclick="save_content('<?php echo $id; ?>', '<?php echo $project_id; ?>', '<?php echo $save_function; ?>')" href="/admin/interface/contents/" title="Save"><i class="icon-save icon-2x"></i> <span class="save-btn-txt">Save</span></button></p>
                    <p><a class="btn back-btn follow" href="/admin/interface/contents/static_contents_default/" title="Go back"><i class="icon-double-angle-left"></i> Back</a></p>
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
    
    	window['blog_uploader'] = new plupload.Uploader({
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
    
    	window['blog_uploader'].bind('Init', function(up, params) {
    		//$('#image-block').html("<div>Current runtime: " + params.runtime + "</div>");
    	});
        /*
    	$('#uploadfiles').click(function(e) {
    		uploader.start();
    		e.preventDefault();
    	});
        */
    	window['blog_uploader'].init();
    
    	window['blog_uploader'].bind('FilesAdded', function(up, files) {
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
    
    	window['blog_uploader'].bind('UploadProgress', function(up, file) {
    		$('#' + file.id + " b").html(file.percent + "%");
    	});
    
    	window['blog_uploader'].bind('Error', function(up, err) {
    		$('#filelist').append("<div>Error: " + err.code +
    			", Message: " + err.message +
    			(err.file ? ", File: " + err.file.name : "") +
    			"</div>"
    		);
    
    		up.refresh(); // Reposition Flash/Silverlight
    	});
    
    	window['blog_uploader'].bind('FileUploaded', function(up, file, response) {
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

</script>