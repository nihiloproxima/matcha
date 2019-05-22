var db = require('../db/connection');

var Chat = {
	// create 
	createNewMessage: function (datas, callback) {
		return db.query("INSERT INTO Chat_messages SET ?", [datas], callback);
	},
	// read
	getAllMesages: function (callback) {
		return db.query("SELECT * FROM Chat_messages", callback);
	},
	getMessagesFromRoom: function (chatroomId, callback) {
		return db.query("SELECT * FROM Chat_messages WHERE chatroom_id = ?", [chatroomId], callback);
	},
	getRoomInfos: function (roomId, callback) {
		return db.query("SELECT * FROM Chat WHERE `id` = ?", [roomId], callback);
	},
	hasUnreadMessages: function (targetId, callback) {
		return db.query("SELECT COUNT(*) as Total FROM Chat_messages WHERE `target_id` = ? AND `status` = 'unread'", [targetId], callback);
	},
	readConversation: function (chatroomId, targetId, callback) {
		return db.query("UPDATE Chat_messages SET `status` = 'read' WHERE `chatroom_id` = ? AND `target_id` = ?", [chatroomId, targetId], callback);
	}
};

module.exports = Chat;