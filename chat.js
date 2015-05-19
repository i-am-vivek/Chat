/* 
Created by: Kenrick Beckett

Name: Chat Engine
*/

var instanse = false;
var state;
var mes;
var file;
function Chat () {
    this.update = updateChat;
    this.send = sendChat;
}
//Updates the chat
function updateChat(user_id){
	 if(!instanse){
		 instanse = true;
	     $.ajax({
			   type: "POST",
			   url: "process/get_chat",
			   data: { 
						'state': state,
						'file': file,
						},
			   dataType: "json",
			   success: function(data){
				   if(data.status==1){
						$(data.chat).each(function(index,item){
							if(!$("#page-wrap"+item.agid).length){
								$new_chat='<div id="page-wrap'+item.agid+'">';
								$new_chat+='<h2>'+item.name+'-'+item.sname+'</h2>';
								$new_chat+='<p id="name-area"></p>';
								$new_chat+='<div id="chat-wrap"><div id="chat-area"></div></div>';
								$new_chat+='<form id="send-message-area">';
								$new_chat+='<p>Your message: </p>';
								$new_chat+='<textarea class="sendie" maxlength = "100" data-gid="'+item.agid+'"></textarea>';
								$new_chat+='</form>';
								$new_chat+='</div>';
								$("body").append($new_chat);
							}
							if(!$("#page-wrap"+item.agid).find('#chat-area').find('#chat_p_'+item.id).length && item.message!=null){
								$("#page-wrap"+item.agid).find('#chat-area').append($("<p id='chat_p_"+item.id+"'>"+ item.message +"</p>"));
							}							
						})
						document.getElementById('chat-area').scrollTop = document.getElementById('chat-area').scrollHeight;
                    }
				   
				   instanse = false;
				   state = data.state;
			   },
			});
	 }
	 else {
		 setTimeout(function(){updateChat()}, 1500);
	 }
}

//send the message
function sendChat(message, gid){
    updateChat();
     $.ajax({
		   type: "POST",
		   url: "process/insert_chat",
		   data: {
					'message': message,
					'gid':gid
				 },
		   dataType: "json",
		   success: function(data){
			   updateChat();
		   },
		});
}
