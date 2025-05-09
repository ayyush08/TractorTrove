<?php include 'config.php' ?>

<?php include 'db/db.php';
$locale = 'en_IN';
$fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);
try {
    $featuredStmt = $pdo->query("SELECT * FROM tractors WHERE featured = 1 LIMIT 1");
    $featuredTractor = $featuredStmt->fetch(PDO::FETCH_ASSOC);

    
    $tractorsStmt = $pdo->query("SELECT * FROM tractors");
    $tractors = $tractorsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NamdevTractors - Find Your Perfect Tractor</title>
    <meta name="description"
        content="Browse our collection of high-quality tractors for farming, landscaping, and industrial needs.">
    <link rel="stylesheet" href="./output.css">

</head>

<body class="min-h-screen bg-gray-50">
    <?php include 'includes/header.php' ?>

    <main>
        <div class="bg-green-600 text-white">
            <div class="container mx-auto px-4 py-12">
                <div class="flex flex-col md:flex-row items-center">
                    <div class="w-full md:w-1/2 mb-8 md:mb-0">
                        <div class="flex items-center mb-2">
                            <span
                                class="bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">FEATURED</span>
                            <span
                                class="ml-2 text-green-100"><?php echo htmlspecialchars($featuredTractor['brand']); ?></span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">
                            <?php echo htmlspecialchars($featuredTractor['name']); ?>
                        </h2>
                        <p class="text-green-100 mb-6">
                            <?php echo htmlspecialchars($featuredTractor['description']); ?>
                        </p>
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-green-700 p-3 rounded">
                                <div class="text-green-200 text-sm">Horsepower</div>
                                <div class="text-xl font-bold">
                                    <?php echo htmlspecialchars($featuredTractor['horsepower']); ?> HP
                                </div>
                            </div>

                            <div class="bg-green-700 p-3 rounded">
                                <div class="text-green-200 text-sm">Price</div>
                                <div class="text-xl font-bold"><?php echo $fmt->formatCurrency($featuredTractor['price'], 'INR') ; ?>
                                </div>
                            </div>

                        </div>
                        <a href="tractor.php?id=<?= $featuredTractor['id'] ?>"
                            class="bg-white cursor-pointer text-green-700 px-6 py-3 rounded-md font-semibold hover:bg-green-50 transition-colors">
                            View Details
                        </a>
                    </div>
                    <div class="w-full md:w-1/2 flex justify-center ">
                        <img src="<?= BASE_URL ?>assets/<?= $featuredTractor['photo_url'] ?>"
                            class=" object-cover rounded-lg " />

                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Browse Our Tractors</h1>
                <p class="text-gray-600 mt-2">
                    Find the perfect tractor for your farming, landscaping, or industrial needs.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <?php
                try {
                    $tractorsStmt = $pdo->query("SELECT * FROM tractors");
                    $tractors = $tractorsStmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($tractors as $tractor) {
                        $formattedPrice = $tractor['price'];
                        $lowStock = $tractor['stock'] < 3;
                        ?>
                        <div
                            class="overflow-hidden h-full flex flex-col shadow-sm shadow-green-400 hover:shadow-lg transition-shadow duration-300 rounded-lg border bg-white ">
                            <div class="relative h-48 bg-gray-100 rounded overflow-hidden">
                                <div class="absolute inset-0 flex items-center justify-center p-2">
                                    <img src="<?= BASE_URL ?>assets/<?= $tractor['photo_url'] ?>"
                                        class="object-contain h-full w-full" alt="Tractor" />
                                </div>

                                <?php if ($lowStock): ?>
                                    <div class="absolute top-2 right-2">
                                        <span class="bg-amber-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                            Only <?php echo $tractor['stock']; ?> left
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="p-4 pb-0">
                                <h2 class="text-lg font-semibold"><?php echo htmlspecialchars($tractor['name']); ?></h2>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($tractor['brand']); ?></div>
                            </div>
                            <div class="p-4 flex-grow">
                                <div class="grid grid-cols-2 gap-2 text-sm mb-2">
                                    <div class="flex items-center gap-1">
                                        <span class="font-semibold">HP:</span> <?php echo $tractor['horsepower']; ?>
                                    </div>


                                </div>
                                <p class="text-sm line-clamp-2 text-gray-600 mt-2">
                                    <?php echo htmlspecialchars($tractor['description']); ?>
                                </p>
                            </div>
                            <div class="p-4 pt-0 flex justify-between items-center mt-auto">
                                <div class="text-xl font-bold text-green-600"><?php echo $fmt->formatCurrency($tractor['price'], 'INR') ; ?></div>
                                <a href="tractor.php?id=<?= $tractor['id'] ?>"
                                    class="bg-green-800 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                                    View Details
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                } catch (PDOException $e) {
                    echo '<div class="col-span-full text-center py-16">
                            <h3 class="text-xl font-semibold text-gray-700">Unable to load tractors</h3>
                            <p class="text-gray-500 mt-2">Please try again later</p>
                            </div>';
                }
                ?>
            </div>



        </div>
    </main>


    <?php include 'includes/footer.php' ?>


</body>

</html>