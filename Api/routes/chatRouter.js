const express = require('express');
const chatRouter = express.Router({
	mergeParams: true
});
const Chat = require('../models/Chat');

// Tags routes
chatRouter.get('/:id', async (req, res, next) => {
	if (req.params.id) {
		Chat.getRoomInfos(req.params.id, function (err, rows) {
			if (err) {
                res.json(err);
            } else {
                res.json(rows[0]);
            }    
		});
	}
});

module.exports = chatRouter;