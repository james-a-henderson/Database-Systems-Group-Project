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

$machineID = $_POST['machineID'];
$popID = $_POST['popID'];
$numInMachine = $_POST['numInMachine'];
$newQuantity = $_POST['newQuantity'];
$oldQuantity = $_POST['oldQuantity'];

echo $newQuantity.'<br>';
echo $oldQuantity.'<br>';

$query = 'SELECT MAXQUANTITY FROM POPMACHINE WHERE MACHINEID = :machineID';
$stid = oci_parse($conn,$query);
oci_bind_by_name($stid, ':machineID', $machineID);
oci_execute($stid,OCI_DEFAULT);
while($row = oci_fetch_array($stid,OCI_ASSOC))
{
   $maxQuantity = $row['MAXQUANTITY'];
}

echo $maxQuantity.'<br>';

if ($newQuantity + $numInMachine - $oldQuantity > $maxQuantity OR $newQuantity < 0)
{
   echo 'You are not good';
}
else
{
   echo 'You are good';
   $query = 'UPDATE INVENTORY SET QUANTITY = :newQuantity WHERE POPID = :popID AND MACHINEID = :machineID';
   $stid = oci_parse($conn,$query);
   oci_bind_by_name($stid, ':newQuantity', $newQuantity);
   oci_bind_by_name($stid, ':popID', $popID);
   oci_bind_by_name($stid, ':machineID', $machineID);
   oci_execute($stid, OCI_COMMIT_ON_SUCCESS);
}
?>

<a href="MachineManagePops.php?machID=<?=$machineID?>">Manage Pops</a><br>

</html>
</body>
