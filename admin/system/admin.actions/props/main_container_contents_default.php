<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $projects;

?>

<div class="custom-container title-container">
    <h3>First page</h3>
    <p>Edit first pages for projects.</p>
</div>
<div class="row-fluid">
    <div class="span11">
        <div class="custom-container">
            <h2>Showing all projects</h2>
            <?php if (is_array($projects) && isset($projects)){ ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="span11">Title</th>
                        <th class="span1">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project ){ ?>
                    <tr id="<?php echo $project['id']; ?>">
                        <td>First page for <strong><?php echo $project['project_name']; ?></strong></td>
                        <td>
                            <a href="/admin/interface/contents/edit_first_page/<?php echo $project['id']; ?>/" class="btn follow"><i class="icon-edit"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
            <h5>There are no first pages to display. Try to add a project.</h5>
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
                    <!--
                    <p><a class="btn follow" href="/admin/interface/contents/" title="Show all static contents"><i class="icon-list-alt icon-2x"></i></a></p>
                    <p><a class="btn" href="#addStaticContent" data-toggle="modal" no-follow="true" title="Create a new static content"><i class="icon-file-alt icon-2x"></i></a></p>
                    <p><a class="btn follow" title="Show rejected static contents"><i class="icon-trash icon-2x"></i></a></p>
                    -->
                    <p><a class="btn follow" title="Show editing history"><i class="icon-time icon-2x"></i></a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addStaticContent" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Add new static content</h3>
    </div>
    <div class="modal-body">
        <p>Enter the title of your new static content.</p>
        <p><label>Title:</label><input id="add_new_static_content_title" class="input-xlarge" type="text" /></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php
        if (count($projects) == 1)
        {
        ?>
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="add_new_static_content" onclick="add_new_content('add_new_static_content', '<?php echo $projects[0]['id']; ?>'); return false;">Save and edit</button>
        <?php
        }
        ?>
    </div>
</div>