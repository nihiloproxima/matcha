var db = require('../db/connection');

var Visits = {
	// create 
	createNewVisit: function (datas, callback) {
		return db.query("INSERT INTO Visits SET ?", [datas], callback);
	},
	// read
	getAllVisits: function (callback) {
		return db.query("SELECT * FROM Visits", callback);
	},
	getVisitsBySenderId(senderId, callback) {
		return db.query("SELECT * FROM Visits WHERE sender_id = ?", [senderId], callback);
	},
	getVisitsById: function (id, callback) {
		return db.query("SELECT * FROM Visits WHERE id = ?", [id], callback);
	},
	getVisitsByUserId: function (userId, callback) {
		return db.query("SELECT * FROM Visits WHERE user_id = ?", [userId], callback);
	},
	getVisitsBySenderIdAndUserId: function (senderId, userId, callback) {
		return db.query("SELECT * FROM Visits WHERE sender_id = ? AND user_id = ?", [senderId, userId], callback);
	},
	// update
	updateVisits: function (datas, callback) {
		return db.query("UPDATE Visits SET ?", [datas], callback);
	},
	updateVisitsById: function (id, datas, callback) {
		return db.query("UPDATE Visits SET ? WHERE id = ?", [datas, id], callback);
	},
	updateVisitsByUserId: function (userId, datas, callback) {
		return db.query("UPDATE Visits SET ? WHERE user_id = ?", [datas, userId], callback);
	},
	// delete
	deleteVisitsById: function (id, callback) {
		return db.query("DELETE FROM Visits WHERE id = ?", [id], callback);
	},
	deleteVisitsByUserId: function (userId, callback) {
		return db.query("DELETE FROM Visits WHERE user_id = ?", [userId], callback);
	},
	deleteVisitsBySenderId: function (senderId, callback) {
		return db.query("DELETE * FROM Visits WHERE sender_id = ?", [senderId], callback);
	}
};

module.exports = Visits;