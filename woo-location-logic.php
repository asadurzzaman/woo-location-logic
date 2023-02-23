<?php
/**
 * Plugin Name:       WC Location Logic (Light)
 * Plugin URI:        https://wplocationlogic.com
 * Description:       WC Location Logic is a woocommerce plugin allowing you to restrict product coupons and prices based on the user's location. Determines products and variations by country, state, city, zip code, and IP address.
 * Version:           1.0.0
 * Author:            Asad
 * Author URI:        https://github.com/asadurzzaman/woo-location-logic
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       location-logic
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
function WCll_plugin_dependency_check()
{
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        add_action('admin_notices', 'WCll_plugin_woocommere_install_warning');
        deactivate_plugins(plugin_basename(__FILE__));
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    }
}

function WCll_plugin_woocommere_install_warning()
{
?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Befour Install WC Location Logic. WooCommerce must be install and active First.', 'location-logic'); ?></p> 
    </div>
<?php
}
add_action('admin_init', 'WCll_plugin_dependency_check');

define( 'WC_LOCATION_LOGIC_VERSION', '1.0.0' );
defined( 'WC_LOCATION_LOGIC_PATH' ) or define( 'WC_LOCATION_LOGIC_PATH', plugin_dir_path( __FILE__ ) );
defined( 'WC_LOCATION_LOGIC_URL' ) or define( 'WC_LOCATION_LOGIC_URL', plugin_dir_url( __FILE__ ) );
defined( 'WC_LOCATION_LOGIC_BASE_FILE' ) or define( 'WC_LOCATION_LOGIC_BASE_FILE', __FILE__ );
defined( 'WC_LOCATION_LOGIC_BASE_PATH' ) or define( 'WC_LOCATION_LOGIC_BASE_PATH', plugin_basename(__FILE__) );
defined( 'WC_LOCATION_LOGIC_IMG_DIR' ) or define( 'WC_LOCATION_LOGIC_IMG_DIR', plugin_dir_url( __FILE__ ) . 'assets/img/' );
defined( 'WC_LOCATION_LOGIC_CSS_DIR' ) or define( 'WC_LOCATION_LOGIC_CSS_DIR', plugin_dir_url( __FILE__ ) . 'assets/css/' );
defined( 'WC_LOCATION_LOGIC_JS_DIR' ) or define( 'WC_LOCATION_LOGIC_JS_DIR', plugin_dir_url( __FILE__ ) . 'assets/js/' );

require_once WC_LOCATION_LOGIC_PATH . 'includes/WC_Location_Logic.php';
require_once WC_LOCATION_LOGIC_PATH . 'backend/Products_Restriction_Setting.php';
require_once WC_LOCATION_LOGIC_PATH . 'backend/Single_Product_Attributes.php';
require_once WC_LOCATION_LOGIC_PATH . 'backend/Single_Product_Variation_Attributes.php';

function enqueue_select2_jquery() {
    wp_register_style( 'select2css', plugin_dir_url( __FILE__ ) . 'assets/css/select2.min.css', false, '1.0', 'all' );
    wp_register_script( 'select2', plugin_dir_url( __FILE__ ) . 'assets/js/select2.min.js', array( 'jquery' ), '1.0', true );
    wp_register_script( 'all-dashboard', plugin_dir_url( __FILE__ ) . 'assets/js/all-dashboard.js', array( 'jquery','select2' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
    wp_enqueue_script( 'all-dashboard' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_select2_jquery' );


