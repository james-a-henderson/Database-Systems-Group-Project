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
$conn = oci_connect('chalvors', 'Player21', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db1.chpc.ndsu.nodak.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
$machineID = $_GET['machID'];


//get and display the maximum quantity in the machine
$query = 'SELECT MAXQUANTITY FROM POPMACHINE WHERE MACHINEID = :machineID';
$stid = oci_parse($conn,$query);
oci_bind_by_name($stid, ':machineID', $machineID);
oci_execute($stid,OCI_DEFAULT);
while($row = oci_fetch_array($stid,OCI_ASSOC))
{
   $maxQuantity = $row['MAXQUANTITY'];
}

//select the rows from inventory that corrispond to the selected machine
$query = 'SELECT * FROM INVENTORY WHERE machineID = :machineID';
$stid = oci_parse($conn, $query);
oci_bind_by_name($stid, ':machineID', $machineID);
oci_execute($stid,OCI_DEFAULT);

//count the number of pops in the machine
$numInMachine = 0;
while($row = oci_fetch_array($stid,OCI_ASSOC))
{
   $numInMachine += $row['QUANTITY'];
}
?>
<h3 style="margin-left: 2%;">Pops in Machine</h3>
<?php echo '<p style="margin-left: 3%;">' . 'Maximum quantity in machine: '.$maxQuantity . '</p>'; ?>
<table class="table table-hover" style="margin-left: 2%; width:98%;">
   <thead>
   <tr>
      <td>PopID</td>
      <td>Quantity</td>
      <td>Pop Name</td>
      <td>Brand</td>
      <td>Cost</td>
      <td></td>
      <td>New Inventory Amount</td>
   </tr>
</thead>
<tbody>
<?php

$query = 'SELECT * FROM INVENTORY INNER JOIN POP ON INVENTORY.POPID = POP.POPID WHERE machineID = :machineID';
$stid = oci_parse($conn, $query);
oci_bind_by_name($stid, ':machineID', $machineID);
oci_execute($stid,OCI_DEFAULT);

while($row = oci_fetch_array($stid,OCI_ASSOC)) : ?>
   <tr>
      <td><?=$row['POPID']?></td>
      <td><?=$row['QUANTITY']?></td>
      <td><?=$row['NAME']?></td>
      <td><?=$row['BRAND']?></td>
      <td><?=$row['COST']?></td>
      <td>
         <form action = "RemovePopFromMachine.php" method="post">
         <input type="hidden" name="machineID" value="<?php echo $machineID?>" />
         <input type="hidden" name="popID" value="<?php echo $row['POPID']?>" />
         <input type="submit" value="Remove From Machine"/> 
         </form></td>

      <td>
         <form action = "AddMore.php" method="post">
         <input type="hidden" name="machineID" value="<?php echo $machineID?>" />
         <input type="hidden" name="popID" value="<?php echo $row['POPID']?>" />
         <input type="hidden" name="numInMachine" value="<?php echo $numInMachine?>" />
         <input type="hidden" name="oldQuantity" value="<?php echo $row['QUANTITY']?>" />
         <input type="text" name="newQuantity" value="<?php echo $row['QUANTITY']?>" />
         <input type="submit" value="Add or Subtract pops"/>
         </form></td>
   </tr>
<? 

endwhile; ?>
</tbody>
</table>
<br><br>

<table class="table table-hover" style="margin-left: 2%; width:98%;">
   <thead>
   <tr>
      <td>PopID</td>
      <td>Pop Name</td>
      <td>Brand</td>
      <td>Cost</td>
      <td></td>
   </tr>
   </thead>
   <tbody>
<?php
$query = 'SELECT POP.POPID, POP.NAME, POP.BRAND, POP.COST FROM POP WHERE NOT EXISTS(SELECT POPID FROM INVENTORY WHERE POP.POPID = INVENTORY.POPID) ORDER BY POPID';
$stid = oci_parse($conn, $query);
oci_execute($stid,OCI_DEFAULT);
while($row = oci_fetch_array($stid,OCI_ASSOC)):?>
   <tr>
      <td><?=$row['POPID']?></td>
      <td><?=$row['NAME']?></td>
      <td><?=$row['BRAND']?></td>
      <td><?=$row['COST']?></td>
      <td>
         <form action = "AddNewPopToMachine.php" method="post">
         <input type="hidden" name="machineID" value="<?php echo $machineID?>" />
         <input type="hidden" name="popID" value="<?php echo $row['POPID']?>" />
         <input type="hidden" name="numInMachine" value="<?php echo $numInMachine?>" />
         <input type="text" name="quantity"/>
         <input type="submit" value="Add New Pop To Machine"/></form></td>
   </tr>
<? endwhile;?>
</tbody>

</html>
</body>
