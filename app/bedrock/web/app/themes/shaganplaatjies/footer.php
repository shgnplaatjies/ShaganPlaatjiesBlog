
<footer class="site-footer bg-gray-900 text-white mt-16">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4"><?php bloginfo('name'); ?></h3>
                <p class="text-gray-400"><?php bloginfo('description'); ?></p>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="<?php echo home_url('/'); ?>" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="<?php echo home_url('/about'); ?>" class="hover:text-white transition-colors">About</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-4">Connect</h3>
                <p class="text-gray-400">Stay updated with our latest posts.</p>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
