var db = require('../db/connection');
var bcrypt = require('bcrypt');

var Users = {
	// create
	createNewUser: function (data, callback) {
		return db.query("INSERT INTO Users SET ?", [data], callback);
	},
	// read
	getAllUsers: function (callback) {
		return db.query("SELECT * FROM Users", callback);
	},
	getUserById: function (id, callback) {
		return db.query("SELECT * FROM Users WHERE id = ?", [id], callback);
	},
	getUserByKey: function (key, value, callback) {
		return db.query("SELECT * FROM Users WHERE " + key + " = ?", [value], callback);
	},
	getUsersStats: function (callback) {
		return db.query("SELECT u.username, u.id, (SELECT COUNT(*) FROM Visits b) AS total_visits, (SELECT COUNT(*) FROM Visits v WHERE v.user_id = u.id) AS visits_count,(SELECT COUNT(*) FROM Likes l WHERE l.user_id = u.id) AS likes_count FROM Users u", callback);
	},
	// update
	updateAllUsers: function (data, callback) {
		return db.query("Update Users SET ?", [data], callback);
	},
	updateUserById: function (id, data, callback) {
		return db.query("UPDATE Users SET ? WHERE id = ?", [data, id], callback);
	},
	updatePopularityScore: function () {
		// TODO
	},
	// delete
	deleteAllUsers: function (callback) {
		return db.query("DELETE FROM Users", callback);
	},
	deleteUserById: function (id, callback) {
		return db.query("DELETE FROM Users WHERE id = ?", [id], callback);
	},
	// Subresources
	cryptPassword: function (password, callback) {
		bcrypt.genSalt(10, function(err, salt) {
		 if (err) 
		   return callback(err);
	 
		 bcrypt.hash(password, salt, function(err, hash) {
		   return callback(err, hash);
		 });
	   });
	 },
	 isCorrectPassword: function(password, hash, callback) {
		bcrypt.compare(password, hash, function(err, same) {
		  if (err) {
			callback(err);
		  } else {
			callback(err, same);
		  }
		});
	 }
};

module.exports = Users;