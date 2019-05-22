var db = require('../db/connection');

var Address = {
    createNewAddress:function(address, callback) {
        return db.query("INSERT INTO Address SET ?", [adderss], callback);
    },
    getAllAddress:function(callback) {
        return db.query("SELECT * FROM Address", callback);
    }, 
    getAddressById:function(id, callback) {
        return db.query("SELECT * FROM Address WHERE id = ?", [id], callback);
	},
	getAddressByUserId:function(userId, callback) {
        return db.query("SELECT * FROM Address WHERE user_id = ?", [userId], callback);
    },
    updateAddressById:function(Id, address, callback) {
        return db.query("UPDATE Address SET ? WHERE id = ?", [address, Id], callback);
	},
	updateAddressByUserId:function(userId, address, callback) {
        return db.query("UPDATE Address SET ? WHERE user_id = ?", [address, userId], callback);
    },
    deleteAddressById:function(userId, callback) {
        return db.query("DELETE FROM Address WHERE user_id = ?", [userId], callback);
    }
};

module.exports = Address;