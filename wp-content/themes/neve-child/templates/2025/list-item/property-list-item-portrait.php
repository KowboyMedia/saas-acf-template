<!-- template from theme -->
<?php

$streetaddress = $datamapper->getStreetAddress();

$price = $datamapper->getPrice();
$price = $price ? number_format(floatval(preg_replace('/[^\d\.]/', '', $price)), 0, '.', ' ') . '&nbsp;kr' : '';
$pricecomment = ucfirst($datamapper->getPriceText());

$area = $datamapper->getArea();
$area .= $area > 0 ? '&nbsp;kvm' : '';

$rooms = $datamapper->getRooms();
$rooms = $rooms ? $rooms . '&nbsp;rum' : '';

$fee = $datamapper->getFee();
$fee = $fee ? 'Avgift ' . number_format(floatval(preg_replace('/[^\d\.]/', '', $fee)), 0, '.', ' ') . '&nbsp;kr' : '';

$images = $datamapper->get_CPT_images_cdn_url();
if (is_array($images) && count($images) > 0) {
    $images = array_slice($images, 0, 1);
} else {
    $images = [];
}

$kowboy_style_options = get_option('kowboy_options_colors');
$use_rounded_images = isset($kowboy_style_options['rounded_images']) && $kowboy_style_options['rounded_images'];
$border_radius_class = $use_rounded_images ? 'rounded-lg' : 'rounded-none';

$is_sold = $datamapper->isSold();
$is_kommande = $datamapper->isKommande();

$href = $datamapper->get_CPT_Href();

$basic_info = array_filter([
    $datamapper->getTenure(),
    $rooms,
    $area,
    $fee,
]);
$badge_label = $datamapper->getStatusString();
?>

<div class="w-full bg-white shadow-lg rounded-lg overflow-hidden kowboy-property-card-portrait">
    <div class="relative kowboy-portrait-media">
        <?php if ($badge_label) : ?>
            <span class="sold_label"><?php echo $badge_label; ?></span>
        <?php endif; ?>
        <?php if (is_array($images) && !empty($images)) : ?>
            <a href="<?php echo $href; ?>" class="flex">
                <img src="<?php echo esc_url($images[0]); ?>" class="w-full object-cover" alt="Property Image" loading="lazy" />
            </a>
        <?php else: ?>
            <a href="<?php echo $href; ?>" class="flex">
                <img src="<?php echo site_url();?>/wp-content/plugins/kowboy-plugin-v2/templates/2025/assets/images/deafult_agent_image.png" class="w-full object-cover" alt="Property Image" loading="lazy" />
            </a>
        <?php endif; ?>
    </div>

    <div class="kowboy-portrait-card-body">
        <?php if ($streetaddress) : ?>
            <a class="<?php echo $is_sold ? 'sold_property' : ''; ?> no-underline" href="<?php echo $href; ?>">
                <h3 class="kowboy-portrait-title"><?php echo $streetaddress; ?></h3>
            </a>
        <?php endif; ?>

        <?php if (!empty($price)) : ?>
            <p class="kowboy-portrait-price"><?php echo $price; ?></p>
        <?php endif; ?>
    </div>
</div>
