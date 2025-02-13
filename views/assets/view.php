<?php
use \Models\User;
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <!-- Asset info header: title, author, edit and delete buttons -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="asset-info">
                    <h2><?= h($asset['title']) ?></h2>
                    <div class="text-muted">Author: <?= h($asset['author'] ?? 'Unknown') ?></div>
                </div>
                <?php if (User::isAdmin()): ?>
                <div>
                    <a href="/assets/edit?id=<?= $asset['id'] ?>" class="btn btn-warning me-2">Edit</a>
                    <a href="/assets/delete?id=<?= $asset['id'] ?>" class="btn btn-danger">Delete</a>
                </div>
                <?php endif; ?>
            </div>

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

            <h3>Description</h3>
            <!-- Block with asset  description -->
            <div class="description mb-3">
                <div id="description-content" class="collapsed">
                    <?= $asset['description'] ?>

                </div>
                <a href="#" id="toggle-description" class="btn btn-link">Show More</a>
            </div>

            <!-- Carousel with similar assets -->
            <?php if (!empty($similarAssets)): ?>
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn btn-secondary carousel-control-btn me-2" data-bs-target="#similarAssetsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button type="button" class="btn btn-secondary carousel-control-btn" data-bs-target="#similarAssetsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <h3>Similar assets</h3>
                <?php $chunks = array_chunk($similarAssets, 3); ?>
                <div id="similarAssetsCarousel" class="carousel slide mb-4" data-bs-interval="false">
                    <div class="carousel-inner">
                        <?php foreach ($chunks as $chunkIndex => $chunk): ?>
                            <div class="carousel-item <?= $chunkIndex === 0 ? 'active' : '' ?>">
                                <div class="row">
                                    <?php foreach ($chunk as $similarAsset): ?>
                                        <div class="col-md-4">
                                            <a href="/assets/view?id=<?= $similarAsset['id'] ?>">
                                                <div class="ratio ratio-16x9">
                                                    <img src="<?= h($similarAsset['thumbnail_url']) ?>" class="img-fluid" alt="<?= h($similarAsset['title']) ?>" style="object-fit: cover;">
                                                </div>
                                            </a>
                                            <div class="text-center mt-2">
                                                <h5 class="carousel-asset-title"><?= h($similarAsset['title']) ?></h5>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
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
                      <a href="<?= $download['url'] ?>" target="_blank" rel="noopener noreferrer">
                          <?= !empty($download['version']) ? h($download['version']) : 'Download' ?>
                      </a>
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

document.addEventListener("DOMContentLoaded", function() {
  var descriptionContent = document.getElementById("description-content");
  var toggleBtn = document.getElementById("toggle-description");
  var collapsedClass = "collapsed";
  var collapseHeight = 400; // Must match the value of max-height in CSS


  // If the height of the description is less than the threshold value, hide the button
  if (descriptionContent.scrollHeight <= collapseHeight) {
    toggleBtn.style.display = "none";
    descriptionContent.classList.remove(collapsedClass);
  }


  toggleBtn.addEventListener("click", function(e) {
    e.preventDefault();
    if (descriptionContent.classList.contains(collapsedClass)) {
      descriptionContent.classList.remove(collapsedClass);
      toggleBtn.textContent = "Show Less";
    } else {
      descriptionContent.classList.add(collapsedClass);
      toggleBtn.textContent = "Show More";
    }
  });
});
</script>