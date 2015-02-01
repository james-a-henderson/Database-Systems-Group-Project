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
$dollar5 = 0;
$price = 1.50;
$machineID = $_POST['machineID'];
$selection = $_POST['selection'];
$pay = $_POST['pay'];
$dollar = $_POST['dollar'];
$quarter = $_POST['quarter'];
$dime = $_POST['dime'];
$nickle = $_POST['nickle'];

		if ($pay == "credit"){
			$totalValue = $price;}
		elseif ($pay == "cash"){ 
			$totalValue = $price;}
		else{}


	//connects to the DB
	$conn = oci_connect('chalvors', 'Player21', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db1.chpc.ndsu.nodak.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');

	//SQL query in here
	$inventoryQuery = 'SELECT INVENTORYID FROM INVENTORY INNER JOIN POP ON INVENTORY.POPID = POP.POPID WHERE MACHINEID = :MACHINEID AND POP.POPID = :SELECTION';

	$stid = oci_parse($conn,$inventoryQuery);
		oci_bind_by_name($stid, ':MACHINEID', $machineID);
		oci_bind_by_name($stid, ':SELECTION', $selection);
	$result = oci_execute($stid,OCI_COMMIT_ON_SUCCESS); 

	$inventoryID = oci_fetch_row($stid);

  

	$query = 'BEGIN SP_TRANSACT(:machineID, :inventoryID, :transactionType, :dollar5, :dollar, :quarter, :dime, :nickle, :totalValue); END;';   

	$stid1 = oci_parse($conn,$query);
		  oci_bind_by_name($stid1, ':machineID', intval($machineID));
		  oci_bind_by_name($stid1, ':inventoryID', intval($inventoryID[0]));
		  oci_bind_by_name($stid1, ':transactionType', $pay);
		  oci_bind_by_name($stid1, ':dollar5', intval($dollar5));
		  oci_bind_by_name($stid1, ':dollar', intval($dollar));
		  oci_bind_by_name($stid1, ':quarter', intval($quarter));
		  oci_bind_by_name($stid1, ':dime', intval($dime));
		  oci_bind_by_name($stid1, ':nickle', intval($nickle));
		  oci_bind_by_name($stid1, ':totalValue', floatval($totalValue));
	$bla = oci_execute($stid1,OCI_COMMIT_ON_SUCCESS);



if($bla)
{
	echo 'Purchase Succeded<br>';
}
else
{
	echo 'Purchase Failed<br>';
}




?>

<p>
<a href="StartPage.php">Click here to go back</a>
</p>
</body>
</html>
