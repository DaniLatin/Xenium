<?php

/**
 * @author Danijel
 * @copyright 2013
 */



?>
<h5>Applications</h5>
<ul class="nav nav-list">
    <li class="active"><a href="/admin/interface/applications/" class="follow">Applications summary</a></li>
    <?php if (isset($additional_menu['application']))
    {
        echo $additional_menu['application'];
    } ?>
    <?php if (isset($_SESSION['admin_user'][0]['role']) && ($_SESSION['admin_user'][0]['role'] == 'admin' || $_SESSION['admin_user'][0]['role'] == 'ecommerce')){ ?>
    <li class="nav-header">Data manipulation</li>
    <li><a href="/admin/interface/applications/applications_exports/" class="follow">Exports</a></li>
    <?php } ?>
</ul>