<?php

/**
 * @author Danijel
 * @copyright 2013
 */



?>
<h5>Contents</h5>
<ul class="nav nav-list">
    <li class="nav-header">Static pages and emails</li>
    <li><a href="/admin/interface/contents/" class="follow">First page</a></li>
    <li class="active"><a href="/admin/interface/contents/static_contents_default/" class="follow">Static contents</a></li>
    <li><a href="/admin/interface/contents/emails_default/" class="follow">Emails</a></li>
    <?php if (isset($additional_menu['dynamic_content'])){ ?>
    <li class="nav-header">Dynamic pages</li>
    <?php } ?>
    <?php echo $additional_menu['dynamic_content']; ?>
    <li class="nav-header">Translations</li>
    <li class="active"><a href="/admin/interface/contents/translations_default/" class="follow">Edit translations</a></li>
</ul>