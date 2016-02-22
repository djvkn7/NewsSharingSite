<?php
	session_start();
	$user = $_SESSION['username'];
    $story_id = $_SESSION['story_id'];
    
    $commentToEdit_id = $_GET['comment_id'];
    $_SESSION['commentToEdit_id'] = $commentToEdit_id;
    
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <title>COMMENT</title>
		
		<link rel="stylesheet" type="text/css" href="stylingSheet.css">
        
        <script type = "text/javascript">
            var story_id = <?php echo $story_id ?>;
        </script>
    </head>
	
    <body>
        <div id = "header">
			
		</div>
        
        <div id = "nav">
			<!--logout button-->
			<input id = "logoutButton"
				   class = "button"
				   type = "button"                              
				   value = "Logout"
				   onclick = "window.location = 'logoutScript.php'"
			/> <br> <br>
			
			<!--go back button-->
			<input id = "backButton"
				   class = "button"
				   type = "button"                              
				   value = "Go Back"
				   onclick = "window.location = 'storyPage.php?story_id='+story_id"
			/>
		</div>
        
        <div id = "leftSection">
            <?php
                require "php_database.php";
                
                //displays the selected story (title, text, link, and author)
                $showStory = $mysqli -> prepare ("select user, title, text, link
                                                 from stories
                                                 where story_id = '$story_id'");
                    
                    if (!$showStory) {
                        printf("Select Query Prep Failed: %s\n", $mysqli -> error);
                        exit;
                    }
                    
                    $showStory -> execute();
                    $showStory -> bind_result($storyAuthor, $title, $text, $link);
                    
                    $showStory -> fetch();
                    
                    
                    printf("<span id = title>%s</span>
                           <span id = user>submitted by %s</span> <br> <br>
                           <span id = text>%s</span> <br> <br>
                           <a id = link href = %s >%s</a> <br> <br>",
                           $title, $user, $text, $link, $link);
                    
                    $showStory -> close();
                
                //displays all comments on selected story
                $showComments = $mysqli -> prepare ("select comment_id, user, comment
                                                    from comments
                                                    where story_id = '$story_id'");
                    
                    if (!$showComments) {
                        printf("Select Query Prep Failed: %s\n", $mysqli -> error);
                        exit;
                    }
                    
                    $showComments -> execute();
                    $showComments -> bind_result($comment_id, $commentAuthor, $comment);
                
                    while ($showComments -> fetch()) {
                       
                       //check if comment is comment to edit
                        if ($comment_id == $commentToEdit_id) {
                        
                            printf("<a href = 'storyPage.php?story_id=%s'>%s</a> <span id = 'user'>submitted by %s</span> <br> <br>",
                                $story_id, $comment, $commentAuthor);
                            
                            //include "subCommentButton.html";
                            
                            if ($user == $commentAuthor) {
                                $_SESSION['commentToEdit'] = $comment;
                                include "commentButtons.html";
                                echo "<br><br>";
                            }
                        
                        } else {
                            printf("<a href = 'commentPage.php?comment_id=%s'>%s</a> <span id = 'user'>submitted by %s</span> <br> <br>",
                                $comment_id, $comment, $commentAuthor);
                        }
                    
                    }
                    
                    $showComments -> close();
            ?>
            
        </div>
     
    </body>
</html>