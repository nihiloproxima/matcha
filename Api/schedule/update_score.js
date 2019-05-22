const Users = require('../models/Users');

var scoreCalc = () => {
    usersStats = Users.getUsersStats((err, rows) => {
        if (err) {
            console.log(err)
        }
        else {
            for (var i = 0; i < rows.length; i++) {
                // Algo is pretty basic, but it also helps new users to rapidly gain visibility, the ratio will then be regulated by visits

                var score = (rows[i].likes_count * 10) + (rows[i].visits_count * 3) + ((rows[i].likes_count / rows[i].visits_count) * 10);
                if (isNaN(score)) {
                    score = 0;
                }
                // Uncomment the following for a detail of the score calculation
                console.log(rows[i].username + "'s score set to --> " + score);
                Users.updateUserById(rows[i].id, { popularity_score: score });
            }
        }
    });
    var d = new Date();

    var datestring = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear() + " - " +
        d.getHours() + ":" + d.getMinutes();
    console.log(datestring + " : Scheduled task : update user's popularity score -> OK")
}

module.exports = scoreCalc;
