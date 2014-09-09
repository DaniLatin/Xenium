<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $offers, $projects, $items_count, $page_limit_start, $page_limit, $loading_page, $loading_function, $search_type_load, $search_term_load;
$loading_page = 'contents';
$loading_function = 'offers_default';

?>

<div class="custom-container title-container">
    <div style="float: left;">
        <h3>Offers</h3>
        <p>Here you can add, edit or delete all the offers of the website.</p>
    </div>
    <div style="float: right;">
        <p>Search users:</p>
        <div class="input-append"><input type="text" class="input-large" id="offer_search_simple" name="offer_search_simple" placeholder="Enter your search term.." value="<?php if (isset($search_type_load) && $search_type_load != 'undefined') echo base64_decode($search_term_load); ?>" /><button id="simple-search" class="btn search-button" type="button"><i class="icon-search"></i></button></div>
        <a id="offer_search" class="follow invisible"></a>
    </div>
    <div class="clear"></div>
</div>
<div class="row-fluid">
    <div class="span11">
        <div class="custom-container">
            <!--<h2>Showing all offers</h2>-->
            <?php echo self::load_prop('paging_prop'); ?>
            <?php if (is_array($offers) && isset($offers) && !empty($offers)){ ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="span10">Offer title</th>
                        <th class="span2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($offers as $offer ){ ?>
                    <tr id="<?php echo $offer['id']; ?>">
                        <td><?php echo $offer['title']; ?></td>
                        <td class="actions">
                            <a title="Edit offer" href="/admin/interface/contents/edit_offer/<?php echo $offer['id']; ?>_<?php echo $offer['project_id']; ?>/" class="btn follow"><i class="icon-edit"></i></a>
                            <a title="Duplicate offer" href="#duplicateContent" data-toggle="modal" no-follow="true" class="btn" onclick="duplicate_offer_row('<?php echo $offer['id']; ?>')"><i class="icon-copy"></i></a>
                            <button title="Trash offer" class="btn" onclick="hide_content_row('<?php echo $offer['id']; ?>', 'trash_offer')"><i class="icon-remove"></i></button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
            <?php if ($search_type_load && $search_type_load != 'undefined'){ ?>
                <h5>Your search term didn't return any results. Try another one.</h5>
            <?php } else { ?>
                <h5>There are no offers to display. Try to add some.</h5>
            <?php } ?>
            <?php } ?>
            
            <?php echo self::load_prop('paging_prop'); ?>
            
        </div>
    </div>
    <div class="span1">
        <div class="right-toolbar-positioner">
            <div class="right-toolbar-included">
                <div class="custom-container right-toolbar">
                    <p class="toolbar-title">Tools:</p>
                    <hr />
                    <p><a class="btn follow" href="/admin/interface/contents/offers_default/" title="Show all offers"><i class="icon-list-alt icon-2x"></i></a></p>
                    <p><a class="btn" href="#addStaticContent" data-toggle="modal" no-follow="true" title="Create a new offer"><i class="icon-file-alt icon-2x"></i></a></p>
                    <p><a class="btn follow" href="/admin/interface/contents/offers_trashed/" title="Show rejected offers"><i class="icon-trash icon-2x"></i></a></p>
                    <p><a class="btn follow" title="Show editing history"><i class="icon-time icon-2x"></i></a></p>
                    <hr />
                    <p><a class="btn" href="#editCategories" data-toggle="modal" no-follow="true" title="Edit offer categories"><i class="icon-sitemap icon-2x"></i></a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addStaticContent" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Add new offer</h3>
    </div>
    <div class="modal-body">
        <p>Enter the title of your new offer.</p>
        <p><label>Title:</label><input id="add_new_offer_title" class="input-xlarge" type="text" /></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php
        if (count($projects) == 1)
        {
        ?>
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="add_new_offer" onclick="add_new_content('add_new_offer', '<?php echo $projects[0]['id']; ?>'); return false;">Save and edit</button>
        <?php
        }
        ?>
    </div>
</div>

<div id="duplicateContent" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Duplicate offer</h3>
    </div>
    <div class="modal-body">
        <p>Enter the title of your new duplicated offer.</p>
        <p><label>Title:</label><input id="duplicate_offer_title" class="input-xlarge" type="text" /></p>
        <div class="alert invisible-block duplicate_alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Warning!</strong> An offer with this title already exists.
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php
        if (count($projects) == 1)
        {
        ?>
        <!--<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" project_id="<?php echo $projects[0]['id']; ?>" id="duplicate_offer" onclick="duplicate_content('duplicate_offer', '<?php echo $projects[0]['id']; ?>'); return false;">Duplicate and edit</button>-->
        <button class="btn btn-primary" project_id="<?php echo $projects[0]['id']; ?>" id="duplicate_offer" onclick="duplicate_content('duplicate_offer', '<?php echo $projects[0]['id']; ?>'); return false;">Duplicate and edit</button>
        <?php
        }
        ?>
    </div>
