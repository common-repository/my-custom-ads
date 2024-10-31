<?php
    $customadsItem = $wpdb->get_results($wpdb->prepare( "SELECT * FROM ".$table_customadsdetails." WHERE id = %d",$_REQUEST['id']));
    if($_REQUEST['action']=='add')
    {
        $title ='Add';
        $label ='Add';
    }else{
        $title ='Edit';            
        $label ='Update';
    }
?>
<div style="overflow: hidden;" id="wpbody-content" aria-label="Main content" tabindex="0">
    <div class="wrap">
        <h2><?php echo $title;?> Custom Ads</h2>
        <!-- Create Ads or Update Ads form -->
        <form name="post" action="<?php echo home_url().'/wp-admin/admin.php?page=customads-listing' ?>" method="post" id="post">
            <input type="hidden" name="id" value="<?php echo $customadsItem[0]->id;?>"> 
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Title</label>
                            </div>
                        </div>
                        <div id="titlediv">
                            <div id="titlewrap" class="boxclass">
                                <input name="title" value="<?php echo $customadsItem[0]->customads_title;?>" minlength="5" maxlength="50" id="title" type="text" class="textbox length">
                            </div>
                        </div>
                        
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Short Description</label>
                            </div>
                        </div>
                        <div id="titlediv">
                            <div id="titlewrap" class="boxclass">
                                <textarea name="short_desc" id="short_desc" class="textareabox"><?php echo esc_textarea($customadsItem[0]->customads_short_desc);?></textarea>
                            </div>
                        </div>
                        
                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Code</label>
                            </div>
                        </div>
                        <div id="titlediv">
                            <div id="titlewrap" class="boxclass">
                                <textarea name="code" id="code" class="textareabox requiredbox"><?php echo esc_textarea($customadsItem[0]->customads_code);?></textarea>
                                 <div class="clear"></div>
                                <span class="item_name errormessage" style="display: none;">This Field is Required.</span>
                            </div>
                        </div>
                        
                         <div id="titlediv">
                            <div id="titlewrap">
                                <label>Short Code</label>
                            </div>
                        </div>
                        <div id="titlediv">
                            <div id="titlewrap" class="boxclass">
                                <?php if($customadsItem[0]->customads_short_code!= ''){ ?>
                                <input name="short_code" value='<?php echo esc_textarea($customadsItem[0]->customads_short_code);?>' id="short_code" type="text" class="textbox" readonly>
                                <?php }else{?>
                                <input name="short_code" value='[Customads id=<?php  echo rand(100000, 999999);?>]' id="short_code" type="text" class="textbox" readonly>
                                <?php }?>
                            </div>
                        </div>

                        <div id="titlediv">
                            <div id="titlewrap">
                                <label>Status</label>
                            </div>
                        </div>
                        <div id="titlediv">
                            <div id="titlewrap" class="boxclass">
                                <select name="status">
                                    <option value="0" <?php if($customadsItem[0]->customads_status==0){echo 'selected="selected"';}?>>Un Available</option>
                                    <option value="1" <?php if($customadsItem[0]->customads_status==1){echo 'selected="selected"';}?>>Available</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="postbox-container-1" class="postbox-container">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable">
                            <div id="submitdiv" class="postbox ">
                                <h3 class="hndle"><span>Publish</span></h3>
                                <div class="inside">
                                    <div class="submitbox" id="submitpost">
                                        <div id="major-publishing-actions">
                                            <div id="publishing-action">
                                                <span class="spinner"></span>
                                                <?php 
                                                    if($customadsItem[0]->id)
                                                        $nonceValue = $customadsItem[0]->id;
                                                    else
                                                        $nonceValue = rand(0,10000);
                                                    $nonce = wp_nonce_field( 'mycustomads_'.$nonceValue );?>
                                                <input type="hidden" id="_wpnonce" name="_wpnonce" value="796c7766b1" />
                                                <input name="original_publish" id="original_publish" value="<?php echo $label;?>" type="hidden">
                                                <input name="publish" id="publish" class="button button-primary button-large" value="<?php echo $label;?>" accesskey="p" type="button">
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div><!-- /poststuff -->
        </form>
    </div>
    <div class="clear"></div>
</div>
<div class="clear"></div>

