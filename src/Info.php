<?php
namespace Php_Info;

/**
 *
 */
class Info {
    function __construct() {
        // Hook our plugins_loaded function
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

        // Add plugin options page to main settings
        add_action( 'admin_menu', array( $this, 'add_options_page' ) );

        // Enqueue admin scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

        // Enqueue admin styles
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
    }

    /**
     * Load language files
     * @action plugins_loaded
     */
    public function load_textdomain() {
        // initialize translations
        load_plugin_textdomain( 'better-php-info', false, PHP_INFO_DIR . '/languages' );
    }

    /**
     * Enqueue main plugin scripts in administration.
     *
     * @access public
     */
    public function admin_enqueue_scripts() {
        // Load the plugin javascript
        wp_enqueue_script( 'better-php-info-functions', plugins_url( '../dist/', __FILE__ ) . '/js/main.min.js', array( 'jquery' ) );
    }

    /**
     * Enqueue main plugin styles in administration.
     *
     * @access public
     */
    public function admin_enqueue_styles() {
        // Load the plugin main styles
        wp_enqueue_style( 'better-php-info-styles', plugins_url( '../dist/', __FILE__ ) . '/css/main.min.css' );
    }

    /**
     * Get the title of the submenu item page.
     *
     * @access public
     *
     * @return string $menu_title The title of the submenu item.
     */
    public function get_menu_title() {
        // allow filtering the title of the submenu page
        $menu_title = apply_filters('php_info_menu_item_title', __( 'Better PHP Info', 'better-php-info' ) );

        return $menu_title;
    }

    /**
     * Add plugin settings page.
     *
     * @access public
     */
    public function add_options_page() {
        // Get menu title
        $menu_title = $this->get_menu_title();

        // register the submenu page - child of the Settings parent menu item
        add_submenu_page(
            'options-general.php',
            $menu_title,
            $menu_title,
            'publish_posts',
            'phpinfo',
            array( $this, 'render' )
        );
    }

    /**
     * Callabck function for options page.
     *
     * @access public
     */
    public function render() {
        include_once( PHP_INFO_DIR . '/templates/options.php' );
    }

    /**
     * Display phpinfo information without styles.
     *
     * @access public
     */
    public function display_info() {
        // Get phpinfo content in var
        ob_start();
        phpinfo();
        $content = ob_get_clean();

        // Get only body content without styles
        $content = preg_replace( '%^.*<body>(.*)</body>.*$%ms', '$1', $content );
        $extension_list = shell_exec("php -m");
        $extension_div = '<div style="white-space:pre"><h1>Extension list</h1>'.$extension_list.'</div>';
        $content .= $extension_div;

        try {
            if (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASSWORD')) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);

                /* print server version */
                $mysql_info =  sprintf("Server version: %s\n", mysqli_get_server_info($link));
            } else {
                $mysql_info = "Cannot find credentials";
            }

        } catch (\Exception $e) {
            $mysql_info = $e->getMessage();
        }

        $mysql_info_div = '<div style="white-space:pre"><h1>DB</h1>'.$mysql_info.'</div>';
        $content .= $mysql_info_div;

        // Display content
        echo $content;
    }
}
