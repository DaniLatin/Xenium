<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $packages;

?>

<div class="custom-container title-container">
    <h3>Subscription packages</h3>
    <p>View, add or edit subscription packages.</p>
</div>
<div class="row-fluid">
    <div class="span11">
        <div class="custom-container">
            <h2>Showing all avaliable packages</h2>
            <?php if (is_array($packages) && isset($packages)){ ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="span10">Package title</th>
                        <th class="span2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($packages as $package ){ ?>
                    <tr id="<?php echo $package['id']; ?>">
                        <td><?php echo $package['title']; ?></td>
                        <td>
                            <a href="/admin/interface/applications/edit_subscription_package/<?php echo $package['id']; ?>_<?php echo $package['project_id']; ?>/" class="btn follow"><i class="icon-edit"></i></a>
                            <button class="btn" onclick="hide_content_row('<?php echo $package['id']; ?>', 'trash_subscription_package')"><i class="icon-remove"></i></button>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
            <h5>There are no packages to display. Try to add some.</h5>
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
                    <p><a class="btn follow" href="/admin/interface/applications/subscription_packages/" title="Show all packages"><i class="icon-list-alt icon-2x"></i></a></p>
                    <p><a class="btn" href="#addPackage" data-toggle="modal" no-follow="true" title="Create a new package"><i class="icon-file-alt icon-2x"></i></a></p>
                    <p><a class="btn follow" title="Show trashed packages"><i class="icon-trash icon-2x"></i></a></p>
                    <p><a class="btn follow" title="Show editing history"><i class="icon-time icon-2x"></i></a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addPackage" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Add new subscription package</h3>
    </div>
    <div class="modal-body">
        <p>Enter the title of your new package.</p>
        <p><label>Title:</label><input id="add_new_subscription_package_title" class="input-xlarge" type="text" /></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <?php
        if (count($projects) == 1)
        {
        ?>
        <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" id="add_new_package" onclick="add_new_content('add_new_subscription_package', '<?php echo $projects[0]['id']; ?>'); return false;">Save and edit</button>
        <?php
        }
        ?>
    </div>
</div>