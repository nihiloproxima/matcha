var db = require('../db/connection');

var Tags = {
	// create
	createNewTag: function (data, callback) {
		return db.query("INSERT INTO Tags SET ?", [data], callback);
	},
	// read
	getAllTags: function (callback) {
		return db.query("SELECT * FROM Tags", callback);
	},
	getTagById: function (id, callback) {
		return db.query("SELECT * FROM Tags WHERE id = ?", [id], callback);
	},
	// update
	updateAllTags: function (data, callback) {
		return db.query("Update Tags SET ?", [data], callback);
	},
	updateTagById: function (id, data, callback) {
		return db.query("UPDATE Tags SET ? WHERE id = ?", [data, id], callback);
	},
	// delete
	deleteAllTags: function (callback) {
		return db.query("DELETE FROM Tags", callback);
	},
	deleteTagById: function (id, callback) {
		return db.query("DELETE FROM Tags WHERE id = ?", [id], callback);
	}
};

module.exports = Tags;