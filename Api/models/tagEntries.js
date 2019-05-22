var db = require('../db/connection');

var TagEntries = {
	// create
	createNewTagEntry: function (data, callback) {
		return db.query("INSERT INTO Tag_entries SET ?", [data], callback);
	},
	// read
	getAllTagEntries: function (callback) {
		return db.query("SELECT * FROM Tag_entries", callback);
	},
	getTagEntryById: function (id, callback) {
		return db.query("SELECT * FROM Tag_entries WHERE id = ?", [id], callback);
	},
	getTagEntriesByTagId: function (tagId, callback) {
		return db.query("SELECT * FROM Tag_entries WHERE tag_id = ?", tagId, callback);
	},
	// update
	updateAllTagEntries: function (data, callback) {
		return db.query("Update Tag_entries SET ?", [data], callback);
	},
	updateTagEntryById: function (id, data, callback) {
		return db.query("UPDATE TagEntries SET ? WHERE id = ?", [data, id], callback);
	},
	// delete
	deleteAllTagEntries: function (callback) {
		return db.query("DELETE FROM Tag_entries", callback);
	},
	deleteTagById: function (id, callback) {
		return db.query("DELETE FROM Tag_entries WHERE id = ?", [id], callback);
	}
};

module.exports = TagEntries;