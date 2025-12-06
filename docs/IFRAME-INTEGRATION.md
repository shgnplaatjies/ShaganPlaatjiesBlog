# Iframe Client App Integration

Documentation for embedding a client-side React/Next.js application within WordPress using iframe integration.

## Overview

This theme supports a hybrid approach where:
- **WordPress** handles routing, SEO, and content management
- **Client App** (React/Next.js) handles all UI rendering
- Communication happens via `postMessage` API

## Architecture

```
User Request → WordPress (URL routing) → Iframe → Client App (Rendering)
                   ↓
              REST API (Content)
```

### How It Works

1. User visits `https://yoursite.com/projects/my-project`
2. WordPress loads the appropriate template (e.g., `single.php`)
3. Template embeds iframe pointing to client app: `http://localhost:3000/projects/my-project`
4. Client app fetches data from WordPress REST API
5. Client app renders the UI inside iframe
6. Client app communicates navigation back to WordPress

## Configuration

### Set Client App URL

Add to `functions.php` or use WordPress Customizer:

```php
// Default client app URL (development)
set_theme_mod('client_app_url', 'http://localhost:3000');

// Production
set_theme_mod('client_app_url', 'https://yourportfolio.com');
```

Or via WordPress admin:
- **Appearance → Customize → Theme Settings**
- Set "Client App URL"

## Template Structure

### Templates Using Iframe

- `index.php` - Blog listing page
- `page.php` - Individual pages
- `single.php` - Single posts/projects
- (Add more as needed: `archive.php`, `single-project.php`, etc.)

### Templates NOT Using Iframe

- `header.php` - WordPress header (minimal, just sets up iframe)
- `footer.php` - Not loaded (iframe is fullscreen)
- Admin pages - Use standard WordPress

## Client App Requirements

Your React/Next.js app must:

### 1. Match WordPress URL Structure

```
WordPress URL          → Client App Route
/                      → / (home)
/about                 → /about
/projects              → /projects (list)
/projects/my-project   → /projects/[slug]
/blog                  → /blog (post list)
/blog/my-post          → /blog/[slug]
```

### 2. Listen for WordPress Context

```javascript
// In your Next.js app (e.g., _app.jsx or layout)
useEffect(() => {
  function handleMessage(event) {
    // Verify origin
    if (event.origin !== 'https://yourwordpresssite.com') return;

    if (event.data.type === 'wordpress-context') {
      const { homeUrl, restUrl, currentPath, postType, postId, slug } = event.data.data;

      // Store WordPress context
      console.log('WordPress Context:', event.data.data);

      // Fetch data from WordPress REST API
      if (postType === 'project' && postId) {
        fetch(`${restUrl}wp/v2/project/${postId}`)
          .then(res => res.json())
          .then(data => {
            // Render project data
          });
      }
    }
  }

  window.addEventListener('message', handleMessage);
  return () => window.removeEventListener('message', handleMessage);
}, []);
```

### 3. Send Navigation Updates to WordPress

```javascript
// When user navigates within your app
function navigateTo(path, title) {
  // Update your app's route (Next.js router)
  router.push(path);

  // Notify WordPress parent window
  if (window.parent !== window) {
    window.parent.postMessage({
      type: 'navigate',
      path: path,
      title: title || document.title
    }, 'https://yourwordpresssite.com');
  }
}

// Example: Navigate to a project
<Link href="/projects/my-project" onClick={(e) => {
  e.preventDefault();
  navigateTo('/projects/my-project', 'My Project | Your Site');
}}>
  My Project
</Link>
```

## Data Flow

### WordPress → Client App

WordPress sends context on iframe load:

```javascript
{
  type: 'wordpress-context',
  data: {
    homeUrl: 'https://yoursite.com',
    restUrl: 'https://yoursite.com/wp-json/',
    currentPath: '/projects/my-project',
    postType: 'project',
    postId: 123,
    slug: 'my-project'
  }
}
```

### Client App → WordPress

Client app sends navigation updates:

```javascript
{
  type: 'navigate',
  path: '/projects/another-project',
  title: 'Another Project | Your Site'
}
```

## Example: Next.js Integration

### pages/_app.jsx

```javascript
import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';

export default function App({ Component, pageProps }) {
  const router = useRouter();
  const [wpContext, setWpContext] = useState(null);

  useEffect(() => {
    function handleWordPressMessage(event) {
      // Verify origin (update with your WordPress domain)
      const allowedOrigins = [
        'http://localhost',
        'https://staging.yoursite.com',
        'https://yoursite.com'
      ];

      if (!allowedOrigins.some(origin => event.origin.startsWith(origin))) {
        return;
      }

      if (event.data.type === 'wordpress-context') {
        setWpContext(event.data.data);
        console.log('Received WordPress context:', event.data.data);
      }
    }

    window.addEventListener('message', handleWordPressMessage);
    return () => window.removeEventListener('message', handleWordPressMessage);
  }, []);

  // Notify WordPress of route changes
  useEffect(() => {
    if (window.parent === window) return; // Not in iframe

    const handleRouteChange = (url) => {
      window.parent.postMessage({
        type: 'navigate',
        path: url,
        title: document.title
      }, '*'); // In production, specify WordPress domain
    };

    router.events.on('routeChangeComplete', handleRouteChange);
    return () => router.events.off('routeChangeComplete', handleRouteChange);
  }, [router]);

  return <Component {...pageProps} wpContext={wpContext} />;
}
```

### pages/projects/[slug].jsx

