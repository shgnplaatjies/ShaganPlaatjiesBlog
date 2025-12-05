<?php
/**
 * Theme Index Template
 */

get_header();
?>

<div class="container mx-auto px-4 py-8">
    <main id="main" class="max-w-4xl mx-auto">
        <?php
        if (have_posts()) {
            ?>
            <div class="space-y-8">
                <?php
                while (have_posts()) {
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md p-6'); ?>>
                        <header class="entry-header mb-4">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                                <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <div class="text-sm text-gray-600">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                                <span class="mx-2">•</span>
                                <span>By <?php the_author(); ?></span>
                            </div>
                        </header>

                        <div class="entry-content prose prose-lg max-w-none">
                            <?php the_excerpt(); ?>
                        </div>

                        <footer class="entry-footer mt-4">
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold">
                                Read More
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </footer>
                    </article>
                    <?php
                }
                ?>
            </div>
            <?php
            // Pagination
            the_posts_pagination([
                'mid_size' => 2,
                'prev_text' => '← Previous',
                'next_text' => 'Next →',
            ]);
        } else {
            ?>
            <div class="bg-gray-100 rounded-lg p-8 text-center">
                <p class="text-gray-600 text-lg">No posts found.</p>
            </div>
            <?php
        }
        ?>
    </main>
</div>

<?php
get_footer();
