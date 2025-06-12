<?php
/**
 * Custom Header Template for Plantground Child Theme
 *
 * This file overrides the Storefront header.
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>




<header id="masthead" role="banner" style="<?php storefront_header_styles(); ?>">







			<style>
				/* Define fade-in and fade-out animations */
				@keyframes fadeIn {
					from {
						opacity: 0;
					}

					to {
						opacity: 1;
					}
				}

				@keyframes fadeOut {
					from {
						opacity: 1;
					}

					to {
						opacity: 0;
					}
				}

				/* Styling for the paragraphs */
				.header__bgtop p {
					opacity: 0;
					transition: opacity 2s ease;
				}

				/* Classes to trigger fade-in and fade-out */
				.fade-in {
					animation: fadeIn 3s ease forwards;
					display: block;

				}

				.fade-out {
					animation: fadeOut 3s ease forwards;
					display: block;
				}
			</style>


			<nav>
				<!-- <div class="header__bgclose">test</div> -->



				<div class="header__bgtop">
					<p id="paragraph1" class="header__notice">free shipping on orders over $80</p>
					<p id="paragraph2" class="header__notice">...Tuesday Date Here</p>


				</div>

				<div class="container">

					<div class="header__wrap homepage__animation">

						<div class="header__logo">
							<a href="<?php echo home_url(); ?>"><span>Plant</span>Ground</a>
						</div>
						<div class="header__container-end">

							<div class="header__account">



							</div>

							<div class="header__cart">
								<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/shopping-bag.svg" class="header__img cfw-side-cart-open-trigger" />


								<span class="header__count-rnd">
									<span id="cart-count"></span>


								</span>

							</div>
						</div>

					</div>
				</div>
			</nav>



=
		</header><!-- #masthead -->