<?php
// Cart fragments
function plantground_cart_count_fragment($fragments)
{
    $fragments['.header__cart-count'] = '<span class="header__cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'plantground_cart_count_fragment');

function plantground_custom_cart_fragment($fragments)
{
    ob_start();
?>
    <div class="header__cart-count-container">
        <svg class="header__cart-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 2L3 6V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V6L18 2H6ZM6 4H18L19.82 5H4.18L6 4ZM5 20V7H19V20H5ZM12 9C11.4696 9 10.9609 9.21071 10.5858 9.58579C10.2107 9.96086 10 10.4696 10 11V14C10 14.5304 10.2107 15.0391 10.5858 15.4142C10.9609 15.7893 11.4696 16 12 16C12.5304 16 13.0391 15.7893 13.4142 15.4142C13.7893 15.0391 14 14.5304 14 14V11C14 10.4696 13.7893 9.96086 13.4142 9.58579C13.0391 9.21071 12.5304 9 12 9Z" fill="currentColor" />
        </svg>
        <span class="header__cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    </div>
<?php
    $fragments['.header__cart-count-container'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'plantground_custom_cart_fragment');
