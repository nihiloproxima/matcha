var db = require('../db/connection');

var Pictures = {
	// create 
	createNewPicture: function (datas, callback) {
		return db.query("INSERT INTO Pictures SET ?", [datas], callback);
	},
	// read
	getAllPictures: function (callback) {
		return db.query("SELECT * FROM Pictures", callback);
	},
	getPictureById: function (id, callback) {
		return db.query("SELECT * FROM Pictures WHERE id = ?", [id], callback);
	},
	getPicturesByUserId: function (userId, callback) {
		return db.query("SELECT * FROM Pictures WHERE user_id = ?", [userId], callback);
	},
	// update
	updatePictures: function (datas, callback) {
		return db.query("UPDATE Pictures SET ?", [datas], callback);
	},
	updatePictureById: function (id, datas, callback) {
		return db.query("UPDATE Pictures SET ? WHERE id = ?", [datas, id], callback);
	},
	updatePicturesByUserId: function (userId, datas, callback) {
		return db.query("UPDATE Pictures SET ? WHERE user_id = ?", [datas, userId], callback);
	},
	// delete
	deletePictureById: function (id, callback) {
		return db.query("DELETE FROM Pictures WHERE id = ?", [id], callback);
	},
	deletePicturesByUserId: function (userId, callback) {
		return db.query("DELETE FROM Pictures WHERE user_id = ?", [userId], callback);
	}
};

module.exports = Pictures;