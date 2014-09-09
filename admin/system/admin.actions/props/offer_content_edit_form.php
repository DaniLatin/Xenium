<?php

/**
 * @author Danijel
 * @copyright 2013
 */



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
                        <?php /* if (!$tab_counter){ ?>
                            <!--<li class="active"><a data-toggle="tab" no-follow="true" href="#content_<?php echo $language['code']; ?>"><img src="/admin/theme/img/flags/32/<?php echo $language['icon']; ?>" /></a></li>-->
                        <?php } else { ?>
                            <!--<li><a data-toggle="tab" no-follow="true" href="#content_<?php echo $language['code']; ?>"><img src="/admin/theme/img/flags/32/<?php echo $language['icon']; ?>" /></a></li>-->
                        <?php } ?>
                        
                        <?php $tab_counter++; */ ?>
                    <?php } ?>
                </ul>
                
                <div id="language-tabsContent" class="tab-content">
                
                    <div id="content_info" class="tab-pane fade active in">
                        <label>Main title (not editable):</label>
                        <h5><?php echo $offer_content['title']; ?></h5>
                        
                        <label>Offer category:</label>
                        <select class="classic-select" id="Offer_category_<?php echo $id . '_' . $project_id; ?>" name="category" style="width: 200px;">
                            <option value="">Choose a category</option>
                            <?php foreach ($main_categories as $main_category){ ?>
                            <optgroup label="<?php echo $main_category['title']; ?>">
                                <?php if (isset($main_category['sub'])){ foreach ($main_category['sub'] as $sub_category){ ?>
                                <option <?php if (isset($offer_content['category']) && $sub_category['id'] == $offer_content['category']){ ?> selected="selected" <?php } ?> value="<?php echo $sub_category['id']; ?>"><?php echo $sub_category['title']; ?></option>
                                <?php }} ?>
                            </optgroup>
                            <?php } ?>
                        </select>
                        <br />
                        
                        <label style="margin-top: 10px;">Voucher validity:</label>
                        <input id="Offer_voucher_validity_<?php echo $id . '_' . $project_id; ?>" name="voucher_validity" class="input-xlarge daterange" type="text" value="<?php if (isset($offer_content['voucher_validity'])) echo $offer_content['voucher_validity']; ?>" />
                        
                        <label>No. of persons per voucher:</label>
                        <input id="Offer_voucher_persons_<?php echo $id . '_' . $project_id; ?>" name="voucher_persons" class="persons-select" value="<?php if (isset($offer_content['voucher_persons'])) echo $offer_content['voucher_persons']; ?>" style="width: 300px;" />
                        <br />
                        
                        <label style="margin-top: 8px;">Voucher print limit:</label>
                        <input id="Offer_voucher_print_limit_<?php echo $id . '_' . $project_id; ?>" name="voucher_print_limit" class="input-xlarge" type="text" value="<?php if (isset($offer_content['voucher_print_limit'])) echo $offer_content['voucher_print_limit']; ?>" />
                        <br />
                        
                        <label style="margin-top: 10px;">Offer images:</label>
                        <textarea style="display: none;" id="Offer_images_<?php echo $id . '_' . $project_id; ?>" name="images"><?php echo $offer_content['images']; ?></textarea>
                        <div id="image-block" class="image-block">
                            <?php if (count($images)){ ?>
                            <?php foreach ($images as $image){ ?>
                            <div class="offer-image" id="<?php echo $image['id']; ?>" year="<?php echo $image['fileYear']; ?>" month="<?php echo $image['fileMonth']; ?>" image="<?php echo $image['fileName']; ?>">
                                <div class="delete"></div>
                                <img src="/images/70x70/<?php echo $image['fileYear']; ?>/<?php echo $image['fileMonth']; ?>/<?php echo $image['fileName']; ?>" />
                            </div>
                            <?php } ?>
                            <?php } ?>
                        </div>
                        
                        <label>Offer prices:</label>
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
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_original_price_<?php echo $id . '_' . $project_id; ?>" name="original_price" value="<?php if (isset($offer_content['prices']['original_price'])) echo $offer_content['prices']['original_price']; else echo '0.00'; ?>" /><span class="add-on">€</span></div></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_discount_price_<?php echo $id . '_' . $project_id; ?>" name="discount_price" value="<?php if (isset($offer_content['prices']['discount_price'])) echo $offer_content['prices']['discount_price']; else echo '0.00'; ?>" /><span class="add-on">€</span></div></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_discount_<?php echo $id . '_' . $project_id; ?>" name="discount" value="<?php if (isset($offer_content['prices']['discount'])) echo $offer_content['prices']['discount']; else echo '0'; ?>" /><span class="add-on">%</span></div></td>
                                </tr>
                                <?php foreach ($countries_currency_own as $country_currency ){ ?>
                                <tr>
                                    <td><img src="/admin/theme/img/flags/32/<?php echo strtolower($country_currency['iso_code']); ?>.png" /></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_original_price_<?php echo strtolower($country_currency['iso_code']) . '_' . $id . '_' . $project_id; ?>" name="original_price_<?php echo strtolower($country_currency['iso_code']); ?>" value="<?php if (isset($offer_content['prices']['original_price_' . strtolower($country_currency['iso_code'])])) echo $offer_content['prices']['original_price_' . strtolower($country_currency['iso_code'])]; else echo '0.00'; ?>" /><span class="add-on"><?php echo $country_currency['currency']; ?></span></div></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_discount_price_<?php echo strtolower($country_currency['iso_code']) . '_' . $id . '_' . $project_id; ?>" name="discount_price_<?php echo strtolower($country_currency['iso_code']); ?>" value="<?php if (isset($offer_content['prices']['discount_price_' . strtolower($country_currency['iso_code'])])) echo $offer_content['prices']['discount_price_' . strtolower($country_currency['iso_code'])]; else echo '0.00'; ?>" /><span class="add-on"><?php echo $country_currency['currency']; ?></span></div></td>
                                    <td><div class="input-append"><input type="text" class="input-small" id="Offer_discount_<?php echo strtolower($country_currency['iso_code']) . '_' . $id . '_' . $project_id; ?>" name="discount_<?php echo strtolower($country_currency['iso_code']); ?>" value="<?php if (isset($offer_content['prices']['discount_' . strtolower($country_currency['iso_code'])])) echo $offer_content['prices']['discount_' . strtolower($country_currency['iso_code'])]; echo '0'; ?>" /><span class="add-on">%</span></div></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                        <hr />
                        <h5>Seller info</h5>
                        
                        <div style="float: left;">
                            <label>Seller name:</label>
                            <input id="Offer_seller_name_<?php echo $id . '_' . $project_id; ?>" name="seller_name" class="input-xxlarge" type="text" value="<?php if (isset($offer_content['seller_name'])) echo $offer_content['seller_name']; ?>" />
                        
                            <label>Seller brandname:</label>
                            <input id="Offer_seller_brandname_<?php echo $id . '_' . $project_id; ?>" name="seller_brandname" class="input-xxlarge" type="text" value="<?php if (isset($offer_content['seller_brandname'])) echo $offer_content['seller_brandname']; ?>" />
                        </div>
                        
                        <div style="float: right; margin-bottom: 15px; position: relative;">
                            <div class="logo-delete"></div>
                            <label>Seller logo:</label>
                            <div id="seller-logo" class="image-block seller-logo" style="width: 120px; height: 120px; padding: 0px; display: table-cell; vertical-align: middle; position: relative;">
                            
                            <?php if (isset($logo) && count($logo)){ ?>
                            <?php foreach ($logo as $image){ ?>
                                <img src="/uploads/image/<?php echo $image['fileYear']; ?>/<?php echo $image['fileMonth']; ?>/<?php echo $image['fileName']; ?>" style="max-width: 100%; max-height: 100%;" />
                            <?php } ?>
                            <?php } ?>
                            </div>
                            <textarea style="display: none;" id="Offer_seller_logo_<?php echo $id . '_' . $project_id; ?>" name="seller_logo" class="seller_logo" ><?php if (isset($offer_content['seller_logo'])) echo $offer_content['seller_logo']; ?></textarea>
                        </div>
                        
                        <hr class="clear" />
                        <h5>Seller location info</h5>
                        <div style="float: left;">
                            
                            <label>Seller address:</label>
                            <input id="Offer_seller_address_<?php echo $id . '_' . $project_id; ?>" name="seller_address" class="input-xxlarge addresspicker" type="text" value="<?php echo $offer_content['seller_address']; ?>" />
                            
                            <label>GEO location:</label>
                            <label>Lat.:</label>
                            <input readonly id="Offer_seller_lat_<?php echo $id . '_' . $project_id; ?>" name="seller_lat" class="input-medium geo-lat" type="text" value="<?php if (isset($offer_content['seller_lat'])) echo $offer_content['seller_lat']; ?>" /><br />
                            <label>Lng.:</label>
                            <input readonly id="Offer_seller_lng_<?php echo $id . '_' . $project_id; ?>" name="seller_lng" class="input-medium geo-lng" type="text" value="<?php if (isset($offer_content['seller_lng'])) echo $offer_content['seller_lng']; ?>" />
                                
                            <label>Seller city, country:</label>
                            <input id="Offer_seller_city_<?php echo $id . '_' . $project_id; ?>" name="seller_city" class="city-select" value="<?php if (isset($offer_content['seller_city'])) echo $offer_content['seller_city']; ?>" /><br />
                        
                        </div>
                            
                        <div id="geo-map" style="width: 400px; height: 300px; float: right; margin-bottom: 15px; border: 1px solid #CCCCCC;"></div>
                        <hr class="clear" />
                            
                        <h5>Seller contact info</h5>
                        
                        <label style="margin-top: 10px;">Seller phone:</label>
                        <input id="Offer_seller_phone_<?php echo $id . '_' . $project_id; ?>" name="seller_phone" class="input-xlarge" type="text" value="<?php if (isset($offer_content['seller_phone'])) echo $offer_content['seller_phone']; ?>" />
                        
                        <label>Seller email:</label>
                        <input id="Offer_seller_email_<?php echo $id . '_' . $project_id; ?>" name="seller_email" class="input-xlarge" type="text" value="<?php if (isset($offer_content['seller_email'])) echo $offer_content['seller_email']; ?>" />
                        
                        <label>Seller website:</label>
                        <input id="Offer_seller_website_<?php echo $id . '_' . $project_id; ?>" name="seller_website" class="input-xlarge" type="text" value="<?php if (isset($offer_content['seller_website'])) echo $offer_content['seller_website']; ?>" />
                        
                        
                        
                        <div class="clear"></div>
                    </div>
                
                    <?php //$tab_counter = 0; ?>
                    <?php foreach ($languages as $language){ ?>
                        <?php //if (!$tab_counter) $tab_class = 'tab-pane fade active in'; else $tab_class = 'tab-pane fade'; ?>
                        <?php $tab_class = 'tab-pane fade'; ?>
                        <div id="content_<?php echo $language['code']; ?>" class="<?php echo $tab_class; ?>">
                            <label>Offer title:</label>
                            <input id="Offer_title_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="title_<?php echo $language['code']; ?>" class="input-xxlarge html_edit_simple" type="text" value="<?php echo $offer_content['title_' . $language['code']]; ?>" />
                            
                            <label>Offer intro:</label>
                            <textarea id="Offer_intro_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="intro_<?php echo $language['code']; ?>" class="span12 html_edit_simple rows5" type="text" rows="5"><?php echo $offer_content['intro_' . $language['code']]; ?></textarea>
                            
                            <label>Offer description:</label>
                            <textarea id="Offer_description_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="description_<?php echo $language['code']; ?>" class="span12 html_edit_advanced rows12" type="text" rows="12"><?php echo $offer_content['description_' . $language['code']]; ?></textarea>
                            
                            <label>Offer includes:</label>
                            <textarea id="Offer_includes_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="offer_includes_<?php echo $language['code']; ?>" class="span12 html_edit_advanced rows5" type="text" rows="5"><?php echo $offer_content['offer_includes_' . $language['code']]; ?></textarea>
                            
                            <label>Offer notes:</label>
                            <textarea id="Offer_notes_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="notes_<?php echo $language['code']; ?>" class="span12 html_edit_advanced rows5" type="text" rows="5"><?php echo $offer_content['notes_' . $language['code']]; ?></textarea>
                            
                            <label>Offer SEO description:</label>
                            <textarea id="Offer_seo_description_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="seo_description_<?php echo $language['code']; ?>" class="span12" type="text" rows="2"><?php echo $offer_content['seo_description_' . $language['code']]; ?></textarea>
                            
                            <label>Offer SEO keywords:</label>
                            <textarea id="Offer_seo_keywords_<?php echo $language['code'] . '_' . $id . '_' . $project_id; ?>" name="seo_keywords_<?php echo $language['code']; ?>" class="span12" type="text" rows="2"><?php echo $offer_content['seo_keywords_' . $language['code']]; ?></textarea>
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

function bind_logo_delete(){
    $('.logo-delete').on('click', function() {
        /*$(this).parent().effect("highlight", {}, 1000);
        $(this).parent().hide("slide", { direction: "left" }, 1000);
        $(this).parent().remove();*/
        //$(this).parent().hide("slide", { direction: "left" }, 1000).delay(1000);
        $(".seller_logo").val("[]");
        $("#seller-logo img").fadeOut(500, function() { $("#seller-logo img").remove(); });
    });
}

bind_delete();
bind_logo_delete();

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

$(function() {
    $(".seller-logo").each(function(){
        
        //var target_textarea_id = $(this).attr('id').replace('-plupload', '');
        var target_textarea_id = $(".seller_logo").attr('id').replace('-plupload', '');
        var drop_element = $(this).attr('id');
        //var language = $(this).attr('language');
    
    	window['uploader'] = new plupload.Uploader({
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
    
    	window['uploader'].bind('Init', function(up, params) {
    		//$('#image-block').html("<div>Current runtime: " + params.runtime + "</div>");
    	});
        /*
    	$('#uploadfiles').click(function(e) {
    		uploader.start();
    		e.preventDefault();
    	});
        */
    	window['uploader'].init();
    
    	window['uploader'].bind('FilesAdded', function(up, files) {
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
    
    	window['uploader'].bind('UploadProgress', function(up, file) {
    		$('#' + file.id + " b").html(file.percent + "%");
    	});
    
    	window['uploader'].bind('Error', function(up, err) {
    		$('#filelist').append("<div>Error: " + err.code +
    			", Message: " + err.message +
    			(err.file ? ", File: " + err.file.name : "") +
    			"</div>"
    		);
    
    		up.refresh(); // Reposition Flash/Silverlight
    	});
    
    	window['uploader'].bind('FileUploaded', function(up, file, response) {
           
           var images = {"image_files" : []};
           
    		var obj = jQuery.parseJSON(response.response);
            //$('#' + file.id).html('<div class="img" style="background: url(/images/1000x200/' + obj.fileYear + '/' + obj.fileMonth + '/' + obj.fileName + ')"></div>');
            $('#seller-logo').html('<img src="/uploads/image/' + obj.fileYear + '/' + obj.fileMonth + '/' + obj.fileName + '" style="max-width: 100%; max-height: 100%;" />');
            //$('#' + file.id).attr('year', obj.fileYear);
            //$('#' + file.id).attr('month', obj.fileMonth);
            //$('#' + file.id).attr('image', obj.fileName);
            images.image_files.push( { "id":file.id, "fileName":obj.fileName, "fileYear":obj.fileYear, "fileMonth":obj.fileMonth } );
            var new_images = JSON.stringify(images.image_files);
            $("#" + target_textarea_id).val(new_images);
    	});
    
    });
});

function locationFormatResult(location) {
    var markup = "<table class='location-result'><tr>";

    if (location.countryCode !== undefined) {
        //markup += "<td class='flag-image'><img src='http://www.geonames.org/flags/x/" + location.countryCode.toLowerCase() + ".gif' width='32' /></td>";
        markup += "<td class='flag-image'><img src='/admin/theme/img/flags/24/" + location.countryCode.toLowerCase() + ".png' style='padding-right: 8px;' title='" + location.countryCode.toLowerCase() + "' /></td>";
    }

    markup += "<td class='location-info'>";
    markup += "<div class='location-name'>" + location.name + ", " + location.adminName1 + ", " + location.countryName + "</div>";
    markup += "</td></tr></table>";

    return markup;
}

function locationFormatSelection(location) {
    //console.log(location);
    return location.name + ", " + location.adminName1 + ", " + location.countryName;
}

$(function () {
    $('.city-select').select2({
        id: function(e) { return e.name + '|' + e.adminName1 + '|' + e.countryName },
        placeholder: 'Start typing a city name...',
        allowClear: true,
        width: '350px',
        minimumInputLength: 2,
        ajax: {
            url: 'http://api.geonames.org/searchJSON',
            dataType: 'jsonp',
            data: function (term) {
                return {
                    featureClass: 'P',
                    q: term,
                    username: 'xplicit',
                    maxRows: 10
                };
            },
            results: function (data) {
                return {
                    results: data.geonames
                };
            }
        },
        
        initSelection: function(element, callback) {
            var id=$(element).val();
            if (id!=="") {
                city_element = id.split('|')
                $.ajax(
                    'http://api.geonames.org/searchJSON',{
                        dataType: 'jsonp',
                        data: {
                                featureClass: 'P',
                                maxRows: 1,
                                name: city_element[0],
                                adminName1: city_element[1],
                                countryName: city_element[2],
                                username: 'xplicit',
                        },
                    }
                ).done(function(data) { callback(data.geonames[0]); });
            }
        },
        formatResult: locationFormatResult,
        formatSelection: locationFormatSelection,
        //dropdownCssClass: "bigdrop"
    });
    
    //var value = $(".city-select").val();
    //$(".city-select").select2("val", value);
    
    $(".persons-select").select2({
        createSearchChoice:function(term, data) { if ($(data).filter(function() { return this.text.localeCompare(term)===0; }).length===0) {return {id:term, text:term};} },
        placeholder: 'Start typing your selection...',
        multiple: false,
        allowClear: true,
        //width: '350px',
        //data: [{id: 0, text: 'story'},{id: 1, text: 'bug'},{id: 2, text: 'task'}]
        data: jQuery.parseJSON('<?php echo $voucher_persons_options; ?>')
    });
    /*
    $("body").on('mouseup', '.dropdown-menu a', function(){
        setTimeout(function () {alert($(".addresspicker").val())}, 500);
    });
    */
});

$(function() {
    
});

</script>