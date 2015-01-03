<?php

	require 'includes/facebook_sdk.php';
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
	
	//Facebook stuff - 405413792880935
	date_default_timezone_set('America/Los_Angeles');
	
	$facebook = new Facebook(array(
    	'appId' => '1421350878116358',
    	'secret' => 'e870b82ea606a19e4421b75b17d21879',
    	'cookie' => true
	));
	
	$fql = "SELECT 
				booking_agent, hometown, press_contact, record_label 
			FROM 
				page 
			WHERE 
				page_id = 405413792880935";
	 
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
            
            <h1>CONTACT</h1>
            
            <div class="left-column">
            
            	<h3>LABEL</h3>
                <?php
			
					foreach($fqlResult as $keys => $values){
						echo $values['record_label'];
					}
					
				?>

                <h3>BOOKING AGENT</h3>
                <?php
			
					foreach($fqlResult as $keys => $values){
						if(filter_var($values['booking_agent'], FILTER_VALIDATE_EMAIL)) {
							echo "<a href='mailto:{$values['booking_agent']}'>{$values['booking_agent']}</a>";
						} else {
							echo formatUrlsInText($values['booking_agent']);
						}
					}
					
				?>
                
                <h3>PRESS AGENT</h3>
                <?php
			
					foreach($fqlResult as $keys => $values){
						if(filter_var($values['press_contact'], FILTER_VALIDATE_EMAIL)) {
							echo "<a href='mailto:{$values['press_contact']}'>{$values['press_contact']}</a>";
						} else {
							echo formatUrlsInText($values['press_contact']);
						}
					}
					
				?>
                
            	<h3>HOME TOWN</h3>
                <?php
			
					foreach($fqlResult as $keys => $values){
						echo formatUrlsInText($values['hometown']);
					}
					
				?>
                
                <br /><br />
            	
            </div>
            
            <div class="right-column">
            	
                <h2>CONTACT FORM</h2>
                
                <form name="contact" id="contact" method="post" action="contact.php">
                
                	<div class="contact-field-column">
                		<label for="contact-name">NAME&nbsp;*</label>
                		<input type="text" value="" name="name" id="contact-name" />
                	</div>
                    
                    <div class="contact-field-column">
                		<label for="contact-phone">PHONE</label>
                		<input type="text" value="" name="phone" id="contact-phone" />
                	</div>
                    
                    <div class="contact-field-column">
                		<label for="contact-email">EMAIL&nbsp;*</label>
                		<input type="text" value="" name="email" id="contact-email" />
                	</div>
                    
                    <div class="cleaner"><br /></div>
                    
                    <div class="contact-field-column">
                		<label for="contact-message">MESSAGE&nbsp;*</label>
                		<textarea value="" name="email" id="contact-message"></textarea>
                	</div>
                    
                    <div class="contact-field-column">
                    	<br />
                    	*&nbsp;Required field
                    </div>
                    
                    <input type="submit" value="SEND" name="send" id="contact-send" class="button">
                    
                </form>
                
            </div>
            
            <!-- <h1>UNDER CONSTRUCTION</h1>
            <b>SORRY!</b> This page isn't quite ready yet... <b>Wait a second!</b> We are close to finishing our site so please come back again soon. In the meantime feel free to sign up to our fan subscription below...<br /><br /><br /> -->
            
            <div class="cleaner"></div>
    	</div>
    </div>
    
    <?php include('includes/footer.php'); ?>
    
</body>
</html>
