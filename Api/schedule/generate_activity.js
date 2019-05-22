const Users = require('../models/Users');
const Visits = require('../models/Visits');
const Likes = require('../models/Likes');
const Notifications = require('../models/Notifications');

function getRandomInt(max) {
	return Math.floor(Math.random() * Math.floor(max));
}

var generateActivity = () => {
	for (i = 0; i < 200; i++) {
		Users.getAllUsers((err, rows) => {
			if (err) {
				console.log(err)
			} else {
				var rand = rows[Math.floor(Math.random() * rows.length)];
				var rand2 = rows[Math.floor(Math.random() * rows.length)];
				if (rand.id != rand2.id) {
					Visits.getVisitsBySenderIdAndUserId(rand.id, rand2.id, (err, res) => {
						if (err)
							console.log(err);
						if (res.length == 0) {
							Visits.getVisitsBySenderIdAndUserId(rand2.id, rand.id, (err, data) => {
								if (err)
									console.log(err);
								else if (data.length == 0) {
									// New visit
									Visits.createNewVisit({
										sender_id: rand2.id,
										user_id: rand.id
									}, (err) => {
										if (err)
											console.log(err);
										else
											console.log(rand2.username + " visited " + rand.username);
									});
									Notifications.createNewNotification({
										user_id: rand.id,
										sender_id: rand2.id,
										object: "New Visit",
										content: rand2.username + " just visited your profile."
									})
								} else {
									console.log(rand2.username + " already visited " + rand.username);
								}
							});
						} else {
							console.log(rand.username + " already visited " + rand2.username);
						}
					});
					Likes.getLikesBySenderId(rand2.id, (err, likes) => {
						randomInt = getRandomInt(100);
						if (err)
							console.log(err);
						else if (likes.length == 0 && randomInt <= 50 && rand2.target_gender == rand.gender) {
							Likes.createNewLike({
								sender_id: rand2.id,
								user_id: rand.id
							}, (err) => {
								if (err)
									console.log(err);
								else
									console.log(rand2.username + " liked " + rand.username);
							})
						} else {
							console.log(randomInt + " >= 30 " + rand2.username + " didnt liked " + rand.username);
						}
					})
				} else {
					console.log("same users");
				}
			}
		});
	}
	var d = new Date();

	var datestring = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear() + " - " +
		d.getHours() + ":" + d.getMinutes();
	console.log(datestring + " : Scheduled task : generate random visits -> OK")
}

module.exports = generateActivity;