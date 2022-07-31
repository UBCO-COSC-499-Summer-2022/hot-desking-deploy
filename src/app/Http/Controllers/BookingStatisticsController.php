<?php

namespace App\Http\Controllers;

use App\Models\BookingHistory;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingStatisticsController extends Controller
{

    public function index()
    {
        $allBookings = BookingHistory::all();
        // get all the years for dropdown
        $booking_years = $this->getYears($allBookings);
        // get a count of all the booking made for the current year
        $booking_count = $this->getBookingData($allBookings, $booking_years[0]);

        return view('admin.usageStatisticsManagement.generalBookingStatistics')->with('booking_count',array_values($booking_count))->with('booking_years', $booking_years); 
    }

    public function getAjaxRequest($year) {
        $allBookings = BookingHistory::all();
        // get the bookings count for the specified year
        $booking_count = $this->getBookingData($allBookings, $year);

        return response()->json($booking_count, 200);
    }

    public function getYears($allBookings) {
        // Return an array containing all of the years that exist for all bookings made. (Used for the drop down which filters the getBookingData function)
        $booking_years = [];
        foreach($allBookings as $booking) {
            $booking_years[date_format(Carbon::parse($booking->book_time_start),'Y')] = date_format(Carbon::parse($booking->book_time_start),'Y');
        }
        // sort years in descending order
        rsort($booking_years);
        
        return $booking_years;
    }

    public function getBookingData($allBookings, $year) {
        $booking_count = ['Jan'=>0, 'Feb'=>0, 'Mar'=>0, 'Apr'=>0, 'May'=>0, 'Jun'=>0, 'Jul'=>0, 'Aug'=>0, 'Sep'=>0, 'Oct'=>0, 'Nov'=>0, 'Dec'=>0];
        // Get and increament the count of bookings made per year and per month 
        foreach($booking_count as $k => $month) {
            foreach($allBookings as $booking) {
                if(!strcmp(date_format(Carbon::parse($booking->book_time_start),'M'), $k)) {
                    if (!strcmp($year, date_format(Carbon::parse($booking->book_time_start),'Y'))) {
                        $booking_count[$k] += 1;
                    }
                }
            }
        }
        // changes booking count from bookingcount ['Jan'=>0, 'Feb'=>0, 'Mar'=>0...   TO   0 => 0, 1 => 0, 1 => 0 .....]
        // This step is required due to the way highcharts needs data for it's output
        $i = 0;
        $newBooking_count = [];
        foreach ($booking_count as $month => $book_count) {
            $newBooking_count[$i] = $booking_count[$month];
            $i++;
        }
        return $newBooking_count;
    } 
}