```javascript
import { useEffect, useState } from 'react';

export default function ProjectPage({ wpContext }) {
  const [project, setProject] = useState(null);

  useEffect(() => {
    if (!wpContext) return;

    const { restUrl, postId, postType } = wpContext;

    if (postType === 'project' && postId) {
      // Fetch project data from WordPress
      fetch(`${restUrl}wp/v2/project/${postId}`)
        .then(res => res.json())
        .then(data => setProject(data))
        .catch(err => console.error('Failed to fetch project:', err));
    }
  }, [wpContext]);

  if (!project) return <div>Loading...</div>;

  return (
    <div>
      <h1>{project.title.rendered}</h1>
      <div dangerouslySetInnerHTML={{ __html: project.content.rendered }} />

      {/* ACF fields if available */}
      {project.acf && (
        <div>
          <img src={project.acf.featured_image} alt={project.title.rendered} />
          <p>{project.acf.description}</p>
        </div>
      )}
    </div>
  );
}
```

## Security Considerations

### Origin Verification

Always verify message origins:

```javascript
// WordPress side (in template)
const allowedOrigins = [
  'http://localhost:3000',
  'https://yourportfolio.com'
];

if (!allowedOrigins.includes(event.origin)) return;
```

```javascript
// Client app side
const allowedOrigins = [
  'http://localhost',
  'https://staging.yoursite.com',
  'https://yoursite.com'
];

if (!allowedOrigins.some(origin => event.origin.startsWith(origin))) return;
```

### Iframe Sandbox

The templates use sandbox attributes:

```html
sandbox="allow-same-origin allow-scripts allow-forms allow-popups allow-popups-to-escape-sandbox allow-presentation"
```

Adjust as needed for your security requirements.

## URL Structure Requirements

### WordPress Permalink Settings

Set permalinks to **Post name**:
- WordPress Admin → Settings → Permalinks
- Select "Post name"
- Save

### Custom Post Type URLs

Ensure custom post types have `rewrite` enabled:

```php
// In app/setup.php
register_post_type('project', [
    'public' => true,
    'show_in_rest' => true,
    'rewrite' => [
        'slug' => 'projects',
        'with_front' => false
    ],
    // ...
]);
```

This creates URLs like: `/projects/my-project`

### Client App Routes

Match WordPress structure in Next.js:

```
pages/
├── index.jsx              → /
├── about.jsx              → /about
├── projects/
│   ├── index.jsx          → /projects
│   └── [slug].jsx         → /projects/[slug]
└── blog/
    ├── index.jsx          → /blog
    └── [slug].jsx         → /blog/[slug]
```

## Development Workflow

### Local Development

1. **Start WordPress:**
   - Local by Flywheel → Start site
   - WordPress running at `http://yoursite.local`

2. **Start Client App:**
   ```bash
   cd your-nextjs-app
   npm run dev
   # Running at http://localhost:3000
   ```

3. **Configure theme:**
   ```php
   set_theme_mod('client_app_url', 'http://localhost:3000');
   ```

4. **Visit WordPress URL:**
   - `http://yoursite.local/projects/my-project`
   - WordPress loads, iframe embeds `http://localhost:3000/projects/my-project`
   - Client app fetches data from `http://yoursite.local/wp-json/`

### Production Deployment

1. **Deploy Client App:**
   - Build Next.js: `npm run build`
   - Deploy to Vercel/Netlify: `https://yourportfolio.com`

2. **Update WordPress:**
   ```php
   set_theme_mod('client_app_url', 'https://yourportfolio.com');
   ```

3. **Update allowed origins** in both WordPress templates and client app

## SEO Considerations

### WordPress Handles SEO

Since WordPress renders the initial page:
- ✅ Search engines see WordPress URLs
- ✅ Meta tags set by WordPress (Yoast, Rank Math work)
- ✅ Open Graph tags work
- ✅ Social media previews work

### Set Meta Tags

```php
// In WordPress template (before iframe)
<meta property="og:title" content="<?php the_title(); ?>">
<meta property="og:description" content="<?php echo wp_trim_words(get_the_excerpt(), 20); ?>">
<meta property="og:image" content="<?php echo get_the_post_thumbnail_url(); ?>">
```

## Troubleshooting

### Iframe Not Loading

1. Check client app is running
2. Verify `client_app_url` is correct
3. Check browser console for CORS errors
4. Check allowed origins match

### postMessage Not Working

1. Verify origins match exactly
2. Check iframe has loaded (wait for `load` event)
3. Check message format matches examples

### URLs Not Syncing

1. Ensure client app sends `navigate` messages
2. Check WordPress receives messages (browser console)
3. Verify `window.history.pushState` is working

### Styling Issues

1. Admin bar overlap: Check `header.php` styles
2. Iframe sizing: Adjust `height: 100vh` if needed
3. Scrolling: Remove `overflow: hidden` on body if needed

## Advanced: Custom Templates

### Create Custom Project Template

```php
<?php
/**
 * Template Name: Project Iframe
 * Template for: project
 */

$current_path = $_SERVER['REQUEST_URI'];
$client_app_url = get_theme_mod('client_app_url', 'http://localhost:3000');
$iframe_url = rtrim($client_app_url, '/') . $current_path;

get_header();
?>

<div id="client-app-container">
    <iframe
        src="<?php echo esc_url($iframe_url); ?>"
        style="width: 100%; height: 100vh; border: none;"
    ></iframe>
</div>

<script>
// Custom messaging logic here
</script>
```

## Summary

This hybrid approach gives you:
- ✅ Modern React/Next.js development
- ✅ WordPress content management
- ✅ SEO-friendly URLs
- ✅ REST API integration
- ✅ Easy deployment

**Remember:** Keep WordPress and client app URL structures in sync!
