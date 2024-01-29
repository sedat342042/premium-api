@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<!-- Content Header (Page header) -->
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
            <h1 class="mt-4">Updates</h1>
            <table class="table mt-4">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Content</th>
                  <!-- Add more table headers for other properties if needed -->
                </tr>
              </thead>
              <tbody id="updatesTableBody">
                <!-- Table rows will be dynamically added here using JavaScript -->
              </tbody>
            </table>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
<!-- /.content-header -->
@stop

@section('content')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop





@section('js')
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  
<script>
/*
Pusher.logToConsole = true;

const pusher = new Pusher('28827d33c562e13a93c1', {
    encrypted: true, 
    cluster: 'ap4',
    authEndpoint: '/broadcasting/auth',
    auth: {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        }
    }
});

var channel = pusher.subscribe('presence-users');
      channel.bind('users-status', function(data) {
            console.log(data);
      });
*/
      //var pusher = new Pusher("28827d33c562e13a93c1", { encrypted: true, 
        //cluster:'ap4', channelAuthorization: { endpoint: "/pusher_auth.php"}  });
    var pusher = new Pusher('28827d33c562e13a93c1', {
        encrypted: true, 
        cluster:'ap4',
        authEndpoint: '/broadcasting/auth',
      });
/*
      // Subscribe to the channel we specified in our Laravel Event
      var id = `<?php echo Auth::id(); ?>`;
      var channel = pusher.subscribe('presence-user.'+id);
      channel.bind('users-status', function(data) {
            //console.log(data);
      });
      pusher.getChannelMembers('presence-user.'+id, function(members) {
         console.log(members);
    });*/



      var updatesChannel = pusher.subscribe('presence-updates');
    updatesChannel.bind('UpdateCreated', function(data) {
        var count = updatesChannel.members.count;
        console.log('There are ' + count + ' users online.');
      updateUpdatesTable(data); // Add the new update to the beginning
    });

     // Function to update the updates table
     function updateUpdatesTable(updates) {
      var tableBody = document.getElementById('updatesTableBody');
      tableBody.innerHTML = ''; // Clear existing content

   
/*
      updates.forEach(function(update) {
        var row = tableBody.insertRow();
        var idCell = row.insertCell(0);
        var titleCell = row.insertCell(1);
        var contentCell = row.insertCell(2);

        idCell.innerText = update.id;
        titleCell.innerText = update.title;
        contentCell.innerText = update.content;
        // Add more cells for other properties if needed
      });*/
    }
    updatesChannel.members.each(function (member) {
        var userId = member.id;
        var userInfo = member.info;
    });
</script>
@stop