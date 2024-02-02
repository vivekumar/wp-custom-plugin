<?php
global $wpdb;
$table_name = $wpdb->prefix . 'multidomains_theme'; // Replace with the actual table name
$currentDateFormatted = date("F j, Y");
$listid = $_GET['listid'] ? $_GET['listid'] : '';

$upload_dir = wp_upload_dir();
$upload_baseurl = $upload_dir['baseurl'];


if (isset($_POST['upload_file'])) {
    $file_name1 = $file_name2 = $file_name3 = '';
    $file = $_FILES['theme_logo'];
    $footer_logo = $_FILES['footer_logo'];
    $file2 = $_FILES['febicon'];

    $listid = $_POST['listid'];

    // Check if the file was uploaded successfully.
    //if ($file['error'] === 0) {
    if (isset($file['name']) && !empty($file['name'])) {
        // Get the file data.
        $time = date("d-m-Y") . "-" . time();
        $file_name1 = $time . '-' . $file['name'];
        $file_tmp = $file['tmp_name'];

        // Define the upload directory within the media library.


        $upload_path = $upload_dir['path'] . '/' . $file_name1;

        // Move the uploaded file to the media library.
        if (move_uploaded_file($file_tmp, $upload_path)) {

            // Insert data into the media library.
            $file_type = wp_check_filetype($file_name, null);
            $attachment = array(
                'guid'           => $upload_dir['url'] . '/' . basename($upload_path),
                'post_mime_type' => $file_type['type'],
                'post_title'     => sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME)),
                'post_content'   => '',
                'post_status'    => 'inherit',
            );

            $attachment_id = wp_insert_attachment($attachment, $upload_path);

            //if (!is_wp_error($attachment_id)) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_path);
            wp_update_attachment_metadata($attachment_id, $attachment_data);

            // You can now use $attachment_id to reference the uploaded file in the media library.


            // Add success handling here.
        }
    }
    if (isset($file2['name']) && !empty($file2['name'])) {
        // Get the file data.
        $time = date("d-m-Y") . "-" . time();
        $file_name2 =  $time . '-' . $file2['name'];
        $file_tmp = $file2['tmp_name'];
        // Define the upload directory within the media library.

        $upload_path2 = $upload_dir['path'] . '/' . $file_name2;

        // Move the uploaded file to the media library.
        if (move_uploaded_file($file_tmp, $upload_path2)) {

            // Insert data into the media library.
            $file_type = wp_check_filetype($file_name2, null);
            $attachment2 = array(
                'guid'           => $upload_dir['url'] . '/' . basename($upload_path2),
                'post_mime_type' => $file_type['type'],
                'post_title'     => sanitize_file_name(pathinfo($file_name2, PATHINFO_FILENAME)),
                'post_content'   => '',
                'post_status'    => 'inherit',
            );

            $attachment_id2 = wp_insert_attachment($attachment2, $upload_path2);

            //if (!is_wp_error($attachment_id)) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data2 = wp_generate_attachment_metadata($attachment_id2, $upload_path2);
            wp_update_attachment_metadata($attachment_id2, $attachment_data2);

            // You can now use $attachment_id to reference the uploaded file in the media library.

        }
    }
    if (isset($footer_logo['name']) && !empty($footer_logo['name'])) {
        // Get the file data.
        $time = date("d-m-Y") . "-" . time();
        $file_name3 = $time . '-' . $footer_logo['name'];
        $file_tmp = $footer_logo['tmp_name'];
        // Define the upload directory within the media library.

        $upload_path3 = $upload_dir['path'] . '/' . $file_name3;

        // Move the uploaded file to the media library.
        if (move_uploaded_file($file_tmp, $upload_path3)) {

            // Insert data into the media library.
            $file_type = wp_check_filetype($file_name3, null);
            $attachment3 = array(
                'guid'           => $upload_dir['url'] . '/' . basename($upload_path3),
                'post_mime_type' => $file_type['type'],
                'post_title'     => sanitize_file_name(pathinfo($file_name3, PATHINFO_FILENAME)),
                'post_content'   => '',
                'post_status'    => 'inherit',
            );

            $attachment_id3 = wp_insert_attachment($attachment3, $upload_path3);

            //if (!is_wp_error($attachment_id)) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data2 = wp_generate_attachment_metadata($attachment_id3, $upload_path3);
            wp_update_attachment_metadata($attachment_id3, $attachment_data2);

            // You can now use $attachment_id to reference the uploaded file in the media library.

        }
    }

    $domain_name = $theme_name = '';
    if (!$_POST['domain_name']) {
        echo '<p class="update-nag notice notice-warning inline" style="color:red; width:100%">Domænenavn er påkrævet.</p>';
    } else {
        $domain_name = $_POST['domain_name'];
    }

    if (!$_POST['theme_name']) {
        echo '<p class="update-nag notice notice-warning inline" style="color:red; width:100%">Temanavn er påkrævet.</p>';
    } else {
        $theme_name = $_POST['theme_name'];
    }

    if (!empty($domain_name) && !empty($theme_name)) {


        /*
        $updated_data = array(
            'domain_name' => $_POST['domain_name'] ? $_POST['domain_name'] : '',
            'theme_name' => $_POST['theme_name'] ? $_POST['theme_name'] : '',
            'theme_color' => isset($_POST['theme_color']) ? $_POST['theme_color'] : '',
            'theme_background' => isset($_POST['theme_background']) ? $_POST['theme_background'] : '',
            'theme_logo' => $file_name1 ? $upload_dir['subdir'] . '/' . $file_name1 : '',
            'febicon' => $file_name2 ? $upload_dir['subdir'] . '/' . $file_name2 : '',
        );*/
        $domain_name = $_POST['domain_name'];
        $updated_data['domain_name'] = $domain_name ? $domain_name : '';
        $updated_data['theme_name'] = $_POST['theme_name'] ? $_POST['theme_name'] : '';
        if (!empty($_POST['theme_color'])) {
            $updated_data['theme_color'] = isset($_POST['theme_color']) ? $_POST['theme_color'] : '';
        }
        if (!empty($_POST['theme_background'])) {
            $updated_data['theme_background'] = isset($_POST['theme_background']) ? $_POST['theme_background'] : '';
        }
        if (!empty($file_name1)) {
            $updated_data['theme_logo'] = $file_name1 ? $upload_dir['subdir'] . '/' . $file_name1 : '';
        }
        if (!empty($file_name2)) {
            $updated_data['febicon'] = $file_name2 ? $upload_dir['subdir'] . '/' . $file_name2 : '';
        }
        if (!empty($file_name3)) {
            $updated_data['footer_logo'] = $file_name3 ? $upload_dir['subdir'] . '/' . $file_name3 : '';
        }


        if ($listid > 0) {
            $query2 = "SELECT * FROM $table_name WHERE id=$listid";
            $data2 = $wpdb->get_results($query2, ARRAY_A);
            $chost = $_SERVER['HTTP_HOST'];

            if ($data2[0]['domain_name'] != $chost) {
                $where = array('id' => $listid);
                $res = $wpdb->update($table_name, $updated_data, $where);
            } else {
                echo '<p class="update-nag notice notice-warning inline" style="color:red; width:100%">Åh! Du kan ikke opdatere det primære domænenavnstema. Prøv med et andet domæne.</p>';
            }
        } else {
            //find domain name in database
            $query = "SELECT * FROM $table_name WHERE domain_name='$domain_name'";
            $find_domain = $wpdb->get_results($query, ARRAY_A);
            if (count($find_domain) == 0) {
                $res = $wpdb->insert($table_name, $updated_data);
            } else {
                echo '<p class="update-nag notice notice-warning inline" style="color:red; width:100%">' . $domain_name . ' gibt es schon. Bitte versuchen Sie es mit einer anderen Domain.</p>';
            }
        }

        if ($res) {
            echo '<p class="update-nag notice notice-warning inline" style="color:green; width:100%">Domæne tilføjet.</p>';
        } else {
            echo '<p class="update-nag notice notice-warning inline" style="color:red; width:100%">En eller anden fejl.</p>';
        }
    }
}


