<?php
// Supposed to have the following variables:
// $asset - array of asset data (e.g. ['title' => '', 'description' => ''])
// $availableTags - list of all tags (if not set, get from model)
// $selectedTags - array of selected tag ids
// $tagsById - associative array of tag id => tag name
// $allowNewTags - flag to allow creating new tags (default true)

if (!isset($asset['tags']) && isset($tags)) {
    $asset['tags'] = $tags;
}

$selectedTags = [];
$tagsById = [];
if (!empty($asset['tags'])) {
    foreach ($asset['tags'] as $tag) {
        if (is_array($tag) && isset($tag['id'], $tag['name'])) {
            $selectedTags[] = $tag['id'];
            $tagsById[$tag['id']] = $tag['name'];
        }
    }
}

if (!isset($availableTags)) {
    $availableTags = \Models\Tags::getAllTags();
    foreach ($availableTags as $tag) {
        if (!isset($tagsById[$tag['id']])) {
            $tagsById[$tag['id']] = $tag['name'];
        }
    }
}
?>
<form method="post" action="<?= $action ?>" enctype="multipart/form-data">
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $field => $error): ?>
                <p><?= h($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" name="title" id="title" class="form-control" value="<?= isset($asset) ? h($asset['title']) : '' ?>" required>
    </div>

    <div class="mb-3">
        <label for="thumbnail" class="form-label">Thumbnail (720p recommended)</label>
        <?php if (isset($asset) && !empty($asset['thumbnail_url'])): ?>
            <div class="mb-2">
                <img src="<?= h($asset['thumbnail_url']) ?>" alt="Current Thumbnail" class="img-thumbnail" style="max-width:200px;">
            </div>
        <?php endif; ?>
        <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
        <?php if (isset($asset)): ?>
            <small>If you want to change the thumbnail, select a new file.</small>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <input type="hidden" name="description" id="description" value="<?= isset($asset) ? h($asset['description']) : '' ?>">
        <div id="editor" style="height:200px; background-color: #fff;">
            <?= isset($asset) ? $asset['description'] : '' ?>
        </div>
    </div>

    <?php if (isset($asset) && isset($images) && !empty($images)): ?>
        <div class="mb-3">
            <label class="form-label">Existing Additional Images</label>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($images as $image): ?>
                    <div class="position-relative">
                        <img src="<?= h($image['url']) ?>" alt="Asset image" class="img-thumbnail" style="max-width:150px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="delete_images[]" value="<?= $image['id'] ?>"
                                   id="delete_image_<?= $image['id'] ?>">
                            <label class="form-check-label" for="delete_image_<?= $image['id'] ?>">Delete</label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <label class="form-label">Add New Additional Images (up to 10, resized to 1080 max)</label>
        <input type="file" name="images[]" multiple accept="image/*" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Download Links</label>
        <div id="download-links" style="max-height: 300px; overflow-y: auto;">
            <?php if (isset($downloads) && !empty($downloads)): ?>
                <?php foreach ($downloads as $download): ?>
                    <div class="download-link mb-2">
                        <input type="text" name="download_url[]" class="form-control" placeholder="Download URL" value="<?= h($download['url']) ?>">
                        <div class="input-group mt-1">
                            <input type="text" name="download_version[]" class="form-control" placeholder="Version (optional)" value="<?= h($download['version']) ?>">
                            <button type="button" class="btn btn-outline-danger remove-download-link" title="Remove">&times;</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="download-link mb-2">
                    <input type="text" name="download_url[]" class="form-control" placeholder="Download URL">
                    <div class="input-group mt-1">
                        <input type="text" name="download_version[]" class="form-control" placeholder="Version (optional)">
                        <button type="button" class="btn btn-outline-danger remove-download-link" title="Remove">&times;</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" id="add-download-link" class="btn btn-secondary mt-2">Add another download link</button>
    </div>

    <!-- Include tags autocomplete -->
    <?php include 'views/assets/_tags_autocomplete.php'; ?>

    <button type="submit" class="btn btn-primary"><?= $submitButton ?></button>
    <?php if (isset($cancelLink)): ?>
        <a href="<?= $cancelLink ?>" class="btn btn-secondary">Cancel</a>
    <?php endif; ?>
</form>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var quill = new Quill('#editor', {
        theme: 'snow'
    });
    var descriptionInput = document.getElementById('description');
    quill.root.innerHTML = descriptionInput.value;
    var form = document.querySelector('form');
    form.addEventListener('submit', function() {
        descriptionInput.value = quill.root.innerHTML;
    });
});

// Pass the tags array to JavaScript for autocomplete
const availableTags = <?= json_encode(array_values($availableTags)); ?>;

document.getElementById('add-download-link').addEventListener('click', function() {
    var container = document.getElementById('download-links');
    var div = document.createElement('div');
    div.className = 'download-link mb-2';
    div.innerHTML = 
         '<input type="text" name="download_url[]" class="form-control" placeholder="Download URL">' +
         '<div class="input-group mt-1">' +
             '<input type="text" name="download_version[]" class="form-control" placeholder="Version (optional)">' +
             '<button type="button" class="btn btn-outline-danger remove-download-link" title="Remove">&times;</button>' +
         '</div>';
    container.appendChild(div);
});

// Делегирование клика для удаления нужного блока ссылки
document.getElementById('download-links').addEventListener('click', function(event) {
    if (event.target && event.target.classList.contains('remove-download-link')) {
        var downloadLinkDiv = event.target.closest('.download-link');
        if (downloadLinkDiv) {
            downloadLinkDiv.remove();
        }
    }
});
</script> 