<?php
/**
 * Setup Unit Tests
 *
 * Test theme setup and initialization functions
 */

namespace Shaganplaatjies\Tests\Unit;

use PHPUnit\Framework\TestCase;

class SetupTest extends TestCase
{
    /**
     * Test that theme constants are defined
     *
     * @test
     */
    public function it_defines_theme_constants()
    {
        $this->assertTrue(defined('SHAGANPLAATJIES_THEME_PATH'));
        $this->assertTrue(defined('SHAGANPLAATJIES_THEME_URL'));
        $this->assertTrue(defined('SHAGANPLAATJIES_DIST_PATH'));
        $this->assertTrue(defined('SHAGANPLAATJIES_DIST_URL'));
    }

    /**
     * Test theme constant values
     *
     * @test
     */
    public function it_has_correct_constant_values()
    {
        $this->assertStringContainsString('shaganplaatjies', SHAGANPLAATJIES_THEME_PATH);
        $this->assertStringContainsString('shaganplaatjies', SHAGANPLAATJIES_THEME_URL);
        $this->assertStringContainsString('dist', SHAGANPLAATJIES_DIST_PATH);
        $this->assertStringContainsString('dist', SHAGANPLAATJIES_DIST_URL);
    }

    /**
     * Test that helper functions exist
     *
     * @test
     */
    public function it_has_acf_helper_functions()
    {
        $this->assertTrue(function_exists('\\Shaganplaatjies\\is_acf_active'));
        $this->assertTrue(function_exists('\\Shaganplaatjies\\get_field_safe'));
        $this->assertTrue(function_exists('\\Shaganplaatjies\\get_theme_option'));
    }

    /**
     * Test ACF active check
     *
     * @test
     */
    public function it_can_check_acf_status()
    {
        $is_active = \Shaganplaatjies\is_acf_active();
        $this->assertIsBool($is_active);
    }
}
