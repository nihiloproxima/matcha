const express = require('express');
const likesRouter = express.Router({
	mergeParams: true
});
const Likes = require('../models/Likes');

likesRouter.get('/:id?', async (req, res, next) => {

	if (req.query.sender_id || req.query.user_id) {
		if (req.query.sender_id && req.query.user_id) {
			Likes.getLikesBySenderIdAndUserId(req.query.sender_id, req.query.user_id, (err, rows) => {
				err ? res.json(err) : res.json(rows);
			});
			ß
		} else if (req.query.sender_id) {
			Likes.getLikesBySenderId(req.query.sender_id, (err, rows) => {
				err ? res.json(err) : res.json(rows);
			});
		} else {
			Likes.getLikesByUserId(req.query.user_id, (err, rows) => {
				err ? res.json(err) : res.json(rows);
			});
		}
	} else if (req.params.id) {
		Likes.getLikesById(req.params.id, function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Likes.getAllLikes(function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	}
}).post('/', async (req, res, next) => {

	Likes.createLike(req.query, (err, rows) => {
		err ? res.json(err) : res.json(rows);
	});
}).put('/:id?', async (req, res, next) => {

	if (req.params.userId) {
		Likes.updateLikesByUserId(req.params.userId, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.id) {
		Likes.updateLikesById(req.params.id, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Likes.updateLikes(req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		})
	}
}).delete('/:id', async (req, res, next) => {

	if (req.query.senderId) {
		Likes.deleteLikesBySenderId(req.query.senderId, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.userId) {
		Likes.deleteLikesByUserId(req.params.userId, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.id) {
		Likes.deleteLikeById(req.params.id, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Likes.deleteLikes((err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
});

module.exports = likesRouter;