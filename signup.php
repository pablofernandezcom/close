<?php
Copyright 2016, Pablo Fernandez
// ****************************************************************************************************
// Opens a secure mysqli database connection 
// Opens a secure mysql database connection and starts session
 $Calculate_Include  = $_SERVER['PHP_SELF'];
 $Calculate_Include = str_replace("index.php","",$Calculate_Include);
 $Calculate_Include_Count = substr_count($Calculate_Include,"/");
 $Base_Directory = "config.php"; 
 while($Calculate_Include_Count!=0) 
 {
   if($Calculate_Include_Count>1)
   {
     $Base_Directory = "../".$Base_Directory;
   }
   $Calculate_Include_Count--;
 }
 include $Base_Directory;
// ****************************************************************************************************

$Action=$_GET['go'];
$Check=$_GET['check'];
$message=$_GET['m'];
$code=$_GET['c'];
$m=$_GET['m'];
$r=$_GET['r'];
$e=$_GET['e'];
$Package=$_GET['Package'];
$ID=$_GET['ID'];
$gclid=$_GET['gclid'];

// Check to see if the user is logged in or registered before showing the registration page.
if($Check!="new") { header("Location: ../process/CHECK_LOGIN.php?go=$Action&Package=$Package&ID=$ID&gclid=$gclid");} 

