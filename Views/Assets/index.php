<?php
use \Models\User;
?>
<?php if (User::isAdmin()) : ?>
<div class="mb-3">
    <h2>Assets</h2>
    <a href="/Assets/create" class="btn btn-primary">Create</a>
</div>
<?php endif; ?>

<div class="container">
    <div class="row">
        <?php foreach ($assets as $asset): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <?php 
                // TODO: Add default thumbnail
                $thumbnail = $asset['thumbnail_url'] ? $asset['thumbnail_url'] : ''; 
                ?>
                <img src="<?= $thumbnail ?>" class="card-img-top" alt="<?php e($asset['title']); ?>">
                <div class="card-body">

                    <h5 class="card-title"><?php e($asset['title']); ?></h5>

                    <?php $tags = $allTags[$asset['id']]; ?>
                    <?php foreach ($tags as $tag): ?>
                        <span class="badge bg-secondary"><?php e($tag['name']); ?></span>
                    <?php endforeach; ?>
                    





                </div>
            </div>
        </div>

        <?php endforeach; ?>
    </div>
</div>


<div class="d-flex justify-content-center">
    <?php 
    $window = 10;
    $startPage = max(1, $page - floor($window / 2));
    $endPage = min($totalPages, $startPage + $window - 1);
    $startPage = max(1, $endPage - $window + 1);

    if ($page > 1) {
        echo '<a href="/Assets?page=' . ($page - 1) . '" class="btn btn-secondary me-2">Previous</a>';
    }

    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $page) {
            echo '<strong class="btn btn-primary me-2">' . $i . '</strong>';
        } else {
            echo '<a href="/Assets?page=' . $i . '" class="btn btn-secondary me-2">' . $i . '</a>';
        }
    }

    if ($page < $totalPages) {
        echo '<a href="/Assets?page=' . ($page + 1) . '" class="btn btn-secondary">Next</a>';
    }
    ?>
</div>