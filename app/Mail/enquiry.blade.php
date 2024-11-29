<!-- resources/views/emails/enquiry.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Enquiry</title>
</head>
<body>
    <h2>New Enquiry Details</h2>

    <p><strong>Name:</strong> {{ $enquiry['name'] }}</p>
    <p><strong>Email:</strong> {{ $enquiry['email'] }}</p>
    <p><strong>Phone:</strong> {{ $enquiry['phone'] }}</p>
    <p><strong>Study Mode:</strong> {{ $enquiry['study_mode'] }}</p>
    <p><strong>Course:</strong> {{ $enquiry['course'] }}</p>
    <p><strong>How Did You Hear About Us:</strong> {{ $enquiry['hear_by'] }}</p>
    <p><strong>Message:</strong> {{ $enquiry['message'] }}</p>
    <p><strong>Accept Terms & Conditions:</strong> {{ $enquiry['accept_condition'] ? 'Yes' : 'No' }}</p>
    <p><strong>Contact Me:</strong> {{ $enquiry['contact_me'] ? 'Yes' : 'No' }}</p>
</body>
</html>
