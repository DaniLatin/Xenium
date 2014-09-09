<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $emails;

?>

<div class="custom-container title-container">
    <h3>Emails</h3>
    <p>Here you can add, edit or delete all the emails sent through website.</p>
</div>
<div class="row-fluid">
    <div class="span11">
        <div class="custom-container">
            <h2>Showing all emails</h2>
            <?php if (is_array($emails) && isset($emails)){ ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="span10">Email title</th>
                        <th class="span2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emails as $email ){ ?>
                    <tr id="<?php echo $email['id']; ?>">
                        <td><?php echo $email['title']; ?></td>
                        <td>
                            <a href="/admin/interface/contents/edit_email/<?php echo $email['id']; ?>_<?php echo $email['project_id']; ?>/" class="btn follow"><i class="icon-edit"></i></a>
                            <button class="btn" onclick="hide_content_row('<?php echo $email['id']; ?>', 'trash_static_content')"><i class="icon-remove"></i></button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
            <h5>There are no emails to display. Try to add some.</h5>
            <?php } ?>
            <!--
            <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
            <p><a class="btn" href="#">View details &raquo;</a></p>
            -->
        </div>
    </div>
    <div class="span1">
        <div class="right-toolbar-positioner">
            <div class="right-toolbar-included">
                <div class="custom-container right-toolbar">
                    <p class="toolbar-title">Tools:</p>
                    <hr />
                    <p><a class="btn follow" href="/admin/interface/contents/emails_default/" title="Show all emails"><i class="icon-list-alt icon-2x"></i></a></p>
                    <p><a class="btn" href="#addEmail" data-toggle="modal" no-follow="true" title="Create a new email"><i class="icon-envelope icon-2x"></i></a></p>
                    <p><a class="btn follow" title="Show rejected emails"><i class="icon-trash icon-2x"></i></a></p>
                    <p><a class="btn follow" title="Show editing history"><i class="icon-time icon-2x"></i></a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addEmail" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Add new email</h3>
    </div>
    <div class="modal-body">
        <p>Enter the title of your new email.</p>
        <p><label>Title:</label><input id="add_new_email_title" class="input-xlarge" type="text" /></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php
        if (count($projects) == 1)
        {
        ?>
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="add_email_content" onclick="add_new_content('add_new_email', '<?php echo $projects[0]['id']; ?>'); return false;">Save and edit</button>
        <?php
        }
        ?>
    </div>
</div>