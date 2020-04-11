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
