<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Assignment 4</title>

    <link href="bootstrap.min.css" rel="stylesheet">
   <script src='scripts/lib/jquery.min.js'></script>
   <script src='scripts/lib/moment.min.js'></script>
   <script src="scripts/lib/bootstrap.min.js"></script>
   <script src='scripts/jquery.qtip.min.js'></script>

   <style>
.error {color: #FF0000;}
</style>
</head>
<body>

   <nav class="navbar navbar-default" role="navigation">
         <div class="container-fluid">
            <div class="navbar-header">
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                 <span class="sr-only">Toggle navigation</span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
               </button>
               <a class="navbar-brand" href="StartPage.php">Pop Machine</a>
               
             </div>
             <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
               <ul class="nav navbar-nav">
               <li><a href="NewMachine.html"> New Machine</a></li>
               <li><a href="pop.php">Add a Pop</a></li>
               </ul>
         </div>
      </div>
   </nav>
<body>
<?php
//gets machine ID from the main page
$machineID = $_GET['machID'];
?>

<?php
//connects to the DB
$conn = oci_connect('chalvors', 'Player21', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db1.chpc.ndsu.nodak.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');


// define variables and set to empty values
$dollarErr = $quarterErr = $payErr = $dimeErr = $nickleErr = $selectionErr = "";
$dollar = $quarter = $dime = $nickle = 0;
$totalValue = "0.0";
$price = 1.50;
$pay = $selection = "";

//See it the server can 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//Checks to see if a selection was made
	if (empty($_POST["selection"])){
	$selectionErr = "A Selection is Required";
	}else{
	$selection = $_POST["selection"];
	}
	//Checks to see if a payment type was selected
	if (empty($_POST["pay"])) {
     $payErr = "Payment is required";
   } else {
     $pay = $_POST["pay"];
   }
	//Looks to see that a value is present for 
	//the Cash fields if cash was selected.
   if (empty($_POST["dollar"])) {
     $dollarErr = "Enter 0 If No Dollars Used";
    }elseif ($pay == "Credit"){
	$dollarErr = "Do Not Use This Field When Paying With Credit";
	}else {
     $dollar = $_POST["dollar"];
     // check if field only contain numbers
     if (!preg_match("/^[0-9]*$/",$dollar)) {
       $dollarErr = "Only Integers Are Allowed";
     }
   }
  
   if (empty($_POST["quarter"])) {
     $quarterErr = "Enter 0 If No Quarters Used";
   } else {
     $quarter = $_POST["quarter"];
     // check if field only contain numbers
     if (!preg_match("/^[0-9]*$/",$quarter)) {
       $quarterErr = "Only Integers Are Allowed";
     }
   }
	if (empty($_POST["dime"])) {
     $dimeErr = "Enter 0 If No Dimes Used";
   } else {
     $dime = $_POST["dime"];
     // check if field only contain numbers
     if (!preg_match("/^[0-9]*$/",$dime)) {
       $dimeErr = "Only Integers Are Allowed";
     }
   }
	if (empty($_POST["nickle"])) {
     $nickleErr = "Enter 0 If No Dollars Used";
   } else {
     $nickle = test_input($_POST["nickle"]);
     // check if field only contain numbers
     if (!preg_match("/^[0-9]*$/",$nickle)) {
       $nickleErr = "Only Integers Are Allowed";
     }
   }
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<div id="content" style="margin-left: 2%;">
  <h2>Purchasing Form for <?php echo "Machine " . $machineID; ?></h2>
  <form method="post" action="Purchase2.php" style="width: 36%; display:inline-block; vertical-align: top">
  	<table class="table table-hover" style="margin-left: 2%; width:98%;">
     <thead>
     <h4>Machine Contents</h4>
        <tr>
           <td>Name</td>
           <td>Quantity</td>
        </tr>
     </thead>
     <tbody>
  	<?php
  		//SQL query in here
        $query = 'SELECT POP.NAME, INVENTORY.QUANTITY FROM POP INNER JOIN INVENTORY ON POP.POPID = INVENTORY.POPID WHERE INVENTORY.MACHINEID = :machineID';   

  	      $stid = oci_parse($conn,$query);
  		  oci_bind_by_name($stid, ':machineID', $machineID);
  	      oci_execute($stid,OCI_DEFAULT);

        while($row = oci_fetch_array($stid,OCI_ASSOC)) : ?>
           <tr>
  			<td><?=$row['NAME']?></td>
  		    <td><?=$row['QUANTITY']?></td><br>
           </tr>
        <? endwhile; 
   
  	oci_free_statement($stid);
  	oci_close($conn);
  	?>
    </tbody>
    </table>
        
  	<!Allow the user to select a pop>
  	<p><span class="error">* required field.</span></p>
  	Please make a selection:
  	<select name = "selection">
  		<?php

  		//connects to the DB
  		$conn = oci_connect('chalvors', 'Player21', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db1.chpc.ndsu.nodak.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');

  		//SQL query in here
  	      $query = 'SELECT POP.NAME, POP.POPID FROM POP INNER JOIN INVENTORY ON POP.POPID = INVENTORY.POPID WHERE INVENTORY.MACHINEID = :machineID';   

  	      $stid = oci_parse($conn,$query);
  		  oci_bind_by_name($stid, ':machineID', $machineID);
  	      oci_execute($stid,OCI_DEFAULT);

  		 while($row = oci_fetch_array($stid,OCI_ASSOC)) : ?>
           	
  			<?php echo "<option value= " . $row['POPID'] . ">" . $row['NAME'] . "</option>";?>

        	<? endwhile;
   
  		oci_free_statement($stid);
  		oci_close($conn);
  		
  		?>
  	</select>
  		<span class="error">* <?php echo $selectionErr;?></span>
      <br><br><br>
      Payment:
  <div class="radio">
    <label>
      <input type="radio" name="pay" id="optionsRadios1" <?php if (isset($pay) && $pay=="cash") echo "checked";?> value="cash" checked>
      Cash
    </label>
  </div>
  <div class="radio">
    <label>
      <input type="radio" name="pay" id="optionsRadios2" <?php if (isset($pay) && $pay=="credit") echo "checked";?> value="credit">
      Credit
    </label>
  </div>
  <span class="error">* <?php echo $payErr;?></span>
     
      <div class="form-group">
         <label for="dollar">Dollar Bills:</label>
         <input type="text" class="form-control" id="dollar" name="dollar" 
                placeholder="Enter Dollar Bills" value="<?php echo $dollar;?>" style="width: 80%;">
         <span class="error"> <?php echo $dollarErr;?></span>
       </div>
       <div class="form-group">
         <label for="quarter">Quarters:</label>
         <input type="text" class="form-control" id="quarter" name="quarter" 
                placeholder="Enter Quarters" value="<?php echo $quarter;?>" style="width: 80%;">
         <span class="error"> <?php echo $quarterErr;?></span>
       </div>
       <div class="form-group">
         <label for="dime">Dimes:</label>
         <input type="text" class="form-control" id="dime" name="dime" 
                placeholder="Enter Dimes" value="<?php echo $dime;?>" style="width: 80%;">
         <span class="error"> <?php echo $dimeErr;?></span>
       </div>
      <div class="form-group">
         <label for="nickle">Nickels:</label>
         <input type="text" class="form-control" id="nickle" name="nickle" 
                placeholder="Enter Nickels" value="<?php echo $nickle;?>" style="width: 80%;">
         <span class="error"> <?php echo $nickleErr;?></span>
       </div>
  		<input type="hidden" name="machineID" value="<?php echo $machineID?>">

     <input type="submit" name="submit" value="Pay">
  </form>
</div>
</body>
</html>
