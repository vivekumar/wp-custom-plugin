<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Custom_List_Table extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'custom_item',
            'plural'   => 'custom_items',
            'ajax'     => false
        ));
    }

    public function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'multidomains_theme'; // Replace with the actual table name
        $query = "SELECT * FROM $table_name";
        $data = $wpdb->get_results($query, ARRAY_A);

        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, array(), $sortable);

        $this->items = $data;
    }

    public function get_columns()
    {

        return array(
            //'cb'              => '<input type="checkbox" />',
            'id'              => 'Id',
            'domain_name'         => 'Domain Name',
            'theme_name'           => 'Theme Name',
            //'theme_color'           => 'Theme Color',
            //'theme_background'      => 'Background Color',
            'theme_logo'        => 'Header Logo',
            'footer_logo'        => 'Footer Logo',
            'febicon'        => 'Febicon',
            'status'            => 'Action',
        );
    }





    public function column_default($item, $column_name)
    {
        $upload_dir = wp_upload_dir();
        $upload_baseurl = $upload_dir['baseurl'];

        if ($column_name === 'theme_logo') {
            return "<img src='" . $upload_baseurl . $item[$column_name] . "' style='height:50px;with:auto;max-width:50%'>";
        }
        if ($column_name === 'footer_logo') {
            return "<img src='" . $upload_baseurl . $item[$column_name] . "' style='height:50px;with:auto;max-width:50%'>";
        }
        if ($column_name === 'febicon') {
            return "<img src='" . $upload_baseurl . $item[$column_name] . "' style='height:50px;with:auto;max-width:50%'>";
        }
        if ($column_name === 'status') {
            $user_id = $item['id']; // Assuming the user's ID is stored in $item['ID']
            $edit_url = admin_url('admin.php?page=info_icon_domains_list&action=edit&listid=' . $user_id); // Adjust 'send_invoice' to match your page slug
            $del_url = admin_url('admin.php?page=info_icon_domains_list&action=delete&listid=' . $user_id); // Adjust 'send_invoice' to match your page slug
            $actions = array(
                'edit' => sprintf('<span class="edit"><a href="%s" >Edit</a></span>', esc_url($edit_url)),
                'view' => sprintf('<span class="trash"><a href="%s" class="submitdelete">Delete</a></span>', esc_url($del_url)),
            );
            $item['status'] =  '';

            return $item[$column_name] . $this->row_actions($actions);
        } else {
            return $item[$column_name];
        }
    }


    public function column_cb($item)
    {
        return '<input type="checkbox" name="custom_item[]" value="' . $item['ID'] . '" />';
    }

    public function get_sortable_columns()
    {
        return array(
            'id' => array('id', false),
            'domain_name' => array('domain_name', false),
            'theme_name' => array('theme_name', false),
            //'theme_color' => array('theme_color', false),
            //'theme_background' => array('theme_background', false),
            'theme_logo' => array('theme_logo', false),
            'footer_logo' => array('footer_logo'),
            'febicon' => array('febicon', false),
            'status' => array('status', false),

        );
    }

    public function no_items()
    {
        echo 'No custom items found.';
    }
}

$custom_list_table = new Custom_List_Table();
$custom_list_table->prepare_items();
?>

<div class="wrap">
    <h2>Domæner <a class="btn button btn-primery align-right" style="float: right;" href="<?php echo admin_url('admin.php?page=info_icon_domains_list&action=add_domain') ?>">Tilføj domæne</a></h2>
    <form method="post">
        <?php
        $custom_list_table->display();
        ?>
    </form>
</div>