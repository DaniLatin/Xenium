<?php

/**
 * @author Danijel
 * @copyright 2014
 */

global $selected_language, $cities, $project_slug, $project_template;

?>

<ul id="location-list">
    <div id="close"></div>
    <?php foreach ($cities as $country => $country_cities){ ?>
        <?php if (count($cities) > 1){ ?>
        <li>
            <a href="#" onclick="return false;"><img src="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/projects/' . $project_slug . '/templates/' . $project_template . '/'; ?>attributes/images/flags/<?php echo $country; ?>.png"/><?php echo translate($country); ?></a>
            <ul>
        <?php } ?>
            <?php foreach ($country_cities as $city){ ?>
                <li><a href="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/' . $selected_language . '/' . seolinker(translate("Offers by city")) . '/' . urlencode($country) . '_' . urlencode($city) . '/'; ?>"><?php echo translate($city); ?>,</a></li>
            <?php } ?>
        <?php if (count($cities) > 1){ ?>
            </ul>
        </li>
        <?php } ?>
    <?php } ?>
</ul>