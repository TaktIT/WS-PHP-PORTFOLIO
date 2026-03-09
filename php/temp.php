<?php
// Initialize variables
$temperature = '';        
$direction = 'c_to_f';   
$result = null;           
$result_string = '';      

// Check if the form was submitted using GET and both fields exist
if (isset($_GET['temperature']) && isset($_GET['direction'])) {

    // Get the temperature value from the form
    $temperature = $_GET['temperature'];

    // Get the conversion direction from the form
    $direction = $_GET['direction'];

    // Make sure temperature is not empty
    if ($temperature !== '') {

        // Convert the temperature to a float number
        $temp = (float) $temperature;

        // Check if the selected conversion is Celsius to Fahrenheit
        if ($direction === 'c_to_f') {

            // Apply Celsius → Fahrenheit formula
            $result = ($temp * 9 / 5) + 32;

            // Create formatted result string
            $result_string = $temp . '°C = ' . number_format($result, 2) . '°F';

        } else {

            // Apply Fahrenheit → Celsius formula
            $result = ($temp - 32) * 5 / 9;

            // Create formatted result string
            $result_string = $temp . '°F = ' . number_format($result, 2) . '°C';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Makes the page responsive on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Page title shown in browser tab -->
    <title>Temperature Converter</title>

    <!-- External CSS file for styling -->
    <link rel="stylesheet" href="#.css">

</head>
<body>

<!-- Main page title -->
<h1>Temperature Converter</h1>

<!-- Form that sends data using GET method -->
<form method="get" action="">

    <!-- Temperature input -->
    <label for="temperature">Temperature:</label><br>

    <input
        type="number"                   
        id="temperature"                
        name="temperature"              
        step="any"                     
        placeholder="e.g. 100"          
        value="<?php echo htmlspecialchars($temperature); ?>" 
        <!-- Keeps the previously entered value after submit -->
    ><br><br>

    <!-- Conversion selection -->
    <label for="direction">Conversion:</label><br>

    <select id="direction" name="direction">

        <!-- Option for Celsius to Fahrenheit -->
        <option value="c_to_f" <?php echo $direction === 'c_to_f' ? 'selected' : ''; ?>>
            Celsius → Fahrenheit
        </option>

        <!-- Option for Fahrenheit to Celsius -->
        <option value="f_to_c" <?php echo $direction === 'f_to_c' ? 'selected' : ''; ?>>
            Fahrenheit → Celsius
        </option>

    </select><br><br>

    <!-- Submit button -->
    <button type="submit">Convert</button>

    <!-- Display result only if calculation was performed -->
    <?php if ($result !== null): ?>

        <p>
            <strong>Result:</strong> 
            <?php echo htmlspecialchars($result_string); ?>
        </p>

    <?php endif; ?>

</form>

</body>
</html>