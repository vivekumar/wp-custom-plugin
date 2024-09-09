       <?php
error_reporting(0);
function da_tree_list_call(){

    global $wpdb;
    $table_name = $wpdb->prefix . 'tree';
    $trees = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * from $table_name", ""
        ), ARRAY_A
    );

    if (count($trees) > 0) { ?>
    <style>
   

.tree ul {
	padding-top: 20px; position: relative;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

.tree li {
	float: left; text-align: center;
	list-style-type: none;
	position: relative;
	padding: 20px 5px 0 5px;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

/*We will use ::before and ::after to draw the connectors*/

.tree li::before, .tree li::after{
	content: '';
	position: absolute; top: 0; right: 50%;
	border-top: 1px solid #ccc;
	width: 50%; height: 20px;
}
.tree li::after{
	right: auto; left: 50%;
	border-left: 1px solid #ccc;
}

/*We need to remove left-right connectors from elements without 
any siblings*/
.tree li:only-child::after, .tree li:only-child::before {
	display: none;
}

/*Remove space from the top of single children*/
.tree li:only-child{ padding-top: 0;}

/*Remove left connector from first child and 
right connector from last child*/
.tree li:first-child::before, .tree li:last-child::after{
	border: 0 none;
}
/*Adding back the vertical connector to the last nodes*/
.tree li:last-child::before{
	border-right: 1px solid #ccc;
	border-radius: 0 5px 0 0;
	-webkit-border-radius: 0 5px 0 0;
	-moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after{
	border-radius: 5px 0 0 0;
	-webkit-border-radius: 5px 0 0 0;
	-moz-border-radius: 5px 0 0 0;
}

/*Time to add downward connectors from parents*/
.tree ul ul::before{
	content: '';
	position: absolute; top: 0; left: 50%;
	border-left: 1px solid #ccc;
	width: 0; height: 20px;
}

.tree li a{
	border: 1px solid #ccc;
	padding: 5px 10px;
	text-decoration: none;
	color: #666;
	font-family: arial, verdana, tahoma;
	font-size: 11px;
	display: inline-block;
	
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	
	transition: all 0.5s;
	-webkit-transition: all 0.5s;
	-moz-transition: all 0.5s;
}

/*Time for some hover effects*/
/*We will apply the hover effect the the lineage of the element also*/
.tree li a:hover, .tree li a:hover+ul li a {
	background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
}
/*Connector styles on hover*/
.tree li a:hover+ul li::after, 
.tree li a:hover+ul li::before, 
.tree li a:hover+ul::before, 
.tree li a:hover+ul ul::before{
	border-color:  #94a0b4;
}
    </style>
    
    
        <div style="margin-top: 40px;">
            <table cellpadding="10" border="1">
                <tr>
                    <th>Sr No</th>
                    <th>Parent Course</th>
                    <th>Child Course</th>
                    <th>Course Position</th>
                    <th>Action</th>
                </tr>
                <?php
                $count = 1;
                foreach ($trees as $index => $tree) { ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php if($tree['parent_id']): echo get_the_title($tree['parent_id']); else: echo get_the_title($tree['course_id']); endif; ?></td>
                        <td><?php if($tree['parent_id']): echo get_the_title($tree['course_id']); endif; ?></td>
                        <td><?php echo $tree['course_position'] ?></td>
                        <td>
                            <a href="admin.php?page=update-tree&id=<?php echo $tree['id']; ?>">Edit</a>
                            <a href="admin.php?page=delete-tree&id=<?php echo $tree['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
        
        <div class="tree">
    <ul>
        <li><a href="#">AOV Forshaga</a>
        
            <ul>
                <li><a href="#">Expedition Medicine 2024</a>
                    
                </li>
              <li><a href="#">Adventure Medicine Alumni</a>
                    <ul>
                      <li><a href="#">Risk Assesment</a>
                        <ul>
                          <li><a href="#">Heat Realted illness</a>

                          </li>
                      </ul>
                      </li>
                      <li><a>Instructors</a></li>
                    </ul>
                </li>
              <li><a href="#">Hypothermia</a></li>
            </ul>
       
        </li>
    </ul>
</div>


        <?php
    }else{
        echo "<h2>Tree Record Not Found</h2>";
    }


// Step 1: Fetch data from the database
global $wpdb;
$results = $wpdb->get_results("SELECT * FROM wp_tree ORDER BY parent_id, id");
/*
// Step 2: Organize data into a hierarchical structure
$tree = [];
foreach ($results as $row) {
    if ($row->parent_id == 0) {
        $tree[$row->id] = [
            'course_id' => $row->course_id,
            'children' => [],
        ];
    } else {
        $tree[$row->parent_id]['children'][] = [
            'course_id' => $row->course_id,
            'siblings' => $row->course_position,
        ];
    }
}

// Step 3: Print the hierarchical structure as a tree
function print_tree($tree, $indent = 0) {
    foreach ($tree as $id => $node) {
        echo str_repeat("&nbsp;&nbsp;----", $indent);
        echo "ID: $id, Course ID:".  get_the_title($node['course_id'])." <br>";
        
        if (!empty($node['children'])) {
            print_tree($node['children'], $indent + 1);
        }
    }
}

// Print the tree structure
echo "<pre>";
print_tree($tree);
echo "</pre>";
*/
/*
$tree = [];
foreach ($results as $row) {
    if ($row->parent_course == '') {
        $tree[$row->child_course] = [
            'parent_course' => $row->parent_course,
            'course_position' => $row->course_position,
            'children' => [],
        ];
    } else {
        $tree[$row->parent_course]['children'][] = [
            'child_course' => $row->child_course,
            'course_position' => $row->course_position,
        ];
    }
}

// Step 2: Print the hierarchical structure as a tree
function print_tree($tree) {
    echo '<div class="tree">';
    echo '<ul>';
    foreach ($tree as $course => $node) {
        echo '<li>';
        echo '<a href="#">' . $course . '</a>';
        if (!empty($node['children'])) {
            print_children($node['children']);
        }
        echo '</li>';
    }
    echo '</ul>';
    echo '</div>';
}

function print_children($children) {
    echo '<ul>';
    foreach ($children as $child) {
        echo '<li>';
        echo '<a href="#">' . $child['child_course'] . '</a>';
        if (!empty($child['children'])) {
            print_children($child['children']);
        }
        echo '</li>';
    }
    echo '</ul>';
}

// Print the tree structure
print_tree($tree);
*/







function generateTree($parent_id) {
    global $wpdb;
    $html = '<ul>';
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_tree WHERE parent_id = %d", $parent_id));
    print_r($results);
    if($results){
    foreach ($results as $row) {
        $html .= '<li><a href="#">' . get_the_title($row->course_id) . '</a>';
        
        // Recursively call the function if the node has children
        $html .= generateTree($row->parent_id);
        
        $html .= '</li>';
    } }
    
    $html .= '</ul>';
    
    return $html;
}

// Root node ID, change this according to your database structure
$root_id = 0;

// Generate the tree structure starting from the root node
$html = generateTree($root_id);

// Output the HTML
echo '<div class="tree">' . $html . '</div>';


}



?>
