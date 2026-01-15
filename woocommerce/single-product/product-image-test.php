<?php
if (! defined('ABSPATH')) exit;

global $product;

// Get ONLY the images in the Product Gallery
$gallery_image_ids = $product->get_gallery_image_ids();

// Remove featured image if accidentally included
$featured_id = (int) $product->get_image_id();
$gallery_image_ids = array_filter($gallery_image_ids, function ($id) use ($featured_id) {
    return (int) $id !== $featured_id;
});
$gallery_image_ids = array_values($gallery_image_ids);

if (empty($gallery_image_ids)) {
    echo '<p>No gallery images.</p>';
    return;
}
?>

<div class="custom-product-gallery">
    <!-- Output ALL gallery images as full-size, no roles -->
    <?php foreach ($gallery_image_ids as $id):
        $full_url = wp_get_attachment_image_url($id, 'full');
        if (! $full_url) continue;
    ?>
        <div class="gallery-item" data-full-src="<?php echo esc_url($full_url); ?>">
            <?php echo wp_get_attachment_image($id, 'full', false, ['class' => 'gallery-image']); ?>
        </div>
    <?php endforeach; ?>

    <!-- Modal (for later use) -->
    <!-- <div id="custom-gallery-modal" class="gallery-modal">
        <span class="modal-close">&times;</span>
        <img class="modal-image" src="" alt="">
    </div> -->
</div>