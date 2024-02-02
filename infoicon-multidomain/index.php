<?php

/*
Plugin Name: infoicon multi domain theme
Description: This plugin add new doamin with theme color and logo.
Version: 1.0.0
Author: Infoicon
*/
define('INFO_VERSION', '1.0.0');
define('INFO_VERSION__MINIMUM_WP_VERSION', '5.5');
define('INFO_VERSION_PLUGIN_NAME', 'infoicon user list');
define('INFO_VERSION__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('INFO_VERSION_BASE_URL', plugin_dir_url(__FILE__));

// Hook to register the activation and deactivation functions
register_activation_hook(__FILE__, 'info_icon_activate');
register_deactivation_hook(__FILE__, 'info_icon_deactivate');

require_once(INFO_VERSION__PLUGIN_DIR . 'function.php');


function info_icon_activate()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'multidomains_theme';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        domain_name varchar(255) NOT NULL,
        theme_name varchar(255) NOT NULL,
        theme_background varchar(255) NOT NULL,
        theme_color varchar(255) NOT NULL,
        status varchar(255) DEFAULT '0',
        theme_logo varchar(255) DEFAULT NULL,
        febicon varchar(255) DEFAULT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function info_icon_deactivate()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'multidomains_theme';
    $sql = "DROP TABLE $table_name";
    //$wpdb->query($sql);
}

// Rest of your code...

// Add a menu to the admin dashboard
add_action('admin_menu', 'info_icon_admin_menu');

function info_icon_admin_menu()
{
    // Add a top-level menu page
    add_menu_page(
        'Domains List',    // Page title
        'Domains List',    // Menu title
        'manage_options',        // Capability required to access this menu
        'info_icon_domains_list',   // Menu slug
        'info_icon_domains_list_page' // Callback function to display the page content
    );
}

function delete($id)
{
    global $wpdb;
    $table = $wpdb->prefix . 'multidomains_theme';
    $wpdb->delete($table, array('id' => $id));
    wp_redirect(admin_url('admin.php?page=info_icon_domains_list'));
}



function info_icon_domains_list_page()
{
    $action = $_GET['action'] ? $_GET['action'] : '';
    if ($action === 'add_domain') {
        include('add-domains.php');
    } else if ($action === 'edit') {
        include('add-domains.php');
    }else if ($action === 'delete') {
        $id = $_GET['listid'] ? $_GET['listid'] : '';
        delete($id);
    } else {
        include('domain_list.php');
    }
}
