<?php

function oauth($redirurl){
		$athlete = null;
			$post_data = array(
				'client_id' => '29380',
				'client_secret' => 'c3ca1962106f77f7adfc5c5053ac2b253639d207',
				'code' => '2cd9e8ba72a6756668f2195d8efa8559d27462ca'
			);
			$options = array(
				'http' => array(
					'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'  => 'POST',
					'content' => http_build_query($post_data),
				),
			);			
			
			$context  = stream_context_create($options);
			
			$result = file_get_contents('https://www.strava.com/oauth/token', false, $context);
			
			$token = json_decode($result)->access_token;
			if($token != null){
				$_SESSION['stravatoken'] = $token;
				header('Location: '.$redirurl);
			} 	
		
	}

	
	function getAthlete(){
		$strava_array = json_decode(file_get_contents('http://www.strava.com/api/v3/athlete?access_token='.$_SESSION['stravatoken']));
	    
        //print_r($strava_array);
        echo '<p>First Name: '.$strava_array->firstname.'</p>';
        echo '<p>Last Name: '.$strava_array->lastname.'</p>';
	}
	
	function getLastActivity() {
        $activity_array = json_decode(file_get_contents('http://www.strava.com/api/v3/activities?access_token='.$_SESSION['stravatoken']));	
        
        $activity_array = $activity_array[0];
        //print_r($activity_array);
        
        echo '<h1>My Latest Activity</h1>';
        echo '<h2>"'.$activity_array->name.'"</h2>';
        echo '<p id="type">'.$activity_array->type.'</p>';     
        echo '<p>'.metersToMiles($activity_array->distance).' miles</p>';
        echo '<p>Elapsed Time: '.secondsToMinutes($activity_array->elapsed_time).' minutes</p>';        
        echo '<p>Elevation Gain: '.metersToFeet($activity_array->total_elevation_gain).' feet</p>';
	}
	
	function metersToMiles($meters) {
	   return number_format($meters * 0.000621371,2);
	}
	
	function metersToFeet($meters) {
	   return number_format($meters * 3.28084,2);
	}
	
	function secondsToMinutes($seconds) {
	   return number_format($seconds / 60,2);
	}
	
	function getYearlyTotals() {
	
	}

?>

<!DOCTYPE html>
<html lang="en">
  <title>Projects</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
    
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
    .row.content {height: 450px}
    
    /* Set gray background color and 100% height */
    .sidenav {
      padding-top: 20px;
      background-color: #f1f1f1;
      height: 100%;
    }
    
    /* Set black background color, white text and some padding */
    footer {
      background-color: #555;
      color: white;
      padding: 15px;
    }
    
    /* On small screens, set height to 'auto' for sidenav and grid */
    @media screen and (max-width: 767px) {
      .sidenav {
        height: auto;
        padding: 15px;
      }
      .row.content {height:auto;} 
    }
  </style>
</head>
<body align="center">

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Alex's Webpage</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="#">Home</a></li>
        <li><a href="about.html">About</a></li>
        <li class="active"><a href="projects.php">Projects</a></li>
        <li><a href="contact.html">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>


<table align="center">
   <tbody>
      <tr>
         <td align="center">

            <?
                oauth("");  
                //getAthlete(); 
                getLastActivity();
            ?>
            
            
            <script>
            // display run/ride image
            var type = document.getElementById("type").innerHTML;
            var img = document.createElement("img");
            
                if (type.trim().toLowerCase() === "run") {
                    img.src = "ghost10.jpeg"
                    img.width = 100;
                    img.height = 100;
                } else {
                    img.src = "aeros.jpg"
                    img.width = 200;
                    img.height = 100;
                }
                  
                document.body.appendChild(img);
            </script> 

        </td>
    </tr>
  </tbody>
</table>

</body>
</html>