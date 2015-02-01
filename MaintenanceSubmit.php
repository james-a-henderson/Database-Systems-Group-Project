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
$location = $_POST['location'];
$maxQuantity = $_POST['maxQuantity'];
$brand = $_POST['brand'];
$machineID = $_POST['machineID'];

$five = $_POST["five"];
$one = $_POST["one"];
$quarter = $_POST["quarter"];
$dime = $_POST["dime"];
$nickel = $_POST["nickel"];
$balanceID = $_POST["balanceID"];

$query = 'UPDATE PopMachine SET LOCATION = :location, MAXQUANTITY = :maxQuantity, BRAND = :brand WHERE MACHINEID = :machineID';
$stid = oci_parse($conn,$query);
oci_bind_by_name($stid, ':location', $location);
oci_bind_by_name($stid, ':maxQuantity', $maxQuantity);
oci_bind_by_name($stid, ':brand', $brand);
oci_bind_by_name($stid, ':machineID', $machineID);

$s = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

$query2 = 'UPDATE BALANCE SET FIVE = :five, ONE = :one, QUARTER = :quarter, DIME = :dime, NICKEL = :nickel WHERE MACHINEID = :machineID AND BALANCEID = :balanceID';
$stid = oci_parse($conn, $query2);
oci_bind_by_name($stid, ':machineID', $machineID);
oci_bind_by_name($stid, ':five', $five);
oci_bind_by_name($stid, ':one', $one);
oci_bind_by_name($stid, ':quarter', $quarter);
oci_bind_by_name($stid, ':dime', $dime);
oci_bind_by_name($stid, ':nickel', $nickel);
oci_bind_by_name($stid, ':balanceID', $balanceID);
$r = oci_execute($stid, OCI_COMMIT_ON_SUCCESS);

if($r)
{
   echo "Success!<br>";
}
?>

<!Link back to main page>
<a href=StartPage.php> Main Page</a>

</body>
</html> 
