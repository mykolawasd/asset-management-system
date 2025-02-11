<div class="container mt-5">
    <h2>Edit Asset</h2>
    <?php 
        $action = "/Assets/edit?id=" . $asset['id']; 
        $submitButton = "Save Changes";
        $cancelLink = "/Assets/view?id=" . $asset['id']; 
        include __DIR__ . '/_form.php'; 
    ?>
</div> 