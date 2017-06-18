<?php
/*
Plugin Name: Advanced Custom Fields: NationBuilder Events
Plugin URI: http://www.raulnangolin.com
Description: New field type listing NationBuilder Events for WordPress Advanced Custom Field plugin
Version: 0.1.0
Author: Raul Mangolin
Author URI: http://www.raulnangolin.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


if (!defined('ABSPATH')) exit;

if (!class_exists('acf_plugin_nationbuilder_events')) {
    class acf_plugin_nationbuilder_events
    {
        function __construct()
        {

            $this->settings = array(
                'version' => '0.1.0',
                'url' => plugin_dir_url(__FILE__),
                'path' => plugin_dir_path(__FILE__)
            );

            load_plugin_textdomain('acf-nationbuilder_events', false, plugin_basename(dirname(__FILE__)) . '/lang');

            add_action('acf/include_field_types', array($this, 'include_field_types')); // v5
            add_action('acf/register_fields', array($this, 'include_field_types')); // v4

        }

        function include_field_types($version = false)
        {
            include_once('fields/acf-nationbuilder_events-v4.php');

        }
    }

    new acf_plugin_nationbuilder_events();
}
