var db = require('../db/connection');

var Likes = {
	// create 
	createNewLike: function (datas, callback) {
		return db.query("INSERT INTO Likes SET ?", [datas], callback);
	},
	// read
	getAllLikes: function (callback) {
		return db.query("SELECT * FROM Likes", callback);
	},
	getLikesBySenderId(senderId, callback) {
		return db.query("SELECT * FROM Likes WHERE sender_id = ?", [senderId], callback);
	},
	getLikesById: function (id, callback) {
		return db.query("SELECT * FROM Likes WHERE like_id = ?", [id], callback);
	},
	getLikesByUserId: function (userId, callback) {
		return db.query("SELECT * FROM Likes WHERE user_id = ?", [userId], callback);
	},
	getLikesBySenderIdAndUserId: function (senderId, userId, callback) {
		return db.query("SELECT * FROM Likes WHERE sender_id = ? AND user_id = ?", [senderId, userId], callback);
	},
	// update
	updateLikes: function (datas, callback) {
		return db.query("UPDATE Likes SET ?", [datas], callback);
	},
	updateLikesById: function (id, datas, callback) {
		return db.query("UPDATE Likes SET ? WHERE like_id = ?", [datas, id], callback);
	},
	updateLikesByUserId: function (userId, datas, callback) {
		return db.query("UPDATE Likes SET ? WHERE user_id = ?", [datas, userId], callback);
	},
	// delete
	deleteLikesById: function (id, callback) {
		return db.query("DELETE FROM Likes WHERE like_id = ?", [id], callback);
	},
	deleteLikesByUserId: function (userId, callback) {
		return db.query("DELETE FROM Likes WHERE user_id = ?", [userId], callback);
	},
	deleteLikesBySenderId: function (senderId, callback) {
		return db.query("DELETE * FROM Likes WHERE sender_id = ?", [senderId], callback);
	}
};

module.exports = Likes;