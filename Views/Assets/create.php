<div class="container mt-5">
    <h2>Create New Asset</h2>
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $field => $error): ?>
                <p><?= h($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form method="post" action="/Assets/create" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="thumbnail" class="form-label">Thumbnail (will be resized to 720p)</label>
            <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description (HTML enabled)</label>
            <input type="hidden" name="description" id="description">
            <div id="editor" style="height: 200px; background-color: #fff;"></div>
        </div>
        <div class="mb-3">
            <label for="tags" class="form-label">Tags (comma separated)</label>
            <input type="text" name="tags" id="tags" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Additional Images (up to 10 photos, resized to 1080 max)</label>
            <div id="image-upload-container" class="d-flex flex-wrap gap-2">
                <div class="image-upload-box add-box">
                    <span class="plus-icon">+</span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Create Asset</button>
    </form>
</div>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<style>
    .image-upload-box {
        width: 120px;
        height: 120px;
        border: 2px dashed #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .image-upload-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .add-box {
        background-color: #f8f9fa;
    }
    .plus-icon {
        font-size: 48px;
        color: #888;
    }
    .remove-image {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255,255,255,0.7);
        border: none;
        font-size: 16px;
        cursor: pointer;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        line-height: 20px;
        text-align: center;
        z-index: 10;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var quill = new Quill('#editor', {
        theme: 'snow'
    });

    var form = document.querySelector('form');
    form.addEventListener('submit', function() {
        document.getElementById('description').value = quill.root.innerHTML;
    });

    const container = document.getElementById('image-upload-container');
    const addBox = container.querySelector('.add-box');
    const maxImages = 10;

    function updateAddBoxVisibility() {
        const count = container.querySelectorAll('.preview-box').length;
        addBox.style.display = count >= maxImages ? 'none' : 'flex';
    }

    addBox.addEventListener('click', function() {
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.name = 'images[]';
        fileInput.accept = 'image/*';
        fileInput.style.display = 'none';

        fileInput.addEventListener('change', function() {
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewBox = document.createElement('div');
                    previewBox.className = 'image-upload-box preview-box';
                    previewBox.innerHTML = '<img src="'+e.target.result+'" alt="Preview"><button type="button" class="remove-image">&times;</button>';
                    previewBox.appendChild(fileInput);
                    container.insertBefore(previewBox, addBox);
                    updateAddBoxVisibility();

                    previewBox.querySelector('.remove-image').addEventListener('click', function() {
                        previewBox.remove();
                        updateAddBoxVisibility();
                    });
                };
                reader.readAsDataURL(fileInput.files[0]);
            }
        });
        fileInput.click();
    });
});
</script>






