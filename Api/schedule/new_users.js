const Users = require('../models/Users');
const request = require('request');

var newUsers = () => {
    request.post('http://localhost/index.php/admin/hydratation/', { form: { number: 3 } }, function(error, response, body) {
        if (error)
            console.log(error);
        else
            console.log(body);
        var d = new Date();

        var datestring = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear() + " - " +
            d.getHours() + ":" + d.getMinutes();
        console.log(datestring + " : New users creation -> OK")
    });
}

module.exports = newUsers;