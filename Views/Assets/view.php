<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <?php if (!empty($images)): ?>
                <div id="assetCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <div class="ratio ratio-16x9">
                                    <img src="<?= $image['url'] ?>" class="d-block w-100" alt="<?= h($asset['title']) ?>" onerror="this.src='<?= $asset['thumbnail_url'] ?>'; this.alt='Fallback image'" style="object-fit: cover;">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#assetCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#assetCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <!-- Preview images under the carousel -->
                <div class="row justify-content-center mt-3">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="col-auto">
                            <img src="<?= $image['url'] ?>" alt="<?= h($asset['title']) ?>" class="img-thumbnail" style="max-height: 70px; cursor: pointer;" data-bs-target="#assetCarousel" data-bs-slide-to="<?= $index ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <img src="<?= $asset['thumbnail_url'] ?>" class="img-fluid mb-4" alt="<?php e($asset['title']); ?>">
            <?php endif; ?>



            <div class="description mb-3">
                <p><?= $asset['description'] ?></p>
            </div>


        </div>

        <!-- Right column: -->
        <div class="col-md-4">
                <!-- Asset info header: title, author and time of addition (time on the right) -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="asset-info">

                    <h2><?= h($asset['title']) ?></h2>
                    <div class="text-muted">Автор: <?= h($asset['author'] ?? 'Unknown') ?></div>
                </div>
                <div class="asset-date">
                    <small><?= date('d.m.Y H:i', strtotime($asset['created_at'] ?? 'now')) ?></small>
                </div>
            </div>

            <!-- Tags -->
            <?php if (!empty($tags)): ?>
                <div class="tags mb-3">
                    <?php foreach ($tags as $tag): ?>
                        <span class="badge bg-secondary"><?= h($tag['name']) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($downloads)): ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#downloadModal">
                    Download
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal for download versions -->
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
            <h5 class="modal-title" id="downloadModalLabel">Download Versions</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
            <ul class="list-group">
               <?php foreach ($downloads as $download): ?>
                   <li class="list-group-item d-flex justify-content-between align-items-center">
                      <a href="<?= $download['url'] ?>"><?= h($download['file_type']) ?></a>
                      <span class="badge bg-secondary"><?= h($download['created_at']) ?></span>
                   </li>
               <?php endforeach; ?>
            </ul>
       </div>
       <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
       </div>
     </div>
  </div>
</div>

<!-- Add styles for highlighting the active thumbnail -->
<style>
    .img-thumbnail.active-thumb {
        border: 2px solid #007bff;
    }
</style>

<!-- Script for synchronizing the thumbnail highlighting with the active carousel slide -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var carouselElement = document.getElementById('assetCarousel');
    var thumbnailElements = document.querySelectorAll('[data-bs-slide-to]');
    
    // Function to update the thumbnail highlighting class
    function updateActiveThumbnail(activeIndex) {
        thumbnailElements.forEach(function(thumb, index) {
            if (index === activeIndex) {
                thumb.classList.add('active-thumb');
            } else {
                thumb.classList.remove('active-thumb');
            }
        });
    }
    
    // Initialize highlighting (first slide is active)
    updateActiveThumbnail(0);
    
    // Event handler for slide change
    carouselElement.addEventListener('slid.bs.carousel', function() {
        var activeSlide = carouselElement.querySelector('.carousel-item.active');
        var slides = carouselElement.querySelectorAll('.carousel-item');
        var activeIndex = Array.from(slides).indexOf(activeSlide);
        updateActiveThumbnail(activeIndex);
    });
});
</script>