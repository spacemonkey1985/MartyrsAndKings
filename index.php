<?php

	require 'includes/facebook_sdk.php';
	require_once 'includes/mobile_detect.php';
	
	$noOfGigs = 0;
	$detect = new Mobile_Detect;
	
	function formatUrlsInText($text){
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		preg_match_all($reg_exUrl, $text, $matches);
		$usedPatterns = array();
		foreach($matches[0] as $pattern){
			if(!array_key_exists($pattern, $usedPatterns)){
				$usedPatterns[$pattern]=true;
				$text = str_replace($pattern, "<a href='{$pattern}' rel='nofollow' target='_blank'>{$pattern}</a>", $text);   
			}
		}
		return $text;            
	}
	
	//Facebook stuff - 405413792880935
	date_default_timezone_set('America/Los_Angeles');
	
	$facebook = new Facebook(array(
    	'appId' => '1421350878116358',
    	'secret' => 'e870b82ea606a19e4421b75b17d21879',
    	'cookie' => true
	));
	
	$fql = "SELECT 
				name, pic, start_time, location, description 
			FROM 
				event 
			WHERE 
				eid IN (SELECT eid FROM event_member WHERE uid = 405413792880935) 
			AND
				start_time >= now()
			ORDER BY 
				start_time desc";
	 
	$param = array(
		'method' => 'fql.query',
		'query' => $fql,
		'callback' => ''
	);
	 
	$fqlResult = $facebook->api($param);
	
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
    <head>
        
		<?php include('includes/head.php'); ?>
        
        <script type="text/javascript">
			
			$(document).ready(function() {
				
				var loaded = false;
				
				if($(location).attr('hostname') == 'localhost') {
					var css = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/MartyrsAndKings/css/home_tweet.css';
					var soundcloud = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/MartyrsAndKings/css/soundcloud.css';
					var font = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/MartyrsAndKings/css/font.css';
					var footer = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/MartyrsAndKings/css/footer_tweet.css';
				} else {
					var css = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/css/home_tweet.css';
					var font = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/css/font.css';
					var footer = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/css/footer_tweet.css';
					var soundcloud = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/css/soundcloud.css';
				}
				
				
				$("iframe#twitter-widget-0").waitUntilExists(function(){
					$("iframe#twitter-widget-0").contents().find('head').append($("<link/>", {rel: "stylesheet", href: css, type: "text/css"}));
					$("iframe#twitter-widget-0").contents().find('head').append($("<link/>", {rel: "stylesheet", href: font, type: "text/css"}));
				});
				
				$("iframe#twitter-widget-1").waitUntilExists(function(){
					$("iframe#twitter-widget-1").contents().find('head').append($("<link/>", {rel: "stylesheet", href: footer, type: "text/css"}));
					$("iframe#twitter-widget-1").contents().find('head').append($("<link/>", {rel: "stylesheet", href: font, type: "text/css"}));
				});
				
				$("iframe#tracks").load(function(){
					try {
						
						//var $c = $('iframe#tracks').contents();
					
						//window.alert('Yay');
						
						//if($("iframe#tracks").contents().find('head') ==  null) {
						//	window.alert($("iframe#tracks").contents().find('head'));
						//}
						//$("iframe#tracks").contents().find('head').append($("<link/>", {rel: "stylesheet", href: soundcloud, type: "text/css"}));
						//$("iframe#tracks").contents().find('head').append($("<link/>", {rel: "stylesheet", href: font, type: "text/css"}));
					}
					catch(e) {
						//window.alert(e);
					}
				});
				
				$(".youtube-feed-video-link").click(function(e) {
      				e.preventDefault(); 
					$("#youtube-video").attr("src", "http://www.youtube.com/embed/" + $(this).attr("id") + "?rel=0&autoplay=1");
					$("#overlay").css("display", "inline");
					
				});
				
				$("#overlay").click(function(e) {
      				e.preventDefault(); 
					$("#overlay").css("display", "none");
					$("#youtube-video").attr("src", "");
				});
				
				$("#close").click(function(e) {
      				e.preventDefault(); 
					$("#overlay").css("display", "none");
					$("#youtube-video").attr("src", "");
				});
				
            });
		
		</script>

    </head>

