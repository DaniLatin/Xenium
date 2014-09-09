<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $id, $project_id, $editing, $package, $content_title, $save_function, $countries_currency_own, $project_info, $languages;

?>

<div class="custom-container title-container">
    <h3>Editing "<?php echo $content_title; ?>"</h3>
</div>
<div class="row-fluid">
    <div class="span10">
        <div class="custom-container custom-container-content">
            <form id="EditContent_<?php echo $editing; ?>_<?php echo $project_id; ?>_<?php echo $id; ?>">
                
                
                <ul id="language-tabs" class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" no-follow="true" href="#content_info"><i class="icon-info-sign icon-2x"></i></a></li>
                    <?php foreach ($languages as $language){ ?>
                        <li><a data-toggle="tab" no-follow="true" href="#content_<?php echo $language['code']; ?>"><img src="/admin/theme/img/flags/32/<?php echo $language['icon']; ?>" /></a></li>
                    <?php } ?>
                </ul>
                
                <div id="language-tabsContent" class="tab-content">
                
                    <div id="content_info" class="tab-pane fade active in">
                        <label>Main title (not editable):</label>
                        <h5><?php echo $package['title']; ?></h5>
                        
                        <label>Package prices:</label>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="span1">Country</th>
                                    <th class="span4">Original price</th>
                                    <th class="span4">Discount price</th>
                                    <th class="span3">Discount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><img src="/admin/theme/img/flags/32/_European Union.png" /></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_original_price_<?php echo $id . '_' . $project_id; ?>" name="original_price" value="<?php if (isset($package['prices']['original_price'])) echo $package['prices']['original_price']; ?>" /><span class="add-on">€</span></div></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_discount_price_<?php echo $id . '_' . $project_id; ?>" name="discount_price" value="<?php if (isset($package['prices']['discount_price'])) echo $package['prices']['discount_price']; ?>" /><span class="add-on">€</span></div></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_discount_<?php echo $id . '_' . $project_id; ?>" name="discount" value="<?php if (isset($package['prices']['discount'])) echo $package['prices']['discount']; ?>" /><span class="add-on">%</span></div></td>
                                </tr>
                                <?php foreach ($countries_currency_own as $country_currency ){ ?>
                                <tr>
                                    <td><img src="/admin/theme/img/flags/32/<?php echo strtolower($country_currency['iso_code']); ?>.png" /></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_original_price_<?php echo strtolower($country_currency['iso_code']) . '_' . $id . '_' . $project_id; ?>" name="original_price_<?php echo strtolower($country_currency['iso_code']); ?>" value="<?php if (isset($package['prices']['original_price_' . strtolower($country_currency['iso_code'])])) echo $package['prices']['original_price_' . strtolower($country_currency['iso_code'])]; ?>" /><span class="add-on"><?php echo $country_currency['currency']; ?></span></div></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_discount_price_<?php echo strtolower($country_currency['iso_code']) . '_' . $id . '_' . $project_id; ?>" name="discount_price_<?php echo strtolower($country_currency['iso_code']); ?>" value="<?php if (isset($package['prices']['discount_price_' . strtolower($country_currency['iso_code'])])) echo $package['prices']['discount_price_' . strtolower($country_currency['iso_code'])]; ?>" /><span class="add-on"><?php echo $country_currency['currency']; ?></span></div></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_discount_<?php echo strtolower($country_currency['iso_code']) . '_' . $id . '_' . $project_id; ?>" name="discount_<?php echo strtolower($country_currency['iso_code']); ?>" value="<?php if (isset($package['prices']['discount_' . strtolower($country_currency['iso_code'])])) echo $package['prices']['discount_' . strtolower($country_currency['iso_code'])]; ?>" /><span class="add-on">%</span></div></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                    </div>
                    
                    <?php foreach ($languages as $language){ ?>
                        <?php //if (!$tab_counter) $tab_class = 'tab-pane fade active in'; else $tab_class = 'tab-pane fade'; ?>
                        <?php $tab_class = 'tab-pane fade'; ?>
                        <div id="content_<?php echo $language['code']; ?>" class="<?php echo $tab_class; ?>">
                            <label>Package title:</label>
                            <input id="Package_title_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="title_<?php echo $language['code']; ?>" class="input-xxlarge html_edit_simple" type="text" value="<?php if (isset($package['title_' . $language['code']])) echo $package['title_' . $language['code']]; ?>" />
                            
                            <label>Package description:</label>
                            <textarea id="Package_description_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="description_<?php echo $language['code']; ?>" class="span12 html_edit_simple rows12" type="text" rows="12"><?php if (isset($package['description_' . $language['code']])) echo $package['description_' . $language['code']]; ?></textarea>
                        </div>
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

$('.offer-image .delete').off();

// Custom example logic
$(function() {
	var uploader = new plupload.Uploader({
		runtimes : 'html5',
		//browse_button : 'pickfiles',
        drop_element : 'image-block',
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
	});

	uploader.bind('Init', function(up, params) {
		//$('#image-block').html("<div>Current runtime: " + params.runtime + "</div>");
	});
/*
	$('#uploadfiles').click(function(e) {
		uploader.start();
		e.preventDefault();
	});
*/
	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#image-block').append(
				'<div class="offer-image" id="' + file.id + '">' +
				//file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                '<b></b>' +
			'</div>');
		});

		up.refresh(); // Reposition Flash/Silverlight
        up.start();
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + " b").html(file.percent + "%");
	});

	uploader.bind('Error', function(up, err) {
		$('#filelist').append("<div>Error: " + err.code +
			", Message: " + err.message +
			(err.file ? ", File: " + err.file.name : "") +
			"</div>"
		);

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file, response) {
	   var images_text = $("#Offer_images_<?php echo $id . '_' . $project_id; ?>").val();
	   var get_images = jQuery.parseJSON(images_text);
       if (get_images == null){
            var images = {"image_files" : []};
       } else {
            var images = {"image_files" : get_images};
       }
		//$('#' + file.id + " b").html("100%");
        var obj = jQuery.parseJSON(response.response);
        //$('#' + file.id + " b").html(obj.result);
        //$('#' + file.id).html('<img src="/uploads/' + obj.fileType + '/' + obj.fileYear + '/' + obj.fileMonth + '/' + obj.fileName + '" />');
        $('#' + file.id).html('<div class="delete"></div><img src="/images/70x70/' + obj.fileYear + '/' + obj.fileMonth + '/' + obj.fileName + '" />');
        $('#' + file.id).attr('year', obj.fileYear);
        $('#' + file.id).attr('month', obj.fileMonth);
        $('#' + file.id).attr('image', obj.fileName);
        images.image_files.push( { "id":file.id, "fileName":obj.fileName, "fileYear":obj.fileYear, "fileMonth":obj.fileMonth } );
        var new_images = JSON.stringify(images.image_files);
        $("#Offer_images_<?php echo $id . '_' . $project_id; ?>").val(new_images);
        bind_delete();
        
        $("#image-block").sortable({
            axis: 'x',
            update : function () {
                reorder_images();
            }
        });
	});
});

$("#image-block").sortable({
    axis: 'x',
    update : function () {
        reorder_images();
    }
});

function bind_delete(){
    $('.offer-image .delete').on('click', function() {
        /*$(this).parent().effect("highlight", {}, 1000);
        $(this).parent().hide("slide", { direction: "left" }, 1000);
        $(this).parent().remove();*/
        //$(this).parent().hide("slide", { direction: "left" }, 1000).delay(1000);
        $(this).parent().fadeOut(500, function() { $(this).remove(); reorder_images(); });
    });
}

bind_delete();

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

</script>