<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Author" content="Sreekhar Ale">
  <meta name="Description" content="Spatial project, Data set, Sentiment analysis">
  <meta name="keywords" content="Personal website, Projects, AWS, HTTP, POST, GET, services, Sentiment analysis, paragraph, Sentiment, Area, quarter mile, mile, latitude, longitude"></meta>
  <title>Analytics page</title>
  <link href="" rel="apple-touch-icon" />
  <link href="" rel="icon" />
  <link rel="shortcut icon" href="images/Spatial-Favicon.png" type="image/x-icon">

  <link href="css/index.css" rel="stylesheet"></link>
  <script src="js/jquery.min.js" type="text/javascript" charset="UTF-8"></script>
  <script src="js/proj4.js"></script>
	<script src="js/highmaps.js"></script>
	<script src="js/exporting.js"></script>
	<script src="js/us-co-all.js"></script>
  <script src="js/index.js" type="text/javascript" charset="UTF-8"></script>
</head>

<body>
	<div class="LoadingContainer"></div>
	<div class="container">
		<div class="heading TextAlignCenter BlackText">Overall sentiment and co-ordinates of best area</div>
		<div class="Overalltitle DisplayInline TextAlignCenter">
			<div class="title WhiteText">Overall Sentiment</div>
			<div class="OverallSentiment BlackText"></div>
		</div>
		<div class="Overalltitle DisplayInline TextAlignCenter">
			<div class="title WhiteText">Best Area Latitudes</div>
			<div class="OverallLatitudes BlackText"></div>
		</div>
		<div class="Overalltitle DisplayInline TextAlignCenter">
			<div class="title WhiteText">Best Area Longitudes</div>
			<div class="OverallLongitudes BlackText"></div>
		</div>
		<div class="heading TextAlignCenter BlackText">Set of best area tweets</div>
		<div class="BestTweets"></div>
	</div>
	<div id="ChartContainer"></div>
</body>
</html>