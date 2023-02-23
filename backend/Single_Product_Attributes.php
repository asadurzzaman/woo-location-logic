<?php
/**
 * Woocommerce Single Product Attributes
 *
 * @package WC
 * @since 1.0.0
 */

if (!defined('WPINC')) {
    die;
}

global $woocommerce;
global $product;

if ( !class_exists( 'WCllSingleProductAttributes' ) ) {
	class WCllSingleProductAttributes {

		public static function get_instance() {

			if ( null === self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		public function __construct() {
			$this->init();
		}

        /*
         * Init Product Panel for WC Setting
         * @since 1.0.0
         * */
		public function init() {

            add_action('woocommerce_product_data_tabs', array( $this, 'wcll_custom_product_meta_tab'));
            add_action('woocommerce_product_data_panels', array( $this, 'wcll_product_panels'));
            add_action('woocommerce_process_product_meta', array( $this, 'wcll_product_custom_fields_save'));
		}

		/*
         * Product Panel for WC_ Tab
         * @since 1.0.0
         * */
        function wcll_custom_product_meta_tab($default_data) {

            $default_data['WC'] = array(
                'label' => __('WC Location', 'location-logic'),
                'target' => 'wcll_product_data',
                'class' => array('wcll-product-tab'),
                'priority' => 21,
            );

            return $default_data;
        }
        
        /*
         * Product Panel for WC Setting
         * @since 1.0.0
         * */
        function wcll_product_panels() {

            global $post; 
            echo '<div id="wcll_product_data" class="panel woocommerce_options_panel hidden">';
            echo '<div class="options_group"><h4 style="padding-left: 12px;font-size: 14px;">' . __('Set This Product Country Based Restrictions','location-logic') . '</h4>';
            $select_country_type = get_post_meta($post->ID, '_wcll_country_restriction_type_role', true);
            woocommerce_wp_select(
                array(
                    'id'        => '_wcll_country_restriction_type_role',
                    'label'     => __('Rule of Restriction', 'location-logic'),
                    'default'   => 'all',
                    'style'     => 'max-width:350px;width:100%;',
                    'class'     => 'availability wc_restricted_type wplcation_select2',
                    'value'     => $select_country_type,
                    'options'   => array(
	                        'all'       => __('Available all countries', 'location-logic'),
	                        'specific'  => __('Available selected countries', 'location-logic'),
	                        'excluded'  => __('Not Available selected countries', 'location-logic'),
                    )
                )
            );

            /**
             * Get countries
             * @since 1.0.0
             */
            $selections = get_post_meta($post->ID, '_wc_restricted_countries', true);

            if (empty($selections) || !is_array($selections)) {
                $selections = array();
            }
            $countries_obj = new WC_Countries();
            $countries = $countries_obj->__get('countries');
            asort($countries);
            ?>
            <p class="form-field form input restricted_countries">
                <label for="_restricted_countries[<?php echo get_the_ID(); ?>]"><?php echo __('Select countries', 'location-logic');
                ?></label>
                <select id="_restricted_countries[<?php echo get_the_ID(); ?>]" multiple="multiple" name="_restricted_countries[<?php echo get_the_ID(); ?>][]"
                        style="max-width: 350px;width:100%;"
                        data-placeholder="<?php esc_attr_e('Choose countries&hellip;', 'location-logic'); ?>"
                        title="<?php esc_attr_e('Country', 'location-logic') ?>"
                        class="wc-enhanced-select">
                    <?php
                    if (!empty($countries)) {
                        foreach ($countries as $key => $val) {
                            echo '<option value="' . esc_attr($key) . '" ' . selected(in_array($key, $selections), true, false) . '>' . $val . '</option>';
                        }
                    }
                    ?>
                </select>
            </p>
            <?php
            if (empty($countries)) {
                echo "<p><b>" . __("You need to setup shipping locations in WooCommerce settings ", 'location-logic') . " <a href='admin.php?page=wc-settings'> " . __("HERE", 'location-logic') . "</a> " . __("before you can choose country restrictions", 'location-logic') . "</b></p>";
            }

            echo '</div>';

        }

        
        /*
         * Save Product Panel for wc Setting
         * @since 1.0.0
         * */
        function wcll_product_custom_fields_save($post_id) { 
           
            $wc_country_restriction_type_role = isset($_POST['_wcll_country_restriction_type_role']) ? $_POST['_wcll_country_restriction_type_role'] : '';
            update_post_meta($post_id, '_wcll_country_restriction_type_role', $wc_country_restriction_type_role);

            $wc_restricted_countries = isset($_POST['_restricted_countries'][$post_id]) ? $_POST['_restricted_countries'][$post_id] : '';
            update_post_meta($post_id, '_wc_restricted_countries', $wc_restricted_countries);

        }

	}

}

new WCllSingleProductAttributes();