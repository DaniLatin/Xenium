<?php

/**
 * @author Danijel
 * @copyright 2014
 */

if ($block_size == 1)
{
    $display_block_size = 'three';
}
elseif ($block_size == 2)
{
    $display_block_size = 'six';
}
elseif ($block_size == 3)
{
    $display_block_size = 'nine';
}
elseif ($block_size == 4)
{
    $display_block_size = 'twelve';
}
elseif ($block_size == 'one-third')
{
    $display_block_size = 'four';
}
elseif ($block_size == 'two-thirds')
{
    $display_block_size = 'eight';
}

?>

<div class="<?php echo $display_block_size; ?> columns">
    <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/images/450x238/<?php echo $blog['main_image']['fileYear']; ?>/<?php echo $blog['main_image']['fileMonth']; ?>/<?php echo $blog['main_image']['fileName']; ?>" alt="<?php echo $blog['dynamic_title']; ?>"/>
    <h3><?php echo $blog['dynamic_title']; ?></h3>
    <p><em><?php echo $blog['dynamic_intro']; ?></em></p>
</div>