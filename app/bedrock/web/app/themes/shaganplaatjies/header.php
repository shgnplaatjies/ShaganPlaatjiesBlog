<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-gray-50'); ?>>
<?php wp_body_open(); ?>

<header class="site-header bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-6">
            <div class="site-branding">
                <h1 class="text-2xl font-bold text-gray-900 m-0">
                    <a href="<?php echo home_url('/'); ?>" class="hover:text-primary-600 transition-colors">
                        <?php bloginfo('name'); ?>
                    </a>
                </h1>
                <?php
                $description = get_bloginfo('description', 'display');
                if ($description) {
                    ?>
                    <p class="text-sm text-gray-600 mt-1"><?php echo esc_html($description); ?></p>
                    <?php
                }
                ?>
            </div>

            <nav class="site-navigation">
                <ul class="flex space-x-6">
                    <li><a href="<?php echo home_url('/'); ?>" class="text-gray-700 hover:text-primary-600 transition-colors">Home</a></li>
                    <li><a href="<?php echo home_url('/about'); ?>" class="text-gray-700 hover:text-primary-600 transition-colors">About</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>
