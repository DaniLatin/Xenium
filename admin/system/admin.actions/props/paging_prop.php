<?php

/**
 * @author Danijel
 * @copyright 2013
 */

global $items_count, $page_limit_start, $page_limit, $loading_page, $loading_function, $search_type_load, $search_term_load;

if ($search_type_load && $search_type_load != 'undefined')
{
    $paging_link_add_search = '_' . $search_type_load . '_' . $search_term_load;
}
else
{
    $paging_link_add_search = '';
}

$items_paging = ceil($items_count / 10);

$last_page = $items_paging - 1;
$current_page = ceil($page_limit_start / 10);

$pages_start_limit = $current_page - 4;
$pages_end_limit = $current_page + 4;
if ($items_paging > 1){
?>
<div class="pagination pagination-centered">
    <ul>
        <li <?php if ($current_page == 0) { ?>class="active"<?php } ?>><a class="follow" href="/admin/interface/<?php echo $loading_page; ?>/<?php echo $loading_function; ?>/0_<?php echo $page_limit . $paging_link_add_search; ?>/">&laquo;</a></li>
        <?php if ($pages_start_limit > 0){ ?>
            <li class="disabled"><a href="#">...</a></li>
        <?php } ?>
        <?php for ($i = 0; $i < $items_paging; ++$i){ ?>
        <?php if ($i >= $pages_start_limit && $i <= $pages_end_limit){ ?>
            <li <?php if ($current_page == $i) { ?>class="active"<?php } ?>><a class="follow" href="/admin/interface/<?php echo $loading_page; ?>/<?php echo $loading_function; ?>/<?php echo $i * 10; ?>_<?php echo $page_limit . $paging_link_add_search; ?>/"><?php echo $i + 1; ?></a></li>
        <?php } ?>
        <?php } ?>
        <?php if ($pages_end_limit < $last_page){ ?>
            <li class="disabled"><a href="#">...</a></li>
        <?php } ?>
        <li <?php if ($current_page == $last_page) { ?>class="active"<?php } ?>><a class="follow" href="/admin/interface/<?php echo $loading_page; ?>/<?php echo $loading_function; ?>/<?php echo $last_page * 10; ?>_<?php echo $page_limit . $paging_link_add_search; ?>/">&raquo;</a></li>
    </ul>
</div>
<?php } ?>