if ($listid > 0) {
    $query2 = "SELECT * FROM $table_name WHERE id=$listid";
    $data = $wpdb->get_results($query2, ARRAY_A);
    $data = $data[0];
}
?>

<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<style>
    .invoice-title h2,
    .invoice-title h3 {
        display: inline-block;
    }

    .table>tbody>tr>.no-line {
        border-top: none;
    }

    .table>thead>tr>.no-line {
        border-bottom: none;
    }

    .table>tbody>tr>.thick-line {
        border-top: 2px solid;
    }

    .tables222 img {
        background: #e3e3e3;
        height: 100px;
        object-fit: contain;
        padding: 10px;
        margin: 20px 0 0 0;
    }
</style>
<div class="container1 tables222">
    <div class="row">
        <div class="col-xs-12">
            <div class="invoice-title">
                <h2>Tilføj nyt domæne</h2>
            </div>

        </div>
    </div>
    <div class="containerq">
        <div class="row">
            <!-- ... Your existing content ... -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Information</strong></h3>
                    </div>
                    <div class="panel-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="domain_name" placeholder="Domain Name" value="<?= @$data['domain_name'] ?>" id="domain_name"><br>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <select class="form-select form-control" name="theme_name" aria-label="Default select example">
                                        <option selected value="" <?= @$data['theme_name'] == '' ? 'selected' : ''; ?>>Select theme</option>
                                        <option value="green" <?= @$data['theme_name'] == 'green' ? 'selected' : ''; ?>>Green</option>
                                        <option value="black" <?= @$data['theme_name'] == 'black' ? 'selected' : ''; ?>>Black</option>
                                    </select>
                                    <!--<input type="text" class="form-control" name="theme_name" placeholder="Theme Name" id="theme_name"><br>-->
                                </div>
                            </div>
                            <!--<div class="row">
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="theme_color" placeholder="Theme Color" id="theme_color"><br>
                                </div>
                                <div class="col-xs-6">
                                    <input type="text" class="form-control" name="theme_background" placeholder="Theme Background Color" id="theme_background"><br>
                                </div>
                            </div>-->
                            <div class="row">
                                <div class="col-xs-4">
                                    <label>Header Logo</label>
                                    <input type="file" class="form-control" name="theme_logo" id="theme_logo">
                                    <?php if (@$data['theme_logo']) {
                                        echo "<img src='" . $upload_baseurl . $data['theme_logo'] . "' style='height:100px;with:auto;max-width:100%'>";
                                    } ?>

                                </div>
                                <div class="col-xs-4">
                                    <label>Footer Logo</label>
                                    <input type="file" class="form-control" name="footer_logo" id="footer_logo">
                                    <?php if (@$data['footer_logo']) {
                                        echo "<img src='" . $upload_baseurl . $data['footer_logo'] . "' style='height:100px;with:auto; max-width:100%'>";
                                    } ?>

                                </div>

                                <div class="col-xs-4 ">
                                    <label>Febicon</label>
                                    <input type="file" class="form-control" name="febicon" id="febicon">
                                    <?php if (@$data['febicon']) {
                                        echo "<img src='" . $upload_baseurl . $data['febicon'] . "'>";
                                    } ?>
                                    <br>
                                </div>
                            </div>

                            <br><br>
                            <input type="hidden" name="listid" value="<?= $listid ?>">
                            <input type="submit" name="upload_file" class="button button-primary button-large" value="Upload File">
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>