const express = require('express');
const blacklistRouter = express.Router({
	mergeParams: true
});
const Blacklist = require('../models/Blacklist');

blacklistRouter.get('/?', async (req, res, next) => {
	console.log(req);
});

module.exports = blacklistRouter;