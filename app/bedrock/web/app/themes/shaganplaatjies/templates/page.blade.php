@extends('templates.app')

@section('content')
    {{-- Page Header --}}
    <section class="page-header bg-gradient-to-r from-primary-600 to-primary-700 text-white py-16">
        <div class="container">
            {{-- Breadcrumbs --}}
            @if (function_exists('yoast_breadcrumb'))
                <div class="breadcrumbs text-sm mb-4">
                    {!! yoast_breadcrumb() !!}
                </div>
            @else
                <nav class="breadcrumbs text-sm mb-4" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url()); ?>" class="hover:opacity-80">
                        <?php esc_html_e('Home', 'shaganplaatjies'); ?>
                    </a>
                    <span class="mx-2">/</span>
                    <span><?php the_title(); ?></span>
                </nav>
            @endif

            {{-- Page Title --}}
            <h1 class="page-title text-4xl md:text-5xl font-bold">
                <?php the_title(); ?>
            </h1>

            {{-- Page Meta Info --}}
            @if (!is_front_page())
                <div class="page-meta text-sm text-primary-100 mt-4">
                    <span class="author">
                        <?php esc_html_e('By', 'shaganplaatjies'); ?>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="hover:underline">
                            <?php the_author(); ?>
                        </a>
                    </span>
                    <span class="separator mx-2">•</span>
                    <span class="published-date">
                        <?php esc_html_e('Published', 'shaganplaatjies'); ?>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </span>
                </div>
            @endif
        </div>
    </section>

    {{-- Main Content --}}
    <article class="page-content my-12">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                {{-- Primary Content Area --}}
                <div class="lg:col-span-2">
                    {{-- Featured Image --}}
                    @if (has_post_thumbnail())
                        <figure class="featured-image mb-8">
                            <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>"
                                 alt="<?php echo esc_attr(get_the_title()); ?>"
                                 class="w-full rounded-lg shadow-lg">
                            @if (get_post(get_post_thumbnail_id())->post_excerpt)
                                <figcaption class="text-sm text-secondary-600 mt-4">
                                    <?php echo wp_kses_post(get_post(get_post_thumbnail_id())->post_excerpt); ?>
                                </figcaption>
                            @endif
                        </figure>
                    @endif

                    {{-- Page Content --}}
                    <div class="page-body prose prose-lg max-w-none mb-12">
                        <?php the_content(); ?>
                    </div>

                    {{-- Content Pagination (for multi-page posts) --}}
                    @php wp_link_pages(['before' => '<div class="pagination">', 'after' => '</div>']); @endphp

                    {{-- Post Navigation (for single posts) --}}
                    @if (is_singular('post'))
                        <nav class="post-navigation border-t border-secondary-200 pt-8 mt-12">
                            <div class="flex justify-between items-center">
                                @php $prev_post = get_previous_post(); @endphp
                                @if (!empty($prev_post))
                                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="prev-post text-primary-600 hover:text-primary-700">
                                        <span class="text-sm text-secondary-600">← <?php esc_html_e('Previous', 'shaganplaatjies'); ?></span>
                                        <p class="font-semibold"><?php echo esc_html($prev_post->post_title); ?></p>
                                    </a>
                                @endif

                                @php $next_post = get_next_post(); @endphp
                                @if (!empty($next_post))
                                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="next-post text-primary-600 hover:text-primary-700 text-right">
                                        <span class="text-sm text-secondary-600"><?php esc_html_e('Next', 'shaganplaatjies'); ?> →</span>
                                        <p class="font-semibold"><?php echo esc_html($next_post->post_title); ?></p>
                                    </a>
                                @endif
                            </div>
                        </nav>
                    @endif

                    {{-- Comments Section (for pages with comments enabled) --}}
                    @if (comments_open() || get_comments_number())
                        <div class="comments-section mt-12 pt-8 border-t border-secondary-200">
                            <?php comments_template(); ?>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <aside class="lg:col-span-1">
                    {{-- Related Posts (for single posts) --}}
                    @if (is_singular('post'))
                        <section class="related-posts bg-secondary-50 rounded-lg p-6 mb-8">
                            <h3 class="text-xl font-bold mb-4">
                                <?php esc_html_e('Related Posts', 'shaganplaatjies'); ?>
                            </h3>
                            @php
                                $related = new \WP_Query([
                                    'posts_per_page' => 3,
                                    'post__not_in' => [get_the_ID()],
                                    'orderby' => 'rand',
                                    'tax_query' => [[
                                        'taxonomy' => 'category',
                                        'field' => 'term_id',
                                        'terms' => wp_get_post_categories(get_the_ID()),
                                    ]],
                                ]);
                            @endphp

                            @if ($related->have_posts())
                                <ul class="related-list space-y-4">
                                    @while ($related->have_posts())
                                        @php $related->the_post(); @endphp
                                        <li>
                                            <a href="<?php the_permalink(); ?>" class="text-primary-600 hover:text-primary-700 font-semibold">
                                                <?php the_title(); ?>
                                            </a>
                                        </li>
                                    @endwhile
                                </ul>
                                @php wp_reset_postdata(); @endphp
                            @endif
                        </section>
                    @endif

                    {{-- Widget Area --}}
                    @if (is_active_sidebar('primary-sidebar'))
                        <aside class="widget-area">
                            <?php dynamic_sidebar('primary-sidebar'); ?>
                        </aside>
                    @endif

                    {{-- Call to Action --}}
                    <section class="cta-box bg-primary-600 text-white rounded-lg p-6 text-center">
                        <h3 class="text-xl font-bold mb-3">
                            <?php esc_html_e('Need Help?', 'shaganplaatjies'); ?>
                        </h3>
                        <p class="text-sm mb-4">
                            <?php esc_html_e('Get in touch with us for more information or questions.', 'shaganplaatjies'); ?>
                        </p>
                        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="inline-block bg-white text-primary-600 px-6 py-2 rounded-lg font-semibold hover:bg-primary-50 transition-colors duration-200">
                            <?php esc_html_e('Contact Us', 'shaganplaatjies'); ?>
                        </a>
                    </section>
                </aside>
            </div>
        </div>
    </article>
@endsection
