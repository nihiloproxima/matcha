var db = require('../db/connection');

var Blacklist = {
	isBlacklistedBy: function (userId, targetId, callback) {
		return db.query("SELECT COUNT(*) AS count FROM Blacklist_entries WHERE `user_id` = ? AND `blacklisted_id` = ?", [userId, targetId], callback);
	}
};

module.exports = Blacklist;