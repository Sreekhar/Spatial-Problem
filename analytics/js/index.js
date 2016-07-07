$(document).ready(function(){
	hideInitialContainers();
	getOverallSentimentAnalysis();
});

function getOverallSentimentAnalysis() {
$.getJSON("webservices/final_positions.php", function(json) {				 
		OverallSentimentAnalysis(json);
	});	
}

function OverallSentimentAnalysis(json) {
	$(".OverallSentiment").html(json[0]);
	var oOverallLatitudes = $(".OverallLatitudes").empty()[0];
	var oOverallLongitudes = $(".OverallLongitudes").empty()[0];
	var oBestTweets = $(".BestTweets").empty()[0];
	createNewHTMLElement("span",null,null,null,null,null,oOverallLatitudes,null,"Latitudes are "+json[1]+" and "+json[2],null,null);
	createNewHTMLElement("span",null,null,null,null,null,oOverallLongitudes,null,"Latitudes are "+json[3]+" and "+json[4],null,null);
	var oBestTweetsTitle = createNewHTMLElement("div",null,"Overalltitle DisplayInline TextAlignCenter",null,null,null,oBestTweets,null,null,null,null);
	createNewHTMLElement("div",null,"newtitle titleLat WhiteText",null,null,null,oBestTweetsTitle,null,"LATITUDES",null,null);
	createNewHTMLElement("div",null,"newtitle titleLong WhiteText",null,null,null,oBestTweetsTitle,null,"LONGITUDES",null,null);
	createNewHTMLElement("div",null,"newtitle titleID WhiteText",null,null,null,oBestTweetsTitle,null,"TWEET ID",null,null);
	createNewHTMLElement("div",null,"newtitle titleTweet WhiteText",null,null,null,oBestTweetsTitle,null,"TWEET",null,null);
	var oTweetDetails = createNewHTMLElement("div",null,"TweetDetails",null,null,null,oBestTweets,null,null,null,null);
	var oOverflowTweetDetails = createNewHTMLElement("div",null,"OverflowTweetDetails",null,null,null,oTweetDetails,null,null,null,null);
	for(var iCount=0; iCount < json[5].length;iCount++) {
		var oIndividualTweetDetails = createNewHTMLElement("div",null,"Overalltitle DisplayInline TextAlignCenter",null,null,null,oOverflowTweetDetails,null,null,null,null);
		createNewHTMLElement("div",null,"titleLat TweetStyle BlackText",null,null,null,oIndividualTweetDetails,null,json[5][iCount],null,null);
		createNewHTMLElement("div",null,"titleLong TweetStyle BlackText",null,null,null,oIndividualTweetDetails,null,json[6][iCount],null,null);
		createNewHTMLElement("div",null,"titleID TweetStyle BlackText",null,null,null,oIndividualTweetDetails,null,json[7][iCount],null,null);
		createNewHTMLElement("div",null,"titleTweet TweetStyle BlackText",null,null,null,oIndividualTweetDetails,null,json[8][iCount],null,null);
	}
	
	$('.LoadingContainer').hide();
	$('.container').show();
}

function createNewHTMLElement(sEleType, sId, sClass, shref, sSrc, sTitle, oParent, sDownload, oInnerHTML,onClickEvent,functionName) {
	var oDiv = document.getElementById(sId);
	if (oDiv == null) {
		oDiv = document.createElement(sEleType);
		if (sClass) {
			oDiv.setAttribute("class", sClass); // Assign class name to the div element
		}
		if (sId) {
			oDiv.setAttribute("id", sId); // Assign ID to the div element
		}
		if (shref) {
			oDiv.setAttribute("href", shref);
		}
		if (sSrc) {
			oDiv.setAttribute("src", sSrc);// Assign src attribute to the element
		}
		if (sTitle) {
			oDiv.setAttribute("title", sTitle); // Insert title into the div
		}
		if (sDownload) {
			oDiv.setAttribute("download", sDownload); // Assign target attribute to the element
		}
		if (oParent) {
			oParent.appendChild(oDiv); // append the div to the required parent at last
		}
		if (oInnerHTML) {
			oDiv.innerHTML = oInnerHTML; // Insert inner HTML into the div
		}
		if(onClickEvent){
			oDiv.setAttribute("onclick",functionName); // setting onclick function attribute with function name.
		}
	}
	return oDiv;
}

function hideInitialContainers() {
	$('.container').hide();
}

$(function () {

    var H = Highcharts,
        map = H.maps['countries/us/us-co-all'],
        chart;

    $.getJSON('webservices/final_positions.php', function (json) {
        var data = [];
		for(var iCount=0; iCount < json[9].length;iCount++) {
			data.push({
				"lat": (json[10][iCount]+json[11][iCount])/2,
				"lon": (json[12][iCount]+json[13][iCount])/2,
				"Size": json[9][iCount]
			});
		}

        $('#ChartContainer').highcharts('Map', {

            title: {
                text: 'Map for colorado state'
            },

			mapNavigation: {
					enabled: true
			},
				
            tooltip: {
                pointFormat: '{point.capital}, {point.parentState}<br>' +
                    'Lat: {point.lat}<br>' +
                    'Lon: {point.lon}<br>' +
                    'Size: {point.Size}'
            },

            xAxis: {
                crosshair: {
                    zIndex: 5,
                    dashStyle: 'dot',
                    snap: false,
                    color: 'gray'
                }
            },

            yAxis: {
                crosshair: {
                    zIndex: 5,
                    dashStyle: 'dot',
                    snap: false,
                    color: 'gray'
                }
            },

            series: [{
                name: 'Basemap',
                mapData: map,
                borderColor: '#606060',
                nullColor: 'rgba(200, 200, 200, 0.2)',
                showInLegend: false
            }, {
                name: 'Separators',
                type: 'mapline',
                data: H.geojson(map, 'mapline'),
                color: '#101010',
                enableMouseTracking: false,
                showInLegend: false
            }, {
                type: 'mapbubble',
                name: 'Only Boulder',
                data: data,
                maxSize: '12%',
                color: H.getOptions().colors[0]
            }]
        });

        chart = $('#ChartContainer').highcharts();
    });
});