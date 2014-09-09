<?php

/**
 * @author Danijel
 * @copyright 2014
 */

global $categories_arranged, $selected_language, $cities;

?>

<table cellpadding="0" cellspacing="0">
    <tr id="mobile-dropdown">
        <td><!-- select city dropdown -->
            <select onchange="location = this.options[this.selectedIndex].value;">
                <?php foreach ($cities as $country => $country_cities){ ?>
                    <?php foreach ($country_cities as $city){ ?>
                        <option value="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . $selected_language . '/' . seolinker(translate("Offers by city")) . '/' . urlencode($country) . '_' . urlencode($city) . '/'; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </td>
        <td><!-- select category dropdown -->
            <select onchange="location = this.options[this.selectedIndex].value;">
                <?php foreach ($categories_arranged as $main_category){ ?>
                    <?php if ($main_category['offer_count'] && count($main_category['sub'])){ ?>
                        <option onmousedown="otf_track_event('Splosno', 'Odpri kategorijo', '<?php echo translate($main_category['title']); ?>');" value="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . $selected_language . '/' . seolinker(translate($main_category['title'])) . '_' . $main_category['id'] . '/'; ?>"><?php echo translate($main_category['title']); ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>
            <a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . $selected_language . '/'; ?>" title="<?php echo translate('Homepage'); ?>" class="home"></a>
        </td>
        <?php foreach ($categories_arranged as $main_category){ ?>
            <?php if ($main_category['offer_count'] && count($main_category['sub'])){ ?>
                <td id="category_<?php echo $main_category['id']; ?>">
                    <a class="category-link" onmousedown="otf_track_event('Splosno', 'Odpri kategorijo', '<?php echo translate($main_category['title']); ?>');" href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . $selected_language . '/' . seolinker(translate($main_category['title'])) . '_' . $main_category['id'] . '/'; ?>" target="_self"><?php echo translate($main_category['title']); ?> <span><?php echo $main_category['offer_count']; ?></span></a>
                    <?php if (count($main_category['sub']) > 1){ ?>
                        <ul>
                            <?php foreach ($main_category['sub'] as $sub_category){ ?>
                                <?php //if ($sub_category['offer_count']){ ?>
                                <li id="category_<?php echo $sub_category['id']; ?>"><a class="category-link" onmousedown="otf_track_event('Splosno', 'Odpri kategorijo', '<?php echo translate($sub_category['title']); ?>');" href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . $selected_language . '/' . seolinker(translate($main_category['title'])) . '_' . $main_category['id'] . '/' . seolinker(translate($sub_category['title'])) . '_' . $sub_category['id'] . '/'; ?>" target="_self"><?php echo translate($sub_category['title']); ?></a></li>
                                <?php //} ?>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </td>
            <?php } ?>
        <?php } ?>
    </tr>
</table>