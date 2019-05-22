const express = require('express');
const picturesRouter = express.Router({
	mergeParams: true
});
const Pictures = require('../models/pictures');


picturesRouter.get('/:id?', async (req, res, next) => {

	if (req.params.userId) {
		Pictures.getPicturesByUserId(req.params.userId, function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.id) {
		Pictures.getPictureById(req.params.id, function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Pictures.getAllPictures(function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	}
}).post('/', async (req, res, next) => {

	Pictures.createNewPicture(req.query, (err, rows) => {
		err ? res.json(err) : res.json(rows);
	});
}).put('/:id?', async (req, res, next) => {

	if (req.params.userId) {
		Pictures.updatePicturesByUserId(req.params.userId, req.query,  (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.id) {
		Pictures.updatePictureById(req.params.id, req,query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Pictures.updatePictures(req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
}).delete('/:id', async (req, res, next) => {

	if (req.params.userId) {
		Pictures.deletePicturesByUserId(req.params.userId, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Pictures.deletePictureById(req.params.id, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
});

module.exports = picturesRouter;