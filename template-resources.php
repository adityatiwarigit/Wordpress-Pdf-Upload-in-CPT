<?php
/*
Template Name: Resources Template
Template Post Type: post, page, resources
*/

get_header();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Resources</title>
</head>
<body <?php body_class(); ?>>

<div class="container mt-5 mb-5">
    <?php
    $resources_posts = new WP_Query(array(
        'post_type' => 'resources',
        'posts_per_page' => -1, // Fetch all resources
    ));

    if ($resources_posts->have_posts()) :
        ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        while ($resources_posts->have_posts()) :
            $resources_posts->the_post();
            $pdf_id = get_post_meta(get_the_ID(), 'resource_pdf_id', true);
            $pdf_url = wp_get_attachment_url($pdf_id);
            ?>
            <div class="col">
                <div class="card h-100">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" class="card-img-top" alt="<?php the_title_attribute(); ?>">
                    <?php else : ?>
                        <img src="https://via.placeholder.com/150" class="card-img-top" alt="Placeholder image">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php the_title(); ?></h5>
                        <?php if ($pdf_url) : ?>
                            <a href="<?php echo esc_url($pdf_url); ?>" class="btn btn-primary" target="_blank">View PDF</a>
                        <?php else : ?>
                            <p>No PDF available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        ?>
        </div>
        <?php
        wp_reset_postdata(); // Reset post data
    else :
        echo '<p>No resources found.</p>';
    endif;
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-pBrbVTTmJYXhLf+yW2X6w6bWfJ5dd4F+SUH9t7h8elWRu4xl5il9TFom3s1x6B5g" crossorigin="anonymous"></script>
</body>
</html>

<?php get_footer(); ?>
