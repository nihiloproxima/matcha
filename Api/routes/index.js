const express = require('express');
const router = express.Router();

router.use('/users', require('./usersRouter'));
router.use('/chat', require('./chatRouter'));
router.use('/address', require('./addressRouter'));
router.use('/pictures', require('./picturesRouter'));
router.use('/likes', require('./likesRouter'));
router.use('/notifications', require('./notificationsRouter'));
router.use('/tags', require('./tagsRouter'));
router.use('/tagentries', require('./tagEntriesRouter'));
router.use('/visits', require('./visitsRouter'));
router.use('/blacklist', require('./blacklistRouter'));

router.get('/home', (req, res) => {
	res.send("C'est chargé lol");
}).get('/checkToken', (req, res) => {
	res.sendStatus(200);
})

module.exports = router;