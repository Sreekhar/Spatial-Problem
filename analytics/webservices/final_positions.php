<?php
//for connection credentials
require 'connect.php';

//for viewing all the details from the CSV file
include 'view_details.php';

    if($con)
    {
		//Variable declaration
		$Dist_Latitude = array();
		$Dist_Longitude = array();
		$Final_Latitude = array();
		$Final_Longitude = array();
		$Final_Area_Latitude = array();
		$Final_Area_Longitude = array();
		$Final_Area_TweetID = array();
		$Final_Area_Tweet = array();

		$Distinct_positions = 0;
		$Threshold_count = 0;
		//Hardcoding the values
		// Link provided here ---> https://www2.usgs.gov/faq/categories/9794/3022
		$Area_latitude = floatval((1/4)*(1/69));
		$Area_longitude = floatval((1/4)*(1/54.6));
		
		//Steps for selecting only the distinct coordinates
		$queryDistinct = "CALL `02_select_distinct`()";
		$conDistinct = mysqli_connect($server_name,$username_db,$password_db,$db_name);
		if($resDistinct = mysqli_query($conDistinct,$queryDistinct)) {
				while($rowDistinct = mysqli_fetch_array($resDistinct))
				{
					$Dist_Latitude[] = $rowDistinct['Latitude'];
					$Dist_Longitude[] = $rowDistinct['Longitude'];
				}
		}
		
		//Steps for considering the sentiments with a threshould of minimum four tweets
		for($iCount = 0; $iCount < count($Dist_Latitude); $iCount++) {
			for($jCount = 0; $jCount < count($Latitude); $jCount++) {
				if(($Dist_Latitude[$iCount] == $Latitude[$jCount]) && ($Dist_Longitude[$iCount] == $Longitude[$jCount])) {
					$Threshold_count++;
					if($Threshold_count >= 4) {
						$Final_Latitude[$Distinct_positions] = floatval($Dist_Latitude[$iCount]);
						$Final_Longitude[$Distinct_positions] = floatval($Dist_Longitude[$iCount]);
						$Distinct_positions++;
						break;
					}
				}
			}
			$Threshold_count = 0;
		}
		
		//Finding the boudaries of the area
		$queryStats = "CALL `03_max_min`()";
		$conStats = mysqli_connect($server_name,$username_db,$password_db,$db_name);
		if($resStats = mysqli_query($conStats,$queryStats)) {
				while($rowStats = mysqli_fetch_array($resStats))
				{
					$max_latitude = floatval($rowStats['max_latitude']);
					$max_longitude = floatval($rowStats['max_longitude']);
					$min_latitude = floatval($rowStats['min_latitude']);
					$min_longitude = floatval($rowStats['min_longitude']);
				}
		}
	$SentimentInfo = array();
	
	//Function to check whether the coordinates lie in the quarter mile by quarter mile box and also finding the total number of sentiments in that particular quarter mile box
	function checkCoordinates($minLatitude,$minLongitude,$maxLatitude,$maxLongitude,$Final_Latitude,$Final_Longitude,$Latitude,$Longitude)
	{
		$SentimentNumber = 0;
		for($iCount = 0; $iCount < count($Final_Latitude); $iCount++) {
			if(($minLatitude <= $Final_Latitude[$iCount] && $Final_Latitude[$iCount] <= $maxLatitude) && ($minLongitude <= $Final_Longitude[$iCount] && $Final_Longitude[$iCount] <= $maxLongitude)) { 
				for($jCount = 0; $jCount < count($Latitude); $jCount++) {
					if(($Final_Latitude[$iCount] == $Latitude[$jCount]) && ($Final_Longitude[$iCount] == $Longitude[$jCount])) { 
						$SentimentNumber++;					
					}
				}	
			}
		}
		return $SentimentNumber;
	}	
	
	$temp_latitude = $min_latitude;
	$temp_longitude = $min_longitude;
		
		//Finding out Sentiment information of each quarter mile area box
		while($temp_latitude <= $max_latitude) {
			while($temp_longitude <= $max_longitude) {
				$SentimentInfo['latitude_one'][] = $temp_latitude;
				$SentimentInfo['longitude_one'][] = $temp_longitude;
				$SentimentInfo['latitude_two'][] = $temp_latitude + $Area_latitude;
				$SentimentInfo['longitude_two'][] = $temp_longitude + $Area_longitude;
				$SentimentInfo['SentimentNumber'][] = checkCoordinates($temp_latitude,$temp_longitude,$temp_latitude + $Area_latitude,$temp_longitude + $Area_longitude,$Final_Latitude,$Final_Longitude,$Latitude,$Longitude);
				$temp_longitude = $temp_longitude + $Area_longitude;
			}
			$temp_longitude = $min_longitude;
			$temp_latitude = $temp_latitude + $Area_latitude;
		}
	
	//For testing.....
	// $test_number = 0;
	// for($iCount = 0; $iCount < count($SentimentInfo['SentimentNumber']); $iCount++) {
		// $test_number = $test_number + $SentimentInfo['SentimentNumber'][$iCount];
	// }
	
	//Function to find the maximum value along with it's index value
	function FindMax($CheckArray){ 
		$maxvalue = max($CheckArray); 
		while(list($key,$value) = each($CheckArray)){ 
			if($value == $maxvalue)
				$maxindex = $key; 
		} 
		return array("maxvalue"=>$maxvalue,"maxindex"=>$maxindex); 
	} 
	
	//Procedure to find the maximum Sentiment from an area
	$max_array = array();
	$max_array = FindMax($SentimentInfo['SentimentNumber']);
	$maxvalue = $max_array['maxvalue'];
	$maxindex = $max_array['maxindex'];

	//collecting all the information and sentiment analysis on the best area which was found from the previous steps
	for($iCount = 0; $iCount < count($Latitude); $iCount++) {
		if(($SentimentInfo['latitude_one'][$maxindex] <= $Latitude[$iCount] && $Latitude[$iCount] <= $SentimentInfo['latitude_two'][$maxindex]) 
			&& ($SentimentInfo['longitude_one'][$maxindex] <= $Longitude[$iCount] && $Longitude[$iCount] <= $SentimentInfo['longitude_two'][$maxindex])) { 
			$Final_Area_Latitude[] = $Latitude[$iCount];
			$Final_Area_Longitude[] = $Longitude[$iCount];
			$Final_Area_TweetID[] = $TweetID[$iCount];
			$Final_Area_Tweet[] = $Tweet[$iCount];
		}
	}	

	//Returning the array to the front end
	$return = array();
	array_push($return,$SentimentInfo['SentimentNumber'][$maxindex]);
	array_push($return,$SentimentInfo['latitude_one'][$maxindex]);
	array_push($return,$SentimentInfo['latitude_two'][$maxindex]);
	array_push($return,$SentimentInfo['longitude_one'][$maxindex]);
	array_push($return,$SentimentInfo['longitude_two'][$maxindex]);
	array_push($return,$Final_Area_Latitude);
	array_push($return,$Final_Area_Longitude);
	array_push($return,$Final_Area_TweetID);
	array_push($return,$Final_Area_Tweet);
	array_push($return,$SentimentInfo['SentimentNumber']);
	array_push($return,$SentimentInfo['latitude_one']);
	array_push($return,$SentimentInfo['latitude_two']);
	array_push($return,$SentimentInfo['longitude_one']);
	array_push($return,$SentimentInfo['longitude_two']);
	
	echo json_encode($return);
	//Closing the connection
    mysqli_close($con);    
    }
    else {
		$sucessmsg = "connection error : please try after some time ";
		echo $sucessmsg;
    }
?>