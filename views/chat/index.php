<div class="row p-2">
    <div class="col-md-2 p-2">
        Status: <span class="connection-status"></span>
    </div>
    <div class="col-md-6 ps-1">
        <div class="float-md-end">
            <button class="btn btn-success btn-connect shadow-none me-2 float-md-start">Connect</button>
            <button class="btn btn-danger btn-disconnect shadow-none float-md-start">Disconnect</button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card chat">
            <div class="card-body chat-messages">
            </div>
            <div class="card-footer d-flex">
                <?php if($isGuest):?>
                Login for messaging
                <?php else: ?>
                    <textarea rows="1" spellcheck="true" id="message" name="message" placeholder="Type message ..."
                              class="form-control shadow-none"></textarea>
                <button class="btn btn-primary send-button">Send</button>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<div class="message-template">
    <div class="chat-message">
        <div class="message-name"></div>
        <div class="message-text"></div>
        <div class="clearfix">
            <div class="message-timestamp"></div>
        </div>
    </div>
</div>

<?php

$appKey = $_ENV['PUSHER_APP_KEY'];
$appCluster = $_ENV['PUSHER_APP_CLUSTER'];

$js = <<<JS
    var lastMessageId = 0;

    addHistory(lastMessageId);

    var pusher = new Pusher('$appKey', {
        cluster: '$appCluster'
    });

    pusher.connection.bind('connecting', function() {
        $('.connection-status').html('connecting');
    });
    pusher.connection.bind('connected', function() {
        $('.connection-status').html('connected');
    });
    pusher.connection.bind('disconnected', function() {
        $('.connection-status').html('disconnected');
    });

    var channel = pusher.subscribe('chat');
    channel.bind('message', function(data) {
        addMessage(data);
        lastMessageId = data.id;
        scrollDown();
    });

    $('.btn').mouseup(function() { this.blur() });

    $('.btn-connect').click(function() {
        pusher.connect();
    });

    $('.btn-disconnect').click(function() {
        pusher.disconnect();
    });

    $('.send-button').click(function() {
        var message = $('#message').val();
        $('#message').val('')
        sendMessage(message);
    });

    function sendMessage(message) {
        $.ajax({
                type: "POST",
                dataType: "json",
                url: "/chat/send-message",
                data: {message: message},
        });
    }

    function addMessage(data) {
        var message =  $('.message-template').clone();
        var date = new Date();

        date.setTime(parseInt(data.timestamp) * 1000)

        message.find('.message-name').html(data.name);
        message.find('.message-text').html(data.text);
        message.find('.message-timestamp').html(date.toLocaleString());
        $('.chat-messages').append(message.html());
    }

    function addHistory(afterId) {
        $.ajax({
                type: "GET",
                dataType: "json",
                url: "/chat/get-messages",
                data: {after_id: afterId},
        }).done(function(data) {
            $.each(data, function(el) {
                addMessage(this);
            });
             scrollDown();
        });
    }

    function scrollDown() {
        $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
    }

JS;

$this->registerJs($js);
