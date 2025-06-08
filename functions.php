<?php
/**
 * Plantground Storefront Child Theme functions and definitions
 */

// Enqueue child theme styles and scripts
function plantground_child_enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');

    // Enqueue compiled CSS from dist/style.css
    wp_enqueue_style(
        'plantground-child-style',
        get_stylesheet_directory_uri() . '/dist/style.css',
        array(), // No dependencies
        $theme_version
    );

    // Enqueue compiled JS from dist/main.js, depends on jQuery, load in footer
    wp_enqueue_script(
        'plantground-child-main-js',
        get_stylesheet_directory_uri() . '/dist/main.js',
        array('jquery'),
        $theme_version,
        true
    );
}
add_action('wp_enqueue_scripts', 'plantground_child_enqueue_assets');


// Remove Storefront header actions on after_setup_theme to ensure hooks exist
add_action('after_setup_theme', function() {
    remove_all_actions('storefront_header');
});



// Custom header
function plantground_custom_header() {
    echo '<!-- Custom header running -->'; // Debug comment to confirm function runs
    ?>
    <header style="background: #f8fafc; padding: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,0.1);">
        <div style="max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
            <!-- Site Title -->
            <div style="font-weight: 700; font-size: 1.25rem;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="text-decoration: none; color: #333;">
                    Plantground
                </a>
            </div>

            <!-- Navigation -->
            <nav>
                <ul style="list-style: none; display: flex; gap: 1rem; margin: 0; padding: 0;">
                    <li><a href="#" style="text-decoration: none; color: #666;">Test Link 1</a></li>
                    <li><a href="#" style="text-decoration: none; color: #666;">Test Link 2</a></li>
                    <li><a href="#" style="text-decoration: none; color: #666;">Test Link 3</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <?php
}
add_action('storefront_header', 'plantground_custom_header', 10);

add_action('init', function() {
    remove_all_actions('storefront_header');
});
