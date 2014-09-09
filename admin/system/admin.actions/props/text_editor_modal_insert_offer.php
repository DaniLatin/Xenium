<div id="addOfferModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Insert an offer in text</h3>
    </div>
    <div class="modal-body">
        <p>Select an offer that you want to add and then select its size.</p>
        <p>
            <label>Select an offer:</label>
            <select id="text_offer_id" class="classic-select" style="width: 400px;">
                <option value="">Please choose</option>
                <optgroup label="<?php echo $project_info['project_name']; ?>">
                <?php foreach ($current_project_offers as $offer){ ?>
                    <option value="<?php echo $offer['id']; ?>"><?php echo $offer['title']; ?></option>
                <?php } ?>
                </optgroup>
            </select>
        </p>
        <p>
            <label>Select offer size:</label>
            &nbsp;&nbsp;&nbsp;<input value="1" type="radio" name="text_offer_size" checked="checked" /> 1
            &nbsp;&nbsp;&nbsp;<input value="2" type="radio" name="text_offer_size" /> 2
            &nbsp;&nbsp;&nbsp;<input value="3" type="radio" name="text_offer_size" /> 3
            &nbsp;&nbsp;&nbsp;<input value="4" type="radio" name="text_offer_size" /> 4
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="add_new_text_offer" onclick="add_new_text_offer(); return false;">Insert offer</button>
        
    </div>
</div>

<script type="text/javascript">
function add_new_text_offer(){
    var offer_id = $("#text_offer_id").val();
    console.log(offer_id);
    var offer_size = $("input[name='text_offer_size']:checked").val();
    var selected_offer = AdminAction.get_offer_for_text(offer_id, offer_size);
    
    $("#" + window.alohaEditable).prepend(selected_offer);
    //Aloha.execCommand('inserthtml', false, selected_offer);
    $("#" + window.alohaEditable).focus();
    //jQuery('.offer').alohaBlock();
    jQuery('.aloha-editable div').alohaBlock();
    bind_aloha_block_functions();
}
</script>