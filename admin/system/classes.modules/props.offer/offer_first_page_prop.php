<div class="<?php echo $offer_class; ?>">
	<div class="image">
		<p class="discount">-<?php echo $offer['view_discount']; ?>%</p>
        <p class="persons"><?php echo translate($offer['voucher_persons']); ?></p>
        <div class="title">
			<p>
				<?php if (!$title_trim) echo $offer['dynamic_title']; else echo trim_text($offer['dynamic_title'], $title_trim); ?>
			</p>
		</div>

		<div class="hover">
            <div>
			<a class="button-rounded button-white view-offer" onmousedown="otf_track_event('Splosno', 'Odpre na prvi strani', '<?php echo $offer['dynamic_title']; ?>', '<?php echo number_format($offer['view_original_price'], 2, '.', ''); ?>');" href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/<?php echo $selected_language; ?>/<?php echo seolinker(translate('offer')); ?>_<?php echo $offer['id']; ?>_<?php echo seolinker($offer['dynamic_title']); ?>/">
				<?php echo translate('View this offer'); ?>
			</a>
            <a class="button-rounded button-red print-offer add-to-cart" href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/print_vouchers/" target="_blank" objectid="<?php echo $offer['id']; ?>" objectviewtitle="<?php echo $offer['dynamic_title']; ?>" objectprice="<?php echo number_format($offer['view_original_price'], 2, '.', ''); ?>" objectdiscountprice="<?php echo number_format($offer['view_discount_price'], 2, '.', ''); ?>"  objecturl="/<?php echo $selected_language; ?>/<?php echo seolinker(translate('offer')); ?>_<?php echo $offer['id']; ?>_<?php echo seolinker($offer['dynamic_title']);?>/"><?php echo translate('Use this offer'); ?></a>
			<!--<span class="button add-to-cart red" objectid="<?php echo $offer['id']; ?>" objectviewtitle="<?php echo $offer['dynamic_title']; ?>" objectprice="<?php echo number_format($offer['view_original_price'], 2, '.', ''); ?>" objectdiscountprice="<?php echo number_format($offer['view_discount_price'], 2, '.', ''); ?>"  objecturl="/<?php echo $selected_language; ?>/<?php echo seolinker(translate('offer')); ?>_<?php echo $offer['id']; ?>_<?php echo seolinker($offer['dynamic_title']);?>/"></span>-->
            </div>
        </div>
		
        <?php if ($offer_counter < 4){ ?>
        <img src="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/images/<?php echo $image_size; ?>/<?php echo $offer['view_images'][0]['fileYear']; ?>/<?php echo $offer['view_images'][0]['fileMonth']; ?>/<?php echo $offer['view_images'][0]['fileName']; ?>"/>
        <?php } else { ?>
		<img data-original="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/images/<?php echo $image_size; ?>/<?php echo $offer['view_images'][0]['fileYear']; ?>/<?php echo $offer['view_images'][0]['fileMonth']; ?>/<?php echo $offer['view_images'][0]['fileName']; ?>" src="/projects/avantbon/templates/default/attributes/images/blank.gif"/>
	    <?php } ?>
        
        <?php if (isset($offer['logo_image'])){ ?>
        <div class="logo-container-main">
            <div class="logo-container" align="right">
                <img data-original="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/images/120x120_1/<?php echo $offer['logo_image']['fileYear']; ?>/<?php echo $offer['logo_image']['fileMonth']; ?>/<?php echo $offer['logo_image']['fileName']; ?>" src="/projects/avantbon/templates/default/attributes/images/blank.gif"/>
            </div>
        </div>
        <?php } ?>
    </div>

	<p class="location">
		<?php echo $offer['seller_brandname'] . ', ' . $offer['city_info']['city_name']; ?>
	</p>

	<p class="content">
		<?php echo trim_text($offer['dynamic_intro'], $trim); ?>
	</p>
	
	<p class="price">
		<span class="price-ours"><?php if ($offer['view_discount_price'] != 0.00){ ?><?php echo number_format($offer['view_discount_price'], 2, ',', ' '); ?> <?php echo $currency; ?><?php } ?></span>
		<span class="price-regular"><?php if ($offer['view_original_price'] != 0.00){ ?><?php echo number_format($offer['view_original_price'], 2, ',', ' '); ?> <?php echo $currency; ?><?php } ?></span>
		<a onmousedown="otf_track_event('Splosno', 'Odpre na prvi strani', '<?php echo $offer['dynamic_title']; ?>', '<?php echo number_format($offer['view_original_price'], 2, '.', ''); ?>');" href="<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/<?php echo $selected_language; ?>/<?php echo seolinker(translate('offer')); ?>_<?php echo $offer['id']; ?>_<?php echo seolinker($offer['dynamic_title']); ?>/" class="button-view-offer"></a>
	</p>
</div>