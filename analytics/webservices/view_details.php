<?php
require 'connect.php';

    if($con)
    {
		$Latitude = array();
		$Longitude = array();
		$TweetID = array();
		$Tweet = array();
		
		$queryAll = "CALL `01_select_all`()";
		if($resAll = mysqli_query($con,$queryAll)) {
				while($rowAll=mysqli_fetch_array($resAll))
				{
					$Latitude[] = $rowAll['Latitude'];
					$Longitude[] = $rowAll['Longitude'];
					$TweetID[] = $rowAll['TweetName'];
					$Tweet[] = $rowAll['Tweet'];
				}
		}
		
    //echo json_encode($Latitude);
    }
    else {
		$sucessmsg = "connection error : please try after some time ";
		echo $sucessmsg;
    }
?>