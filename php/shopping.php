<?php
// Start a session to store the shopping list data
session_start();

// Check if the form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // If the "Clear All" button was pressed
    if (isset($_POST['clear'])) {

        // Remove the shopping list from the session
        unset($_SESSION['shopping_list']);

    // If the user submitted an item
    } elseif (isset($_POST['item'])) {

        // Get the item name and remove extra spaces
        $item = trim($_POST['item'] ?? '');

        // Get quantity and make sure it is at least 1
        $qty  = max(1, (int)($_POST['qty'] ?? 1));

        // Only add the item if the name is not empty
        if ($item !== '') {

            // Add item and quantity to the session array
            $_SESSION['shopping_list'][] = [
                'item' => $item,
                'qty'  => $qty
            ];
        }
    }

    // Redirect back to the same page to prevent form resubmission
    header('Location: shopping.php');
    exit;
}

// Get the shopping list from the session (or empty array if none exists)
$list  = $_SESSION['shopping_list'] ?? [];

// Count how many items are in the list
$count = count($list);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- Makes the page responsive on mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Page title -->
    <title>Shopping List</title>
</head>
<body>

<h1>Shopping List</h1>

<!-- Form for adding a new item -->
<form method="post" action="">
    <p>
        <label for="item">Item Name:</label><br>

        <!-- Text input for item name -->
        <input type="text" id="item" name="item" placeholder="e.g. Milk" required>
    </p>

    <p>
        <label for="qty">Quantity:</label><br>

        <!-- Number input for quantity -->
        <input type="number" id="qty" name="qty" min="1" value="1">
    </p>

    <!-- Button to add the item -->
    <button type="submit">Add Item</button>
</form>

<hr>

<?php if ($count > 0): ?>
    
    <!-- Display shopping list if items exist -->
    <h2>
        Shopping List (<?php echo $count; ?> item<?php echo $count !== 1 ? 's' : ''; ?>)
    </h2>

    <!-- Ordered list of items -->
    <ol>
        <?php foreach ($list as $entry): ?>
            <li>
                <!-- Display item name safely -->
                <?php echo htmlspecialchars($entry['item']); ?>

                <!-- Display quantity -->
                &times; <?php echo (int)$entry['qty']; ?>
            </li>
        <?php endforeach; ?>
    </ol>

    <br>

    <!-- Form for clearing the entire shopping list -->
    <form method="post" action="">
        <button type="submit" name="clear" value="1">Clear All</button>
    </form>

<?php else: ?>

    <!-- Message if the list is empty -->
    <p>Your shopping list is empty.</p>

<?php endif; ?>

</body>
</html>