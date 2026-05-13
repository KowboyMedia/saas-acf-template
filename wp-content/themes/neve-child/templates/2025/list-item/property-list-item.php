<!-- template from theme override -->
<?php

$streetaddress = $datamapper->getStreetAddress();

$price = $datamapper->getPrice();
$price = $price ? number_format(floatval(preg_replace('/[^\d\.]/', '', $price)), 0, '.', ' ') . '&nbsp;kr' : '';

$area = $datamapper->getArea();
$area .= $area > 0 ? '&nbsp;kvm' : '';

$rooms = $datamapper->getRooms();
$rooms = $rooms ? $rooms . '&nbsp;rum' : '';

$fee = $datamapper->getFee();
$fee = $fee ? 'Avgift ' . number_format(floatval(preg_replace('/[^\d\.]/', '', $fee)), 0, '.', ' ') . '&nbsp;kr' : '';

$format_range = function ($min, $max, $suffix = '', $prefix = '') {
    $min = is_numeric($min) ? floatval($min) : 0;
    $max = is_numeric($max) ? floatval($max) : 0;
    if ($min <= 0 && $max <= 0) {
        return '';
    }
    $format = function ($value) {
        return ((float) $value !== floor((float) $value))
            ? (string) $value
            : number_format($value, 0, '.', ' ');
    };
    if ($min > 0 && $max > 0) {
        if ($min == $max) {
            return trim($prefix . $format($min) . $suffix);
        }
        return trim($prefix . $format($min) . '-' . $format($max) . $suffix);
    }
    if ($max > 0) {
        return trim($prefix . 'upp till ' . $format($max) . $suffix);
    }
    return trim($prefix . 'från ' . $format($min) . $suffix);
};

$property_type = mb_strtolower($datamapper->getPropertyType(false));
$project_name = $datamapper->getProjectName();
$is_project = $project_name !== '' || in_array($property_type, array('project', 'projekt'), true);
if ($is_project) {
    $rooms = $format_range($datamapper->getRoomsRangeMin(), $datamapper->getRoomsRangeMax(), '&nbsp;rum');
    $area = $format_range($datamapper->getAreaRangeMin(), $datamapper->getAreaRangeMax(), '&nbsp;kvm');
    $fee = $format_range($datamapper->getFeeRangeMin(), $datamapper->getFeeRangeMax(), '&nbsp;kr', 'Avgift ');
    $price_range = $format_range($datamapper->getPriceRangeMin(), $datamapper->getPriceRangeMax(), '&nbsp;kr');
    if ($price_range) {
        $price = $price_range;
    }
}

$images = $datamapper->get_CPT_images_cdn_url();
if (is_array($images) && count($images) > 0) {
    $images = array_slice($images, 0, 3);
} else {
    $images = array();
}

$kowboy_style_options = get_option('kowboy_options_colors');
$use_rounded_images = isset($kowboy_style_options['rounded_images']) && $kowboy_style_options['rounded_images'];
$border_radius_class = $use_rounded_images ? 'rounded-lg' : 'rounded-none';

$is_sold = $datamapper->isSold();
$href = $datamapper->get_CPT_Href();

$basic_info = array_filter(array(
    $datamapper->getTenure(),
    $rooms,
    $area,
    $fee,
));
$badge_label = $datamapper->getStatusString();
?>

<div class="w-full bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="relative">
        <?php if ($badge_label) : ?>
            <span class="sold_label"><?php echo $badge_label; ?></span>
        <?php endif; ?>
        <?php if (is_array($images) && !empty($images)) : ?>
            <div class="swiper overflow-hidden property-list-gallery <?php echo $border_radius_class; ?>">
                <div class="swiper-wrapper">
                    <?php foreach ($images as $image) : ?>
                        <div class="swiper-slide">
                            <div class="w-full">
                                <a href="<?php echo $href; ?>" class="flex">
                                    <img src="<?php echo esc_url($image); ?>" class="w-full object-cover" style="height: 16rem;" alt="Property Image" />
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-pagination"></div>
            </div>
        <?php else: ?>
            <a href="<?php echo $href; ?>" class="flex">
                <img src="<?php echo esc_url(KOWBOY_PLUGIN_DIR_URL . '/templates/2025/assets/images/deafult_agent_image.png'); ?>" class="w-full object-cover" style="height: 16rem;" alt="Property Image" />
            </a>
        <?php endif; ?>
    </div>

    <div class="p-4">
        <div class="flex justify-between items-end mb-2">
            <?php if ($streetaddress) : ?>
                <a class="<?php echo $is_sold ? 'sold_property' : ''; ?> no-underline" href="<?php echo $href; ?>">
                    <h3 class="text-black text-[18px] md:text-[24px] leading-none md:leading-[1.5] font-semibold m-0"><?php echo $streetaddress; ?></h3>
                </a>
            <?php endif; ?>

            <span class="text-[16px] md:!text-[20px] leading-none md:leading-[1.5] text-gray-700 font-medium">
                <?php echo !empty($price) ? $price : ''; ?>
            </span>
        </div>

        <div class="h[1px] bg-gray-300 mb-3"></div>

        <?php if (is_array($basic_info) && !empty($basic_info)) : ?>
            <ul class="p-0 list-none flex flex-wrap justify-between md:!justify-start gap-x-4 text-[14px] md:text-[16px] text-gray-600 mb-2">
                <?php foreach ($basic_info as $info_item) : ?>
                    <li><?php echo $info_item; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
