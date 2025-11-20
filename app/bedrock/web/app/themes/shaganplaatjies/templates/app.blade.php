<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div class="site" id="app">
        {{-- Skip to main content link for accessibility --}}
        <a href="#main-content" class="sr-only focus:not-sr-only" data-skip-link>
            <?php esc_html_e('Skip to main content', 'shaganplaatjies'); ?>
        </a>

        {{-- Header --}}
        @include('templates.header')

        {{-- Main content area --}}
        <main id="main-content" class="main-content">
            @yield('content')
        </main>

        {{-- Footer --}}
        @include('templates.footer')
    </div>

    <?php wp_footer(); ?>
</body>

</html>
