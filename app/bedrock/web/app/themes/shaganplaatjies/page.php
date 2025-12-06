<?php
/**
 * Template for displaying pages - Iframe Wrapper
 */

$current_path = $_SERVER['REQUEST_URI'];
$client_app_url = get_theme_mod('client_app_url', 'https://localhost:3000');

$iframe_url = rtrim($client_app_url, '/') . $current_path;

get_header('iframe');
?>

<div id="client-app-container" style="width: 100%; height: 100vh; margin: 0; padding: 0;">
    <iframe
        id="client-app-iframe"
        src="<?php echo esc_url($iframe_url); ?>"
        style="width: 100%; height: 100%; border: none; display: block;"
        title="<?php echo esc_attr(wp_title('', false)); ?>"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        sandbox="allow-same-origin allow-scripts allow-forms allow-popups allow-popups-to-escape-sandbox allow-presentation"
    ></iframe>
</div>

<script>
(function() {
    const iframe = document.getElementById('client-app-iframe');

    window.addEventListener('message', function(event) {
        const allowedOrigins = [
            'https://localhost:3000',
            'http://localhost:3000',
            'https://staging.shaganplaatjies.co.za',
            'https://shaganplaatjies.co.za'
        ];

        if (!allowedOrigins.includes(event.origin)) return;

        if (event.data.type === 'navigate') {
            window.history.pushState({}, '', event.data.path);
            if (event.data.title) document.title = event.data.title;
        }
    });

    iframe.addEventListener('load', function() {
        iframe.contentWindow.postMessage({
            type: 'wordpress-context',
            data: {
                homeUrl: '<?php echo esc_js(home_url()); ?>',
                restUrl: '<?php echo esc_js(rest_url()); ?>',
                currentPath: '<?php echo esc_js($current_path); ?>',
                postType: 'page',
                postId: <?php echo get_the_ID(); ?>
            }
        }, '<?php echo esc_js($client_app_url); ?>');
    });
})();
</script>

<?php
// Don't include footer
