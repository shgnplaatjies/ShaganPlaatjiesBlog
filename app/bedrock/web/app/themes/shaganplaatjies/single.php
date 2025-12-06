<?php
/**
 * Template for displaying single posts - Iframe Wrapper
 */

$post_type = get_post_type();
$post_slug = get_post_field('post_name');
$client_app_url = get_theme_mod('client_app_url', 'https://localhost:3000');

$route_prefix = '';
switch ($post_type) {
    case 'post':
        $route_prefix = '/posts';
        break;
    case 'project':
        $route_prefix = '/projects';
        break;
    default:
        $route_prefix = '/' . $post_type . 's';
        break;
}

$iframe_path = $route_prefix . '/' . $post_slug;
$iframe_url = rtrim($client_app_url, '/') . $iframe_path;

get_header('iframe');
?>

<div id="client-app-container" style="width: 100%; height: 100vh; margin: 0; padding: 0;">
    <iframe
        id="client-app-iframe"
        src="<?php echo esc_url($iframe_url); ?>"
        style="width: 100%; height: 100%; border: none; display: block;"
        title="<?php echo esc_attr(get_the_title()); ?>"
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
                currentPath: '<?php echo esc_js($iframe_path); ?>',
                postType: '<?php echo esc_js($post_type); ?>',
                postId: <?php echo get_the_ID(); ?>,
                slug: '<?php echo esc_js($post_slug); ?>'
            }
        }, '<?php echo esc_js($client_app_url); ?>');
    });
})();
</script>

<?php
// Don't include footer
