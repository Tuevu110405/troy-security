<?php
// Capture data from the GET request
$fullname = isset($_GET['fullname']) ? htmlspecialchars($_GET['fullname']) : 'N/A';
$position = isset($_GET['position']) ? htmlspecialchars($_GET['position']) : 'N/A';

// Handle boolean logic for gender
// If checkbox is checked, it sends a value; if not, it is null.
$isFemale = isset($_GET['gender']); 
$genderLabel = $isFemale ? "Female" : "Male";
$accentColor = $isFemale ? "#e91e63" : "#2c3e50";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $fullname; ?> - Name Card</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #d1d8e0; 
            display: flex; 
            flex-direction: column;
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            margin: 0; 
        }
        .card {
            width: 450px;
            height: 250px;
            background: white;
            border-radius: 15px;
            display: flex;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            overflow: hidden;
            position: relative;
        }
        .sidebar {
            width: 20px;
            background-color: <?php echo $accentColor; ?>;
            height: 100%;
        }
        .main-content {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        h1 {
            margin: 0;
            font-size: 28px;
            color: #2d3436;
            text-transform: capitalize;
        }
        p.position {
            margin: 5px 0 20px 0;
            color: #636e72;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-size: 14px;
        }
        .tag {
            display: inline-block;
            padding: 5px 15px;
            background: #f1f2f6;
            border-radius: 20px;
            font-size: 12px;
            color: <?php echo $accentColor; ?>;
            font-weight: bold;
            border: 1px solid #dfe4ea;
        }
        .back-link {
            margin-top: 20px;
            text-decoration: none;
            color: #2c3e50;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="sidebar"></div>
        <div class="main-content">
            <h1><?php echo $fullname; ?></h1>
            <p class="position"><?php echo $position; ?></p>
            <div>
                <span class="tag">Gender: <?php echo $genderLabel; ?></span>
            </div>
        </div>
    </div>

    <a href="index.html" class="back-link">← Create another card</a>

</body>
</html>
