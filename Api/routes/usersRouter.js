const express = require('express');
const usersRouter = express.Router({
	mergeParams: true
});
const jwt = require('jsonwebtoken');
const Users = require('../models/Users');

// Subresources routers
const addressRouter = require('./addressRouter');
const picturesRouter = require('./picturesRouter');
const likesRouter = require('./likesRouter');
const notificationsRouter = require('./notificationsRouter');
const visitsRouter = require('./visitsRouter');

// users routes
usersRouter.get('/stats', async (req, res, next) => {
	Users.getUsersStats((err, rows) => {
		err ? res.json(err) : res.json(rows);
	})
}).get('/:id?', async (req, res, next) => {
	if (req.params.id) {
		Users.getUserById(req.params.id, (err, rows) => {
			err ? res.json(err) : res.json(rows[0]);
		});
	} else if (req.query.username) {
		Users.getUserByKey('username', req.query.username, (err, rows) => {
			err ? res.json(err) : res.json(rows[0]);
		});
	} else {
		Users.getAllUsers((err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
}).post('/login', function (req, res) {
	const {
		username,
		password
	} = req.body;
	Users.getUserByKey('username', username, function (err, user) {
		if (err) {
			console.error(err);
			res.status(500)
				.json({
					error: 'Internal error please try again'
				});
		} else if (!user) {
			res.status(401)
				.json({
					error: 'Incorrect email or password'
				});
		} else {
			Users.isCorrectPassword(password, user[0].password, function (err, same) {
				if (err) {
					res.status(500)
						.json({
							error: 'Internal error please try again'
						});
				} else if (!same) {
					res.status(401).json({
						error: 'Incorrect email or password'
					});
				} else {
					// Issue token
					const payload = {
						username
					};
					res.send(payload)
						.sendStatus(200);
				}
			});
		}
	});
}).post('/register', async (req, res, next) => {

	datas = req.body;

	Users.cryptPassword(req.body.password, (err, hash) => {
		if (err) {
			res.send(err);
		} else {
			datas.password = hash;

			Users.createNewUser(datas, (err, rows) => {
				if (err) {
					res.status(500).json(err);
				} else {
					Users.getUserByKey('username', req.body.username, (err, rows) => {
						if (err) {
							res.status(500).json(err);
						} else {
							res.status(200).json(rows[0]);
						}
					})
				}
			})
		}
	})
}).put('/:id?', async (req, res, next) => {

	if (req.params.id) {
		Users.updateUserById(req.params.id, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Users.updateUsers((err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
}).delete('/:id?', async (req, res, next) => {

	if (req.params.id) {
		Users.deleteUseById(req.params.id, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Users.deleteAllUsers((err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
});

// Subresources for User

usersRouter.use('/:userId/address', addressRouter);
usersRouter.use('/:userId/pictures', picturesRouter);
usersRouter.use('/:userId/likes', likesRouter);
usersRouter.use('/:userId/notifications', notificationsRouter);
usersRouter.use('/:userId/visits', visitsRouter);


module.exports = usersRouter;