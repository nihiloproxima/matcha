async (req, res, next) => {

	Users.getUserByKey('username', req.body.username, (err, rows) => {
		if (err) {
			res.status(500).send(err);
		}
		var user = rows[0];

		if (!user) {
			res.status(404).send("User does not exists.");
		} else {
			Users.comparePassword(req.body.password, user.password, (err, isPassWordMatch) => {
				if (err) {
					res.status(500).json(err);
				} else if (isPassWordMatch) {
					res.status(200).send(user);
				} else {
					res.status(401).send("ko");
				}
			})
		};
	});