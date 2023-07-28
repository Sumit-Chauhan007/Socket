<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Chat App</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="chat-section">
                <div class="chat-box">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Your message</label>
                        <input type="text" class="form-control" id="chatInput" aria-describedby="emailHelp">

                    </div>

                    <div class="clientchatinput">
                        <ul style="list-style: none">
                            @foreach ($messages as $message)
                                <?php
                                $mess = DB::table('messages')
                                    ->where('uuid', $message->uuid)
                                    ->where('status', 'Active')
                                    ->first(); ?>
                                @if ($message->userCurrentId == $currentUser)
                                    @if ($mess)
                                        <li align="right" style="color:red">{{ $mess->message }} <a
                                                href="javascript:void(0)" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{ $mess->uuid }}"><i
                                                    class="fa fa-remove" style="font-size:24px"></i></a></li>
                                    @endif
                                @else
                                    <li style="color:black">
                                        {{ $message->message }} </li>
                                @endif
                                <!-- Modal -->
                                @if ($mess)
                                    <div class="modal fade" id="exampleModal{{ $mess->uuid }}" tabindex="-1"
                                        aria-labelledby="exampleModal{{ $mess->uuid }}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5"
                                                        id="exampleModal{{ $mess->uuid }}Label">
                                                        Modal title</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <a href="/delete/{{ $mess->uuid }}">delete for everyone</a><br>
                                                    <a href="/delete-temp/{{ $mess->uuid }}">delete for me</a>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js"
        integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous">
    </script>

    <script>
        $(function() {
            let ip_address = '127.0.0.1';
            let socket_port = '3000';
            let socket = io(ip_address + ':' + socket_port)
            socket.on('connection');
            var user1 = "{{ $currentUser }}";
            var user2 = "{{ $users->id }}";
            if (user1.charCodeAt(0) > user2.charCodeAt(0)) {
                $roomId = user1 + user2;
            } else {
                $roomId = user2 + user1;
            }
            console.log($roomId);
            let chatInput = $('#chatInput');

            chatInput.keypress(function(e) {
                let message = $(this).val();
                var formData = new FormData();
                formData.append('message', message);
                formData.append('roomId', $roomId);
                formData.append('currentUser', user1);
                if (e.which == 13 && !e.shiftKey) {
                    $.ajax({
                        type: "post",
                        url: "/store-chat",
                        contentType: 'multipart/form-data',
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        success: function(response) {
                            chatInput.val('');
                            const eventName = 'sendChatToMyServer' + $roomId;
                            socket.emit('sendChatToMyServer', {
                                roomId: $roomId,
                                message: message,
                                id1: user1,
                                uuid: response.uuid
                            });
                            return false;
                        }
                    });

                }
            });
            const clientId = 'sendChatToMyClient' + $roomId;
            socket.on(clientId, (data) => {
                console.log(data);
                console.log('current user id' + data.current);
                console.log(user1);
                if (data.current == user1) {
                    $('.clientchatinput ul').append(`<li  style="color:red" align="right" >${data.message}  <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal${data.uuid}"><i class="fa fa-remove" style="font-size:24px"></i></a></li>


                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal${data.uuid}" tabindex="-1" aria-labelledby="exampleModal${data.uuid}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModal${data.uuid}Label">Modal title</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <a href="/delete/${data.uuid}">delete for everyone</a><br>
                                    <a href="/delete-temp/${data.uuid}">delte for me</a>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                            </div>`)
                } else {
                    $('.clientchatinput ul').append(`<li>${data.message}  </li>`)
                }
            })
        })
    </script>
</body>

</html>
