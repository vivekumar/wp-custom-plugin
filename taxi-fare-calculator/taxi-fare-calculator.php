<?php
/*
Plugin Name: infoicon Taxi Fare Calculator
Description: This plugin Calculate taxi fare of two distance and save data in database.
Version: 1.1.0
Author: Infoicon technologies
*/
class MyPlugin
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'fare_taxi_data';

        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        // Enqueue the CSS file
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));

        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_shortcode('FareTaxi-Calculator', array($this, 'fare_taxi_calculator_shortcode'));
    }
    public function enqueue_styles()
    {
        wp_enqueue_style('FareTaxi-styles', plugin_dir_url(__FILE__) . 'FareTaxi-style.css', array(), '1.0.0', 'all');
    }
    public function activate()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $this->table_name (
            `id` int NOT NULL AUTO_INCREMENT,
          `name` varchar(255) DEFAULT NULL,
          `email` varchar(255) DEFAULT NULL,
          `tel` varchar(255) DEFAULT NULL,
          `starting_time` datetime DEFAULT NULL,
          `starting_adres` varchar(255) DEFAULT NULL,
          `dropp_adres` varchar(255) DEFAULT NULL,
          `garage_address` varchar(255) DEFAULT NULL,
          `dropp_time` datetime DEFAULT NULL,
          `total_time` varchar(255) DEFAULT NULL,
          `total_cost` varchar(255) DEFAULT NULL,
          `date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public function deactivate()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS $this->table_name");
    }

    public function add_admin_menu()
    {
        add_menu_page('Taxi Fare', 'Taxi Fare list', 'manage_options', 'taxi-fare', array($this, 'list_entries'));
        add_submenu_page('taxi-fare', 'Plugin Settings', 'Settings', 'manage_options', 'taxi-fare-settings', array($this, 'plugin_settings'));
        add_submenu_page('taxi-fare', 'Calculator', 'Calculator', 'manage_options', 'taxi-fare-form', array($this, 'plugin_form'));
    }

    public function list_entries()
    {
        require_once plugin_dir_path(__FILE__) . 'fare_taxi_list.php';

        $custom_list_table = new Custom_List_Table($this->table_name);
        $custom_list_table->prepare_items();
?>
        <div class="wrap">
            <h2>List <a class="btn button btn-primery align-right" style="float: right;" href="<?php echo admin_url('admin.php?page=info_icon_domains_list&action=add_domain') ?>">Settings</a></h2>
            <form method="post">
                <?php $custom_list_table->display(); ?>
            </form>
        </div>
    <?php
    }

    public function plugin_settings()
    {
        // Code for plugin settings
        // Handle form submission
        if (isset($_POST['save_settings'])) {
            // Validate and save the form data
            $this->save_settings();
        }

        // Display the settings form
        $this->render_settings_form();
    }

    public function plugin_form()
    {
        echo do_shortcode('[FareTaxi-Calculator]');
    }

    // other plugin support functions settings and form
    function fare_taxi_calculator_shortcode($atts, $content = "")
    {
        ob_start(); ?>
        <style>
            .row {
                --bs-gutter-x: 1.5rem;
                --bs-gutter-y: 0;
                display: flex;
                flex-wrap: wrap;
                margin-top: calc(-1 * var(--bs-gutter-y));
                margin-right: calc(-.5 * var(--bs-gutter-x));
                margin-left: calc(-.5 * var(--bs-gutter-x));
            }

            .row>* {
                flex-shrink: 0;
                width: 100%;
                max-width: 100%;
                padding-right: calc(var(--bs-gutter-x) * .5);
                padding-left: calc(var(--bs-gutter-x) * .5);
                margin-top: var(--bs-gutter-y);
            }

            .col-md-4 {
                flex: 0 0 auto;
                width: 33.33333333%;
            }

            .col-md-8 {
                flex: 0 0 auto;
                width: 66.66666667%;
            }

            .col-md-6 {
                flex: 0 0 auto;
                width: 50%;
            }

            label {
                display: inline-block;
            }

            input.text,
            input.title,
            input[type=email],
            input[type=password],
            input[type=tel],
            input[type=text],
            input[type=time],
            input[type=date],
            select,
            textarea {
                background-color: #fff;
                border: 1px solid #bbb;
                padding: 2px;
                color: #4e4e4e;
            }

            .form-control {
                display: block;
                width: 100%;
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: var(--bs-body-color);
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                background-color: var(--bs-body-bg);
                background-clip: padding-box;
                border: var(--bs-border-width) solid var(--bs-border-color);
                border-radius: var(--bs-border-radius);
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

            .btn {
                --bs-btn-padding-x: 0.75rem;
                --bs-btn-padding-y: 0.375rem;
                --bs-btn-font-family: ;
                --bs-btn-font-size: 1rem;
                --bs-btn-font-weight: 400;
                --bs-btn-line-height: 1.5;
                --bs-btn-color: var(--bs-body-color);
                --bs-btn-bg: transparent;
                --bs-btn-border-width: var(--bs-border-width);
                --bs-btn-border-color: transparent;
                --bs-btn-border-radius: var(--bs-border-radius);
                --bs-btn-hover-border-color: transparent;
                --bs-btn-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 1px rgba(0, 0, 0, 0.075);
                --bs-btn-disabled-opacity: 0.65;
                --bs-btn-focus-box-shadow: 0 0 0 0.25rem rgba(var(--bs-btn-focus-shadow-rgb), .5);
                display: inline-block;
                padding: var(--bs-btn-padding-y) var(--bs-btn-padding-x);
                font-family: var(--bs-btn-font-family);
                font-size: var(--bs-btn-font-size);
                font-weight: var(--bs-btn-font-weight);
                line-height: var(--bs-btn-line-height);
                color: var(--bs-btn-color);
                text-align: center;
                text-decoration: none;
                vertical-align: middle;
                cursor: pointer;
                -webkit-user-select: none;
                -moz-user-select: none;
                user-select: none;
                border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
                border-radius: var(--bs-btn-border-radius);
                background-color: var(--bs-btn-bg);
                transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

            .btn-primary {
                --bs-btn-color: #fff;
                --bs-btn-bg: #1e4278;
                --bs-btn-border-color: #1e4278;
                --bs-btn-hover-color: #fff;
                --bs-btn-hover-bg: #0b5ed7;
                --bs-btn-hover-border-color: #236299;
                --bs-btn-focus-shadow-rgb: 49, 132, 253;
                --bs-btn-active-color: #fff;
                --bs-btn-active-bg: #236299;
                --bs-btn-active-border-color: #236299;
                --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
                --bs-btn-disabled-color: #fff;
                --bs-btn-disabled-bg: #1e4278;
                --bs-btn-disabled-border-color: #1e4278;
            }

            .red {
                color: red;
            }
        </style>
        <div style="padding: 10px;">
            <form action="" method="post">
                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="name">Full Name:</label>
                            <input type="text" name="full_name" value="<?php if ($_POST) {
                                                                            echo $_POST['full_name'];
                                                                        } ?>" class="form-control datatimepicker" id="name" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="femail" value="<?php if ($_POST) {
                                                                            echo $_POST['femail'];
                                                                        } ?>" class="form-control datatimepicker" id="email" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="tel">Tel:</label>
                            <input type="tel" name="ftel" value="<?php if ($_POST) {
                                                                        echo $_POST['ftel'];
                                                                    } ?>" class="form-control datatimepicker" id="tel" required>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start">Starting time:</label>
                            <input type="time" name="starting_time" value="<?php if ($_POST) {
                                                                                echo $_POST['starting_time'];
                                                                            } ?>" class="form-control datatimepicker" id="start" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="saddress">Starting adres:</label>
                            <input type="text" name="starting_adres" value="<?php if ($_POST) {
                                                                                echo $_POST['starting_adres'];
                                                                            } ?>" class="form-control" id="starting_adres" onchange="initialize()" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="saddress">Final Dropp off adres:</label>
                            <input type="text" name="dropp_adres" value="<?php if ($_POST) {
                                                                                echo $_POST['dropp_adres'];
                                                                            } ?>" class="form-control" id="dropp_adres" onchange="initialize()" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start">Final Drop off time:</label>
                            <input type="time" name="dropp_time" value="<?php if ($_POST) {
                                                                            echo $_POST['dropp_time'];
                                                                        } ?>" class="form-control datatimepicker" id="start" required>
                        </div>
                    </div>

                </div>
                <br>
                <button type="submit" name="taxi_calculator" value="submit" class="btn btn-primary ">Submit</button>
            </form>
            <br>
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5do7bjm6wBjq7g5sygsKGBytsdKcawQc&libraries=places"></script>
            <script>
                function initialize() {
                    var input1 = document.getElementById('starting_adres');
                    var autocomplete = new google.maps.places.Autocomplete(input1);

                    var input2 = document.getElementById('dropp_adres');
                    var autocomplete = new google.maps.places.Autocomplete(input2);

                }
                google.maps.event.addDomListener(window, 'load', initialize);
            </script>

            <?php

            if ($_POST['taxi_calculator']) {
                $costper_hour = esc_attr(get_option('cost_per_hour'));
                $garage_address = esc_attr(get_option('garage_address'));
                if (!$costper_hour) {
                    echo "<p class='red'>Cost per hour is required<p>";
                    die;
                }
                if (!$garage_address) {
                    echo "<p class='red'>Garage Address is required<p>";
                    die;
                }
                $gaddress = urlencode($garage_address);
                $origin = urlencode($_POST['starting_adres']); // Replace 'Origin Address' with your starting address
                $destination = urlencode($_POST['dropp_adres']); // Replace 'Destination Address' with your destination address

                $starting_time = $_POST['starting_time'];
                $dropp_time = $_POST['dropp_time'];

                $start = strtotime($starting_time);
                $end = strtotime($dropp_time);
                $customer_driving_mins = ($end - $start) / 60;
                $costper_hour_inminuts = $costper_hour / 60;
                // Create the API request URL
                $garage_to_starting = $this->google_metrix_api($gaddress, $origin);
                $dropp_to_garage = $this->google_metrix_api($destination, $gaddress);

                if ($garage_to_starting && $dropp_to_garage) {
                    $garatostart = round($garage_to_starting['value'] / 60);
                    $droptogarage = round($dropp_to_garage['value'] / 60);
                    $minuts_total = $garatostart + $droptogarage + $customer_driving_mins;
                    $total_coast = number_format((float)($minuts_total * $costper_hour_inminuts), 2, '.', '');

                    echo 'Total cost = ' . $total_coast . ' euro';

                    // save this info to database
                    global $wpdb;
                    $table_name = $wpdb->prefix . "fare_taxi_data";
                    $data = array(
                        'name' => $this->test_input($_POST['full_name']),
                        'email' => $this->test_input($_POST['femail']),
                        'tel' => $this->test_input($_POST['ftel']),
                        'starting_time'    => date('Y-m-d H:i:s', $start),
                        'starting_adres' => $this->test_input($_POST['starting_adres']),
                        'dropp_adres' => $_POST['dropp_adres'],
                        'garage_address' => $garage_address,
                        'dropp_time' => date('Y-m-d H:i:s', $end),
                        'total_time' => $minuts_total,
                        'total_cost' => $total_coast,
                        'date'    => date('Y-m-d H:i:s'),
                    );
                    $wpdb->insert($table_name, $data);
                }
            } ?>
        </div>
    <?php return ob_get_clean();
    }

    function google_metrix_api($origin, $destination)
    {
        // Replace 'YOUR_API_KEY' with your actual Google Maps API key
        $conf_apikey = esc_attr(get_option('google_api_key'));
        $apiKey =  $conf_apikey ? $conf_apikey : 'AIzaSyA5do7bjm6wBjq7g5sygsKGBytsdKcawQc';
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$destination&key=$apiKey";

        // Initialize cURL session
        $curl = curl_init();
        // Set cURL options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Execute cURL session and store the JSON response
        $response = curl_exec($curl);
        // Close cURL session
        curl_close($curl);
        // Decode JSON response
        $data = json_decode($response, true);
        //print_r($data['routes']);

        // Extracting the duration in traffic

        // Close cURL session
        curl_close($curl);
        // Decode JSON response
        $data = json_decode($response, true);
        //print_r($data['routes']);

        // Extracting the duration in traffic
        if ($data['status'] == 'OK') {
            $duration_in_traffic = isset($data['routes'][0]['legs'][0]['duration_in_traffic']) ? $data['routes'][0]['legs'][0]['duration_in_traffic'] : '';
            $duraton = $data['routes'][0]['legs'][0]['duration'];
            return $duration_in_traffic ? $duration_in_traffic : $duraton;
        } else {
            return false;
        }
    }
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    // end calculator functions
    // setting support function 
    private function render_settings_form()
    {
    ?>
        <div class="wrap">
            <h2>Plugin Settings</h2>
            <form method="post" action="">
                <!-- Add your form fields here -->
                <div class="row">
                    <div class="col-md-6">
                        <label for="setting1">Garage Address :</label>
                        <input type="text" name="garage_address" class="form-control" value="<?php echo esc_attr(get_option('garage_address')); ?>" />
                        <br> <br>
                    </div>
                    <div class="col-md-6">
                        <label for="setting2">Cost Per Hour(euro) :</label>
                        <input type="number" name="cost_per_hour" class="form-control" value="<?php echo esc_attr(get_option('cost_per_hour')); ?>" />
                    </div>
                    <div class="col-md-6">
                        <label for="setting2">Google api key :</label>
                        <input type="text" name="google_api_key" class="form-control" value="<?php echo esc_attr(get_option('google_api_key')); ?>" />
                    </div>
                </div>
                <br> <br>
                <div class="col-md-6">
                    <label for="setting2">Shortcode:</label>
                    <input type="text" value="[FareTaxi-Calculator]" readonly delete>
                </div>


                <!-- Add more fields as needed -->

                <?php submit_button('Save Settings', 'primary', 'save_settings'); ?>
            </form>
        </div>
<?php
    }

    private function save_settings()
    {
        // Validate and sanitize form data
        $setting1 = sanitize_text_field($_POST['garage_address']);
        $setting2 = sanitize_text_field($_POST['cost_per_hour']);
        $setting3 = sanitize_text_field($_POST['google_api_key']);

        // Save the data to the options table
        update_option('garage_address', $setting1);
        update_option('cost_per_hour', $setting2);
        update_option('google_api_key', $setting3);

        // Add more update_option calls for additional settings

        // Optionally, redirect the user or display a success message
        wp_redirect(admin_url('admin.php?page=taxi-fare-settings'));
        //exit;
    }
}

// Initialize the plugin
$my_plugin_instance = new MyPlugin();
