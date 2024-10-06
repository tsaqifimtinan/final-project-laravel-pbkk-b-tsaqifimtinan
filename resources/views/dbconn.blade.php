<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection</title>
</head>
<body>
    <div>
    <?php
        if(DB::connection()->getPdo()) {
            echo "Connected successfully to: " . DB::connection()->getDatabaseName();
        } else {
            echo "Failed to connect to database!";
        }
    ?>
    </div>
</body>
</html>