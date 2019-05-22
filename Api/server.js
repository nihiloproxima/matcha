const express = require('express');
const cors = require('cors');
const cookieParser = require('cookie-parser');
const bodyParser = require('body-parser');
const fs = require('fs')
const https = require('https')
const PORT = 5000;
const Chat = require('./models/Chat');
const Blacklist = require('./models/Blacklist');
const Pictures = require('./models/Pictures');
const Notifications = require('./models/Notifications');
var schedule = require('node-schedule');
const scoreCalc = require('./schedule/update_score');
const generateActivity = require('./schedule/generate_activity');
const newUsers = require('./schedule/new_users');

const app = express();

app.use(bodyParser.urlencoded({
    extended: false
}));
app.use(bodyParser.json());
app.use(cookieParser());

app.use(cors());
app.use(cookieParser());

app.use(express.json());

app.use('/api/v1/', require('./routes'));

server = https.createServer({
    key: fs.readFileSync('server.key'),
    cert: fs.readFileSync('server.cert')
}, app);

const io = require('socket.io')(server, {
    origins: '*:*'
});

server.listen(PORT, function() {
    console.log('Listening on port 5000! Go to https://localhost:5000/')
})
var rule = new schedule.RecurrenceRule();

rule.second = [0, 20, 40];
rule.second = [0, 20, 40];

// schedule.scheduleJob(rule, generateActivity);
// schedule.scheduleJob(rule, scoreCalc);

io.on('connection', (socket) => {

    socket.on('change_username', (data) => {
        socket.username = data.username;
        console.log(socket.id + " is now called : " + socket.username);
    });

    socket.on('visit', (data) => {
        console.log("New visit " + data);
    })

    socket.on('join', (data) => {
        socket.join(data.roomId);
        Chat.readConversation(data.roomId, data.userId, (err, rows) => {
            if (err)
                console.log(err);
            else {
                console.log("Conversation read, check for unread msg");
                Chat.hasUnreadMessages(data.userId, (err, rows) => {
                    if (err) {
                        console.log(err);
                    } else {
                        if (rows[0].Total == 0) {
                            console.log(data.userId);
                            io.sockets.to('notif-' + data.userId).emit('no-unread-msg');
                        }
                    }
                });
            }
        });
        console.log("joined " + data.roomId);
    })

    socket.on('new_message', (data) => {
        console.log("Emit message to room : " + data.roomId + " from " + data.username);
        infos = {
            chatroom_id: data.roomId,
            sender_id: data.sender_id,
            target_id: data.target_id,
            content: data.content
        };

        Chat.createNewMessage(infos, (err, rows) => {
            if (err) {
                console.log(err);
            }
            console.log("Message saved.");
        })

        var path = '';
        Pictures.getPicturesByUserId(infos.sender_id, (err, rows) => {
            if (err) {
                console.log(err);
            } else if (rows[0]) {
                path = rows[0].path;
            } else {
                path = 'assets/uploads/default_user.jpeg';
            }
            io.sockets.to(data.roomId).emit('new_message', {
                message: data.content,
                username: data.username,
                roomId: data.roomId,
                path: path
            });
        });
        Blacklist.isBlacklistedBy(infos.sender_id, infos.target_id, (err, rows) => {
            if (err)
                console.log(err);
            else {
                if (rows[0].count == 0) {
                    io.sockets.to('notif-' + data.target_id).emit('new-msg-notif', data);
                }
            }
        })
    })

    // Notifications

    socket.on('join-notif', (data) => {
        console.log("User joined notifications : notif-" + data.id);
        socket.join('notif-' + data.id);
        Notifications.getNotificationsByUserId(data.id, (err, rows) => {
            if (err)
                console.log(err)
            else {
                for (i = 0; i < rows.length; i++) {
                    io.sockets.to('notif-' + data.id).emit('new-notification', rows[i]);
                }
                console.log("Notification charged and sent to user.")
            }
        });
        Chat.hasUnreadMessages(data.id, (err, rows) => {
            if (err) {
                console.log(err);
            } else {
                if (rows[0].Total > 0) {
                    io.sockets.to('notif-' + data.id).emit('new-msg-notif');
                }
            }
        });
    });

    socket.on('send-notification', (data) => {
        Blacklist.isBlacklistedBy(data.userId, data.sender, (err, rows) => {
            if (err)
                console.log(err);
            else {
                if (rows[0].count == 0)
                    io.sockets.to('notif-' + data.user_id).emit('new-notification', data);
            }
        })
    })

    socket.on('msg-notif', (data) => {
        Blacklist.isBlacklistedBy(data.userId, data.sender, (err, rows) => {
            if (err)
                console.log(err);
            else {
                if (rows[0].count == 0)
                    io.sockets.to('notif-' + data.target_id).emit('new-msg-notif', data);
            }
        })
    })
})
