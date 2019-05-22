const express = require('express');
const notificationsRouter = express.Router({
	mergeParams: true
});
const Notifications = require('../models/Notifications');

notificationsRouter.get('/:id?', async (req, res, next) => {

	if (req.query.sender_id || req.query.user_id) {
		if (req.query.sender_id && req.query.user_id) {
			Notifications.getNotificationsBySenderIdAndUserId(req.query.sender_id, req.query.user_id, (err, rows) => {
				err ? res.json(err) : res.json(rows);
			});
		} else if (req.query.sender_id) {
			Notifications.getNotificationsBySenderId(req.query.sender_id, (err, rows) => {
				err ? res.json(err) : res.json(rows);
			});
		} else {
			Notifications.getNotificationsByUserId(req.query.user_id, (err, rows) => {
				err ? res.json(err) : res.json(rows);
			});
		}
	} else if (req.params.userId) {
		console.log("ok");
		Notifications.getNotificationsByUserId(req.params.userId, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		})
	} else if (req.params.id) {
		Notifications.getNotificationsById(req.params.id, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Notifications.getAllNotifications((err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
}).post('/', async (req, res, next) => {

	Notifications.createNotification(req.query, (err, rows) => {
		err ? res.json(err) : res.json(rows);
	});
}).put('/:id?', async (req, res, next) => {

	if (req.params.userId) {
		Notifications.updateNotificationsByUserId(req.params.userId, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.id) {
		Notifications.updateNotificationsById(req.params.id, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Notifications.updateNotifications(req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		})
	}
}).delete('/:id', async (req, res, next) => {

	if (req.params.userId) {
		Notifications.deleteNotificationsByUserId(req.params.userId, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else if (releaseEvents.params.id) {
		Notifications.deleteNotificationsById(req.params.id, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Notifications.deleteAllNotifications((err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
});

module.exports = notificationsRouter;