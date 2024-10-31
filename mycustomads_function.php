<?php
    function customads_menu(){
        $my_plugins_page = add_menu_page('Custom-Ads', 'Custom-Ads', 'manage_options', 'customads-listing', 'customads_listing' );
        add_submenu_page('customads-listing', 'Manage Custom-Ads', 'Manage Custom-Ads', 'manage_options', 'customads-listing','customads_listing' );
        add_submenu_page('customads-listing', 'Help', 'Help','manage_options', 'help','customads_help' );
    }

    //This Function will add script/css for admin section
    function customads_adminscripts(){
        wp_enqueue_script( 'customads_script', MYCUSTOMADS_PLUGIN_PATH.'/js/customads_js.js', array( 'jquery' ), null, true );
        wp_register_style( 'customads_css',    MYCUSTOMADS_PLUGIN_PATH.'/css/customads_css.css');
        wp_enqueue_style(  'customads_css' );
    }    

    //This function called when plugin installed
    function customads_install(){
        global $wpdb;
        global $table_customadsdetails;

        $sql_exist = "DROP TABLE IF EXISTS $table_customadsdetails";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_exist );

        $sql = "CREATE TABLE $table_customadsdetails (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        customads_title VARCHAR(150),
        customads_short_desc text,
        customads_code text,
        customads_short_code VARCHAR(50),
        customads_status  BOOLEAN,
        customads_created_date  datetime,
        customads_updated_date datetime,
        UNIQUE KEY id (id)
        );";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    //This function called when plugin uninstalled.
    function customads_uninstall(){
        global $wpdb;
        global $table_customadsdetails;

        $sql = "DROP TABLE $table_customadsdetails";       
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $wpdb->query($sql); 
    }
    //This function for database and redirct to link when menu called 
    function customads_listing(){
        global $wpdb;
        global $table_customadsdetails;
        //insert record in database

        if($_POST['original_publish']=='Add'){
            $fields = 'customads_title,customads_short_desc,customads_code,customads_short_code,customads_status,customads_created_date,customads_updated_date';
            $values =  "'".trim(sanitize_text_field($_POST['title']))."','".trim(esc_textarea($_POST['short_desc']))."','".trim(sanitize_text_field($_POST['code']))."','".trim(sanitize_text_field($_POST['short_code']))."','".sanitize_text_field($_POST['status'])."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."'";  
            mycustomads_insertdata($table_customadsdetails,$fields,$values);  
            $message = '<span style="color:green;"><b>Inserted Successfully.</b></span>';
            $_REQUEST['action']='';
        }
        //update record in database
        if($_POST['original_publish']=='Update'){
            $fields = "customads_title = '".trim(sanitize_text_field($_POST['title']))."',customads_short_desc = '".trim(esc_textarea($_POST['short_desc']))."',customads_code = '".trim(sanitize_text_field($_POST['code']))."',customads_status = '".sanitize_text_field($_POST['status'])."',customads_updated_date = '".date("Y-m-d h:i:s")."'";
            $where = 'id = '.$_POST['id'];
            mycustomads_updatedata($table_customadsdetails,$fields,$where);                                                                                                                                         
            $message = '<span style="color:green;"><b>Updated Successfully.</b></span>';    
            $_REQUEST['action']='';
        }
        //delete record from database
        if($_REQUEST['action']=='delete' || $_REQUEST['action2']=='delete'){                                                          
            if($_REQUEST['id']!=''){
                mycustomads_deletedata($table_customadsdetails,'id',$_REQUEST['id']);
                $message = '<span style="color:red;"><b>Deleted Successfully.</b></span>';
            }
            else if(count($_REQUEST['item'])>0){
                    mycustomads_deletedata($table_customadsdetails,'id',$_REQUEST['item']);
                    $message = '<span style="color:red;"><b>Deleted Successfully.</b></span>';
                }
                else{
                    $message = '<span style="color:red;"><b>No Data Found.</b></span>';
            }
            $_REQUEST['action']='';
        }
        //redirect to create new add page
        if($_REQUEST['action']=='add' || $_REQUEST['action']=='edit'  ){
            include('admin/edit_customads.php');
        }
        else{
            include('admin/ads_inventory_details.php');
        }
    }

    function customads_help(){
        include('admin/help.php');
    }

    //Widget Start Here     
    //Creating the widget
    class mycustomads_widget extends WP_Widget {
        function __construct() {
            parent::__construct(
            // Base ID of your widget
            'mycustomads_widget',
            // Widget name will appear in UI
            __('Custom Ads Management', 'mycustomads_widget_domain'),
            // Widget description
            array( 'description' => __( 'Sample widget based on Custom ads plugin', 'mycustomads_widget_domain' ), )
            );
        }

        // Creating widget front-end
        // This is where the action happens
        public function widget( $args, $instance ) {
            global $wpdb;
            global $table_customadsdetails;
            echo $args['before_widget'];
            if($instance['title']!="")
            {
                echo $args['before_title'];
                echo $instance['title'];
                echo $args['after_title'];
            }
            $customadsList = $wpdb->get_row("SELECT * FROM ".$table_customadsdetails ." where id=".$instance['custom_ads_title'], ARRAY_A);
        ?>
        <div id="customads">
            <?php 
                $instance['display_customads_description'];
                if($instance['display_customads_title']==on){
                ?><div id="customads_title"><h3> <?php echo esc_html($customadsList['customads_title']);?></h3></div><?php
                }
                if($instance['display_customads_description']==on){
                ?><div id="customads_description"><p> <?php echo esc_html($customadsList['customads_short_desc']);?></p> </div> <?php
                }
                $ext= explode(".",$customadsList['customads_code']); 
                if(end($ext)=='png' || end($ext)=='jpg' || end($ext)=='jpeg' || end($ext)=='gif'){
                ?><div id="customads"><img src="<?php echo esc_url($customadsList['customads_code']); ?>"/></div></div><?php    
            }else{
            ?>
            <div id="customads"><?php echo $customadsList['customads_code']?></div></div>
            <?php 
            }
            echo $args['after_widget'];
        ?>

        <?php
        }

        // Widget Backend
        public function form($instance) {
            global $wpdb;
            global $table_customadsdetails;
            $instance[ 'custom_ads_title' ];
            $your_checkbox_var = $instance['display_customads_title'] ? 'true' : 'false';
            $your_checkbox_var = $instance['display_customads_description'] ? 'true' : 'false';
            if ( isset( $instance[ 'title' ] ) ) {
                $title = $instance[ 'title' ];
            }
            else {
                $title = __( 'New title', 'mycustomads_widget_domain' );
            }
            // Widget admin form
            $customadsList = $wpdb->get_results("SELECT * FROM ".$table_customadsdetails ." WHERE customads_status='1' ORDER BY customads_title ");
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" placeholder="<?php echo esc_attr( $title ); ?>" /> 
            <br><br>
            <label for="<?php echo $this->get_field_id( 'custom_ads_title' ); ?>">Custom Ads</label>
            <select name="<?php echo $this->get_field_name( 'custom_ads_title' ); ?>" id="<?php echo $this->get_field_id( 'custom_ads_title' ); ?>">
                <?php foreach ( $customadsList as $customads ) { ?>           
                    <option value="<?php echo  $customads->id;?>" <?php if($customads->id==$instance[ 'custom_ads_title' ]){?>selected="selected"<?php }?>><?php echo  $customads->customads_title;?></option>
                    <?php }?> 
            </select>
            <br><br>
            <input class="checkbox" type="checkbox" <?php checked($instance['display_customads_title'], 'on'); ?> id="<?php echo $this->get_field_id('display_customads_title'); ?>" name="<?php echo $this->get_field_name('display_customads_title'); ?>" /> Display customads title<br>            
            <input class="checkbox" type="checkbox" <?php checked($instance['display_customads_description'], 'on'); ?> id="<?php echo $this->get_field_id('display_customads_description'); ?>" name="<?php echo $this->get_field_name('display_customads_description'); ?>" /> Display customads discription
        </p>
        <?php
        }        

        // Updating widget replacing old instances with new
        public function update( $new_instance, $old_instance ) {
            $instance                                  = array();
            $instance['title']                         = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['custom_ads_title']              = ( ! empty( $new_instance['custom_ads_title'] ) ) ? strip_tags( $new_instance['custom_ads_title'] ) : '';
            $instance['display_customads_title']       = $new_instance['display_customads_title'];
            $instance['display_customads_description'] = $new_instance['display_customads_description'];
            return $instance;
        }
    }   
    //Class mycustomads_widget ends here
    //Register and load the widget
    function mycustomads_load_widget() {
        register_widget( 'mycustomads_widget' );
    }
    add_action( 'widgets_init', 'mycustomads_load_widget' );
    //Widget End Here

    //Shortcode Start Here
    function Customads($content=null){
        global $wpdb;
        global $table_customadsdetails;
        global $shortcode_tags;        
        $customadsList = $wpdb->get_row("SELECT customads_code FROM ".$table_customadsdetails ." WHERE customads_status='1' AND customads_short_code  ='[Customads id=".$content['id']."]'", ARRAY_A);
        $data = '';
        $ext= explode(".",$customadsList['customads_code']); 
        if(end($ext)=='png' || end($ext)=='jpg' || end($ext)=='jpeg' || end($ext)=='gif'){
            $data.='<div id="customads"><img src='.esc_url($customadsList['customads_code']).'/></div>';    
        }else{
            $data.='<div id="customads">'.$customadsList['customads_code'].'</div>';
        }
        return $data;
    }
    //Shortcode End Here 
?>