<?php
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Custom_List_Table extends WP_List_Table
{
    private $table_name;
    public function __construct($table_name)
    {
        parent::__construct(array(
            'singular' => 'custom_item',
            'plural'   => 'custom_items',
            'ajax'     => false
        ));
        $this->table_name = $table_name;
    }

    public function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'fare_taxi_data'; // Replace with the actual table name

        // Pagination
        $per_page = 20; // Number of items per page
        $current_page = $this->get_pagenum();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY id DESC LIMIT %d OFFSET %d", $per_page, ($current_page - 1) * $per_page), ARRAY_A);

        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, array(), $sortable);

        $this->items = $data;

        // Pagination settings
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page),
        ));
    }

    public function get_columns()
    {

        return array(
            //'cb'              => '<input type="checkbox" />',
            'id'              => 'Id',
            'name'         => 'Name',
            'email'           => 'Email',
            'tel'           => 'tel',
            'starting_time'      => 'Start time',
            'starting_adres'        => 'Start add',
            'dropp_adres'        => 'Dropp add',
            'dropp_time'        => 'Dropp time',
            'total_cost'        => 'cost',
            'date'        => 'date',
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

        if ($column_name === 'status') {
            $user_id = $item['id']; // Assuming the user's ID is stored in $item['ID']
            //$edit_url = admin_url('admin.php?page=info_icon_domains_list&action=edit&listid=' . $user_id); // Adjust 'send_invoice' to match your page slug
            $del_url = admin_url('admin.php?page=info_icon_fare_taxi_list&action=delete&listid=' . $user_id); // Adjust 'send_invoice' to match your page slug
            $actions = array(
                //'edit' => sprintf('<span class="edit"><a href="%s" >Edit</a></span>', esc_url($edit_url)),
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
            'id'                => array('id', false),
            'name'              => array('name', false),
            'email'             => array('email', false),
            'tel'               => array('tel', false),
            'starting_time'     => array('starting_time', false),
            'starting_adres'    => array('starting_adres', false),
            'dropp_adres'       => array('dropp_adres', false),
            'dropp_time'        => array('dropp_time', false),
            'total_cost'        => array('total_cost', false),
            'date'              => array('date', false),
            'status'            => array('status', false),

        );
    }

    public function no_items()
    {
        echo 'No custom items found.';
    }
}
