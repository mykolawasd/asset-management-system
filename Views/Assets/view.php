<div class="container mt-5">

    <div class="row">
        <div class="col-md-8">
            <img src="<?= $asset['thumbnail_url'] ?>" class="img-fluid" alt="<?php e($asset['title']); ?>">
        </div>
        <div class="col-md-4">
            <h1><?php e($asset['title']); ?></h1>
            <p><?php e($asset['description']); ?></p>
            <p>
                <?php foreach ($tags as $tag): ?>
                    <span class="badge bg-secondary"><?php e($tag['name']); ?></span>
                <?php endforeach; ?>
            </p>
            <?php if (!empty($downloads)): ?>
                <h3>Downloads</h3>
                <ul>
                    <?php foreach ($downloads as $download): ?>
                        <li>

                            <a href="<?= $download['url'] ?>"><?= $download['file_type'] ?></a>
                            <small><?= $download['created_at'] ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if (!empty($images)): ?>
                <h3>Images</h3>
                <div class="gallery">
                    <?php foreach ($images as $image): ?>
                        <img src="<?= $image['url'] ?>" alt="" style="max-width:200px; margin:10px;">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>