<div class="container mt-5">
    <h2>Edit Asset</h2>
    <?php 
        $action = "/assets/edit?id=" . $asset['id']; 
        $submitButton = "Save Changes";
        $cancelLink = "/assets/view?id=" . $asset['id']; 
        include __DIR__ . '/_form.php'; 
    ?>
</div> 