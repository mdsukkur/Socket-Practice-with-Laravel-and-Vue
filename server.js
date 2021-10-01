const express = require('express');
const app = express();
const http = require('http').createServer(app);
const io = require('socket.io')(http, {
    cors: {origin: '*'}
})

io.on('connection', function (socket) {
    console.log('socket connected');

    socket.on("sendMessageToServer", function (message) {
        socket.broadcast.emit("sendMessageToClient", message);
    })

    socket.on('disconnect', function () {
        console.log('socket disconnected');
    })
})

http.listen(3000, () => {
    console.log('listening on *:3000');
});
