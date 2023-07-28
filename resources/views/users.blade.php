<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
        <div class="container">
            <div class="row">
                <ul>
                    @foreach($users as $user)
                    <li><a href="chatroom/{{$user->id}}">{{$user->name}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
</body>
</html>
