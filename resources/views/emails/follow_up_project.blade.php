<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['subject'] }}</title>
</head>
<body>
    <p>A quick reminder that the project {{ $data['project_name'] }} requires your attention. We need to follow up on some important details to keep the project on track.</p>
    <p>Please check the project status and take the necessary actions as soon as possible.</p>
    <?php if ($data['last_date_detail_submitted']) { ?>
        <p>Last details submitted date is {{ $data['last_date_detail_submitted']->format('d F Y') }}.</p>
    <?php } ?>
</body>
</html>
