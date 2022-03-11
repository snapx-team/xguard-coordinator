<!DOCTYPE html>
<html>
<head>
    <title>Coordinator</title>
    <link href="{{ secure_asset(mix("app.css", 'vendor/xguard-coordinator')) }}?v={{config('coordinator_app.version')}}"
          rel="stylesheet" type="text/css">
</head>
<body>
<div id="app"></div>
<script
    src="{{ secure_asset(mix('app.js', 'vendor/xguard-coordinator')) }}?v={{config('coordinator_app.version')}}"></script>
</body>
</html>
