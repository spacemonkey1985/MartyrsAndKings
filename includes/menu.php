<div class="menu">
    <span class="logo"><b>Martyrs</b> &amp; Kings</span>
    
    <div class="socialmedia">
        <a href='https://twitter.com/MartyrsandKings' rel='nofollow' target='_blank' id="twitter" title="Twitter">&nbsp;</a>
        <a target="_blank" href="https://www.facebook.com/MartyrsKings" id="facebook" title="Facebook">&nbsp;</a>
        <a target="_blank" href="http://www.youtube.com/user/MartyrsAndKings" id="youtube" title="YouTube">&nbsp;</a>
        <a target="_blank" href="https://soundcloud.com/martyrs-kings" id="soundcloud" title="Soundcloud">&nbsp;</a>
    </div>
    
    <ul>
    	<?php
			$page = "";
			$page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		
			if(strtolower($page) == "index.php" || $page == ""){
				echo "<li class='current'><a href='index.php'>HOME</a></li>";
			}
			else{
				echo "<li><a href='index.php'>HOME</a></li>";
			}
			
			if(strtolower($page) == "about.php"){
				echo "<li class='current'><a href='about.php'>ABOUT</a></li>";
			}
			else{
				echo "<li><a href='about.php'>ABOUT</a></li>";
			}
			
			if(stristr(strtolower($_SERVER["REQUEST_URI"]), 'gigs') != ''){
				echo "<li class='current'><a href='gigs.php'>GIGS</a></li>";
			}
			else{
				echo "<li><a href='gigs.php'>GIGS</a></li>";
			}
			
			if(stristr(strtolower($_SERVER["REQUEST_URI"]), 'photos') != ''){
				echo "<li class='current'><a href='photos.php'>PHOTOS</a></li>";
			}
			else{
				echo "<li><a href='photos.php'>PHOTOS</a></li>";
			}
			
			if(stristr(strtolower($_SERVER["REQUEST_URI"]), 'videos') != ''){
				echo "<li class='current'><a href='videos.php'>VIDEOS</a></li>";
			}
			else{
				echo "<li><a href='videos.php'>VIDEOS</a></li>";
			}
			
			if(stristr(strtolower($_SERVER["SCRIPT_NAME"]), 'blog') != ''){
				echo "<li class='current'><a href='blog/'>BLOG</a></li>";
			}
			else{
				echo "<li><a href='blog/'>BLOG</a></li>";
			}
			
			if(strtolower($page) == "contact.php" || strtolower($page) == "thankyou.php"){
				echo "<li class='current'><a href='contact.php'>CONTACT</a></li>";
			}
			else{
				echo "<li><a href='contact.php'>CONTACT</a></li>";
			}
			
		?>
        
    </ul>
    
</div>