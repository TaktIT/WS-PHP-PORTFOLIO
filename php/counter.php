<?php
// Text Analysis Tool - Analyzes user-submitted text to calculate character, word, and line statistics
$error  = '';  // Stores validation error messages
$stats  = null;  // Stores calculated text statistics
$text   = '';  // Stores the submitted text for display

// Check if form was submitted via POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve text from form submission
    $text = $_POST['text'] ?? '';

    // Validate that text is not empty or whitespace only
    if (trim($text) === '') {
        $error = 'Please enter some text before submitting.';
    } else {
        // Count total characters including spaces
        $chars_with    = strlen($text);

        // Count total characters excluding spaces
        $chars_without = strlen(str_replace(' ', '', $text));

        // Count total words in the text
        $words         = str_word_count($text);

        // Count total lines (occurrences of newline + 1)
        $lines         = substr_count($text, "\n") + 1;

        // Find the most frequently occurring character (excluding spaces)
        $freq     = count_chars(str_replace(' ', '', strtolower($text)), 1);
        $top_char = '';
        $top_count = 0;
        foreach ($freq as $code => $count) {
            if ($count > $top_count) {
                $top_count = $count;
                $top_char  = chr($code);
            }
        }

        // Store all calculated statistics in an associative array
        $stats = [
            'Characters (with spaces)'    => $chars_with,
            'Characters (without spaces)' => $chars_without,
            'Words'                        => $words,
            'Lines'                        => $lines,
            'Most Frequent Character'      => htmlspecialchars($top_char) . ' (' . $top_count . ' times)',
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Counter</title>
    <link rel="stylesheet" href="#.css">
</head>
<body>

<h1>Text Analysis Tool</h1>

<!-- Form for text submission -->
<form method="post" action="">
    <p>
        <label for="text">Enter or paste your text below:</label><br>
        <!-- Textarea to capture user input text -->
        <textarea id="text" name="text" rows="6" cols="60"><?php echo htmlspecialchars($text); ?></textarea>
    </p>
    <button type="submit">Analyze</button>
</form>

<hr>

<!-- Display validation error if text is empty -->
<?php if ($error !== ''): ?>
    <p style="color: red;">&#10060; <?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<!-- Display analysis results table if statistics are available -->
<?php if ($stats !== null): ?>
    <h2>&#128202; Analysis Results</h2>
    <!-- Table showing all calculated text statistics -->
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Statistic</th>
            <th>Value</th>
        </tr>
        <?php foreach ($stats as $label => $value): ?>
        <tr>
            <td><?php echo htmlspecialchars($label); ?></td>
            <td><?php echo $value; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>