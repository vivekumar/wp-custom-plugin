<?php

/*
Plugin Name: Infoicon Tree
Description: This plugin add tree structure.
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


function custom_plugin_enqueue_scripts() {
    // Enqueue JavaScript file
    wp_enqueue_script( 'jquery' );
    //wp_enqueue_script( 'admin-scripts', 'https://cdn.jsdelivr.net/npm/orgchart@4.0.1/dist/js/jquery.orgchart.min.js', array('jquery'), '1.0.0', true );
    wp_enqueue_script('custom-plugin-js', plugins_url('assets/js/scripts.js', __FILE__), array('jquery'), '1.0', true);
}

// Hook the function to the wp_enqueue_scripts action
add_action('admin_enqueue_scripts', 'custom_plugin_enqueue_scripts');

function utm_user_scripts() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'style', "https://cdn.jsdelivr.net/npm/orgchart@4.0.1/dist/css/jquery.orgchart.min.css");

    wp_enqueue_style( 'style',  $plugin_url . "/assets/css/styles.css");
}

add_action( 'admin_print_styles', 'utm_user_scripts' );



function info_icon_activate()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tree';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        parent_id mediumint(9) NOT NULL,
        course_id varchar(255) NOT NULL,
        course_position varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


// Rest of your code...

// Add a menu to the admin dashboard
add_action('admin_menu', 'info_icon_admin_menu');

function info_icon_admin_menu()
{
    // Add a top-level menu page
    add_menu_page(
        'Tree List',    // Page title
        'Tree List',    // Menu title
        'manage_options',        // Capability required to access this menu
        'info_icon_tree_list',   // Menu slug
        'info_icon_tree_list_page' // Callback function to display the page content
    );
    add_submenu_page(null, "Update Tree", "Update Tree", "manage_options", "update-tree", "da_tree_update_call");
    add_submenu_page(null, "Delete Tree", "Delete Tree", "manage_options", "delete-tree", "da_tree_delete_call");
}

function info_icon_tree_list_page(){
    
    include('add_tree.php');
    
}


function info_icon_deactivate()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'tree';
    $sql = "DROP TABLE $table_name";
    //$wpdb->query($sql);
}


function da_tree_update_call(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'tree';
    $msg = '';

    $id = isset($_GET['id']) ? intval($_GET['id']) : "";

    
    if (isset($_POST['update'])) {

        $parent_course = sanitize_text_field($_POST['parent_course']);
        $child_course = sanitize_text_field($_POST['child_course']);
        $node_type = sanitize_text_field($_POST['node-type']);
        $id = sanitize_text_field($_POST['id']);

        if (!empty($id)) {
            if($parent_course && $child_course){

                $wpdb->update("$table_name", array(
                    "parent_id" => $parent_course,
                    "course_id" => $child_course,
                    "course_position" => $node_type,
                ), array(
                    "id" => $id
                ));

                $msg = "Form data updated successfully";

            }else{

                $wpdb->update("$table_name", array(
                    "parent_id" => '',
                    "course_id" => $parent_course,
                    "course_position" => $node_type,
                
                ), array(
                    "id" => $id
                ));
                $msg = "Form data updated successfully";

            }

            
        }
    }
    

    $row_details = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * from $table_name WHERE id = %d", $id
        ), ARRAY_A
    );

    //print_r($row_details);

    ?>

    <p><?php echo $msg; ?></p>
    <br>
    <!--tree code-->
    <form action="" method="post">
    <div id="chart-container"></div>
    <div id="edit-panel">
    
        <select name="parent_course" required>
            <option value="">Select a parent course</option>
            <?php
            echo $row_details['parent_id']; 
            // Get all posts of custom post type "sfwd-courses"
            $args = array(
                'post_type' => 'sfwd-courses',
                'posts_per_page' => -1, // Retrieve all posts
            );
            $courses = get_posts($args);

            // Loop through courses and generate options
            foreach ($courses as $course) {
                ?>
                <option value="<?php echo $course->ID; ?>" <?php if($row_details['parent_id'] ): echo ($row_details['parent_id'] == $course->ID)?'selected':'';  else: echo ($row_details['course_id'] == $course->ID)?'selected':''; endif; ?>><?php echo $course->post_title; ?></option>
                <?php
            }
            ?>
        </select>

        <select name="child_course">
            <option value="">Select a Child course</option>
            <?php
            // Get all posts of custom post type "sfwd-courses"
            $args = array(
                'post_type' => 'sfwd-courses',
                'posts_per_page' => -1, // Retrieve all posts
            );
            $courses = get_posts($args);

            // Loop through courses and generate options
            foreach ($courses as $course) {
                ?>
                
                <option value="<?php echo $course->ID; ?>" <?php if($row_details['parent_id'] ): echo ($row_details['course_id'] == $course->ID)?'selected':''; endif; ?>><?php echo $course->post_title; ?></option>
                <?php
            }
            ?>
        </select>

        <input type="radio" name="node-type" id="rd-parent" value="parent" <?php echo ($row_details['course_position'] == 'parent')?'checked':''; ?> required><label for="rd-parent">Parent</label>
        <input type="radio" name="node-type" id="rd-child" value="children" <?php echo ($row_details['course_position'] == 'children')?'checked':''; ?> required><label for="rd-child">Child</label>
        <input type="radio" name="node-type" id="rd-sibling" value="siblings" <?php echo ($row_details['course_position'] == 'siblings')?'checked':''; ?> required><label for="rd-sibling">Sibling</label>
        <input type="hidden" name="id"value="<?php echo $id; ?>">

        <button type="submit" name="update" value="update" id="btn-add-nodes">Update</button>
        
    </div>
    </form>
<?php
da_tree_list_call();
}

function da_tree_delete_call(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'tree';

    $id = isset($_GET['id']) ? intval($_GET['id']) : "";
    if (isset($_REQUEST['delete'])) {
        if($_REQUEST['conf'] == 'yes'){
            $row_exists = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * from $table_name WHERE id = %d", $id
                ),ARRAY_A
            );
            if (count($row_exists) > 0) {
                $wpdb->delete("$table_name", array(
                    "id" => $id
                ));
            }
        }
        ?>
        <script>
            location.href = "<?php echo site_url() ?>/wp-admin/admin.php?page=info_icon_tree_list";
        </script>
    <?php
    } ?>
        <form method="post">
            <p>
                <label>Are you sure want to delete?</label><br>
                <input type="radio" name="conf" value="yes">Yes
                <input type="radio" name="conf" value="no" checked>No
            </p>

            <p>
                <button type="submit" name="delete">Delete</button>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
            </p>
        </form>
        <?php

}