<?php 

    global $wpdb;

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Sanitize form data
        $parent_course = sanitize_text_field($_POST['parent_course']);
        $child_course = sanitize_text_field($_POST['child_course']);
        $node_type = sanitize_text_field($_POST['node-type']);

        // Validate form data (add your validation logic here if needed)

        // Insert data into custom table
        $table_name = $wpdb->prefix . 'tree';

        if($parent_course && $child_course){
            $wpdb->insert(
                $table_name,
                array(
                    'parent_id' => $parent_course,
                    'course_id' => $child_course,
                    'course_position' => $node_type
                ),
                array(
                    '%s',
                    '%s',
                    '%s'
                )
            );

            $msg = "Form data added successfully";

        }else{

            $wpdb->insert(
                $table_name,
                array(
                    'parent_id' => '',
                    'course_id' => $parent_course,
                    'course_position' => $node_type
                ),
                array(
                    '%s',
                    '%s',
                    '%s'
                )
            );

            $msg = "Form data added successfully";
        }
        
    }
?>

<br>

<p><?php echo $msg; ?></p>
<br>
<!--tree code-->
<form action="" method="post">
  <div id="chart-container"></div>
  <div id="edit-panel">
   
    <span id="node-type-panel" class="radio-panel">

    <select name="parent_course" required>
        <option value="">Select a parent course</option>
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
            <option value="<?php echo $course->ID; ?>"><?php echo $course->post_title; ?></option>
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
            <option value="<?php echo $course->ID; ?>"><?php echo $course->post_title; ?></option>
            <?php
        }
        ?>
    </select>

      <input type="radio" name="node-type" id="rd-parent" value="parent" required><label for="rd-parent">Parent</label>
      <input type="radio" name="node-type" id="rd-child" value="children" required><label for="rd-child">Child</label>
      <input type="radio" name="node-type" id="rd-sibling" value="siblings" required><label for="rd-sibling">Sibling</label>
    </span>
    <button type="submit">Add</button>
  </div>
</form>
<?php 
da_tree_list_call();

