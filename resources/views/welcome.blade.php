<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Real time chat application using laravel and socket.io</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>
<div class="container" id="app">
    <div class="row">
        <div class="col-md-6 offset-3 col-12">
            <div class="card mt-5">
                <div class="card-header text-center">
                    Messages
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item" v-if="message_lists.length > 0" v-for="message in message_lists">
                        <span v-bind:class="{'float-right':message.type === 0}">@{{ message.message }}</span>
                    </li>
                    <span class="p-3" v-if="isTyping">Someone is typing</span>
                </ul>
            </div>

            <form @submit.prevent="send" class="mt-2">
                <input type="text" class="form-control" v-model="newMessage" placeholder="Type Messages">
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>
<script src="https://cdn.socket.io/4.2.0/socket.io.min.js"
        integrity="sha384-PiBR5S00EtOj2Lto9Uu81cmoyZqR57XcOna1oAuVuIEjzj0wpqDVfD0JA9eXlRsj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>

<script>
    const socket = io('127.0.0.1:3000');

    var vm = new Vue({
        el: '#app',
        data: {
            newMessage: null,
            message_lists: [],
            isTyping: false,
        },
        methods: {
            send() {
                this.message_lists.push({message: this.newMessage, type: 0});
                socket.emit('sendMessageToServer', this.newMessage);
                this.newMessage = null;
            }
        },
        watch: {
            newMessage: (val) => {
                if (val.length > 0) socket.emit('startTyping', true);
                else socket.emit('stopTyping', true);
            }
        },
        created() {
            socket.on('sendMessageToClient', (data) => {
                this.message_lists.push({message: data, type: 1});
                this.isTyping = false;
            });
            socket.on('startTyping', (data) => this.isTyping = true);
            socket.on('stopTyping', (data) => this.isTyping = false);
        }
    })
</script>
</body>
</html>
