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
				
				if($(location).attr('hostname') == 'localhost') {
					var css = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/MartyrsAndKings/css/home_tweet.css';
					var font = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/MartyrsAndKings/css/font.css';
					var footer = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/MartyrsAndKings/css/footer_tweet.css';
				} else {
					var css = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/css/home_tweet.css';
					var font = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/css/font.css';
					var footer = $(location).attr('protocol') + '//' +  $(location).attr('hostname') + '/css/footer_tweet.css';
				}
				
				$("iframe#twitter-widget-0").waitUntilExists(function(){
					$("iframe#twitter-widget-0").contents().find('head').append($("<link/>", {rel: "stylesheet", href: footer, type: "text/css"}));
					$("iframe#twitter-widget-0").contents().find('head').append($("<link/>", {rel: "stylesheet", href: font, type: "text/css"}));
				});
				
            });
		
		</script>

    </head>

<body>

	<div class="wrapper">
        <div class="header">
        
        	<div class="main-page-image">
        		<img src="images/home.png" alt="Martyrs &amp; Kings" />
            </div>
            
            <?php include('includes/menu.php'); ?>
            
        </div>
  	</div>
    
    <div class="main">
    	<div class="wrapper">
        
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
            
            
            <h1>TRACKS</h1>
            <iframe id="tracks" width="100%" height="450" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/users/52379063&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_artwork=true"></iframe>
            <br /><br /><br />
            <!-- <h1>UNDER CONSTRUCTION</h1>
            <b>SORRY!</b> This page isn't quite ready yet... <b>Wait a second!</b> We are close to finishing our site so please come back again soon. In the meantime feel free to sign up to our fan subscription below...<br /><br /><br /> -->
            
            <div class="cleaner"></div>
    	</div>
    </div>
    
    <?php include('includes/footer.php'); ?>
    
</body>
</html>
