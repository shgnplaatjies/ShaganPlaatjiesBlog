{{-- Footer --}}
<footer class="footer bg-secondary-900 text-white py-12 mt-20" role="contentinfo">
    <div class="container">
        {{-- Footer Content Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            {{-- Footer Column 1: About --}}
            <div class="footer-column footer-about">
                <h3 class="text-xl font-bold mb-4">
                    <?php bloginfo('name'); ?>
                </h3>
                <p class="text-secondary-300 mb-4">
                    <?php bloginfo('description'); ?>
                </p>

                {{-- Social Media Links --}}
                @php
                    $social_links = get_social_media_links();
                @endphp
                @if (!empty($social_links))
                    <div class="social-links flex gap-4">
                        @foreach ($social_links as $link)
                            <a href="<?php echo esc_url($link['url']); ?>"
                               class="social-link inline-flex items-center justify-center w-10 h-10 rounded-lg bg-secondary-800 hover:bg-primary-600 transition-colors duration-200"
                               rel="noopener noreferrer"
                               title="<?php echo esc_attr($link['title'] ?? ''); ?>">
                                <span class="sr-only"><?php echo esc_html($link['title'] ?? ''); ?></span>
                                <i class="fab fa-<?php echo esc_attr($link['icon']); ?>"></i>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Footer Column 2: Navigation --}}
            <div class="footer-column footer-nav">
                <h3 class="text-xl font-bold mb-4">
                    <?php esc_html_e('Quick Links', 'shaganplaatjies'); ?>
                </h3>
                @if (has_nav_menu('footer_navigation'))
                    {!! wp_nav_menu([
                        'theme_location' => 'footer_navigation',
                        'fallback_cb' => false,
                        'echo' => false,
                        'items_wrap' => '<ul class="list-none space-y-2">%3$s</ul>',
                        'link_before' => '',
                        'link_after' => '',
                        'depth' => 1,
                    ]) !!}
                @else
                    <ul class="list-none space-y-2">
                        <li><a href="<?php echo esc_url(home_url()); ?>" class="text-secondary-300 hover:text-white"><?php esc_html_e('Home', 'shaganplaatjies'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/blog')); ?>" class="text-secondary-300 hover:text-white"><?php esc_html_e('Blog', 'shaganplaatjies'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-secondary-300 hover:text-white"><?php esc_html_e('Contact', 'shaganplaatjies'); ?></a></li>
                    </ul>
                @endif
            </div>

            {{-- Footer Column 3: Contact Info --}}
            <div class="footer-column footer-contact">
                <h3 class="text-xl font-bold mb-4">
                    <?php esc_html_e('Contact', 'shaganplaatjies'); ?>
                </h3>
                <div class="contact-info space-y-2 text-secondary-300">
                    @php
                        $email = get_theme_option('contact_email');
                        $phone = get_theme_option('contact_phone');
                        $address = get_theme_option('contact_address');
                    @endphp

                    @if (!empty($email))
                        <p>
                            <strong><?php esc_html_e('Email:', 'shaganplaatjies'); ?></strong><br>
                            <a href="mailto:<?php echo esc_attr($email); ?>" class="hover:text-white">
                                <?php echo esc_html($email); ?>
                            </a>
                        </p>
                    @endif

                    @if (!empty($phone))
                        <p>
                            <strong><?php esc_html_e('Phone:', 'shaganplaatjies'); ?></strong><br>
                            <a href="tel:<?php echo esc_attr($phone); ?>" class="hover:text-white">
                                <?php echo esc_html($phone); ?>
                            </a>
                        </p>
                    @endif

                    @if (!empty($address))
                        <p>
                            <strong><?php esc_html_e('Address:', 'shaganplaatjies'); ?></strong><br>
                            <?php echo wp_kses_post($address); ?>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="footer-bottom border-t border-secondary-800 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                {{-- Copyright --}}
                <p class="text-secondary-400 text-sm mb-4 md:mb-0">
                    &copy; <?php echo date('Y'); ?> <a href="<?php echo esc_url(home_url()); ?>" class="hover:text-white"><?php bloginfo('name'); ?></a>
                    <?php esc_html_e('All rights reserved.', 'shaganplaatjies'); ?>
                </p>

                {{-- Footer Links --}}
                <div class="footer-meta flex gap-6 text-sm text-secondary-400">
                    <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>" class="hover:text-white">
                        <?php esc_html_e('Privacy Policy', 'shaganplaatjies'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/terms')); ?>" class="hover:text-white">
                        <?php esc_html_e('Terms of Service', 'shaganplaatjies'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
