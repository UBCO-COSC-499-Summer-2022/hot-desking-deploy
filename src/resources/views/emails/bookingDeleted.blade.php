@component('mail::message')

# Your booking has been Canceled

<table class="info" align="center">
    <tr>
        <th>Booking Id</th>
        <td>{{ $booking_id }}</td>
    </tr>
    <tr>
        <th>Desk Id</th>
        <td>{{ $desk_id }}</td>
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

Unfortunately you're booking has been canceled, this is likely due to an administrator either removing or closing the resource you have booked. You may create another booking through the site by clicking the button below.

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