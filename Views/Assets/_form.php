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

    <div class="mb-3">
        <label for="tags" class="form-label">Tags (comma separated)</label>
        <?php
            $tagsValue = isset($tags) ? implode(',', array_map(function($tag){ return $tag['name']; }, $tags)) : '';
        ?>
        <input type="text" name="tags" id="tags" class="form-control" value="<?= h($tagsValue) ?>">
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
</script> 