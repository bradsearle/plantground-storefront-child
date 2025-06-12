<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme tos
 * maintain compatibility. We try to do this as little as possible, but it does ......
 * the readme will list any important changes..
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates /
 * @version 3.4.0
 */







defined('ABSPATH') || exit;



get_header('shop');





?>





<style>
	.test {
		display: block;
	}

	.testing {
		width: 100%;

		background: #ccc;
		position: relative;
		animation-name: example;
		animation-duration: 3s;
		animation-fill-mode: forwards;
	}

	@keyframes example {
		from {
			top: 0px;
		}

		to {
			top: 200px;
			background-color: blue;
		}
	}
</style>

<style>
	.video-grid {
		border-radius: 8px;
		padding: 0px;
		grid-column: span 4;
		width: 100% !important;


	}



	.video-gridmid {
		border-radius: 8px;
		padding: 0px;
		grid-column: span 4;
		width: 100%;
		background-color: #000000;
		height: auto;


	}

	.hpfront__grid {
		border-radius: 8px;
		padding: 0px;
		grid-column: span 12;
		width: 100%;

	}

	.hvideo {
		display: grid;
		grid-template-columns: repeat(12, 1fr);
		padding-inline-start: 0px;
		list-style-type: none;
		gap: 4px;
		margin: 0 0.5rem;

	}


	#mybackgroundvideo {
		width: 100%;
		object-fit: cover;
		border-radius: 8px;



	}

	.lineborder {
		width: 3px;
		background-color: #fff;
		height: 100%;
		margin: auto;
	}

	.switcher23-container {
		width: 100%;
	}

	.switcher23-toggle {
		height: 333px;
		position: absolute;
	}



	.dunno2 {}
</style>


<style>
	.hello-animation {
		height: 370px;
		width: 100%;
		/* background-color: #efefef; */
	}
</style>



<div class="hello-animation">


</div>


<div class="hvideo">

	<div class="hpfront__grid">
		<video id="mybackgroundvideo"
			autoplay loop muted
			poster="https://assets.codepen.io/6093409/river.jpg">
			<source src="https://www.plantground.com/wp-content/uploads/mp4/vid-homepage.mp4"
				type="video/mp4">
		</video>
	</div>
</div>











<div class="test">

	<?php
	if (woocommerce_product_loop()) {

		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 */
		do_action('woocommerce_before_shop_loop');

		woocommerce_product_loop_start();

		if (wc_get_loop_prop('total')) {
			while (have_posts()) {
				the_post();

				/**
				 * Hook: woocommerce_shop_loop.
				 */
				do_action('woocommerce_shop_loop');

				wc_get_template_part('content', 'product');
			}
		}

		woocommerce_product_loop_end();

		/**
		 * Hook: woocommerce_after_shop_loop.
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action('woocommerce_after_shop_loop');
	} else {
		/**
		 * Hook: woocommerce_no_products_found.
		 *
		 * @hooked wc_no_products_found - 10
		 */
		do_action('woocommerce_no_products_found');
	}

	/**
	 * Hook: woocommerce_after_main_content.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action('woocommerce_after_main_content');


	/**
	 * Hook: woocommerce_sidebar.
	 *
	 * @hooked woocommerce_get_sidebar - 10
	 */
	do_action('woocommerce_sidebar');









	get_footer('shop');
	?>
</div>