<link rel="stylesheet" type="text/css" href="/Assets/css/chat.css">

<div id="frame" style="height:calc(100% - 50px)">
	<div id="sidepanel">
		<div id="profile">
			<div class="wrap">
				<img id="profile-img"
					src="/<?php echo ($picture['path'] ? $picture['path'] : "assets/uploads/default_user.jpeg"); ?>"
					class="online" alt="" />
				<p><a href="/index.php/profile/" id="username"><?php echo $_SESSION['username']; ?></a><span id="userid"
						class="is-hidden"><?php echo $_SESSION['id']; ?></span></p>
			</div>
		</div>
		<div id="contacts">
			<ul>
				<?php foreach ($chats as $chat): ?>
				<li id="<?=$chat['chat_id']?>" class="contact chatrooms">
					<div class="wrap">
						<?php if (-(strtotime($chat['last_connection']) - time() + 7200) < 300): ?>
						<span class="contact-status online"></span>
						<?php else: ?>
						<span class="contact-status offline"></span>
						<?php endif;?>
						<img id="pic-<?=$chat['id']?>" src="/
							<?php echo $chat['path'] ? $chat['path'] : "assets/uploads/default_user.jpeg"; ?>" alt="" />
						<div class="meta">
							<a href="/index.php/profile/<?=$chat['username']?>" class="name">
								<?=$chat['username']?>
							</a>
						</div>
					</div>
				</li>
				<?php endforeach;?>
			</ul>
		</div>
	</div>
	<div class="content">
		<div class="messages">
			<?php foreach ($chats as $chat): ?>
			<ul id="room-<?=$chat['chat_id']?>" class="room is-hidden" style="height:100%;">

			</ul>
			<?php endforeach;?>
		</div>
		<div id="input-box" class="message-input is-hidden">
			<div class="wrap">
				<input id="message" type="text" style="width:85%;" placeholder="Write your message..." />
				<button id="send_message" style="width:15%;" class="bp3-button"><i class="fa fa-paper-plane"
						aria-hidden="true"></i></button>
			</div>
		</div>
	</div>
</div>