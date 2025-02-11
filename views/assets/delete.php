<div class="container mt-5">
    <h2>Delete Asset</h2>
    <div class="alert alert-warning">
        <p>Are you sure you want to delete the asset "<?= h($asset['title']) ?>"?</p>
    </div>
    <form method="post" action="/assets/delete?id=<?= $asset['id'] ?>">
        <button type="submit" class="btn btn-danger">Yes, Delete Asset</button>
        <a href="/assets/view?id=<?= $asset['id'] ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div> 