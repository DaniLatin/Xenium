<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $users, $projects, $items_count, $page_limit_start, $page_limit, $loading_page, $loading_function, $search_type_load, $search_term_load;
$loading_page = 'users';
$loading_function = 'users_default';

?>
<div class="custom-container title-container">
    <div style="float: left;">
        <h3>Users summary</h3>
        <p>Overview and statistics of users.</p>
    </div>
    <div style="float: right;">
        <p>Search users:</p>
        <div class="input-append"><input type="text" class="input-large" id="users_search_simple" name="users_search_simple" placeholder="Enter your search term.." value="<?php if (isset($search_type_load) && $search_type_load != 'undefined') echo base64_decode($search_term_load); ?>" /><button id="simple-search" class="btn search-button" type="button"><i class="icon-search"></i></button></div>
        <a id="users_search" class="follow invisible"></a>
    </div>
    <div class="clear"></div>
</div>

<div class="row-fluid">
    <div class="span11">
        <div class="custom-container">
            <!--<h2>Showing all offers</h2>-->
            <?php echo self::load_prop('paging_prop'); ?>
            <?php if (is_array($users) && isset($users) && !empty($users)){ ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="span6">User info</th>
                        <th class="span4"></th>
                        <th class="span2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user ){ ?>
                    <tr id="<?php echo $user['id']; ?>">
                        <td class="view-user-info">
                            <div class="img-polaroid image-wrapper-main">
                                <div class="image-wrapper">
                                <?php if ($user['fb_photo_url']){ ?>
                                    <img src="<?php echo $user['fb_photo_url']; ?>" width="75" height="75" />
                                <?php } elseif (!$user['fb_photo_url'] && $user['google_photo_url']){ ?>
                                    <img src="<?php echo $user['google_photo_url']; ?>" width="75" height="75" />
                                <?php } else { ?>
                                    <i class="icon-user icon-4x image-user"></i>
                                <?php } ?>
                                <img class="image-user-flag" src="/admin/theme/img/flags/24/<?php echo strtolower($user['country']); ?>.png" />
                                </div>
                            </div>
                            <span class="name-surname"><?php if ($user['name'] && $user['surname']) echo $user['name'] . ' ' . $user['surname'] . '<br />' . '<a href="mailto:' . $user['email'] . '">' . $user['email'] . '</a>'; else echo '<a href="mailto:' . $user['email'] . '">' . $user['email'] . '</a>'; ?></span>
                            <br />
                            <?php if ($user['fb_id']){ ?><i class="icon-facebook-sign icon-2x"></i><?php } ?>
                            <?php if ($user['google_id']){ ?><i class="icon-google-plus-sign icon-2x"></i><?php } ?>
                        </td>
                        <td class="time-info">
                            <small>
                                <label>Registration time:</label>
                                <strong><?php echo $user['datetime_registration']; ?></strong>
                                <hr class="clear" />
                                <label>Activation time:</label>
                                <strong id="uactivated_<?php echo $user['id']; ?>"><?php echo $user['datetime_activation']; ?></strong>
                                <hr class="clear" />
                                <label>Last change time:</label>
                                <strong><?php echo $user['datetime_last_change']; ?></strong>
                            </small>
                        </td>
                        <td class="user-actions" style="border-left: 1px solid #DDDDDD;">
                        <!--
                            <a title="Edit offer" href="/admin/interface/contents/edit_offer/<?php echo $user['id']; ?>_<?php echo $user['project_id']; ?>/" class="btn follow"><i class="icon-edit"></i></a>
                            <a title="Duplicate offer" href="#duplicateContent" data-toggle="modal" no-follow="true" class="btn" onclick="duplicate_offer_row('<?php echo $user['id']; ?>')"><i class="icon-copy"></i></a>
                            <button title="Trash offer" class="btn" onclick="hide_content_row('<?php echo $user['id']; ?>', 'trash_offer')"><i class="icon-remove"></i></button>
                        -->
                            <button class="btn"><i class="icon-info-sign"></i>Show info</button>
                            <button class="btn<?php if ($user['activated']) echo ' disabled'; ?>" id="activation_<?php echo $user['id']; ?>" onclick="uactivate(<?php echo $user['id']; ?>);"><i class="icon-thumbs-up"></i>Activate</button>
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
                    <p><a class="btn follow" title="Show rejected offers"><i class="icon-trash icon-2x"></i></a></p>
                    <p><a class="btn follow" title="Show editing history"><i class="icon-time icon-2x"></i></a></p>
                    <hr />
                    <p><a class="btn" href="#editCategories" data-toggle="modal" no-follow="true" title="Edit offer categories"><i class="icon-sitemap icon-2x"></i></a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function () { 
	$("#simple-search").click(function () {
        send_search();
    });
    
    $("#users_search_simple").keypress(function(e) {
        if(e.which == 13) {
            send_search();
        }
    });
    
    function send_search(){
        var search_term = $("#users_search_simple").val();
        var search_term_encoded = utf8_to_b64(search_term);
        if (!search_term){
            bootbox.alert("Please enter a search term.");
        } else {
            $("#users_search").attr('href', '/admin/interface/users/users_default/0_10_search-simple_' + search_term_encoded + '/');
            $("#users_search").trigger('click');
        }
    }
    
    function uactivate(user_id){
        AdminAction.user_activate(user_id);
        $("#activation_" + user_id).addClass('disabled');
    }
});

function uactivate(user_id){
        var activation_time = AdminAction.user_activate(user_id);
        $("#activation_" + user_id).addClass('disabled');
        $("#uactivated_" + user_id).text(activation_time);
    }
</script>