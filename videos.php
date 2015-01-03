<?php

	require_once 'includes/mobile_detect.php';
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
	
	date_default_timezone_set('America/Los_Angeles');
	
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
        
        	<div class="main-page-image">
        		<img src="images/home.png" alt="Martyrs &amp; Kings" />
            </div>
            
            <?php include('includes/menu.php'); ?>
            
        </div>
  	</div>
    
    <div class="main">
    	<div class="wrapper">
            
            <h1>VIDEOS</h1>
            
            <div class="youtube-feed">
			<?php
				
				$youtube_feed = simplexml_load_file('https://gdata.youtube.com/feeds/api/users/MartyrsAndKings/uploads');
				
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
            
            <div class="cleaner"></div>
    	</div>
    </div>
    
    <?php include('includes/footer.php'); ?>
    
</body>
</html>
