    <!-- jquery 3.3.1 -->
    <script src="{{ asset('assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <!-- bootstap bundle js -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <!-- slimscroll js -->
    <script src="{{ asset('assets/vendor/slimscroll/jquery.slimscroll.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset('assets/libs/js/main-js.js') }}"></script>
</body>
</html>
<script>
    var counter=0;
    $( "#addrow" ).click(function() {
        counter++;
    $('#fields').append('<br><div class="col-xl-3 form-group"><label for="exampleInputEmail1">Member name</label><input type="text" name="member_name'+counter+'" class="form-control" id="member_name" placeholder="Member name"></div>');
    $('#fields').append('<div class="col-xl-3 form-group"><label for="exampleInputEmail1">Job Title</label><input type="text" name="job_title'+counter+'" class="form-control" id="job_title" placeholder="Job Title"></div></div>');
    $('#fields').append('<div class="col-xl-3 form-group"><label for="exampleInputEmail1">Picture</label><input type="file" name="picture'+counter+'" class="form-control" id="picture" placeholder="picture"></div></div>');
    $('#fields').append('<button type="button" class="btn btn-default" data-dismiss="modal">Delete</button>');
    });
    $( "#deleterow" ).click(function() {
        counter++;
    $('#fields').
    });
    </script>


