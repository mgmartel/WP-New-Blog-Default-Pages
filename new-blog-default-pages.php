<?php
/*
  Plugin Name: New Blog Default Pages
  Plugin URI: http://trenvo.com
  Description: Adds default pages to new blogs in your network
  Version: 0.1
  Author: Mike Martel
  Author URI: http://trenvo.com
 */

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Version number
 *
 * @since 0.1
 */
define('DEFAULT_PAGES_VERSION', '0.1');

/**
 * PATHs and URLs
 *
 * @since 0.1
 */
define('DEFAULT_PAGES_DIR', plugin_dir_path(__FILE__));

if (!class_exists('WP_NewBlogDefaultPages')) :

    class WP_NewBlogDefaultPages    {

        public $pages = array();

        /**
         * Creates an instance of the WP_NewBlogDefaultPages class
         *
         * @return WP_NewBlogDefaultPages object
         * @since 0.1
         * @static
        */
        public static function &init( $blog_id, $user_id ) {
            static $instance;
            $instance = new WP_NewBlogDefaultPages( $blog_id, $user_id );

            return $instance;
        }

        /**
         * Constructor
         *
         * Thanks to http://wordpress.stackexchange.com/questions/71863/wp-multisite-adding-pages-on-blog-creation-by-default
         * @since 0.1
         */
        public function __construct( $blog_id, $user_id ) {
            $default_pages = $this->get_default_pages();

            switch_to_blog( $blog_id );

            if ( $current_pages = get_pages() ) {
                foreach ( wp_list_pluck( $current_pages, 'post_name' )  as $existing_slug ) {
                    if ( key_exists( $existing_slug, $default_pages ) )
                        unset ( $default_pages[$existing_slug] );
                }
                unset ( $current_pages );
            }

            foreach ( $default_pages as $post_slug => $page ) {
                $data = array(
                    'post_name'    => $post_slug,
                    'post_status'  => 'publish',
                    'post_type'    => 'page',
                    'post_author'  => $user_id
                );
                $page = array_merge ( $data, $page );

                wp_insert_post( add_magic_quotes( $page ) );
            }

            restore_current_blog();
        }

        /**
         * PHP4
         *
         * @since 0.1
             */
        public function WP_NewBlogDefaultPages() {
            $this->__construct();
        }

        /**
         * Get default pages added, modded and abused by other plugins by replacing
         * the ini file (filter 'default_pages_ini_replace'), adding ini files
         * to the extra ini files array (filter 'default_pages_ini_append'), or
         * by hooking directly in the config (filter 'default_pages').
         *
         * @return array The default pages
         */
        public function get_default_pages() {
            if ( ! empty ( $this->pages ) ) return $this->pages;

            $ini_file = apply_filters ( 'default_pages_ini_replace', DEFAULT_PAGES_DIR . "pages.ini" );

            $config = ( file_exists( $ini_file ) ) ? parse_ini_file( $ini_file, 1) : array();

            if ( has_filter ( 'default_pages_ini_append' ) ) {
                $extra_ini_files = apply_filters ( 'default_pages_ini_append', array() );
                foreach ( $extra_ini_files as $extra_ini_file ) {
                    if ( file_exists( $extra_ini_file ) )
                        $config = array_merge_recursive ( $config, parse_ini_file( $extra_ini_file, 1) );
                }
            }
            $this->pages = apply_filters( 'default_pages', $config );

            return $this->pages;
        }

    }

    add_action( 'wpmu_new_blog', array('WP_NewBlogDefaultPages', 'init'), 10, 2 );
endif;