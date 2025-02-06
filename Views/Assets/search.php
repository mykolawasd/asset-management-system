<?php
$availableTags = \Models\Tags::getAllTags();
// Формируем ассоциативный массив для быстрого поиска имени тега по его id
$tagsById = [];
foreach ($availableTags as $tag) {
    $tagsById[$tag['id']] = $tag['name'];
}
?>
<div class="container mt-5">

    <h2>Search Assets</h2>
    <form method="get" action="/Assets/search">
        <div class="mb-3">
            <label for="title" class="form-label">Asset Title</label>
            <input type="text" id="title" name="title" value="<?= h($searchTitle) ?>" class="form-control" placeholder="Enter asset title">
        </div>
        <div class="mb-3">
            <label for="tags-autocomplete" class="form-label">Tags</label>
            <input type="text" id="tags-autocomplete" class="form-control" placeholder="Type tag name" list="tags-list">
            <!-- Datalist for autocomplete -->
            <datalist id="tags-list">
                <?php foreach ($availableTags as $tag): ?>
                    <option data-tag-id="<?= $tag['id'] ?>" value="<?= h($tag['name']) ?>"></option>
                <?php endforeach; ?>
            </datalist>
            <!-- Hidden field for storing selected tags -->
            <input type="hidden" id="tags" name="tags" value="<?= h(implode(',', $searchTags)) ?>">
            <div id="selected-tags" class="mt-2">
                <?php if (!empty($searchTags)): ?>
                    <?php foreach ($searchTags as $tagId): ?>
                        <span class="badge bg-secondary me-1" data-tag-id="<?= $tagId ?>"><?= isset($tagsById[$tagId]) ? h($tagsById[$tagId]) : h($tagId) ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <hr>

    <div class="row">
        <?php if (!empty($assets)): ?>
            <?php foreach ($assets as $asset): ?>
                <div class="col-md-4 mb-4">
                    <a href="/Assets/view?id=<?= $asset['id'] ?>" style="text-decoration:none; color:inherit;">
                        <div class="card h-100">
                            <?php 
                            $thumbnail = $asset['thumbnail_url'] ? $asset['thumbnail_url'] : '/path/to/default-thumbnail.jpg'; 
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
            echo '<a href="/Assets/search?page=' . ($page - 1) . '&' . $queryParams . '" class="btn btn-secondary me-2">Previous</a>';
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $page) {
                echo '<strong class="btn btn-primary me-2">' . $i . '</strong>';
            } else {
                echo '<a href="/Assets/search?page=' . $i . '&' . $queryParams . '" class="btn btn-secondary me-2">' . $i . '</a>';
            }
        }

        if ($page < $totalPages) {
            echo '<a href="/Assets/search?page=' . ($page + 1) . '&' . $queryParams . '" class="btn btn-secondary">Next</a>';
        }
        ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tagInput = document.getElementById('tags-autocomplete');
    const tagHidden = document.getElementById('tags');
    const selectedTagsContainer = document.getElementById('selected-tags');
    let selectedTags = [];

    if (tagHidden.value.trim() !== "") {
        let ids = tagHidden.value.split(',').filter(id => id.trim() !== "");
        ids.forEach(function(tagId) {
            const badge = selectedTagsContainer.querySelector(`[data-tag-id="${tagId}"]`);
            let tagName = badge ? badge.textContent.trim() : tagId;
            selectedTags.push({ id: tagId, name: tagName });
        });
    }
    
    selectedTagsContainer.querySelectorAll('.badge').forEach(function(badge) {
        badge.addEventListener('click', function() {
            const tagId = badge.getAttribute('data-tag-id');
            removeTag(tagId);
        });
    });
    
    function updateHiddenField() {
        tagHidden.value = selectedTags.map(tag => tag.id).join(',');
    }

    function addBadge(tagId, tagName) {
        const badge = document.createElement('span');
        badge.className = 'badge bg-secondary me-1';
        badge.textContent = tagName;
        badge.dataset.tagId = tagId;
        badge.addEventListener('click', function() {
            removeTag(tagId);
        });
        selectedTagsContainer.appendChild(badge);
    }

    function removeTag(tagId) {
        selectedTags = selectedTags.filter(tag => tag.id !== tagId);
        const badge = selectedTagsContainer.querySelector(`[data-tag-id="${tagId}"]`);
        if (badge) {
            badge.remove();
        }
        updateHiddenField();
    }

    tagInput.addEventListener('change', function() {
        const tagName = tagInput.value.trim();
        if (tagName === '') return;
        const dataList = document.getElementById('tags-list');
        const option = Array.from(dataList.options).find(opt => opt.value.toLowerCase() === tagName.toLowerCase());
        if (option) {
            const tagId = option.getAttribute('data-tag-id');
            if (!selectedTags.find(tag => tag.id === tagId)) {
                selectedTags.push({ id: tagId, name: tagName });
                addBadge(tagId, tagName);
                updateHiddenField();
            }
        }
        tagInput.value = '';
    });
});
</script> 