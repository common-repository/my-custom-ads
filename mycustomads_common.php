<?php
    //Global Query
    function mycustomads_getquery($query)
    {
        global $wpdb;  
        $wpdb->query( $wpdb->prepare( $query,""));
    }
    //Get Queries
    function mycustomads_selectdata($tablename,$where,$select = '*')
    {
        global $wpdb;  
        $q = "SELECT $select FROM $tablename WHERE $where";    
        $result = $wpdb->get_results($q);
        return $result;
    }
    //Update Queries
    function mycustomads_updatedata($tablename,$values,$where)
    {
        global $wpdb;  
        $q = "UPDATE $tablename SET $values WHERE $where";
        mycustomads_getquery($q);
        return ;
    }
    //Insert Queries
    function mycustomads_insertdata($tablename,$fields,$values)
    {
        global $wpdb;  
        $q = "INSERT INTO $tablename ($fields) VALUES($values)";
        mycustomads_getquery($q); 
        return $lastid = $wpdb->insert_id;   
    }
    //Delete Queries
    function mycustomads_deletedata($tablename,$field,$value)
    {
        global $wpdb;  
        if(is_array($value))
            $tags = implode(', ',$value);
        else
            $tags = $value;
        $q = "DELETE FROM $tablename WHERE $field IN(".$tags.")";
        mycustomads_getquery($q);
    }
    
    //This Function is used to upload image
    function mycustomads_uploadimage($data)
    {
        global $wpdb; 
        //Add Custom Options Value when Plugin is been Activated
        $uploadpath = get_option('iwashimages_upload').'/';
        $fileupload = rand().$data['name'];
        $filenme  = $uploadpath.$fileupload;
        move_uploaded_file($data['tmp_name'],$filenme);
        @unlink($file);
        return $fileupload;
    }
?>