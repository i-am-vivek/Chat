<?php 
ini_set("display_errors",1);
session_start();
$_SESSION["user_id"]=1;
$_SESSION["supporter_id"]=2;
$_SESSION["usertype"]="user";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Chat</title>
    <link rel="stylesheet" href="style.css" type="text/css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="chat.js"></script>
	<style>
			#chat-wrap {
				border: 1px solid #eee;
				display: inline-block;
				width: 100%;
				margin: 10px 0;
			}
	</style>
    <script type="text/javascript">

    	// kick off chat
        var chat =  new Chat();
    	$(function() {
    	
    		 //chat.getState(); 
    		 
    		 // watch textarea for key presses
             $("#sendie").keydown(function(event) {  
             
                 var key = event.which;  
           
                 //all keys including return.  
                 if (key >= 33) {
                     var maxLength = $(this).attr("maxlength");  
                     var length = this.value.length;  
                     // don't allow new content if length is maxed out
                     if (length >= maxLength) {  
                         event.preventDefault();  
                     }  
                  }  
    		 });
    		 // watch textarea for release of key press
    		 $(document).on('keyup','.sendie',function(e) {	
    			  if (e.keyCode == 13) { 
                    var text = $(this).val();
    				var maxLength = $(this).attr("maxlength");  
                    var length = text.length; 
                    // send 
                    if (length <= maxLength + 1) { 
    			        chat.send(text, "3", $(this).data("gid"));	
    			        $(this).val("");
                    } else {
    					$(this).val(text.substring(0, maxLength));
    				}	
    			  }
             });
            
    	});
    </script>

</head>

<body onload="setInterval('chat.update(3)', 1000)">

</body>

</html>