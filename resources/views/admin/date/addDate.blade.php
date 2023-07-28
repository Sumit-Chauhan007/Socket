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
                            <form method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="basic-icon-default-fullname">Event Name</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" name="EventName" id="EventName"
                                            placeholder="Enter Event name"
                                            class="form-control input form-control-line" minlength="1" >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-6">
                                        <label class="form-label" for="basic-icon-default-fullname">Event Designation
                                        </label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" name="EventPlace" id="EventPlace"
                                                placeholder="Enter Designation"
                                                class="form-control input form-control-line" minlength="1" maxlength="30" >
                                        </div>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label" for="basic-icon-default-fullname">Event Designation
                                        </label>
                                        <div class="input-group input-group-merge">
                                            <input type="date" name="EventDate" id="EventDate"
                                                class="form-control input form-control-line" minlength="1" maxlength="30" >
                                        </div>
                                    </div>
                                    <div class="mb-3 col-6">
                                        <label class="form-label" for="basic-icon-default-fullname">Event Image
                                        </label>
                                        <div class="input-group input-group-merge">
                                            <input type="file" id="EventImage" accept="image/png, image/gif, image/jpeg"
                                                name="EventImage" class="form-control form-control-line">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-icon-default-fullname">Event Description </label>
                                    <div class="input-group input-group-merge">
                                        <textarea name="EventDescription" id="EventDescription" maxlength="150" class="form-control "></textarea>
                                    </div>
                                </div>
                                <button type="button" onclick="addEvent()"
                                    class="btn btn-success text-white">Save</button>

                            </form>
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
                url: "{{ url('/add-new-date') }}",
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
