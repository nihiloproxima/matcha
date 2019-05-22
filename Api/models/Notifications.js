var db = require('../db/connection');

var Notifications = {
	// create 
	createNewNotification: function (datas, callback) {
		return db.query("INSERT INTO Notifications SET ?", [datas], callback);
	},
	// read
	getAllNotifications: function (callback) {
		return db.query("SELECT * FROM Notifications", callback);
	},
	getNotificationsBySenderId(senderId, callback) {
		return db.query("SELECT * FROM Notifications WHERE sender_id = ?", [senderId], callback);
	},
	getNotificationsById: function (id, callback) {
		return db.query("SELECT * FROM Notifications WHERE _id = ?", [id], callback);
	},
	getNotificationsByUserId: function (userId, callback) {
		return db.query("SELECT n.*, p.path, u.profile_pic_id FROM Notifications n INNER JOIN Users u ON u.id = n.sender_id LEFT OUTER JOIN Pictures p ON p.id = u.profile_pic_id WHERE n.user_id = ? ORDER BY n.`creation_date`", [userId], callback);
	},
	getNotificationsBySenderIdAndUserId: function (senderId, userId, callback) {
		return db.query("SELECT * FROM Notifications WHERE sender_id = ? AND user_id = ?", [senderId, userId], callback);
	},
	// update
	updateNotifications: function (datas, callback) {
		return db.query("UPDATE Notifications SET ?", [datas], callback);
	},
	updateNotificationsById: function (id, datas, callback) {
		return db.query("UPDATE Notifications SET ? WHERE like_id = ?", [datas, id], callback);
	},
	updateNotificationsByUserId: function (userId, datas, callback) {
		return db.query("UPDATE Notifications SET ? WHERE user_id = ?", [datas, userId], callback);
	},
	// delete
	deleteNotificationById: function (id, callback) {
		return db.query("DELETE FROM Notifications WHERE like_id = ?", [id], callback);
	},
	deleteNotificationsByUserId: function (userId, callback) {
		return db.query("DELETE FROM Notifications WHERE user_id = ?", [userId], callback);
	},
	deleteAllNotifications: function (callback) {
		return db.query("DELETE FROM Notifications", callback);
	}
};

module.exports = Notifications;