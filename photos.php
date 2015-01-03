<?php

	require 'includes/facebook_sdk.php';
	require_once 'includes/mobile_detect.php';

	$detect = new Mobile_Detect;
	$photoArray = array();
	
	//Facebook stuff - 405413792880935
	date_default_timezone_set('America/Los_Angeles');
	
	$facebook = new Facebook(array(
    	'appId' => '1421350878116358',
    	'secret' => 'e870b82ea606a19e4421b75b17d21879',
    	'cookie' => true
	));
	
	if(isset($_GET['album'])) {
		$photos = "SELECT 
						aid, pid, src_small, src, src_big, object_id, src_big_height, src_big_width
					FROM 
						photo 
					WHERE 
						aid = '" . $_GET['album'] . "'";
		
		$param = array(
			'method' => 'fql.query',
			'query' => $photos,
			'callback' => ''
		);
		 
		$photosResult = $facebook->api($param);
		
		foreach($photosResult as $keys => $values){
			$photoArray[] = $values['src_big'] . "#" . $values['src_big_height'] . "_" . $values['src_big_width'];
		}
		
	} else {
		$albums = "SELECT 
						aid, owner, name, photo_count, type, object_id, cover_object_id 
					FROM 
						album 
					WHERE 
						owner = '405413792880935'
					AND
						name != 'Cover Photos'
					AND
						name != 'Profile Pictures'";
					
		$covers = "SELECT 
						aid, pid, src_small, src, src_big, object_id, src_big_height, src_big_width
					FROM 
						photo 
					WHERE 
						aid IN(SELECT aid FROM album WHERE owner = '405413792880935' AND name != 'Cover Photos' AND name != 'Profile Pictures')";
		 
		$param = array(
			'method' => 'fql.query',
			'query' => $albums,
			'callback' => ''
		);
		 
		$albumsResult = $facebook->api($param);
		
		$param = array(
			'method' => 'fql.query',
			'query' => $covers,
			'callback' => ''
		);
		 
		$coversResult = $facebook->api($param);
	}
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
				
				var nextPhoto;
				var prevPhoto;
				var arrayFromPHP = <?php echo json_encode($photoArray); ?>;
				
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
				
				$(".photo-link").click(function(e) {
      				e.preventDefault(); 
					
					var dimensions = $(this).attr("href").replace("#", "").split('_');
					
					setSize(dimensions[0], dimensions[1]);
					$("#photo").attr("src", $(this).attr("id"));
					$("#overlay").css("display", "inline");
					
					setPrevNext();
					
				});
				
				$("#overlay").click(function(e) {
      				e.preventDefault(); 
					//$("#overlay").css("display", "none");
					//$("#photo").attr("src", "");
				});
				
				$("#close").click(function(e) {
      				e.preventDefault(); 
					$("#overlay").css("display", "none");
					$("#photo").attr("src", "");
				});
				
				$("#next").click(function(e) {
					e.preventDefault();
					$("#photo").attr("src", nextPhoto);
					setPrevNext();
					
				});
				
				$("#prev").click(function(e) {
      				e.preventDefault();
					$("#photo").attr("src", prevPhoto);
					setPrevNext();
				});
				
				function setSize(height, width) {
					var halfHeight = height / 2;
					var halfWidth = width / 2;
					
					$("#photo").css("margin-left", halfWidth * -1);
					$("#photo").css("margin-top", halfHeight * -1);
					$("#photo").css("width", width);
					$("#photo").css("height", height);
					$("#close").css("margin-left", halfWidth - 15);
					$("#close").css("margin-top", (halfHeight * -1) - 20);
					$("#prev").css("margin-top", (halfHeight - halfHeight) - 10);
					$("#prev").css("margin-left", (halfWidth + 20) * -1);
					$("#next").css("margin-top", (halfHeight - halfHeight) - 10);
					$("#next").css("margin-left", halfWidth - 15);
				}
				
				function setPrevNext() {
					$.each(arrayFromPHP, function (i, elem) {
						if(elem.split('#')[0] == $("#photo").attr("src")) {
							setSize(arrayFromPHP[i].split('#')[1].split('_')[0], arrayFromPHP[i].split('#')[1].split('_')[1]);
							
							if(i == 0) {
								nextPhoto = arrayFromPHP[1].split('#')[0];
								prevPhoto = "";
								$("#prev").css("display", "none");
								$("#next").css("display", "inline");
							} else if (i == arrayFromPHP.length - 1) {
								nextPhoto = "";
								prevPhoto = arrayFromPHP[arrayFromPHP.length - 2].split('#')[0];
								$("#prev").css("display", "inline");
								$("#next").css("display", "none");
							} else {
								nextPhoto = arrayFromPHP[i + 1].split('#')[0];
								prevPhoto = arrayFromPHP[i - 1].split('#')[0];
								$("#prev").css("display", "inline");
								$("#next").css("display", "inline");
							}
						}
					});
				}
				
            });
		
		</script>

    </head>

