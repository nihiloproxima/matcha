const mysql = require('mysql');
var port = process.env.DATABASE_SOCKET;

const connection = mysql.createPool({
	connectionLimit: 10,
	user: 'root',
	password: 'rootpass', // root
	host: '0.0.0.0',
	database: 'matcha',

	port: "3306"
});

module.exports = connection;