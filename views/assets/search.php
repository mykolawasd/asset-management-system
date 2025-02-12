<?php
$availableTags = \Models\Tags::getAllTags();
$tagsById = [];
foreach ($availableTags as $tag) {
    $tagsById[$tag['id']] = $tag['name'];
}
$selectedTags = $searchTags;
$allowNewTags = false; // Disable new tags in search
?>
<div class="container mt-5">

    <h2>Search Assets</h2>
    <form method="get" action="/assets/search">
        <div class="mb-3">
            <label for="title" class="form-label">Asset Title</label>
            <input type="text" id="title" name="title" value="<?= h($searchTitle) ?>" class="form-control" placeholder="Enter asset title">
        </div>
        
        <?php include 'views/assets/_tags_autocomplete.php'; ?>

        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <hr>

    <div class="row">
        <?php if (!empty($assets)): ?>
            <?php foreach ($assets as $asset): ?>
                <div class="col-md-4 mb-4">
                    <a href="/assets/view?id=<?= $asset['id'] ?>" style="text-decoration:none; color:inherit;">
                        <div class="card h-100">
                            <?php 
                            // TO DO: add default thumbnail
                            $thumbnail = $asset['thumbnail_url'] ? $asset['thumbnail_url'] : '/uploads/thumbnails/default.jpg'; 
                            ?>
                            <img src="<?= $thumbnail ?>" class="card-img-top" alt="<?= h($asset['title']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= h($asset['title']) ?></h5>
                                <?php $tags = $allTags[$asset['id']] ?? []; ?>
                                <?php foreach ($tags as $tag): ?>
                                    <span class="badge bg-secondary"><?= h($tag['name']) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No assets found matching your criteria.</p>
        <?php endif; ?>
    </div>

    <div class="d-flex justify-content-center">
        <?php 
        $window = 10;
        $startPage = max(1, $page - floor($window / 2));
        $endPage = min($totalPages, $startPage + $window - 1);
        $startPage = max(1, $endPage - $window + 1);

        $queryParams = http_build_query([
            'title' => $searchTitle,
            'tags'  => implode(',', $searchTags)
        ]);

        if ($page > 1) {
            echo '<a href="/assets/search?page=' . ($page - 1) . '&' . $queryParams . '" class="btn btn-secondary me-2">Previous</a>';
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $page) {
                echo '<strong class="btn btn-primary me-2">' . $i . '</strong>';
            } else {
                echo '<a href="/assets/search?page=' . $i . '&' . $queryParams . '" class="btn btn-secondary me-2">' . $i . '</a>';
            }
        }

        if ($page < $totalPages) {
            echo '<a href="/assets/search?page=' . ($page + 1) . '&' . $queryParams . '" class="btn btn-secondary">Next</a>';
        }
        ?>
    </div>
</div>

<script>
    const availableTags = <?= json_encode(array_values($availableTags)); ?>;
</script>
