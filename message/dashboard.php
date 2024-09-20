<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Dashboard</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            height: 100vh;
            position: fixed;
        }
        .sidebar a {
            color: white;
            padding: 15px;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2 class="text-center">Dashboard</h2>
    <a href="#" onclick="loadSection('home.php')">Home</a>
    <a href="#" onclick="loadSection('add_member.php')">Members</a>
    <a href="#" onclick="loadSection('sms.html')">Send Messages</a>
    <a href="#" onclick="loadSection('settings.php')">Settings</a>
</div>

<div class="content">
    <h1>Welcome to the Dashboard</h1>
    <div id="dynamicContent">
        <p>Select an option from the sidebar to view content.</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    function loadSection(file) {
        $('#dynamicContent').html('<p>Loading...</p>');

        // AJAX call to load the selected PHP file
        $.ajax({
            url: file,
            method: 'GET',
            success: function(data) {
                $('#dynamicContent').html(data);
            },
            error: function() {
                $('#dynamicContent').html('<p>Error loading content.</p>');
            }
        });
    }
</script>

</body>
</html>