</div>

<!-- Modal for categories -->
<div id="editCategories" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Edit offer categories</h3>
    </div>
    <div class="modal-body">
        <p>Edit, add or delete offer categories using the following tree view.</p>
        
        <div id="mmenu" style="height:30px; overflow:auto;">
            <button id="add_folder" class="btn">Add category</button>
            <button id="add_default" class="btn">Add subcategory</button>
            <button id="rename" class="btn">Rename</button>
            <button id="remove" class="btn">Remove</button>
        </div>
        
        <p>&nbsp;</p>
        
        <div id="category_tree">
            <ul>
            <?php foreach ($projects as $project){ ?>
                <li id="drive_<?php echo $project['id']; ?>" class="jstree-open" rel="drive">
                    <a href="#"><?php echo $project['project_name']; ?></a>
                    <ul>
                    <?php $main_categories = Offer::get_all_offer_categories($project['id']); foreach ($main_categories as $main_category){ ?>
        				<li id="node_<?php echo $main_category['id']; ?>" rel="folder">
        					<a href="#" onmouseover="show_edit_category_button('<?php echo $main_category['id']; ?>')" onmouseout="hide_edit_category_button('<?php echo $main_category['id']; ?>')"><?php echo $main_category['title']; ?>
                                <button class="btn" id="edit_category_node_<?php echo $main_category['id']; ?>" onclick="trigger_category_edit('<?php echo $main_category['id']; ?>', '<?php echo $project['id']; ?>')" style="padding: 2px; margin-left: 8px; display: none; border: none; background: none;"><i class="fam-icon-application-form-edit"></i></button>
                            </a>
                            <ul>
                            <?php if (isset($main_category['sub'])){ foreach ($main_category['sub'] as $sub_category){ ?>
                                <li id="node_<?php echo $sub_category['id']; ?>" rel="default">
                                    <a href="#" onmouseover="show_edit_category_button('<?php echo $sub_category['id']; ?>')" onmouseout="hide_edit_category_button('<?php echo $sub_category['id']; ?>')"><?php echo $sub_category['title']; ?>
                                        <button class="btn" id="edit_category_node_<?php echo $sub_category['id']; ?>" onclick="trigger_category_edit('<?php echo $sub_category['id']; ?>', '<?php echo $project['id']; ?>')" style="padding: 2px; margin-left: 8px; display: none; border: none; background: none;" title="Advanced edit"><i class="fam-icon-application-form-edit"></i></button>
                                    </a>
                                </li>
                            <?php }} ?>
                            </ul>
        				</li>
                    <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        	</ul>
        </div>
        
        <a href="" id="category_edit_link" class="follow" style="display: none;"></a>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php
        if (count($projects) == 1)
        {
        ?>
        <!--<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="add_new_offer" onclick="add_new_content('add_new_offer', '<?php echo $projects[0]['id']; ?>'); return false;">Save and edit</button>-->
        <?php
        }
        ?>
    </div>
</div>

<!-- jsTree code -->
<script type="text/javascript" class="source below">
// Note method 2) and 3) use `one`, this is because if `refresh` is called those events are triggered
$(function () {
	$("#category_tree")
		.jstree({ 
		  //"plugins" : ["themes","html_data","ui","json_data","crrm","cookies","dnd","search","types","hotkeys","contextmenu" ],
          "plugins" : ["themes","html_data","ui","json_data","crrm","cookies","dnd","search","types","contextmenu" ],
          "types" : {
			// I set both options to -2, as I do not need depth and children count checking
			// Those two checks may slow jstree a lot, so use only when needed
			"max_depth" : 3,
			"max_children" : -2,
            "valid_children" : [ "drive" ],
			"types" : {
				// The default type
				"default" : {
					// I want this type to have no children (so only leaf nodes)
					// In my case - those are files
					"valid_children" : "none",
					// If we specify an icon for the default type it WILL OVERRIDE the theme icons
					"icon" : {
						"image" : "/admin/theme/img/file.png"
					}
				},
				// The `folder` type
				"folder" : {
					// can have files and other folders inside of it, but NOT `drive` nodes
					"valid_children" : [ "default" ],
					"icon" : {
						"image" : "/admin/theme/img/folder.png"
					}
				},
				// The `drive` nodes 
				"drive" : {
					// can have files and folders inside, but NOT other `drive` nodes
					"valid_children" : [ "folder" ],
					"icon" : {
						"image" : "/admin/theme/img/root.png"
					},
					// those prevent the functions with the same name to be used on `drive` nodes
					// internally the `before` event is used
					"start_drag" : false,
					"move_node" : false,
					"delete_node" : false,
					"remove" : false
				}
			}
			}
		
        })
		// 1) the loaded event fires as soon as data is parsed and inserted
		.bind("loaded.jstree", function (event, data) { })
		// 2) but if you are using the cookie plugin or the core `initially_open` option:
		.one("reopen.jstree", function (event, data) { })
		// 3) but if you are using the cookie plugin or the UI `initially_select` option:
		.one("reselect.jstree", function (event, data) { })
        
        .bind("create.jstree", function (e, data) {
            var project_id = data.rslt.obj.parents('li[rel="drive"]').attr('id').replace('drive_', '');
            var position = data.rslt.position;
            var title = data.rslt.name;
            var relation = data.rslt.obj.attr("rel");
            var parent_id = data.rslt.parent.attr("id").replace('node_', '');
            
            var last_id = AdminAction.new_offer_category(project_id, parent_id, position, title, relation);
            $(data.rslt.obj).attr("id", "node_" + last_id);
	   })
       .bind("rename.jstree", function (e, data) {
            var project_id = data.rslt.obj.parents('li[rel="drive"]').attr('id').replace('drive_', '');
            var id = data.rslt.obj.attr("id").replace('node_', '');
            var new_title = data.rslt.new_name;
            
            AdminAction.rename_offer_category(project_id, id, new_title);
	   })
       .bind("remove.jstree", function (e, data) {
	        var id = data.rslt.obj.attr("id").replace('node_', '');
            AdminAction.delete_offer_category(id);
            //console.log(id);
        /*data.rslt.obj.each(function () {
			$.ajax({
				async : false,
				type: 'POST',
				url: "./server.php",
				data : { 
					"operation" : "remove_node", 
					"id" : this.id.replace("node_","")
				}, 
				success : function (r) {
					if(!r.status) {
						data.inst.refresh();
					}
				}
			});
		});*/
	   });
});
</script>
<script type="text/javascript" class="source below">
// Code for the menu buttons
$(function () { 
	$("#mmenu button").click(function () {
		switch(this.id) {
			case "add_default":
			case "add_folder":
				$("#category_tree").jstree("create", null, "last", { "attr" : { "rel" : this.id.toString().replace("add_", "") } });
				break;
			case "search":
				$("#category_tree").jstree("search", document.getElementById("text").value);
				break;
			case "text": break;
			default:
				$("#category_tree").jstree(this.id);
				break;
		}
	});
    
    $("#simple-search").click(function () {
        send_search();
    });
    
    $("#offer_search_simple").keypress(function(e) {
        if(e.which == 13) {
            send_search();
        }
    });
    
    function send_search(){
        var search_term = $("#offer_search_simple").val();
        var search_term_encoded = utf8_to_b64(search_term);
        if (!search_term){
            bootbox.alert("Please enter a search term.");
        } else {
            $("#offer_search").attr('href', '/admin/interface/contents/offers_default/0_10_search-simple_' + search_term_encoded + '/');
            $("#offer_search").trigger('click');
        }
    }
});

function duplicate_offer_row(id){
    var offer_title = $("tr#" + id + " td").first().html().replace("'", "&#39;");
    var lastChar = offer_title.substr(offer_title.length - 1);
    var project_id = $("#duplicate_offer").attr('project_id');
    if ($.isNumeric(lastChar)){
        var nextNmb = lastChar * 1 + 1;
    } else {
        var nextNmb = 1;
    }
    $("#duplicate_offer_title").val(offer_title + ' ' + nextNmb);
    
    $("#duplicate_offer").attr('onclick', "duplicate_content('duplicate_offer', '" + offer_title + "', '#duplicate_offer_title', '" + project_id + "'); return false;");
}

function show_edit_category_button(id){
    $("#edit_category_node_" + id).css('display', 'inline');
}

function hide_edit_category_button(id){
    $("#edit_category_node_" + id).css('display', 'none');
}

function change_category_edit(cat_id, project_id){
    $("#category_edit_link").attr('href', '/admin/interface/contents/category_edit/' + cat_id + '_' + project_id + '/');
}

function trigger_category_edit(cat_id, project_id){
    $("#category_edit_link").attr('href', '/admin/interface/contents/category_edit/' + cat_id + '_' + project_id + '/');
    $("#category_edit_link")[0].click();
    //$('#editCategories').modal('hide');
    //$('.modal-backdrop').removeClass('in').delay(1000).remove();
    $('.modal-backdrop').remove();
}
</script>