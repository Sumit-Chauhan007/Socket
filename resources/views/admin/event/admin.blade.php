<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y row">
            <div class="col-6">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Posts /</span> Testimonials</h4>
            </div>
            <div class="col-6">
                <div class="text-end upgrade-btn">
                    <a href="/add-testimonials" class="btn btn-primary text-white">Add New Testimonials</a>
                </div>
            </div>
            <div class="card">
                <h5 class="card-header">Testimonial List</h5>
                <div class="table course_div dataTable text-nowrap">
                    <table class="table course_table" id="services">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Place</th>
                                <th scope="col">Description</th>
                                <th scope="col">Event Actual Date</th>
                                <th scope="col">Event Created Date</th>
                                <th scope="col" >Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @if (count($events) != 0)
                            @foreach ($events as $key => $event)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td><img src="assets/images/event/{{ $event->image }}" alt="" style="max-width: 100px"></td>
                                <td>{{ $event->name }}</td>
                                <td>{{ $event->place }}</td>
                                <td>{{ $event->description }}</td>
                                <td>{{ $event->date }}</td>
                                <td>{{ $event->created_at }}</td>
                                <td>
                                    <a href="/edit-event/{{$event->uuid}}"><button class="btn btn-primary">edit event</button></a>
                                    <a href="/delete-event/{{$event->uuid}}"><button class="btn btn-primary">delete event</button></a>
                                </td>
                            </tr>
                        @endforeach
                            @else
                         <span>{{ 'No record found' }}</span>

                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>
