<?php
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
    <?php echo $textblock['dynamic_description']; ?>
</div>