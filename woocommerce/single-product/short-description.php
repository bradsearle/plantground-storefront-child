<?php

/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

?>

<?php

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

global $post;

$short_description = apply_filters('woocommerce_short_description', $post->post_excerpt);

if (!$short_description) {
	return;
}

?>


<div class=" woocommerce-product-details__short-description">
	<?php echo $short_description; // WPCS: XSS ok. 
	?>
</div>

<div class=""><?php the_field('product_shipping'); ?></div>

<ul class="single-product-list">
	<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/pet_safe.svg" />
		<h5>
			Pet
			<span><?php the_field('pet_safe'); ?></span>
		</h5>
	</li>
	<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/soil_preference.svg" />
		<h5>
			Soil Preference
			<span><?php the_field('soil_preference'); ?></span>
		</h5>
	</li>
	<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hardiness_zone.svg" />
		<h5>
			Hardiness Zone
			<span><?php the_field('hardiness_zone'); ?></span>
		</h5>
	</li>
</ul>
<?php
wc_get_template('global-product-info.php');
?>

</div>









<div>
	<a href="#" class="umm">*</a>
</div>