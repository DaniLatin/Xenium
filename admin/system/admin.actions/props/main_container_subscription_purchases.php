<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $payments, $projects, $items_count, $page_limit_start, $page_limit, $loading_page, $loading_function, $search_type_load, $search_term_load, $payments_confirmed_overall;
$loading_page = 'applications';
$loading_function = 'purchased_subscriptions';

?>
<div class="custom-container title-container">
    <div style="float: left;">
        <h3>Subscription payments summary</h3>
        <p>Overview and statistics of payments.</p>
    </div>
    
    <div style="float: right; margin-left: 20px;">
        <p>Search payments:</p>
        <div class="input-append"><input type="text" class="input-large" id="payments_search_simple" name="payments_search_simple" placeholder="Enter your search term.." value="<?php if (isset($search_type_load) && $search_type_load != 'undefined') echo base64_decode($search_term_load); ?>" /><button id="simple-search" class="btn search-button" type="button"><i class="icon-search"></i></button></div>
        <a id="payments_search" class="follow invisible"></a>
    </div>
    
    <div style="float: right;">
        <h4><small>Total confirmed payments:</small> <span id="total_payments_nmb"><?php echo $payments_confirmed_overall['number']; ?></span></h4>
        <h4><small>Total confirmed amount:</small> <span id="total_payments_amount"><?php echo $payments_confirmed_overall['amount']; ?></span> €</h4>
    </div>
    
    <div class="clear"></div>
</div>

<div class="row-fluid">
    <div class="span11">
        <div class="custom-container">
            <!--<h2>Showing all offers</h2>-->
            <?php echo self::load_prop('paging_prop'); ?>
            <?php if (is_array($payments) && isset($payments) && !empty($payments)){ ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="span6">Payment info</th>
                        <th class="span3"></th>
                        <th class="span1" style="text-align: center;">Status</th>
                        <th class="span2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $payment_date = ''; foreach ($payments as $payment ){ ?>
                    <?php if (!$payment_date || ($payment_date != substr($payment['datetime_created'], 0, strpos($payment['datetime_created'], ' ')))){ ?>
                    <tr><td colspan="4"><h5><?php echo substr($payment['datetime_created'], 0, strpos($payment['datetime_created'], ' ')); ?></h5></td></tr>
                    <?php } ?>
                    <tr id="<?php echo $payment['id']; ?>">
                        <td class="view-user-info">
                            <div class="img-polaroid image-wrapper-main">
                                <div class="image-wrapper">
                                <img src="/admin/theme/img/payments/<?php echo $payment['payment_type']; ?>.jpg" width="75" height="75" />
                                <img class="image-user-flag" src="/admin/theme/img/flags/24/<?php echo strtolower($payment['country']); ?>.png" />
                                </div>
                            </div>
                            <h4><span id="purchase_price_<?php echo $payment['id']; ?>"><?php echo $payment['discount_price']; ?></span> € <small>(<?php echo $payment['product_info']['title']; ?>)</small></h4>
                            <span class="name-surname"><?php echo $payment['user_info']['name'] . ' ' . $payment['user_info']['surname']; ?></span><br />
                            <a href="mailto:<?php echo $payment['user_info']['email']; ?>"><?php echo $payment['user_info']['email']; ?></a>
                        </td>
                        <td class="time-info">
                            <small>
                                <label>Payment started time:</label>
                                <strong><?php echo $payment['datetime_created']; ?></strong>
                                <hr class="clear" />
                                <label>Payment confirmed time:</label>
                                <?php if ($payment['status'] == 'payed' ){ ?>
                                <strong><?php if (isset($payment['datetime_payed'])) echo $payment['datetime_payed']; ?></strong>
                                <?php } else { ?>
                                <strong>not confirmed</strong>
                                <?php } ?>
                                <hr class="clear" />
                            </small>
                        </td>
                        <td id="purchase_status_<?php echo $payment['id']; ?>" style="text-align: center; color: <?php if ($payment['status'] == 'payed'){ ?>green<?php } else { ?>#0088CC<?php } ?>;">
                            <?php if ($payment['status'] == 'payed'){ ?><i class="icon-ok icon-3x"></i><?php } else { ?><i class="icon-time icon-3x"></i><?php } ?><br />
                            <?php echo str_replace('payed', 'confirmed', $payment['status']); ?>
                        </td>
                        <td class="user-actions" style="border-left: 1px solid #DDDDDD;">
                        <!--
                            <a title="Edit offer" href="/admin/interface/contents/edit_offer/<?php echo $payment['id']; ?>_<?php echo $payment['project_id']; ?>/" class="btn follow"><i class="icon-edit"></i></a>
                            <a title="Duplicate offer" href="#duplicateContent" data-toggle="modal" no-follow="true" class="btn" onclick="duplicate_offer_row('<?php echo $payment['id']; ?>')"><i class="icon-copy"></i></a>
                            <button title="Trash offer" class="btn" onclick="hide_content_row('<?php echo $payment['id']; ?>', 'trash_offer')"><i class="icon-remove"></i></button>
                        -->
                            <button class="btn"><i class="icon-info-sign"></i>Show info</button>
                            <button id="purchase_<?php echo $payment['id']; ?>" class="btn confirm-purchase<?php if ($payment['status'] == 'payed') echo ' disabled'; ?>"><i class="icon-thumbs-up"></i>Confirm</button>
                            <small><?php echo $payment['reference']; ?></small>
                        </td>
                    </tr>
                    <?php $payment_date = substr($payment['datetime_created'], 0, strpos($payment['datetime_created'], ' ')); } ?>
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
    
    $("#payments_search_simple").keypress(function(e) {
        if(e.which == 13) {
            send_search();
        }
    });
    
    function send_search(){
        var search_term = $("#payments_search_simple").val();
        var search_term_encoded = utf8_to_b64(search_term);
        if (!search_term){
            bootbox.alert("Please enter a search term.");
        } else {
            $("#payments_search").attr('href', '/admin/interface/applications/purchased_subscriptions/0_10_search-simple_' + search_term_encoded + '/');
            $("#payments_search").trigger('click');
        }
    }
});

$(".confirm-purchase").click(function(){
    var element_id = $(this).attr('id');
    var element_id_split = element_id.split("_");
    var id = element_id_split[1];
    var answer = $.parseJSON(AdminAction.confirm_payment(id));
    
    var total_payments_nmb = $("#total_payments_nmb").text();
    var total_payments_amount = $("#total_payments_amount").text();
    
    var purchase_price = $("#purchase_price_" + id).text();
    
    if (answer.result == 1){
        var new_total_payments_nmb = total_payments_nmb * 1 + 1;
        var new_total_payments_amount = total_payments_amount * 1 + purchase_price * 1;
        var new_total_payments_amount_fixed = new_total_payments_amount.toFixed(2);
        
        $(this).addClass("disabled");
        $("#purchase_status_" + id).css({"text-align":"center","color":"green"});
        $("#purchase_status_" + id).html('<i class="icon-ok icon-3x"></i><br />confirmed');
        
        $("#total_payments_nmb").text(new_total_payments_nmb);
        $("#total_payments_amount").text(new_total_payments_amount_fixed);
    }
});
</script>