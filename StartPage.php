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
               <a class="navbar-brand" href="#">Pop Machine</a>
               
             </div>
             <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
               <ul class="nav navbar-nav">
               <li><a href="NewMachine.html"> New Machine</a></li>
               <li><a href="pop.php">Add a Pop</a></li>
               </ul>
         </div>
      </div>
   </nav>

<table class="table table-hover" style="margin-left: 2%; width:98%;">
   <thead>
      <tr>
         <td>MachineID</td>
         <td>Location</td>
         <td>Brand</td>
         <td>Date Installed</td>
         <td>Max Quantity</td>
      </tr>
   </thead>
   <tbody>
      <?php
      $conn = oci_connect('chalvors', 'Player21', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db1.chpc.ndsu.nodak.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');

      //put your query in here
      $query = 'SELECT * FROM POPMACHINE ORDER BY MACHINEID';   

      $stid = oci_parse($conn,$query);
      oci_execute($stid,OCI_DEFAULT);

      ?>

      <?php while($row = oci_fetch_array($stid,OCI_ASSOC)) : ?>
         <tr>
            <td><?=$row['MACHINEID']?></td>
            <td><?=$row['LOCATION']?></td>
            <td><?=$row['BRAND']?></td>
            <td><?=$row['DATEINSTALLED']?></td>
            <td><?=$row['MAXQUANTITY']?></td>
            <td><a href="Maintenance.php?machID=<?=$row['MACHINEID']?>">Maintenance</a></td>
            <td><a href="Purchase.php?machID=<?=$row['MACHINEID']?>">Purchase Pop</a></td>

         </tr>
      <? endwhile; ?>
      <?php 
            oci_free_statement($stid);
            oci_close($conn);
      ?>
      <tr><td colspan="6"><a href="NewMachine.html">Add New Machine</a></td></tr>
   </tbody>
</table>
</body>
</html>
