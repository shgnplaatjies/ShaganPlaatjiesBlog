<?php
/**
 * PHPUnit Bootstrap File
 *
 * Configure PHPUnit before running tests
 */

// Define test constants
define('PHPUNIT_TESTSUITE', true);
define('WP_TESTS_DOMAIN', 'shaganplaatjies');

// Set up error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Load WordPress test framework (if available)
// For integration tests with WordPress, you would need to set up the WordPress test suite
// This is a basic setup for unit testing theme functions without WordPress

// Load Composer autoloader
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

// Load theme functions
require_once __DIR__ . '/../functions.php';
