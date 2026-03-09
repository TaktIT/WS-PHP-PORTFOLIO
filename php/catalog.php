<?php
// Product Catalog System - Displays and filters a product database
// Array containing all available products with their properties
$products = [
    ['name' => 'Smartphone Pro',      'price' => 28990, 'category' => 'Electronics'],
    ['name' => 'Wireless Earbuds',    'price' => 3499,  'category' => 'Electronics'],
    ['name' => 'Laptop Ultra',        'price' => 54999, 'category' => 'Electronics'],
    ['name' => 'Mechanical Keyboard', 'price' => 2799,  'category' => 'Electronics'],
    ['name' => 'USB-C Hub',           'price' => 1299,  'category' => 'Electronics'],
    ['name' => 'Cotton T-Shirt',      'price' => 399,   'category' => 'Clothing'],
    ['name' => 'Running Shoes',       'price' => 3200,  'category' => 'Clothing'],
    ['name' => 'Denim Jacket',        'price' => 1899,  'category' => 'Clothing'],
    ['name' => 'Sports Cap',          'price' => 450,   'category' => 'Clothing'],
    ['name' => 'Instant Noodles (12-pack)', 'price' => 199, 'category' => 'Food'],
    ['name' => 'Premium Coffee Beans','price' => 849,   'category' => 'Food'],
    ['name' => 'Protein Bar Box',     'price' => 1200,  'category' => 'Food'],
];

// Retrieve and sanitize filter/sort parameters from GET query string
$q         = trim($_GET['q']         ?? '');  // Search query for product name
$category  = trim($_GET['category']  ?? '');  // Category filter
$min_price = trim($_GET['min_price'] ?? '');  // Minimum price filter
$max_price = trim($_GET['max_price'] ?? '');  // Maximum price filter
$sort      = trim($_GET['sort']      ?? '');  // Sort order preference

// Initialize result set with all products, then apply filters
$result = $products;

// Filter by search query (case-insensitive substring match)
if ($q !== '') {
    $result = array_filter($result, fn($p) => stripos($p['name'], $q) !== false);
}

// Filter by category (exact match)
if ($category !== '') {
    $result = array_filter($result, fn($p) => $p['category'] === $category);
}

// Filter by minimum price (if valid number provided)
if ($min_price !== '' && is_numeric($min_price)) {
    $result = array_filter($result, fn($p) => $p['price'] >= (float)$min_price);
}

// Filter by maximum price (if valid number provided)
if ($max_price !== '' && is_numeric($max_price)) {
    $result = array_filter($result, fn($p) => $p['price'] <= (float)$max_price);
}

// Apply sorting based on selected sort option
if ($sort === 'price-asc') {
    usort($result, fn($a, $b) => $a['price'] - $b['price']);
} elseif ($sort === 'price-desc') {
    usort($result, fn($a, $b) => $b['price'] - $a['price']);
} elseif ($sort === 'name-asc') {
    usort($result, fn($a, $b) => strcmp($a['name'], $b['name']));
}

// Count active filters for display
$active_filters = array_filter([$q, $category, $min_price, $max_price, $sort], fn($v) => $v !== '');
$filter_count   = count($active_filters);

// Extract unique categories from all products for the category dropdown
$categories = array_unique(array_column($products, 'category'));
sort($categories);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
</head>
<body>

<h1>Product Catalog</h1>

<!-- Filter and Search Form -->
<form method="get" action="">

    <!-- Text search input for product name -->
    <label>Search:
        <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search product name...">
    </label>
    &nbsp;

    <!-- Category dropdown filter -->
    <label>Category:
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    &nbsp;

    <!-- Minimum price filter input -->
    <label>Min Price (&#8369;):
        <input type="number" name="min_price" min="0" value="<?php echo htmlspecialchars($min_price); ?>" placeholder="0">
    </label>
    &nbsp;

    <!-- Maximum price filter input -->
    <label>Max Price (&#8369;):
        <input type="number" name="max_price" min="0" value="<?php echo htmlspecialchars($max_price); ?>" placeholder="99999">
    </label>
    &nbsp;

    <!-- Sort order dropdown -->
    <label>Sort:
        <select name="sort">
            <option value="">Default</option>
            <option value="price-asc"  <?php echo $sort === 'price-asc'  ? 'selected' : ''; ?>>Price: Low → High</option>
            <option value="price-desc" <?php echo $sort === 'price-desc' ? 'selected' : ''; ?>>Price: High → Low</option>
            <option value="name-asc"   <?php echo $sort === 'name-asc'   ? 'selected' : ''; ?>>Name: A → Z</option>
        </select>
    </label>
    &nbsp;

    <!-- Apply filters button -->
    <button type="submit">Apply Filters</button>
    <!-- Show clear filters link only if filters are active -->
    <?php if ($filter_count > 0): ?>
        &nbsp;<a href="catalog.php">Clear Filters</a>
    <?php endif; ?>

</form>

<hr>

<!-- Display result count and active filters -->
<p>
    Showing <strong><?php echo count($result); ?></strong> of <strong><?php echo count($products); ?></strong> products
    <?php if ($filter_count > 0): ?>
        &mdash; <em><?php echo $filter_count; ?> active filter<?php echo $filter_count !== 1 ? 's' : ''; ?></em>
    <?php endif; ?>
</p>

<hr>

<!-- Display product results table or no results message -->
<?php if (count($result) > 0): ?>
    <!-- Results table showing filtered products -->
    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through filtered results and display each product in a table row -->
            <?php $i = 1; foreach ($result as $p): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($p['name']); ?></td>
                    <td><?php echo htmlspecialchars($p['category']); ?></td>
                    <td>&#8369;<?php echo number_format($p['price'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- Message displayed when no products match the applied filters -->
    <p>No products match your filters. <a href="catalog.php">Clear all filters</a>.</p>
<?php endif; ?>

</body>
</html>