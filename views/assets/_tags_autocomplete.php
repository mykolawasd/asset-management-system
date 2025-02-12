<?php
// Before including this partial, you need to define the following variables:
// $availableTags, $selectedTags, $tagsById
// The flag $allowNewTags is true by default, but you can override it in the search.
if (!isset($allowNewTags)) {
    $allowNewTags = true;
}
?>
<div class="mb-3">
    <label for="tags-autocomplete" class="form-label">Tags</label>
    <input type="text" id="tags-autocomplete" class="form-control" placeholder="Enter tag name" data-allow-new="<?= $allowNewTags ? 'true' : 'false' ?>">
    <div id="tags-suggestions" class="list-group" style="max-height: 200px; overflow-y: auto; display: none;"></div>
    <input type="hidden" id="tags" name="tags" value="<?= h(implode(',', $selectedTags ?? [])) ?>">
    <div id="selected-tags" class="mt-2">
        <?php if (!empty($selectedTags)): ?>
            <?php foreach ($selectedTags as $tagId): ?>
                <span class="badge bg-secondary me-1" data-tag-id="<?= h($tagId) ?>">
                    <?= h($tagsById[$tagId] ?? $tagId) ?>
                </span>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>