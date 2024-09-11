<?php
$transport_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM transport WHERE student_id = '".$student['id']."'"));
?>

<h3>Transport Information</h3>
<table class="table table-bordered">
    <tr>
        <th>Route</th>
        <td><?= $transport_info['route'] ?></td>
    </tr>
    <tr>
        <th>Bus Number</th>
        <td><?= $transport_info['bus_no'] ?></td>
    </tr>
    <tr>
        <th>Pickup Time</th>
        <td><?= $transport_info['pickup_time'] ?></td>
    </tr>
</table>
