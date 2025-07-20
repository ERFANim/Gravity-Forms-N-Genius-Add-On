<?php
/**
 * Plugin Name: Gravity Forms N-Genius Payments Add-on
 * Plugin URI:  https://example.com
 * Description: Integrates the N-Genius payment gateway with Gravity Forms.
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://example.com
 * License:     GPLv2 or later
 * Text Domain: gravityforms-ngenius
 * Domain Path: /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'GF_NGenius_Addon' ) ) {
    /**
     * Main plugin class.
     */
    class GF_NGenius_Addon {

        /** Plugin version. */
        const VERSION = '1.0.0';

        /**
         * The single instance of the class.
         *
         * @var GF_NGenius_Addon|null
         */
        private static $instance = null;

        /**
         * Gets an instance of the class.
         *
         * @return GF_NGenius_Addon
         */
        public static function instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Class constructor.
         */
        private function __construct() {
            $this->define_constants();

            // Register autoloader for classes within the includes directory.
            spl_autoload_register( array( $this, 'autoload' ) );

            // Initialize plugin once all plugins are loaded.
            add_action( 'plugins_loaded', array( $this, 'init' ) );
        }

        /**
         * Defines plugin constants.
         */
        private function define_constants() {
            if ( ! defined( 'GF_NGENIUS_VERSION' ) ) {
                define( 'GF_NGENIUS_VERSION', self::VERSION );
            }

            if ( ! defined( 'GF_NGENIUS_PLUGIN_FILE' ) ) {
                define( 'GF_NGENIUS_PLUGIN_FILE', __FILE__ );
            }

            if ( ! defined( 'GF_NGENIUS_PLUGIN_DIR' ) ) {
                define( 'GF_NGENIUS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            }

            if ( ! defined( 'GF_NGENIUS_PLUGIN_URL' ) ) {
                define( 'GF_NGENIUS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            }
        }

        /**
         * Autoloader for plugin classes located in the includes directory.
         *
         * @param string $class Name of the class being requested.
         */
        public function autoload( $class ) {
            $prefix = 'GF_NGenius_';
            if ( 0 !== strpos( $class, $prefix ) ) {
                return;
            }

            $filename = strtolower( str_replace( '_', '-', substr( $class, strlen( $prefix ) ) ) );
            $file     = trailingslashit( GF_NGENIUS_PLUGIN_DIR ) . 'includes/class-' . $filename . '.php';

            if ( file_exists( $file ) ) {
                require_once $file;
            }
        }

        /**
         * Initializes the plugin after Gravity Forms is loaded.
         */
        public function init() {
            if ( ! class_exists( 'GFForms' ) ) {
                // Gravity Forms is required.
                add_action( 'admin_notices', array( $this, 'missing_gravityforms_notice' ) );
                return;
            }

            // Load additional functionality here.
        }

        /**
         * Displays an admin notice when Gravity Forms is not active.
         */
        public function missing_gravityforms_notice() {
            echo '<div class="error"><p>' . esc_html__( 'Gravity Forms N-Genius Payments Add-on requires Gravity Forms to be installed and active.', 'gravityforms-ngenius' ) . '</p></div>';
        }

        /**
         * Runs on plugin activation.
         */
        public static function activate() {
            // Place activation logic here if needed.
        }

        /**
         * Runs on plugin deactivation.
         */
        public static function deactivate() {
            // Place deactivation logic here if needed.
        }
    }
}

// Register activation/deactivation hooks.
register_activation_hook( __FILE__, array( 'GF_NGenius_Addon', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'GF_NGenius_Addon', 'deactivate' ) );

// Initialize the plugin.
GF_NGenius_Addon::instance();
