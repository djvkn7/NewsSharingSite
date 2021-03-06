<?php
	session_start();
	$user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <title>NEWSFEED</title>
		
		<link rel = "stylesheet" type = "text/css" href = "stylingSheet.css">
    </head>
	
    <body>
		<div id = "header">
			<h1>NEWSFEED</h1>
		</div>
		
		<div id = "nav">
			<?php
				if ($user !== null) {
					include "regUser.html";
				}
			?>
		</div>
		
		<div id = "leftSection">
			<?php
				require "php_database.php";
			
				$showStory = $mysqli -> prepare ("select story_id, user, title, text, link
											 from stories");
				
				if (!$showStory) {
					printf("Select Query Prep Failed: %s\n", $mysqli -> error);
					exit;
				}
				
				$showStory -> execute();
				$showStory -> bind_result($story_id, $user, $title, $text, $link);
				
				$num = 1;
			
				while ($showStory -> fetch()) {
					printf("%s. <a href = 'storyPage.php?story_id=%s' >%s</a> <span id = 'user'>submitted by %s</span> <br> <br>",
                           $num, $story_id, $title, $user);
					$num ++; 
				}
				
				$showStory -> close();	
			?>
		</div>
		
		<div id = "footer">
			<i>Copyright</i> &copy; Carolyn Dean Wolf & Devin Kuokka
		</div>
		
    </body>
</html>