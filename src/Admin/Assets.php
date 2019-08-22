<?php
/**
 * Load admin assets
 *
 * @class   Assets
 * @package TrackMage\WordPress\Admin
 * @Author  TrackMage
 */

namespace TrackMage\WordPress\Admin;

use TrackMage\WordPress\Utils;

defined('WPINC') || exit;

/**
 * TrackMage\WordPress\Admin\Assets class.
 *
 * @since 1.0.0
 */
class Assets {
    /**
     * Init the TrackMage\WordPress\Admin\Assets class.
     *
     * @since 1.0.0
     */
    public static function init() {
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueueStyles']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueueScripts']);
    }

    /**
     * Enqueue admin styles.
     *
     * @since 1.0.0
     */
    public static function enqueueStyles() {
        $screen   = get_current_screen();
        $screenId = $screen ? $screen->id : '';
        $suffix   = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Register admin styles.
        wp_register_style('trackmage_admin', TRACKMAGE_URL . 'assets/dist/css/admin/main' . $suffix . '.css', [], TRACKMAGE_VERSION, 'all');

        // Enqueue WooCommerce styles.
        wp_enqueue_style('select2', WC()->plugin_url() . '/assets/css/select2.css', array(), WC_VERSION);
        wp_enqueue_style('woocommerce_admin_styles');
        
        // Enqueue admin styles.
        wp_enqueue_style('trackmage_admin');
    }

    /**
     * Enqueue admin scripts.
     *
     * @since 1.0.0
     */
    public static function enqueueScripts() {
        $screen   = get_current_screen();
        $screenId = $screen ? $screen->id : '';
        $suffix   = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // Register admin scripts.
        wp_register_script('trackmage-admin', TRACKMAGE_URL . 'assets/dist/js/admin/main' . $suffix . '.js', ['jquery', 'jquery-effects-highlight', 'wc-enhanced-select', 'selectWoo'], TRACKMAGE_VERSION, true);
        wp_register_script('trackmage-admin-settings', TRACKMAGE_URL . 'assets/dist/js/admin/settings' . $suffix . '.js', ['jquery', 'jquery-effects-highlight', 'wc-enhanced-select', 'selectWoo'], TRACKMAGE_VERSION, true);
        wp_register_script('trackmage-admin-status-manager', TRACKMAGE_URL . 'assets/dist/js/admin/status-manager' . $suffix . '.js', ['jquery', 'jquery-effects-highlight', 'wc-enhanced-select', 'selectWoo'], TRACKMAGE_VERSION, true);
        wp_register_script('trackmage-admin-meta-boxes', TRACKMAGE_URL . 'assets/dist/js/admin/meta-boxes' . $suffix . '.js', ['jquery', 'jquery-effects-highlight', 'wc-enhanced-select', 'selectWoo'], TRACKMAGE_VERSION, true);

        if (in_array($screenId, Utils::getScreenIds())) {
            // External scripts.
            wp_enqueue_script('selectWoo');

            // Main scripts.
            wp_enqueue_script('trackmage-admin');
            wp_localize_script('trackmage-admin', 'trackmageAdmin', [
                'urls' => [
                    'ajax' => admin_url('admin-ajax.php'),
                ],
                'i18n' => [
                    'noSelect'     => __('— Select —', 'trackmage'),
                    'success'      => __('Success', 'trackmage'),
                    'failure'      => __('Failure', 'trackmage'),
                    'unknownError' => __('Unknown error occured.', 'trackmage'),
                ],
            ]);
        }

        if ($screenId === 'toplevel_page_trackmage-settings') {
            // Settings.
            wp_enqueue_script('trackmage-admin-settings');
            wp_localize_script('trackmage-admin-settings', 'trackmageAdminSettings', [
                'i18n' => [
                    'testCredentials'  => __('Test Credentials', 'trackmage'),
                    'successValidKeys' => __('Valid credentials. Click on <em>“Save Changes”</em> for the changes to take effect.', 'trackmage'),
                ],
            ]);
        }

        if ($screenId === 'trackmage_page_trackmage-status-manager') {
            // Status manager.
            wp_enqueue_script('trackmage-admin-status-manager');
            wp_localize_script('trackmage-admin-status-manager', 'trackmageAdminStatusManager', [
                'aliases' => Utils::get_aliases(),
                'i18n' => [
                    'edit'                => __('Edit', 'trackmage'),
                    'delete'              => __('Delete', 'trackmage'),
                    'name'                => __('Name', 'trackmage'),
                    'slug'                => __('Slug', 'trackmage'),
                    'alias'               => __('Alias', 'trackmage'),
                    'cancel'              => __('Cancel', 'trackmage'),
                    'update'              => __('Update', 'trackmage'),
                    'successUpdateStatus' => __('Status has been updated successfully!', 'trackmage'),
                    'successAddStatus'    => __('Status has been added successfully!', 'trackmage'),
                    'successDeleteStatus' => __('Status has been deleted successfully!', 'trackmage'),
                    'confirmDeleteStatus' => __('Are you sure you want to delete this status?', 'trackmage'),
                ],
            ]);
        }

        if ($screenId === 'shop_order') {
            // Meta-boxes.
            wp_enqueue_script('trackmage-admin-meta-boxes');
            wp_localize_script('trackmage-admin-meta-boxes', 'trackmageAdminMetaBoxes', [
                'nonces' => [
                    'addTrackingNumber' => wp_create_nonce('add-tracking-number'),
                ],
                'i18n' => [
                    'successAddTrackingNumber' => __('Tracking number added successfully.', 'trackmage'),
                ],
            ]);
        }
    }
}
