<h2>📢 Conference Hall Booking Notification</h2>

<p><strong>Department:</strong> {{ $data->booking_info['department'] }}</p>
<p><strong>Booked By (User ID):</strong> {{ $data->booking_info['booked_by'] }}</p>
<p><strong>Date:</strong> {{ $data->booking_info['booking_date'] }}</p>
<p><strong>Time:</strong> {{ $data->booking_info['start_time'] }} to {{ $data->booking_info['end_time'] }}</p>
<p><strong>Purpose:</strong> {{ $data->booking_info['purpose'] }}</p>

<p><strong>Participants (User IDs):</strong></p>
<ul>
    @foreach ($data->booking_info['participants'] as $participant)
        <li>{{ $participant }}</li>
    @endforeach
</ul>

<p><strong>Message:</strong> {{ $data->message }}</p>