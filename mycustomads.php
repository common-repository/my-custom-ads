<?php
    /**
    * Plugin Name:  My Custom Ads
    * Plugin URI: http://pixeltoweb.com
    * Description: This Plugin will Display My Adds on Front end in Sidebar by widget or in Content area with the help of short code.
    * Version: 1.0.0
    * Author: Pixeltoweb
    * Author URI: http://www.pixeltoweb.com
    * License: GPL12
*/
?>
<?php
    //This is table names which are used in Plugin
    global $table_customadsdetails;
    global $wpdb;

    $table_customadsdetails = $wpdb->prefix . "customads";
    define( 'MYCUSTOMADS_PLUGIN_PATH', plugins_url().'/mycustomads');
    //This are the common files which are included for Global Use
    include('mycustomads_common.php');
    include('mycustomads_function.php');

    //This are Hooks which are called when plugin is loaded
    add_action('admin_menu', 'customads_menu');   
    add_action('admin_enqueue_scripts', 'customads_adminscripts');
    register_activation_hook( __FILE__, 'customads_install' );
    register_deactivation_hook( __FILE__, 'customads_uninstall' );
    add_shortcode('Customads', 'Customads');
    add_filter('widget_text', 'do_shortcode');

?>