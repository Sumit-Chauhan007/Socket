<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> Add New Event</h4>
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Event Details</h5>
                             </div>
                        <div class="card-body size_box">
                              <div class="container">
                                <div class="row">
                                    <div class="mb-3 col-xl-8">
                                        <label for="EventName" class="form-label">Event Name</label>
                                        <input type="text" class="form-control" id="EventName" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-3 col-xl-8">
                                        <label for="EventPlace" class="form-label">Place</label>
                                        <input type="text" class="form-control" id="EventPlace" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-3 col-xl-8">
                                        <label for="EventDescription" class="form-label">Description</label>
                                        <input type="text" class="form-control" id="EventDescription" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-3 col-xl-8">
                                        <label for="EventImage" class="form-label">Image</label>
                                        <input type="file" class="form-control" id="EventImage" accept="image/png, image/gif, image/jpeg" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-3 col-xl-8">
                                        <label for="EventDate" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="EventDate" aria-describedby="emailHelp">
                                    </div>
                                </div>
                              </div>
                                <button type="button" onclick="addEvent()"
                                    class="btn btn-success text-white">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-backdrop fade"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
        integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script>
        function addEvent() {
            var formData = new FormData();
            var EventName = $('#EventName').val();
            var EventPlace = $('#EventPlace').val();
            var EventImage = $('#EventImage').prop('files')[0];
            var EventDate = $("#EventDate").val();
            var EventDescription = $("#EventDescription").val();
            formData.append('EventName', EventName);
            formData.append('EventPlace', EventPlace);
            formData.append('EventDate', EventDate);
            formData.append('EventDescription', EventDescription);

            if (EventImage) {
                formData.append('EventImage', EventImage);
            }
            $.ajax({
                type: "post",
                url: "{{ url('/add-new-event') }}",
                contentType: 'multipart/form-data',
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: formData,
                success: function(data) {
                    $('.errorSpan').html(' ');
                    if ($.isEmptyObject(data.error)) {
                        window.location.href = data.success;
                    } else {
                        $.each(data.error, function(field_name, error) {
                            $(document).find('#' + field_name + '').closest('div').after(
                                '<span class="text-strong text-danger errorSpan">' + error + '</span>')

                        })
                    }
                }

            })
        }
    </script>
</body>

</html>
