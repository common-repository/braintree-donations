<?php
if (current_user_can('administrator')) {
if(isset($_POST) && !empty($_POST)){
$new_value = array( 
            'merchnatid' =>$_POST['merchnatid'],
            'publickey'=> $_POST['publickey'],
            'Privatekey' => $_POST['Privatekey'],
            'braintree_type'=>$_POST['select_braintree_type'],
            );                 

  $option_name = 'braintree_settings' ;

        if ( get_option( $option_name ) !== false ) {
        	// The option already exists, so we just update it.
            update_option( $option_name, $new_value );
        } else {
                // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
                $deprecated = null;
                $autoload = 'no';        
                add_option( $option_name, $new_value, $deprecated, $autoload );
                }     
}
	?>

<div class="wrap fbviral-main">

      <div id="icon-users" class="icon32"></div>

    <h2>Braintree  Admin Settings</h2>
<div id="login-box">
  <div id="login-box-top">
    <div id="login-box-logo"></div>
  </div>
  <div id="login-box-middle">
    <div id="login-form">
    <form name="" action="" method="post"/>    
    <?php       $option_name = 'braintree_settings' ;
                $option = get_option($option_name);
                $braintree = $option['braintree_type'];                    
                     if($braintree=="braintree_sendbox"){
                        $braintree_sendbox = 'checked=checked';                                         
                        }else{                              
                            $braintree_live = 'checked=checked';                           
                        }
                   ?>
    <div id="name-text">
      <div id="name">Envirnment &nbsp;:</div>
      <div id="radio">
        <input type="radio" name="select_braintree_type" <?php    echo $braintree_sendbox;  ?> value="braintree_sendbox"/>
        Sandbox
        <input type="radio" value="braintree_Production" name="select_braintree_type" <?php echo $braintree_live; ?>/>
        Production </div>
    </div>
    <div id="name-text">
      <div id="name">Merchant Id :</div>
      <div id="text">
        <input type="text" id="textbox" name="merchnatid" value="<?php echo $option['merchnatid']; ?>" />
      </div>
    </div>
    <div id="name-text">
      <div id="name"> Publick Key&nbsp;&nbsp;:</div>
      <div id="text">
        <input type="text" id="textbox" name="publickey" value="<?php echo $option['publickey']; ?>" />
      </div>
    </div>
    <div id="name-text">
      <div id="name"> Private Key  &nbsp;&nbsp;:</div>
      <div id="text">
        <input type="text" id="textbox" name="Privatekey" value="<?php echo $option['Privatekey']; ?>" />
      </div>
    </div>
    <div id="name-text">
      <div id="name"></div>
      <div id="text">
        <input type="submit" id="form-btn" value="Save Settings" />
      </div>
    </div>
    </form>
    </div>
  </div>
</div>
<?php } //is admin ?>