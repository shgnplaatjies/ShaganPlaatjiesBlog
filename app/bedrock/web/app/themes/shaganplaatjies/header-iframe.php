<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>

    <style>
        html, body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            height: 100%;
        }

        #wpadminbar {
            position: fixed !important;
            z-index: 99999;
        }

        #client-app-container {
            margin-top: <?php echo is_admin_bar_showing() ? '32px' : '0'; ?>;
            height: <?php echo is_admin_bar_showing() ? 'calc(100vh - 32px)' : '100vh'; ?>;
        }

        @media screen and (max-width: 782px) {
            #client-app-container {
                margin-top: <?php echo is_admin_bar_showing() ? '46px' : '0'; ?>;
                height: <?php echo is_admin_bar_showing() ? 'calc(100vh - 46px)' : '100vh'; ?>;
            }
        }
    </style>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
