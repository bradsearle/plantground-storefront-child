<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Plantground
 */

?>

    </div><!-- #content -->

    <footer class="footer" role="contentinfo">
      <div class="footer__inner container">
        <div class="footer__brand">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__logo">Plantground</a>
        </div>

        <nav class="footer__nav" aria-label="Footer Navigation">
          <!-- <ul>
            <li><a href="/shop">Shop</a></li>
            <li><a href="/about">About</a></li>
            <li><a href="/contact">Contact</a></li>
            <li><a href="/faq">FAQ</a></li>
          </ul> -->
        </nav>

        <div class="footer__legal">
          <p>&copy; <?php echo date( 'Y' ); ?> Plantground. All rights reserved.</p>
        </div>
      </div><!-- .footer__inner -->
    </footer><!-- .footer -->

  </div><!-- #page -->

  <?php wp_footer(); ?>

</body>
</html>
