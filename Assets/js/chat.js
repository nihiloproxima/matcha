$(function () {
	var socket = io.connect('https://localhost:5000');

	let userid = document.getElementById("userid").innerHTML;
	let username = document.getElementById("username").innerHTML;
	let message = $("#message");
	let send_message = $("#send_message");
	let chatroom = $(".chatrooms");
	let rooms = $(".room");
	let roomId = 0;

	$("#message").keyup(function (ev) {
		if (ev.keyCode == 13) {
			sendMsg();
		}
	});

	function sendMsg() {
		$.get('https://localhost:5000/api/v1/chat/' + roomId + '?sender=' + userid, (data) => {
			target_id = userid != data.user1_id ? data.user1_id : data.user2_id;
			infos = {
				username: username,
				roomId: roomId,
				content: message.val(),
				sender_id: userid,
				target_id: target_id
			}
			if (message.val() != '') {
				socket.emit('new_message', infos);
				message.val('');
			}
		});
	}

	send_message.click(() => {
		sendMsg();
	});

	socket.on("new_message", (data) => {
		if (data.username != username) {
			$("#room-" + data.roomId).append('<li class="replies"><img src="/' + (data.path == null ? "assets/uploads/default_user.jpeg" : data.path) + '" alt=""><p style="word-break:break-word">' + encodeHTML(data.message) + '</p></li>');
		} else {
			$("#room-" + data.roomId).append('<li class="sent"><img src="/' + (data.path == null ? "assets/uploads/default_user.jpeg" : data.path) + '" alt=""><p style="word-break:break-word">' + encodeHTML(data.message) + '</p></li>');
		}
		el = document.getElementById("room-" + roomId).lastChild;
		if (el)
			el.scrollIntoView();
	})

	chatroom.click((e) => {
		document.getElementById("input-box").classList.remove("is-hidden");
		roomId = e.currentTarget.id;
		for (var i = 0; i < chatroom.length; i++) {
			chatroom[i].classList.remove("menu-active");
		}
		e.currentTarget.classList.add("menu-active");
		currentRoom = document.getElementById("room-" + roomId);
		for (var i = 0; i < rooms.length; i++) {
			rooms[i].classList.add("is-hidden");
		}
		$("#room-" + roomId).empty();
		$.get('/index.php/chat/get_chat_messages?chat_id=' + roomId, (data) => {
			var res = JSON.parse(data);
			for (var i = 0; i < res.length; i++) {
				if (res[i].username != username) {
					$("#room-" + roomId).append('<li class="replies"><img src="/' + (res[i].path == null ? "assets/uploads/default_user.jpeg" : res[i].path) + '" alt=""><p style="word-break:break-word">' + encodeHTML(res[i].content) + '</p></li>');
				} else {
					$("#room-" + roomId).append('<li class="sent"><img src="/' + (res[i].path == null ? "assets/uploads/default_user.jpeg" : res[i].path) + '" alt=""><p style="word-break:break-word">' + encodeHTML(res[i].content) + '</p></li>');
				}
			}
			if (document.getElementById("room-" + roomId).lastChild != null)
				document.getElementById("room-" + roomId).lastChild.scrollIntoView();
		});
		currentRoom.classList.remove("is-hidden");
		socket.emit('join', {
			roomId: roomId,
			userId: userid
		});
	})

	if ($('.chatrooms')[0])
		$('.chatrooms')[0].click();
	else {
		$('.messages')[0].innerHTML = '<h1 class="has-text-centered mt-5" style="color:black;">No active discussions.</h1>';
	}

});


function baseName(path) {
	return path.split('/').reverse()[0];
}

function encodeHTML(s) {
	return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
}

function getMessages(id) {
	var chatroom = $("#chatroom");
	$(".media").remove();
}