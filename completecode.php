<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 4px solid #ccc;
  border-radius: 6px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}

input[type=submit] {
  background-color: #4CAF50;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

.container {
  border-radius: 5px;
  background-color: #EC7063;
  padding: 40px;
}
p    {color: #2ECC71;
      font-size: 180%}
</style>
</head>
<body>
<h1 style="color:red;">Enter Your Details</h1>
<div class="container">
<form method="post" action="dbmovies.php">
<label for="user_name">Name:</label>
<input type="text" name="user_name" placeholder="Enter Your Name" /><br />
<label for="user_movie">Movie Name:</label>
<input type="text" name="user_movie" placeholder="Enter movie Name" /><br />
<label for="user_rating">Ratings: (between 1 and 5):</label>
<input type="number" id="rating" name="user_rating" min="1" max="5">
<input type="submit" class="btn" value="Submit" />
</form>
</div>
<p>This is a Movie Registration and rating form</p>

</body>
</html>

<?php
//dbmovies.php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
	$mysql_host = "localhost";
	$mysql_username = "root";
	$mysql_password = "";
	$mysql_database = "movies";
	
	$u_name = filter_var($_POST["user_name"], FILTER_SANITIZE_STRING); 
	$u_movie = filter_var($_POST["user_movie"], FILTER_SANITIZE_STRING);
	$u_rating = filter_var($_POST["user_rating"], FILTER_SANITIZE_NUMBER_INT);

	if (empty($u_name)){
		die("Please enter your name");
	}
	if (empty($u_movie)){
		die("Please enter movie name");
	}
		
	if (empty($u_rating)){
		die("Please enter rating");
	}	


	$mysqli = new mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database);
	

	if ($mysqli->connect_error) 
	{
		die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}	
	
	$statement = $mysqli->prepare("INSERT INTO movies (user_name, user_movie, user_rating) VALUES(?, ?, ?)"); 

	$statement->bind_param('ssi', $u_name, $u_movie, $u_rating);
	
	if($statement->execute()){
		print "Hello " . $u_name . "!, your RATINGS has been saved!<br><br>";
		echo "  And " . $u_movie . " , is RATED average by other users!!!!<br><br>";
	}else{
		print $mysqli->error; 
	}
		
	$query="select user_movie,avg(user_rating)as `avguser_rating` from movies GROUP BY user_movie";
	$res=mysqli_query($mysqli, $query);
	while($data=mysqli_fetch_array($res))
	{
	
	echo"  Average RATINGS of ".$data['user_movie']." is $ ".$data['avguser_rating'];
	echo"<br/>";
	}
	echo"<br/>";
	echo"  THIS PAGE WILL BE AUTOMATICALLY REDIRECTED IN 30 SECONDS ";
     
	 $url = $_SERVER['HTTP_REFERER'];
	 echo'<meta http-equiv="refresh" content="30;URL=' .$url . '">';
	
	
}
?>