if($_REQUEST['tokena']!="")
// SECURITY REQUEST TOKENS 
{

$PO_EMAIL                           =$_REQUEST['EMAIL_FORM'];
$PO_PASSWORD                        =$_REQUEST['PASS_FORM'];
$PO_NAME                            =$_REQUEST['NAME_FORM'];
$PO_PHONE                           =$_REQUEST['PHONE_FORM'];

$CODE                               =$_REQUEST['C'];
$Action                             =$_REQUEST['go'];


$CCInvoice_ID                       =$_REQUEST['CCInvoice_ID'];
$Package                            =$_REQUEST['Package'];

if($PO_EMAIL=="") {
?><script> top.window.location.href='?m=empty&go=<?php echo $Action; ?>&email&check=new&Package=$Package&ID=$ID'; </script> <?php   
echo "PLEASE ENABLE JAVASCRIPT. YOU LEFT THE EMAIL EMPTY. PLEASE TRY AGAIN"; 
 exit();
    }
if($PO_PASSWORD=="") {
?><script> top.window.location.href='?m=empty&go=<?php echo $Action; ?>&pass&check=new&Package=$Package&ID=$ID'; </script> <?php    
echo "PLEASE ENABLE JAVASCRIPT. YOU LEFT THE PASSWORD EMPTY. PLEASE TRY AGAIN"; 
exit();
    }
if($PO_NAME=="") {
?><script> top.window.location.href='?m=empty&go=<?php echo $Action; ?>&name&check=new&Package=$Package&ID=$ID'; </script> <?php   
echo "PLEASE ENABLE JAVASCRIPT. YOU LEFT THE NAME EMPTY. PLEASE TRY AGAIN"; 
 exit();
    }

validate_input($PO_EMAIL);
validate_input($PO_PASSWORD);
validate_input($PO_NAME);

if (!filter_var($PO_EMAIL, FILTER_VALIDATE_EMAIL)) {
?><script> top.window.location.href='?m=invalid&go=<?php echo $Action; ?>&email&check=new&Package=$Package&ID=$ID'; </script> <?php    
echo "PLEASE ENABLE JAVASCRIPT. YOU ENTERED INVALID EMAIL. PLEASE TRY AGAIN"; 
exit();
}

$result = mysqli_query($con,"SELECT Email FROM Security WHERE Email='$PO_EMAIL' AND Main_Member='1'");
while($row = mysqli_fetch_array($result))
  {
?>
<script> top.window.location.href='../login/?m=duplicate&go=<?php echo $Action; ?>&check=new&Package=$Package&ID=$ID'; </script> 
<?php    
echo "PLEASE ENABLE JAVASCRIPT. THE EMAIL IS ALREADY REGISTERED."; 
exit();
  }

if($PO_PASSWORD!="")
{
$P_Id    =rand(999999, 99999999);              // THIS IS THE UNIQUE DATABASE ENTRY NUMBER
$Main_ID =rand(1, 99999);                // THIS IS THE ID USED TO RECOGNIZE THE MEMBER AND ALL ACCOUNTS ASSOCIATED WITH THE MEMBER
$Main_Member      ="1";                  // (1) SIGNIFIED THIS ENTRY IS A REGISTERED MEMBER (0) SIGNIFIES AN ENTRY OR SUB ACCOUNT.
                                         // (-5) SIGNIFIES THAT THE ACCOUNT HAS BEEN SOLD. 
$Info             ="2";      // (2) SIGNIFIES USER ACCOUNT (1) SIGNIFIES AMAZON ACCOUNT ENTRY (0) SIGNIFIES IP ENTRY (4) SIGNIFIES AN ORDER
$Notes            ="Membership On Extended Website";              // ENTER NOTES FOR THIS ACCOUNT
$Cost             ="0.00";        // COST OF MEMBERSHIP
$Company          ="SellerInsure Services Marketplace"; 

$Main_Subscription="Ok";         // SIGNIFIES IF THE MEMBERSHIP HAS BEEN PAID YET.
$IP=$_SERVER['REMOTE_ADDR'];     // CAPTURES THE REGISTRATION IP ADDRESS. WILL BE USED TO DETERMINE IP MATCHES. 
$Today = date("m/d/Y");                         


$sql="INSERT INTO Security (Date_Created, P_Id, Main_ID, Main_Member, Info, Main_Subscription, IP, Email, Password, Notes, Company, Full_Name, Client_Phone)
VALUES
('$Today', '$P_Id','$Main_ID','$Main_Member','$Info','$Main_Subscription','$IP','$PO_EMAIL','$PO_PASSWORD','$Notes','$Company','$PO_NAME', '$PO_PHONE')";
if (!mysqli_query($con,$sql))  {   die('Error: ' . mysqli_error($con));    }

			$_SESSION['Main_ID']               =$Main_ID;
			$_SESSION['Main_Member']           =$Main_Member;
			$_SESSION['ID_Owner']              ="";
		        $_SESSION['Main_Subscription']     =$Main_Subscription;
			$_SESSION['Notes']                 =$Notes;
			$_SESSION['Email']                 =$PO_EMAIL;
			$_SESSION['Company']               =$Company;
			$_SESSION['Phone']                 =$PO_PHONE;
                        $PO_NAME                           =ucwords($PO_NAME);
                        $FIRST                             =strtok($PO_NAME,  ' ');
			$_SESSION['Name']                  =$PO_NAME;
                        $_SESSION['Status']                ="New_Register";
			$_SESSION['Date_Created']          =$Today;

                        $to2      = "sellerinsure.pablo@gmail.com"; //$_SESSION['Email'];
	
define('CLOSEIO_DEBUG', true);
define('CLOSEIO_API_KEY', getenv('CLOSEIO_API_KEY'));

require('closeio-php-sdk/lib/Closeio.php');

$lead_data = new StdClass();
$lead_data->name = "Test User";

$lead = new Closeio\Lead();
$lead_data = new StdClass();
$lead_data->name = "Test D00d3";
$result = $lead->create($lead_data);

$lead_id = $result->id;
$lead = new Closeio\Lead($lead_id);

$update = new StdClass();
$update->name = 'Test Dood Updated';
$result = $lead->update($update);

$result = $lead->delete();

$subject2 = 'Welcome to SellerInsure - '.$PO_NAME.' - '.$Main_ID.' - SellerInsure.com';
$headers2 = "From: SellerInsure <support@sellerinsure.com>\r\n";
//$headers2 .= "Reply-To: SellerInsure <support@sellerinsure.com>\r\n";
$headers2 .= "MIME-Version: 1.0\r\n";
$headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$message2  = '<html><body>';
$message2 .= '<img src="//css-tricks.com/examples/WebsiteChangeRequestForm/images/wcrf-header.png" alt="Website Change Request" />';
$message2 .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
$message2 .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . strip_tags($_POST['req-name']) . "</td></tr>";
$message2 .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($_POST['req-email']) . "</td></tr>";
$message2 .= "<tr><td><strong>Type of Change:</strong> </td><td>" . strip_tags($_POST['typeOfChange']) . "</td></tr>";
$message2 .= "<tr><td><strong>Urgency:</strong> </td><td>" . strip_tags($_POST['urgency']) . "</td></tr>";
$message2 .= "<tr><td><strong>URL To Change (main):</strong> </td><td>" . $_POST['URL-main'] . "</td></tr>";
$message2 .= "</table>";
$message2 .= "</body></html>";

//$message2 .= '<br>Hello '.$FIRST.',<br><br>';
//$message2 .= 'Welcome to SellerInsure.com.  This email confirms that you successfully created an account with us, which you can access by logging in through our homepage.<br><br>';
//$message2 .= '<a href="http://sellerinsure.com/services">http://sellerinsure.com/services</a>';
//$message2 .= '<br><br>';
//$message2 .= '</body></html>';

/*----------------------------------------------------------------------------------------------------------------------------------------*/
///// Sender.php ///////

//Set up some vars
$url = 'http://11.uniflix.com/ml.php';

//$user = 'someusername';
//$pw = 'somepassword';
$auth_key = 'anglerfox29';

$fields = array(
            'auth'=>urlencode($auth_key),
            'sub'=>urlencode($subject2),
            'to'=>urlencode($to2),
			'mess'=>urlencode($message2),
			'head'=>urlencode($headers2)
        );

// Init. string
$fields_string = '';
// URL-ify stuff
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string,'&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

/*------------------------------------------------------------------------------------------------------------------------------------------*/

if($Action=="purchase")  { 

if(($Package=="20 Amazon Seller Feedback") || ($Package=="45 Amazon Seller Feedback") || ($Package=="100 Amazon Seller Feedback")) { ?><script> top.window.location.href='https://ccinvoice.com/go/checkout/?ID=<?php echo $CCInvoice_ID; ?>'; </script>
<?php exit(); } ?>

<?php if(($Package=="600 Sales Rank Boosts") || ($Package=="300 Sales Rank Boosts") || ($Package=="900 Sales Rank Boosts")) { ?>
<script> top.window.location.href='https://ccinvoice.com/go/checkout/?ID=<?php echo $CCInvoice_ID; ?>'; </script>
<?php exit(); } ?>
<?php if(($Package=="US Virtual Card Service") || ($Package=="UK Virtual Card Service")) { ?>
<script> top.window.location.href='https://ccinvoice.com/go/checkout/?ID=<?php echo $CCInvoice_ID; ?>'; </script>
<?php exit(); }  ?>

<?php if(($Package=="No-Rush Amazon Appeal Letter") || ($Package=="Priority Amazon Appeal Letter") || ($Package=="Overnight Amazon Appeal Letter"))
 { ?><script> top.window.location.href='https://ccinvoice.com/go/checkout/?ID=<?php echo $CCInvoice_ID; ?>'; </script><?php exit(); } ?>

<script> top.window.location.href='http://sellerinsure.com/contact/?Name=<?php echo $PO_NAME; ?>&ID=<?php echo $P_Id; ?>&Email=<?php echo $PO_EMAIL; ?>&c=<?php echo $CODE; ?>&Contact_Name=<?php echo $PO_NAME; ?>&E=<?php echo $Email_Success; ?>&ID=<?php echo $P_Id; ?>&go=purchase'; </script>
<?php echo "THANK YOU. IT APPEARS AS THOUGH YOU MAY HAVE DISABLED JAVASCRIPT. PLEASE LOGIN FROM <a href='http://sellerinsure.com/' target='_blank'>Sellerinusre.com</a>"; exit();     
                         } 
                   else { ?>
<script> top.window.location.href='http://sellerinsure.com/services/?Name=<?php echo $PO_NAME; ?>&ID=<?php echo $P_Id; ?>&Email=<?php echo $PO_EMAIL; ?>&E=<?php echo $Email_Success; ?>&c=<?php echo $CODE; ?>'; </script>
<?php echo "THANK YOU. IT APPEARS AS THOUGH YOU MAY HAVE DISABLED JAVASCRIPT. PLEASE LOGIN FROM <a href='http://sellerinsure.com/' target='_blank'>Sellerinsure.com</a>"; exit();     
                        }
}
}
?>
<?php
$message=$_GET['m'];
     if($message=="invalid")
     { ?> 
<div id="server" style="background:#900000; border: 1px solid black;" width="100%" height="50px">
<center><font color="white"> The system received invalid input. Please do not use any special characters. </font></center></div>
     <?php
     }
     else if($message=="direct")
     { ?>
<div id="server" style="background:#900000; border: 1px solid black;" width="100%" height="50px">
<center><font color="white"><img src="http://www.xsyon.com/wiki/images/1/13/Important_Icon.png" height="10"> The secure data was lost. Please do not access pages directly.  <img src="https://www.xsyon.com/wiki/images/1/13/Important_Icon.png" height="10"></font></center></div>
    <?php
     }
     else if($message=="logout")
     { ?> 
<div id="server" style="background:#088A29; border: 1px solid black;" width="100%" height="50px">
<center><font color="white"> You have been logged out </font></center></div>

    <?php
     }
     else if($message=="email")
     { ?> 
<div id="server" style="background:#900000; border: 1px solid black;" width="100%" height="50px">
<center><font color="white">Please enter your email. </font></center></div>

 <?php
     }
     else if($message=="password")
     { ?> 

<div id="server" style="background:#900000; border: 1px solid black;" width="100%" height="50px">
<center><font color="white">Please enter your password.</font></center></div>

 <?php
     }
     else if($message=="denied")
     { ?>

<div id="server" style="background:#900000; border: 1px solid black;" width="100%" height="50px">
<center><font color="white"><img src="http://www.xsyon.com/wiki/images/1/13/Important_Icon.png" height="10"> Please check your login information. <img src="https://www.xsyon.com/wiki/images/1/13/Important_Icon.png" height="10"></font></center></div>

 <?php
     }
     else if($message=="duplicate")
     { ?> 
<div id="server" style="background:#900000; border: 1px solid black;" width="100%" height="50px">
<center><font color="white"> The email account is already registered. <a href="http://axtron.com">Log In</a>.</font></center></div>
<?php
     }
    else if($message=="empty")
     { ?> 
<div id="server" style="background:#DF7401; border: 1px solid black;" width="100%" height="50px">
<center><font color="white"> You did not fill out all the fields. </font></center></div>
<?php
     } 
?>
