<!doctype html>
<html lang="de">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>BlueBatteryWeb Day Statistics</title>
	<link rel="stylesheet" href="/css/stylesheet.css"> 
	<meta name="description" content="BlueBattery History">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>

<body>
   <table><tbody><tr>
      <td> <label for="searchdateInput">Filter Datum oder Uhrzeit</label>
			  <input type="text" name="searchdateInput" id="searchdateInput" placeholder="(YYYY-MM-DD HH:MM)" class="form-control" /> </td> <!-- Suchfeld -->
      <td> <form class="form-horizontal" action="daystat_import.php" method="post" name="upload_excel" enctype="multipart/form-data" id="ImportForm">
				 <fieldset>
					 <label class="col-md-4 control-label" for="filebutton">Select File for import</label>
					 <input type="file" name="file" id="file" class="input-large">
					 <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
				 </fieldset>
				</form>
		</td>
   </tr></tbody></table>

		<div id="result"></div> <!-- Ergebnis -->
</body>
</html>

<script>
$(document).ready(function(){

 load_data();

 function load_data(query)
 {
  $.ajax({
   url:"daystat_fetch.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('#result').html(data);
   }
  });
 }
 $('#searchdateInput').keyup(function(){

  var search = $(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   load_data();
  }
 });
});
</script>
