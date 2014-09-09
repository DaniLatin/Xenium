<div id="addTextBlockModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Insert a text block in text</h3>
    </div>
    <div class="modal-body">
        <p>Select a text block that you want to add and then select its size.</p>
        <p>
            <label>Select a text block:</label>
            <select id="text_textblock_id" class="classic-select" style="width: 400px;">
                <option value="">Please choose</option>
                <?php foreach ($project_data as $project){ ?>
                <optgroup label="<?php echo $project['project_name']; ?>">
                <?php foreach ($project['data'] as $text_block){ ?>
                    <option value="<?php echo $text_block['id']; ?>"><?php echo $text_block['title']; ?></option>
                <?php } ?>
                <?php } ?>
                </optgroup>
            </select>
        </p>
        <p>
            <label>Select text block size:</label>
            &nbsp;&nbsp;&nbsp;<input value="1" type="radio" name="text_textblock_size" checked="checked" /> 25%
            &nbsp;&nbsp;&nbsp;<input value="2" type="radio" name="text_textblock_size" /> 50%
            &nbsp;&nbsp;&nbsp;<input value="3" type="radio" name="text_textblock_size" /> 75%
            &nbsp;&nbsp;&nbsp;<input value="4" type="radio" name="text_textblock_size" /> 100%
            &nbsp;&nbsp;&nbsp;<input value="-one-third" type="radio" name="text_textblock_size" /> one third
            &nbsp;&nbsp;&nbsp;<input value="-two-thirds" type="radio" name="text_textblock_size" /> two thirds
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="add_new_text_block" onclick="add_new_text_block(); return false;">Insert text block</button>
        
    </div>
</div>

<script type="text/javascript">
function add_new_text_block(){
    var textblock_id = $("#text_textblock_id").val();
    //console.log(offer_id);
    var textblock_size = $("input[name='text_textblock_size']:checked").val();
    var selected_textblock = AdminAction.get_textblock_for_text(textblock_id, textblock_size);
    
    $("#" + window.alohaEditable).prepend(selected_textblock);
    //Aloha.execCommand('inserthtml', false, selected_offer);
    $("#" + window.alohaEditable).focus();
    //jQuery('.offer').alohaBlock();
    
    var elem_id = window.alohaEditable.replace("-aloha", "");
    matchit(elem_id);
    
    jQuery('.aloha-editable .alohablock').alohaBlock();
    
    //$('.col1, .col2, .col1').wrapAll('<div class="row" />'); 
    
    bind_aloha_block_functions();
}
</script>