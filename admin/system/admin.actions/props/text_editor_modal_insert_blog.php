<div id="addBlogModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Insert a text block in text</h3>
    </div>
    <div class="modal-body">
        <p>Select a text block that you want to add and then select its size.</p>
        <p>
            <label>Select a text block:</label>
            <select id="text_blog_id" class="classic-select" style="width: 400px;">
                <option value="">Please choose</option>
                <?php foreach ($project_data as $project){ ?>
                <optgroup label="<?php echo $project['project_name']; ?>">
                <?php foreach ($project['data'] as $blog){ ?>
                    <option value="<?php echo $blog['id']; ?>"><?php echo $blog['title']; ?></option>
                <?php } ?>
                <?php } ?>
                </optgroup>
            </select>
        </p>
        <p>
            <label>Select block size:</label>
            &nbsp;&nbsp;&nbsp;<input value="1" type="radio" name="text_blog_size" checked="checked" /> 25%
            &nbsp;&nbsp;&nbsp;<input value="2" type="radio" name="text_blog_size" /> 50%
            &nbsp;&nbsp;&nbsp;<input value="3" type="radio" name="text_blog_size" /> 75%
            &nbsp;&nbsp;&nbsp;<input value="4" type="radio" name="text_blog_size" /> 100%
            &nbsp;&nbsp;&nbsp;<input value="-one-third" type="radio" name="text_blog_size" /> one third
            &nbsp;&nbsp;&nbsp;<input value="-two-thirds" type="radio" name="text_blog_size" /> two thirds
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="add_new_blog" onclick="add_new_blog(); return false;">Insert block</button>
        
    </div>
</div>

<script type="text/javascript">
function add_new_blog(){
    var blog_id = $("#text_blog_id").val();
    //console.log(offer_id);
    var blog_size = $("input[name='text_blog_size']:checked").val();
    var selected_blog = AdminAction.get_blog_for_text(blog_id, blog_size);
    
    $("#" + window.alohaEditable).prepend(selected_blog);
    //Aloha.execCommand('inserthtml', false, selected_offer);
    $("#" + window.alohaEditable).focus();
    //jQuery('.offer').alohaBlock();
    
    var elem_id = window.alohaEditable.replace("-aloha", "");
    matchit(elem_id);
    
    jQuery('.aloha-editable .alohablock').alohaBlock();
    
    bind_aloha_block_functions();
}
</script>