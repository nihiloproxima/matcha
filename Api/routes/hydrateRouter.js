const express = require('express');
const chatRouter = express.Router({
    mergeParams: true
});
const Hydrate = require('../models/Hydrate');

// Tags routes
hydrateRouter.get('/:id', async(req, res, next) => {
    if (req.params.id) {
        Hydrate.getRoomInfos(req.params.id, function(err, rows) {
            if (err) {
                res.json(err);
            } else {
                res.json(rows[0]);
            }
        });
    }
});

module.exports = hydrateRouhydrate