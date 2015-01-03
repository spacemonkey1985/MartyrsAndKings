<?php

	require_once 'includes/mobile_detect.php';
	$detect = new Mobile_Detect;
	
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
            
            <h1>ABOUT</h1>
            
            <div class="left-column">
            
            	<span class="quote">&quot;We're a modern four piece acoustic/folk band from around the Berkshire area&quot;</span>
                <br /><br />
            	<!-- <span class="quote-by">Martyrs &amp; Kings</span> -->
            
            </div>
            
            <div class="right-column">
            	
                <div class="about-image">
                	<img src="images/about.jpg" alt="Martyrs &amp; Kings" />
                </div>
                
            	The band initially started as a two piece consisting of Mike Williams (Vocals, Guitar) and James Jackman (Drums). Both studying at Sandhurst Sixth Form, they spent their spare time rehearsing in the music rooms and before long wanted to take it a step further.
                <br /><br />
                Soon after came the addition of Chris Bright on bass guitar and Zach Johnson on lead guitar to complete the line up. Here the band spent their time learning and completing songs written by Mike Williams but before long, the songs became far more than the original singer/songwriter feel. The inclusion of Chris' funk bass elements and Zach's melodic lead guitar playing became the modern folk/rock sound the band were looking for.
                <br /><br />
                After spending the rest of 2012 rehearsing and writing new material, it was a year later in April 2013 when the band began playing their first shows. With a warm reception to their sound, the band continued to build on their live show and include as much material, old and new, as they could.
                <br /><br />
                It didn't take long for the rest of the band to find their feet when it came to song writing. With involvement from all members the new songs were a step in the right direction.
                <br /><br />
                Soon after, the band went into the studio in late July to record their first <a href="https://soundcloud.com/martyrs-kings" target="_blank">3-track EP</a>.
                <br /><br />
                The rest of the year was spent playing shows and writing new material ready for a second EP early 2014. They also wrote a Facebook bio.
                
            </div>
            
            <div class="cleaner"></div>
            
    	</div>
    </div>
    
    <?php include('includes/footer.php'); ?>
    
</body>
</html>
