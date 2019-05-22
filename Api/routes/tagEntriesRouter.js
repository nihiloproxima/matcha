const express = require('express');
const tagEntriesRouter = express.Router({
	mergeParams: true
});
const TagEntries = require('../models/tagEntries');

tagEntriesRouter.get('/:id?', async (req, res, next) => {

	if (req.params.tagId || req.query.tag_id) {
		TagEntries.getTagEntriesByTagId(req.params.tagId || req.query.tag_id, function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.id) {
		TagEntries.getTagEntryById(req.params.id, function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		TagEntries.getAllTagEntries(function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	}
}).post('/', async (req, res, next) => {

	TagEntries.createTagEntry(req.query, (err, rows) => {
		err ? res.json(err) : res.json(rows);
	});
}).put('/:id?', async (req, res, next) => {

	if (req.params.id) {
		TagEntries.updateTagEntriesById(req.params.id, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		TagEntries.updateTagEntries(req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		})
	}
}).delete('/:id', async (req, res, next) => {

	if (req.params.id) {
		TagEntries.deleteTagEntriesById(req.params.id, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		TagEntries.deleteTagEntries((err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
});

module.exports = tagEntriesRouter;