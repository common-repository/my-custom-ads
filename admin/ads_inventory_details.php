<?php
    if(!class_exists('WP_List_Table')){
        require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    }

    class Customadsdetail_List_Table extends WP_List_Table {
        
        function getAllRecords(){
            global $wpdb;
            global $table_customadsdetails;
            $customadsList = $wpdb->get_results("SELECT * FROM ".$table_customadsdetails ." ORDER BY id desc");
            $i=0;
            foreach ( $customadsList as $customads ) {
                $customadsArray[$i]['id'] = $customads->id;
                $customadsArray[$i]['customads_title'] = $customads->customads_title;
                $customadsArray[$i]['customads_short_code'] = $customads->customads_short_code;
                $status = 'Not Available';
                if($customads->customads_status=='1')
                $status = 'Available';
                $customadsArray[$i]['customads_status'] = $status;
                $customadsArray[$i]['customads_created_date'] = $customads->customads_created_date;
                $customadsArray[$i]['customads_updated_date'] = $customads->customads_updated_date;
                $i++;
            }
            return $customadsArray;
        }
        function __construct(){
            global $status, $page;

            //Set parent defaults
            parent::__construct( array(
            'singular'  => 'item',     //singular name of the listed records
            'plural'    => 'items',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
            ) );

        }
        function column_default($item, $column_name){
            switch($column_name){
                case 'id':
                case 'customads_title':
                case 'customads_short_code':
                case 'customads_status':
                case 'customads_created_date':
                case 'customads_updated_date':
                    return $item[$column_name];
                default:
                    return print_r($item,true); exit; //Show the whole array for troubleshooting purposes
            }
        }

        function column_title($item){
            //Build row actions
            $actions = array(
            'edit'      => sprintf(

                // '<form name="action" id="actionedit" action="" method="post">
                // <input type="hidden" name="page" value="iwash-zipcode">
                // <input type="hidden" name="action" value= "edit">
                // <input type="hidden" name="id" value="'.$item['id'].'">
                // <input type="submit" id="edit" name="edit" value="Edit" >
                // </form>'
                 '<a href="?page=%s&action=%s&id=%s">Edit</a>','customads-listing','edit',$item['id']
                ),
            'delete'    => sprintf(
                // '<form name="deleteA" id="deleteA" action="" method="post">
                // <input type="hidden" name="page" value="iwash-zipcode">
                // <input type="hidden" name="action" value= "delete">
                // <input type="hidden" name="id" value="'.$item['id'].'">
                // <input type="submit" id="delete" name="delete" value="Delete" >
                // </form>'

                '<a  href="?page=%s&action=%s&id=%s"  class="deleteitem">Delete</a>','customads-listing','delete',$item['id']
                ),
            );
            ?>
            
            <?php
            //Return the cattitle contents
            return sprintf('%1$s <span style="color:silver">%2$s</span>',
            /*$1%s*/ $item['id'],            
            /*$3%s*/ $this->row_actions($actions)
            );
        }

        function column_cb($item){
            return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['id']                //The value of the checkbox should be the record's id
            );
        }
        function get_columns(){
            $columns = array(
            'cb'                          => '<input type="checkbox" />', //Render a checkbox instead of text
            'title'                       => 'Id',
            'customads_title'             => 'Title',
            'customads_short_code'        => 'Short Code',
            'customads_status'            => 'Status',
            'customads_created_date'      => 'Created Date',
            'customads_updated_date'      => 'Updated Date',
            );
            return $columns;
        }
        function get_sortable_columns() {
            $sortable_columns = array(
            'id'     => array('id',false)
            );
            return $sortable_columns;
        }
        function get_bulk_actions() {
            $actions = array(
            'delete'    => 'Delete'
            );
            return $actions;
        }
        function process_bulk_action() {
            if( 'delete'===$this->current_action() ) {
                wp_die('Items deleted (or they would be if we had items to delete)!');
            }
        }
        function prepare_items() {
            global $wpdb; //This is used only if making any database queries
            /**
            * First, lets decide how many records per page to show
            */
            $per_page = 10;
            $example_data = $this->getAllRecords();
            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();

            $this->_column_headers = array($columns, $hidden, $sortable);
            $this->process_bulk_action();
            $data = $this->getAllRecords();

            function usort_reorder($a,$b){
                $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'id'; //If no sort, default to title
                $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
                $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
                return ($order==='desc') ? $result : -$result; //Send final sort direction to usort
            }

            @usort($data, 'usort_reorder');
            $current_page = $this->get_pagenum();
            $total_items = count($data);
            $data = @array_slice($data,(($current_page-1)*$per_page),$per_page);
            $this->items = $data;
            $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
            ) );
        }
    }
    $customads = new  Customadsdetail_List_Table();
    $customads->prepare_items();

?>
<div class="wrap">
    <div id="icon-users" class="icon32"><br/></div>
    <h2>Custom Ads Listing <a class="add-new-h2" href="?page=customads-listing&action=add">Add New</a></h2>
    <?php echo @$message;?>
    <form id="zipcode-filter" method="post">
        <input type="hidden" name="page" value="<?php echo $_GET['page'] ?>" />
        <?php echo $customads->display() ?>
    </form>
</div>
<script type="text/javascript">
    jQuery('#doaction,#doaction2').click(function(){

        d = jQuery('.bulkactions select[name="action"]').val();
        d1 = jQuery('.bulkactions select[name="action2"]').val();
        if(d==-1 && d1==-1){
            alert("Please Select Action to Delete Data");
            return false;
        }
        if(d=='delete' || d1=='delete')
            {
            var sList = 1;
            jQuery('input[type=checkbox]:checked').each(function () {
                sList++;
            });
            if(sList==1){
                alert("Please Select Checkbox to Delete Items.");
                return false;
            }
            if (!confirm('Are you Sure, you want to delete items?')) {
                return false;
            }
        }
    } );
    jQuery('.deleteitem').click(function(){
        if (!confirm('Are you Sure, you want to delete Items?')) 
            {
            return false;
        }
    });
</script>
<?php
