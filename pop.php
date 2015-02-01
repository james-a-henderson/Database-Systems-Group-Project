<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Pop</title>

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
               <li><a href="NewMachine.html">New Machine</a></li>
               <li><a href="pop.php">Add a Pop</a></li>
               </ul>
         </div>
      </div>
   </nav>
<div id="content" style="margin-left: 2%;">
  <form role="form" action="newpop.php" method="post" style="width: 36%; display:inline-block; vertical-align: top">
  <h3>Add new Pop</h3>
     <div class="form-group">
       <label for="name">Name:</label>
       <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" style="width: 80%;">
     </div>
     <div class="form-group">
       <label for="brand">Brand:</label>
       <input type="text" class="form-control" id="brand" name="brand" placeholder="Enter Brand" style="width: 80%;">
     </div>
     <div class="form-group">
       <label for="cost">Cost:</label>
       <input type="text" class="form-control" id="cost" name="cost" placeholder="Enter Cost" style="width: 80%;">
     </div>
     <button type="submit" class="btn btn-default">Submit</button>
  </form>

  <div id="container" style="height: 500px; overflow-y: auto; width: 59%; display:inline-block;">
  <h3>Available Pops</h3>
    <table class="table table-hover" style="position: relative;">
       <thead>
          <tr>
             <td>PopID</td>
             <td>Name</td>
             <td>Brand</td>
             <td>Cost</td>
          </tr>
       </thead>
       <tbody>
          <?php

          //put your query in here
          $conn = oci_connect('chalvors', 'Player21', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=db1.chpc.ndsu.nodak.edu)(Port=1521)))(CONNECT_DATA=(SID=cs)))');
          $query = 'SELECT * FROM POP ORDER BY POPID';   

          $stid = oci_parse($conn,$query);
          oci_execute($stid,OCI_DEFAULT);

          ?>

          <?php while($row = oci_fetch_array($stid,OCI_ASSOC)) : ?>
             <tr>
                <td><?=$row['POPID']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['BRAND']?></td>
                <td><?=$row['COST']?></td>
             </tr>
          <? endwhile; ?>
          <?php 
                oci_free_statement($stid);
                oci_close($conn);
          ?>
       </tbody>
    </table>
  </div>
</div>
</body>
</html>
