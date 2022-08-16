@component('mail::message')

# Your booking has been created!

<table class="info" align="center">
    <tr>
        <th>Campus </th>
        <td>{{ $campus_name }}</td>
    </tr>
    <tr>
        <th>Building </th>
        <td>{{ $building_name }}</td>
    </tr>
    <tr>
        <th>Floor #</th>
        <td>{{ $floor_num }}</td>
    </tr>
    <tr>
        <th>Room </th>
        <td>{{ $room_name }}</td>
    </tr>
    <tr>
        <th>Date</th>
        <td>{{ date('F d, Y',strtotime($book_time_start)) }}</td>
    </tr>
    <tr>
        <th>Time</th>
        <td>{{ date('g:ia',strtotime($book_time_start)) }} - {{ date('g:ia',strtotime($book_time_end)) }}</td>
    </tr>
</table>

<br>

Your booking has been created successfully!

@component('mail::button', ['url' => config('app.url')])
    Book Now
@endcomponent

@endcomponent

<style>
    .info td, th, tr {
        padding: 5px;
        border-bottom: 2px solid lightblue;
        text-align:left;
    }
    .info th {
        background-color: lightblue;
    }
    table.info {
        border-collapse: collapse;
        border: 2px solid lightblue;
        width: 75%;
        font-size:medium;
    }
</style>