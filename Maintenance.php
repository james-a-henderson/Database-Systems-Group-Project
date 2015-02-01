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

<?php
//gets machine ID from main page
$machID = $_GET['machID'];
$conn = oci_connect('chalvors', 'Player21', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db1.chpc.ndsu.nodak.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
$query = 'SELECT * FROM POPMACHINE WHERE MACHINEID = '.$machID;
$stid = oci_parse($conn,$query);
oci_execute($stid,OCI_DEFAULT);

while($row = oci_fetch_array($stid,OCI_ASSOC))
{
   $location = $row['LOCATION'];
   $maxQuantity = $row['MAXQUANTITY'];
   $brand = $row['BRAND'];
}

$query = 'SELECT * FROM BALANCE where MACHINEID = :machineID';   

	          $stid = oci_parse($conn,$query);
	          oci_bind_by_name($stid, ':machineID', $machID);
	          oci_execute($stid,OCI_DEFAULT);

while($row = oci_fetch_array($stid,OCI_ASSOC))
{
   $five = $row['FIVE'];
   $one = $row['ONE'];
   $quarter = $row['QUARTER'];
   $dime = $row['DIME'];
   $nickel = $row['NICKEL'];
   $balanceID = $row['BALANCEID'];
}

;?>
<div id="content" style="margin-left: 2%;">
	<form role="form" action="MaintenanceSubmit.php" method="post" style="width: 36%; display:inline-block; vertical-align: top">
	<h3>Update Machine</h3>
	   <div class="form-group">
	     <label for="location">Location:</label>
	     <input type="text" class="form-control" id="location" name="location" value="<?php echo $location?>" style="width: 80%;">
	      </div>
	   <div class="form-group">
	     <label for="brand">Brand:</label>
	     <input type="text" class="form-control" id="brand" name="brand" value="<?php echo $brand?>" style="width: 80%;">
	   </div>
	   <div class="form-group">
	     <label for="location">Max Quantity:</label>
	     <input type="text" class="form-control" id="max" name="max" value="<?php echo $maxQuantity?>" style="width: 80%;">
	   </div>

	   <div class="form-group">
	     <label for="location">Five Dollar Bills:</label>
	     <input type="text" class="form-control" id="five" name="five" value="<?php echo $five?>" style="width: 25%;">
	   </div>
	   <div class="form-group">
	     <label for="location">One Dollar Bills:</label>
	     <input type="text" class="form-control" id="one" name="one" value="<?php echo $one?>" style="width: 25%;">
	   </div>
	   <div class="form-group">
	     <label for="location">Quarters:</label>
	     <input type="text" class="form-control" id="quarter" name="quarter" value="<?php echo $quarter?>" style="width: 25%;">
	   </div>
	   <div class="form-group">
	     <label for="location">Dimes:</label>
	     <input type="text" class="form-control" id="dime" name="dime" value="<?php echo $dime?>" style="width: 25%;">
	   </div>
	   <div class="form-group">
	     <label for="location">Nickels:</label>
	     <input type="text" class="form-control" id="nickel" name="nickel" value="<?php echo $nickel?>" style="width: 25%;">
	   </div>
	   <input type='hidden' name='machineID' value="<?php echo $machID?>" />
	   <input type='hidden' name='balanceID' value="<?php echo $balanceID?>" />
	   <button type="submit" class="btn btn-default">Submit</button>
	</form>

	<div id="container" style="height:400px; overflow-y: auto; width: 59%; display:inline-block;">
	<h3>Machine Balance</h3>
	    <table class="table table-hover" style="position: relative;">
	       <thead>
	          <tr>
	             <td>$5</td>
	             <td>$1</td>
	             <td>$0.25</td>
	             <td>$0.10</td>
	             <td>$0.05</td>
	          </tr>
	       </thead>
	       <tbody>
	          <?php

	          //put your query in here
	          $conn = oci_connect('chalvors', 'Player21', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db1.chpc.ndsu.nodak.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
	          $query = 'SELECT * FROM BALANCE where MACHINEID = :machineID';   

	          $stid = oci_parse($conn,$query);
	          oci_bind_by_name($stid, ':machineID', $machID);
	          oci_execute($stid,OCI_DEFAULT);

	          ?>

	          <?php while($row = oci_fetch_array($stid,OCI_ASSOC)) : ?>
	             <tr>
	                <td><?=$row['FIVE']?></td>
	                <td><?=$row['ONE']?></td>
	                <td><?=$row['QUARTER']?></td>
	                <td><?=$row['DIME']?></td>
	                <td><?=$row['NICKEL']?></td>
	             </tr>
	          <? endwhile; ?>

	          <?php
	          $query = 'SELECT (FIVE*5)+ONE+(QUARTER*.25)+(DIME*.10)+(NICKEL*.05) AS TOTAL FROM BALANCE where MACHINEID = :machineID';   
	          $stid = oci_parse($conn,$query);
	          oci_bind_by_name($stid, ':machineID', $machID);
	          oci_execute($stid,OCI_DEFAULT);
	          ?>
	          <?php while($row = oci_fetch_array($stid,OCI_ASSOC)) : ?>
	             <tr>
	                <td colspan="5">Total: $<?=$row['TOTAL']?></td>
	              </tr>
	          <? endwhile; ?>

	          <?php 
	                oci_free_statement($stid);
	                oci_close($conn);
	          ?>
	       </tbody>
	    </table>
	</div>
	<a href="transactionhistory.php?machID=<?=$machID?>">Transaction History</a><br>

<a href="MachineManagePops.php?machID=<?=$machID?>">Manage Pops</a><br>
</div>

</body>
</html> 
