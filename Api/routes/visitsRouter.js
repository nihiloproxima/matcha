const express = require('express');
const visitsRouter = express.Router({
	mergeParams: true
});
const Visits = require('../models/Visits');

visitsRouter.get('/:id?', async (req, res, next) => {

	if (req.query.sender_id || req.query.user_id) {
		if (req.query.sender_id && req.query.user_id) {
			Visits.getVisitsBySenderIdAndUserId(req.query.sender_id, req.query.user_id, (err, rows) => {
				err ? res.json(err) : res.json(rows);
			});
			ß
		} else if (req.query.sender_id) {
			Visits.getVisitsBySenderId(req.query.sender_id, (err, rows) => {
				err ? res.json(err) : res.json(rows);
			});
		} else {
			Visits.getVisitsByUserId(req.query.user_id, (err, rows) => {
				err ? res.json(err) : res.json(rows);
			});
		}
	} else if (req.params.id) {
		Visits.getVisitsById(req.params.id, function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Visits.getAllVisits(function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	}
}).post('/', async (req, res, next) => {

	Visits.createVisit(req.query, (err, rows) => {
		err ? res.json(err) : res.json(rows);
	});
}).put('/:id?', async (req, res, next) => {

	if (req.params.userId) {
		Visits.updateVisitsByUserId(req.params.userId, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.id) {
		Visits.updateVisitsById(req.params.id, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Visits.updateVisits(req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		})
	}
}).delete('/:id', async (req, res, next) => {

	if (req.query.senderId) {
		Visits.deleteVisitsBySenderId(req.query.senderId, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.userId) {
		Visits.deleteVisitsByUserId(req.params.userId, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.id) {
		Visits.deleteVisitById(req.params.id, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Visits.deleteVisits((err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
});

module.exports = visitsRouter;