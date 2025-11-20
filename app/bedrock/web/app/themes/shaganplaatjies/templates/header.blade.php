{{-- Header Navigation --}}
<header class="header bg-white shadow-sm" role="banner">
    <nav class="navbar container flex items-center justify-between py-4" role="navigation" aria-label="Main Navigation">
        {{-- Logo/Site Branding --}}
        <div class="navbar-brand flex items-center">
            @if (has_custom_logo())
                <a href="<?php echo esc_url(home_url()); ?>" class="logo logo-image" rel="home">
                    <?php the_custom_logo(); ?>
                </a>
            @else
                <a href="<?php echo esc_url(home_url()); ?>" class="logo logo-text text-2xl font-bold text-primary-600" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
            @endif
        </div>

        {{-- Mobile Menu Button --}}
        <button class="navbar-toggler md:hidden" type="button" data-menu-toggle aria-label="Toggle navigation" aria-expanded="false">
            <span class="sr-only"><?php esc_html_e('Toggle menu', 'shaganplaatjies'); ?></span>
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button>

        {{-- Primary Navigation Menu --}}
        <div class="navbar-menu hidden md:block" data-mobile-menu>
            @if (has_nav_menu('primary_navigation'))
                {!! wp_nav_menu([
                    'theme_location' => 'primary_navigation',
                    'fallback_cb' => false,
                    'echo' => false,
                    'items_wrap' => '<ul class="navbar-nav flex flex-col md:flex-row md:items-center md:gap-8 list-none">%3$s</ul>',
                    'link_before' => '',
                    'link_after' => '',
                ]) !!}
            @endif
        </div>

        {{-- Search Icon (Optional) --}}
        <button class="search-button ml-4 hidden md:inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-secondary-100" type="button" aria-label="Search">
            <span class="sr-only"><?php esc_html_e('Search', 'shaganplaatjies'); ?></span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
    </nav>
</header>

{{-- Styles for hamburger menu --}}
<style>
.hamburger-box {
    display: inline-block;
    position: relative;
    width: 30px;
    height: 24px;
}

.hamburger-inner,
.hamburger-inner::before,
.hamburger-inner::after {
    display: block;
    position: absolute;
    width: 100%;
    height: 2px;
    background: currentColor;
    transition: all 0.3s ease;
}

.hamburger-inner {
    top: 50%;
    transform: translateY(-50%);
}

.hamburger-inner::before {
    content: '';
    top: -8px;
}

.hamburger-inner::after {
    content: '';
    bottom: -8px;
}

.navbar-menu:not(.hidden) ~ .navbar-toggler .hamburger-inner,
.navbar-menu:not(.hidden) ~ .navbar-toggler .hamburger-inner::before,
.navbar-menu:not(.hidden) ~ .navbar-toggler .hamburger-inner::after {
    background: transparent;
}

.navbar-menu:not(.hidden) ~ .navbar-toggler .hamburger-inner::before {
    top: 0;
    transform: rotate(45deg);
}

.navbar-menu:not(.hidden) ~ .navbar-toggler .hamburger-inner::after {
    bottom: 0;
    transform: rotate(-45deg);
}
</style>
