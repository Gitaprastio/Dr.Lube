<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BotMan Studio</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        body {
            font-family: "Source Sans Pro", sans-serif;
            margin: 0;
            padding: 0;
            background: radial-gradient(#57bfc7, #45a6b3);
        }

        .container {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .content {
            text-align: center;
        }

        .ChatLog {
            list-style: none;
            max-width: 20em;
            margin: 0 auto 1em;
            padding: 0;
        }

        .ChatLog__entry {
            display: flex;
            flex-direction: row;
            align-items: flex-end;
            max-width: 100%;
            margin: .5em;
        }

        .ChatLog__entry_mine {
            flex-direction: row-reverse;
        }

        .ChatLog__avatar {
            flex-shrink: 0;
            flex-grow: 0;
            z-index: 1;
            height: 50px;
            width: 50px;
            border-radius: 25px;
        }

        .ChatLog__entry_mine .ChatLog__avatar {
            display: none;
        }

        .ChatLog__message {
            position: relative;
            margin: 0 12px;
            background-color: #ddd;
            padding: .5em;
            border-radius: 4px;
            font-weight: lighter;
            max-width: 70%;
        }

        .ChatLog__message::before {
            position: absolute;
            right: auto;
            bottom: .6em;
            left: -12px;
            height: 0;
            content: '';
            border: 6px solid transparent;
            border-right-color: #ddd;
            z-index: 2;
        }

        .ChatLog__entry_mine .ChatLog__message {
            border-top: 1px solid #07f;
            border-bottom: 1px solid #07f;
            background-color: #08f;
            color: #fff;
        }

        .ChatLog__entry_mine .ChatLog__message::before {
            right: -12px;
            bottom: .6em;
            left: auto;
            border: 6px solid transparent;
            border-left-color: #08f;
        }

        .ChatLog__message__image {
            max-width: 100%;
        }

        .ChatAction {
            display: block;
            padding: 5px;
            border-radius: 5px;
            margin: 5px;
            min-width: 100px;
            background-color: #d3d3d3;
            border: 0;
            cursor: pointer;
        }

        .ChatAttachment {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .ChatAttachment + label,
        .ChatInput {
            height: 25px;
            border-radius: 5px;
            border: none;
            padding: 10px;
        }

        .ChatAttachment + label {
            cursor: pointer;
            display: inline-block;
            background-color: #fff;
        }

        .ChatInput {
            width: 300px;
        }

        .ChatStatus {
            color: #fff;
            font-size: 13px;
            min-height: 18px;
            margin-top: 10px;
        }

        .ChatStatus_error {
            color: #ffe0e0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <ul class="ChatLog" id="chat-log"></ul>

        <input type="file" class="ChatAttachment" id="attachment">
        <label for="attachment">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
            <span>Attachment</span>
        </label>
        <input type="text" class="ChatInput" id="chat-input" autocomplete="off">
        <div class="ChatStatus" id="chat-status" role="status" aria-live="polite"></div>
    </div>
</div>

<script src="/js/app.js"></script>
<script>
(function () {
    var endpoint = '{{ route('botman.handle') }}';
    var userId = '{{ auth()->id() }}';
    var chatLog = document.getElementById('chat-log');
    var input = document.getElementById('chat-input');
    var attachment = document.getElementById('attachment');
    var status = document.getElementById('chat-status');

    function setStatus(message, isError) {
        status.textContent = message || '';
        status.className = 'ChatStatus' + (isError ? ' ChatStatus_error' : '');
    }

    function appendMessage(text, media, isMine, original) {
        var entry = document.createElement('li');
        entry.className = 'ChatLog__entry' + (isMine ? ' ChatLog__entry_mine' : '');

        var avatar = document.createElement('img');
        avatar.className = 'ChatLog__avatar';
        avatar.src = '/logo.png';
        avatar.alt = '';
        entry.appendChild(avatar);

        var bubble = document.createElement('p');
        bubble.className = 'ChatLog__message';
        if (text) {
            bubble.appendChild(document.createTextNode(text));
        }

        if (media && media.url) {
            bubble.appendChild(renderAttachment(media));
        }

        entry.appendChild(bubble);

        if (original && original.type === 'actions' && original.actions) {
            entry.appendChild(renderActions(original));
        }

        chatLog.appendChild(entry);
        chatLog.scrollTop = chatLog.scrollHeight;
    }

    function renderAttachment(media) {
        if (media.type === 'image') {
            var image = document.createElement('img');
            image.className = 'ChatLog__message__image';
            image.src = media.url;
            image.alt = '';
            return image;
        }

        if (media.type === 'video') {
            var video = document.createElement('video');
            video.className = 'ChatLog__message__image';
            video.controls = true;
            video.height = 160;

            var videoSource = document.createElement('source');
            videoSource.src = media.url;
            videoSource.type = 'video/mp4';
            video.appendChild(videoSource);
            return video;
        }

        if (media.type === 'audio') {
            var audio = document.createElement('audio');
            audio.controls = true;

            var audioSource = document.createElement('source');
            audioSource.src = media.url;
            audioSource.type = 'audio/mp3';
            audio.appendChild(audioSource);
            return audio;
        }

        var link = document.createElement('a');
        link.href = media.url;
        link.textContent = 'Attachment';
        return link;
    }

    function renderActions(message) {
        var wrapper = document.createElement('div');

        message.actions.forEach(function (action) {
            var button = document.createElement('button');
            button.type = 'button';
            button.className = 'ChatAction';
            button.textContent = action.text;
            button.addEventListener('click', function () {
                message.actions = null;
                wrapper.innerHTML = '';
                sendToBot(action.value, true);
            });
            wrapper.appendChild(button);
        });

        return wrapper;
    }

    function sendToBot(text, interactive, attachmentType, file) {
        setStatus('');

        var data = new FormData();
        data.append('driver', 'web');
        data.append('userId', userId);
        data.append('message', text || '');
        data.append('interactive', interactive ? '1' : '0');

        if (attachmentType) {
            data.append('attachment', attachmentType);
        }

        if (file) {
            data.append('attachment_data', file);
        }

        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: data,
            credentials: 'same-origin'
        })
            .then(function (response) {
                return response.text().then(function (body) {
                    var payload = {};

                    if (body) {
                        try {
                            payload = JSON.parse(body);
                        } catch (error) {
                            throw new Error('The chat endpoint returned an invalid response.');
                        }
                    }

                    if (!response.ok || (payload.status && payload.status >= 400)) {
                        if (response.status === 401) {
                            throw new Error('Please sign in again before using chat.');
                        }

                        if (response.status === 419) {
                            throw new Error('The chat session expired. Refresh the page and try again.');
                        }

                        throw new Error('The chat request failed.');
                    }

                    return payload;
                });
            })
            .then(function (payload) {
                (payload.messages || []).forEach(function (message) {
                    appendMessage(message.text, message.attachment, false, message);
                });
            })
            .catch(function (error) {
                setStatus(error.message || 'The chat request failed.', true);
            });
    }

    input.addEventListener('keydown', function (event) {
        if (event.key !== 'Enter') {
            return;
        }

        var message = input.value.trim();
        input.value = '';

        if (message === 'clear') {
            chatLog.innerHTML = '';
            return;
        }

        if (message === '') {
            return;
        }

        appendMessage(message, null, true);
        sendToBot(message, false);
    });

    attachment.addEventListener('change', function () {
        var file = attachment.files[0];
        if (!file) {
            return;
        }

        var type = file.type.split('/')[0];
        if (['audio', 'image', 'video'].indexOf(type) === -1) {
            type = 'file';
        }

        var reader = new FileReader();
        reader.addEventListener('load', function () {
            appendMessage(null, { type: type, url: reader.result }, true);
            sendToBot(null, false, type, file);
            attachment.value = '';
        });
        reader.readAsDataURL(file);
    });
}());
</script>
</body>
</html>
