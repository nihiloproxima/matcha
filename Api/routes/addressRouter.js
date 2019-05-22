const express = require('express');
const addressRouter = express.Router({ mergeParams: true });
const Address = require('../models/Address');

addressRouter.get('/:id?', async (req, res, next) => {

	if (req.params.userId) {
		Address.getAddressByUserId(req.params.userId, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		})
	}
	else if (req.params.id) {
		Address.getAddressById(req.params.id, function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Address.getAllAddress(function (err, rows) {
			err ? res.json(err) : res.json(rows);
		});
	}
}).post('/', async (req, res, next) => {

	Address.createNewAddress(req.query, (err, rows) => {
		err ? res.json(err) : res.json(rows);
	})
}).put('/:id?', async (req, res, next) => {

	if (req.params.userId) {
		Address.updateAddressByUserId(req.params.userId, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else if (req.params.id) {
		Address.updateAddressById(req.params.id, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Address.updateAddress(req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	}
}).delete('/:userId?', async (req, res, next) => {

	if (req.params.userId) {
		Address.deleteAddressbyId(req.params.id, req.query, (err, rows) => {
			err ? res.json(err) : res.json(rows);
		});
	} else {
		Address.deleteAllAddress((err, rows) => {
			err ? res.json(err) : res.json(rows);

		});
	}
});

module.exports = addressRouter;