const express = require('express');
const tagsRouter = express.Router({
	mergeParams: true
});
const Tags = require('../models/Tags');

// Subresources
const tagEntriesRouter = require('./tagEntriesRouter');

// Tags routes
tagsRouter.get('/:id?', async (req, res, next) => {

	if (req.params.id) {
		Tags.getTagById(req.params.id, function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Tags.getAllTags(function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	}
}).post('/', async (req, res, next) => {

	Tags.createNewTag(req.query, (err, rows) => {
		err ? res.json(err) : res.json(rows);
	})
}).put('/:id?', async (req, res, next) => {

	if (req.params.id) {
		Tags.updateTagById(req.params.id, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Tags.updateTags((err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
}).delete('/:id?', async (req, res, next) => {

	if (req.params.id) {
		Tags.deleteTagById(req.params.id, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Tags.deleteAllTags((err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
});

tagsRouter.use('/:tagId/tagentries', tagEntriesRouter);

module.exports = tagsRouter;