<body>

	<div class="dim" id="overlay">
    	<img src="#" alt="Photo" id="photo" />
    	<a href="#‎" id="close" title="Close">&nbsp;</a>
    	<a href="#‎" id="prev" title="Previous">&nbsp;</a>
    	<a href="#‎" id="next" title="Next">&nbsp;</a>
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
            
            <?php
			
				if(isset($_GET['name'])) {
					echo '<h1>' . urldecode($_GET['name']) . '</h1>';
				} else {
	            	echo '<h1>PHOTOS</h1>';
				}
            
			?>
            
            <div class="photos">
            
            <?php
			
				$i = 0;
			
				if(isset($_GET['album'])) {
					foreach($photosResult as $keys => $values){
						$i++;
						$photoArray[] = $values['src_big'];
						
						echo '<div class="photo-item">';
						echo '<div class="photo-item-frame">';
						if($values['src_big_height'] > $values['src_big_width']) {
							echo '<a class="photo-link" href="#' . $values['src_big_height'] . '_' . $values['src_big_width'] . '" id="' . $values['src_big'] . '"><img src="' . $values['src_big'] . '" alt="' . urldecode($_GET['name']) . ' Photo" style="margin-top: -'. $values['src_big_height'] / 4 .'px;" /></a>';
						} else {
							echo '<a class="photo-link" href="#' . $values['src_big_height'] . '_' . $values['src_big_width'] . '" id="' . $values['src_big'] . '"><img src="' . $values['src_big'] . '" alt="' . urldecode($_GET['name']) . ' Photo" /></a>';
						}
						echo '</div>';								
						//echo '<h2>' . $values['name'] . '</h2>';
						echo '</div>';
						
						if($i == 4) {
							echo '<div class="cleaner"></div>';
							$i = 0;
						}
					}
					
					echo '<div class="cleaner"></div>';
					echo '<a href="photos.php" title="Back to Albums" id="back">BACK TO ALBUMS</a>';
					
				} else {
					foreach($albumsResult as $keys => $values){
						$i++;
						
						echo '<div class="photo-item">';
						echo '<div class="photo-item-frame">';
						
						$cover = 0;
						
						foreach($coversResult as $keys2 => $values2){
							if($values2['object_id'] == $values['cover_object_id']) {
								if($values2['src_big_height'] > $values2['src_big_width']) {
									echo '<a href="photos.php?album=' .$values['aid'] . '&name=' . urlencode($values['name']) . '"><img src="' . $values2['src_big'] . '" alt="' . $values['name'] . '" style="margin-top: -'. $values2['src_big_height'] / 5 .'px;" /></a>';
								} else {
									echo '<a href="photos.php?album=' .$values['aid'] . '&name=' . urlencode($values['name']) . '"><img src="' . $values2['src_big'] . '" alt="' . $values['name'] . '" /></a>';
								}
								$cover = 1;
							}
						}
						
						if($cover == 0)
							echo '<a href="photos.php?album=' .$values['aid'] . '&name=' . urlencode($values['name']) . '"><img src="images/blank_cover.png" alt="' . $values['name'] . '" /></a>';
							
						echo '</div>';								
						echo '<h2>' . $values['name'] . '</h2>';
						echo '</div>';
						
						if($i == 4) {
							echo '<div class="cleaner"></div>';
							$i = 0;
						}
					}
				}
				
			?>
            
            </div>
            
            <!-- <h1>UNDER CONSTRUCTION</h1>
            <b>SORRY!</b> This page isn't quite ready yet... <b>Wait a second!</b> We are close to finishing our site so please come back again soon. In the meantime feel free to sign up to our fan subscription below...<br /><br /><br /> -->
            
            <div class="cleaner"></div>
    	</div>
    </div>
    
    <?php include('includes/footer.php'); ?>
    
</body>
</html>
