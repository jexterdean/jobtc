/*
 * Socket.io js Server 
 * For Auto Update of containers
 * For Chat Box
 * For Browser to Browser Calling/Video Calling
 * For Interview Recording
 **/
var isUseHTTPs = !(!!process.env.PORT || !!process.env.IP);
var exec = require('child_process').exec;
var sys = require('sys');
var ws = require('ws');
var fs = require('fs');
var url = require('url');
var path = require('path');
var express = require('express');
var app = express();
var uuid = require('node-uuid');
var io = require('socket.io');

var options = {
    //Production
    key: fs.readFileSync("/var/www/html/hirefitnet/public/certs/apache.job.tc.key"),
    cert: fs.readFileSync("/var/www/html/hirefitnet/public/certs/apache.job.tc.crt")
    //Local
    //key: fs.readFileSync("E://xampp-new/htdocs/laravel-pm/main-app/public/certs/apache.key"),
    //cert: fs.readFileSync("E://xampp-new/htdocs/laravel-pm/main-app/public/certs/apache.crt")
};

var server = require(isUseHTTPs ? 'https' : 'http');
var concat;

app.get('/', function (req, res) {
    res.send('<h1>Real Time Server </h1>');
});

if (isUseHTTPs) {
    server = require('https').createServer(options, app);

} else {
    server = require('http').createServer(app);
}

server.listen(3000, function () {
    console.log('Listening on Port 3000');
    console.log(isUseHTTPs);
});

io = io.listen(server);


io.on('connection', function (socket) {
    console.log('client connected');
    var room_name;
    /*
     * This is for creating a socket.io room per applicant 
     **/
    socket.on('create', function (room) {
        console.log('Joining Room,' + room);
        socket.join(room);
        room_name = room;
    });

    /*
     * This is for applicant comments 
     **/
    socket.on('applicant-comment', function (msg) {
        io.to(room_name).emit('applicant-comment', msg);
    });
    /*
     * This is for task list items
     **/
    socket.on('add-task-list-item', function (msg) {
        console.log('Sent Task list');
        console.log(msg.room_name);
        io.to(msg.room_name).emit('add-task-list-item', msg);
    });
    
    /*
     * This is for task comments
     **/
    socket.on('task-comment', function (msg) {
        //console.log('task-comment: '+msg);
        io.emit('task-comment', msg);
    });
    /*
     *This is for adding tasks
     **/
    socket.on('add-task', function (task) {
        io.emit('add-task', task);
    });
    /*
     * This is for Dropping tasks to a new day or time
     **/
    socket.on('calendar-drop-task', function (task) {
        console.log('calendar-drop-task: ' + JSON.stringify(task));
        io.emit('calendar-drop-task', task);
    });

    socket.on('add-video', function (video) {
        console.log('Adding video to Room: ' +room_name);
        io.to(room_name).emit('add-video', video);
    });
    
    socket.on('delete-video', function (video) {
        console.log('Deleting video to Room: ' +room_name);
        io.to(room_name).emit('delete-video', video);
    });
});
