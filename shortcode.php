<?php 

require_once(BRAINTREE_FILE_PATH.'/lib/Braintree.php');

$option = get_option('braintree_settings');

if(!empty($option)){  

      $marchantid=$option['merchnatid'];

      $publickey=$option['publickey'];

      $Privatekey=$option['Privatekey'];

      $keyoption=$option['braintree_type'];

          if( $keyoption == 'braintree_sendbox'){

              $key='sandbox';

            }else{   $key='Production';   }



    Braintree_Configuration::environment($key);

    Braintree_Configuration::merchantId($marchantid);

    Braintree_Configuration::publicKey($publickey);

    Braintree_Configuration::privateKey($Privatekey);

    } // options not empty

    if('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce'))

    { 

  //post variable

  $fname=$_POST['fname'];

  $lname=$_POST['lanme'];

  $email=$_POST['email'];

  $amount=$_POST['amount'];

  $cardnumber=$_POST['cardnumber'];

  $expirymonth=$_POST['expiry-month'];

  $expiryyear=$_POST['expiry-year'];

  $varificationcode=$_POST['cvv'];

  $userchoice=$_POST['userchoice']; 

    // single time donation

    if($userchoice == 'onetime'){ 

        // user pay only on time       

         

          $result_transaction = Braintree_Transaction::sale(array(

                    'amount' => $amount,

                    'creditCard' => array(

                            'number' => $cardnumber,

                            'expirationMonth' => $expirymonth,

                            'expirationYear' => $expiryyear,

                    ),

                    'customer' => array(

                      "firstName" =>$fname,

                      "lastName" => $lname, 

                      "email"=>$email   

                    ),

                    'options' => array(

                      'storeInVaultOnSuccess' => true

                    )

                  ));         

                if ($result_transaction->success) {

                  echo "We have received your donation. Thank You. <br />

				        Your transaction ID is : " . $result_transaction->transaction->id;                  

                  }
                  // any error occur when transaction not suceess
                  else{
                    echo "<p style='color:red' >Error:</p>";
                    foreach($result_transaction->errors->deepAll() AS $error) {
                             echo '<p style="color:red" >'.($error->code . ": " . $error->message . "\n").'</p>'; 
                         }
                  }

        }

     // monthly reccuring payment   

   if($userchoice == 'monthly'){ 

      

      $result = Braintree_Customer::create(array(

                "firstName" =>$fname,

                "lastName" => $lname, 

                "email"=>$email,  

                "creditCard" => array(

                    "number" => $cardnumber,

                    "expirationMonth" => $expirymonth,

                    "expirationYear" => $expiryyear,

                    "cvv" => $varificationcode          

                )

            ));

      if ($result->success) {

          echo("Success!  Customer ID : " . $result->customer->id . "<br/>");

                  $customer_id=$result->customer->id;

                  $customer = Braintree_Customer::find($customer_id);

                  $payment_method_token = $customer->creditCards[0]->token;                  

                  // recurring donation                   

                  $data=explode(".",$amount);

                           

                    $result_recurring = Braintree_Subscription::create(array(

                        'paymentMethodToken' => $payment_method_token,

                        'planId' => $data[0]

                    ));

                      if ($result_recurring->success) {

                        echo("We have received your donation. Thank You. <br /> Subscription " . $result_recurring->subscription->id . " is " . $result_recurring->subscription->status);

                      }
                       // any error occur when Subscription transaction not suceess
                        else{
                          echo "<p style='color:red' >Error:</p>";
                          foreach($result_transaction->errors->deepAll() AS $error) {
                                   echo '<p style="color:red" >'.($error->code . ": " . $error->message . "\n").'</p>'; 
                               }
                        }

      }

      // any error occur when Subscription user  not create
                        else{
                          echo "<p style='color:red' >Error:</p>";
                          foreach($result->errors->deepAll() AS $error) {
                                   echo '<p style="color:red" >'.($error->code . ": " . $error->message . "\n").'</p>'; 
                               }
                        }       

}// monthly reccuring payment

}// if isset post



?>

<div class="panel panel-default col-md-12">

   <form class="form-horizontal" role="form" action="" method="post">

   <?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>

    <fieldset>

      <legend>Donation Form</legend>

      <div class="form-group" id="monthlypay"><label class="col-sm-6 control-label" for="amount">Select Amount</label>

        <div class="col-sm-6">

        <select name="amount">

         <?php 

           $plans = Braintree_Plan::all();         

         foreach($plans AS $plan){ ?>

            <option value="<?php  echo $plan->price; ?>"><?php echo $plan->name; ?></option>

           <?php } ?>

         </select>

        </div>

      </div>

        <div class="form-group">  

         <label class="col-sm-3 control-label" for="card-number">Type:</label>  

        <div class="col-sm-6">

          <input type="radio" name="userchoice"  value="onetime" checked="checked" />

      <label for="userchoice1">One-time Donation </label>

        <input type="radio" name="userchoice"  value="monthly" />

      <label for="userchoice2">Monthly Recurring</label>

        </div>

      </div>       

      <div class="form-group">

        <label class="col-sm-3 control-label" for="card-number">Card Number</label>

        <div class="col-sm-9">

          <input type="text" class="form-control" name="cardnumber" id="cardnumber" placeholder="Debit/Credit Card Number">

        </div>

      </div>

      <div class="form-group">

        <label class="col-sm-4 control-label" for="expiry-month">Expiration Date</label>

        <div class="col-sm-8">

          <div class="row">

            <div class="col-xs-6">            

                <input type="text" class="form-control" name="expiry-month" id="expiry-month" placeholder="MM"> 

            </div>

            <div class="col-xs-6">

              <input type="text" class="form-control" name="expiry-year" id="expiry-year" placeholder="YY">   

            </div>

          </div>

        </div>

      </div>

      <div class="form-group">

        <label class="col-sm-3 control-label" for="cvv">Card CVV</label>

        <div class="col-sm-9">

          <input type="text" class="form-control" name="cvv" id="cvv" placeholder="Security Code">

        </div>

      </div>

      <div class="form-group">

        <label class="col-sm-3 control-label" for="card-holder-name">First Name</label>

        <div class="col-sm-9">

          <input type="text" class="form-control" name="fname" id="fname" placeholder="First Name">

        </div>

      </div>

      <div class="form-group">

        <label class="col-sm-3 control-label" for="card-holder-name">Last Name</label>

        <div class="col-sm-9">

          <input type="text" class="form-control" name="lname" id="lname" placeholder="Last Name">

        </div>

      </div>

      <div class="form-group">

        <label class="col-sm-3 control-label" for="card-holder-name">Email</label>

        <div class="col-sm-9">

          <input type="text" class="form-control" name="email" id="email" placeholder="Email">

        </div>

      </div>

      <div class="form-group">

        <div class="col-sm-offset-3 col-sm-9">

          <button type="submit" class="btn btn-success" name="send" value="send">Submit Donation</button>

        </div>

      </div>

    </fieldset>

      </form>

</div>