<body>

	<div class="dim" id="overlay">
    	<iframe id="youtube-video" src="" width="800" height="480"></iframe>
        <a href="#‎" id="close" title="Close">&nbsp;</a>
    </div>

	<div class="wrapper">
        <div class="header">
        
        	<img src="images/home.png" alt="Martyrs &amp; Kings" />
            
            <?php include('includes/menu.php'); ?>
            
            <img src="images/tweet.png" alt="Latest Tweet" />
            
            <div class="home-tweet">
            
            	<a class="twitter-timeline" href="https://twitter.com/MartyrsandKings" data-widget-id="449168813125152768" data-tweet-limit="1" data-chrome="noheader nofooter noborders transparent noscrollbar" height="130" width="960"></a>
    			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            
            </div>
            
        </div>
  	</div>
    
    <div class="main">
    	<div class="wrapper">
    		
            <h1>VIDEOS</h1>

			<div class="youtube-feed">
			<?php
			
				$youtube_feed = simplexml_load_file('https://gdata.youtube.com/feeds/api/users/MartyrsAndKings/uploads?max-results=4');
				foreach ($youtube_feed->entry as $entry) :
					$date = strtotime($entry->published);
					
					$namespaces = $entry->getNameSpaces(true);
					$media = $entry->children($namespaces['media']);
					$thumbnail =  $media->group->children($namespaces['media']);
					
					echo '<div class="youtube-feed-item">';
					// Any mobile device (phones or tablets).
					if ( $detect->isMobile() ) {
						echo '<a href="' . $entry->link->attributes()->href . '" target="_blank"><img src="' . $thumbnail->thumbnail->attributes()->url . '" alt="' . $entry->title . '" /></a>';
					} else {
						echo '<a class="youtube-feed-video-link" href="#" id="' . str_replace('&feature=youtube_gdata', '', str_replace('https://www.youtube.com/watch?v=', '', $entry->link->attributes()->href)) . '"><img src="' . $thumbnail->thumbnail->attributes()->url . '" alt="' . $entry->title . '" /></a>';
					}
					echo '<h2>' . str_replace('Martyrs & Kings - ', '', $entry->title) . '</h2>';
					echo formatUrlsInText($entry->content) . '<br /><br />';
					echo '<b>Published ' . date('d M Y', $date) . '</b>';
					
					echo '</div>';
				endforeach;
				
			?>
            </div>
            
            <h1>TRACKS</h1>
            <iframe id="tracks" width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/users/52379063&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=true"></iframe>
            <br /><br />
            <!-- <b>SORRY!</b> We've yet to upload any of our songs or recordings... <b>Worry not</b> we are close to releasing some tracks so to get an update as soon as we do feel free to follow us on <a href="https://soundcloud.com/connect?client_id=b45b1aa10f1ac2941910a7f0d10f8e28&response_type=token&scope=non-expiring%20fast-connect%20purchase%20upload&display=next&redirect_uri=https%3A//soundcloud.com/soundcloud-callback.html" target="_blank" title="Follow us on Soundcloud">Soundcloud</a>.<br /><br /><br /> -->
            
            <h1>GIGS</h1>
            
            <?php
			
				foreach($fqlResult as $keys => $values){
					
					$start_date = date( 'l, F d, Y', strtotime($values['start_time']) );
				 	
					$start_time = ''; 
				 
					echo "<div class='event'>";
				 
						echo "<div class='floatLeft eventImage'>";
							echo "<img src={$values['pic']} width='150px' />";
						echo "</div>";
				 
						echo "<div class='floatLeft'>";
							echo "<div class='eventName'>{$values['name']}</div>";
							echo "<div class='eventInfo'>{$start_date} at {$start_time}</div>";
							echo "<div class='eventInfo'>{$values['location']}</div>";
							echo "<div class='eventInfo'>{$values['description']}</div>";
						echo "</div>";
				 
						echo "<div class='clearBoth'></div>";
					echo "</div>";
					
					$noOfGigs++;
				}
				
			?>
            
            <?php
				if($noOfGigs == 0) {
            		echo '<b>SORRY!</b> Unfortunately there are no venues we are due to play at... <b>However</b> if you\'d like to see us somewhere or even better run a venue and would like to book us please feel free to make a request via our <a href="#" title="Contact us">contact form</a>.<br /><br /><br />';
				}
			?>
            
            <div class="cleaner"></div>
    	</div>
    </div>
    
    <?php include('includes/footer.php'); ?>
    
</body>
</html>
