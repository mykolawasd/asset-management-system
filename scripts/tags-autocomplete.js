document.addEventListener('DOMContentLoaded', function () {
    const tagInput = document.getElementById('tags-autocomplete');
    const tagHidden = document.getElementById('tags');
    const selectedTagsContainer = document.getElementById('selected-tags');
    const suggestionsContainer = document.getElementById('tags-suggestions');
    let selectedTags = [];

    // Initialize selected tags from the hidden field
    if (tagHidden.value.trim() !== "") {
        let ids = tagHidden.value.split(',').filter(id => id.trim() !== "");
        ids.forEach(function (tagId) {
            const badge = selectedTagsContainer.querySelector(`[data-tag-id="${tagId}"]`);
            let tagName = badge ? badge.textContent.trim() : tagId;
            selectedTags.push({ id: tagId, name: tagName });
        });
    }

    function updateHiddenField() {
        tagHidden.value = selectedTags.map(tag => tag.id || tag.name).join(',');
    }

    function addBadge(tagId, tagName) {
        const badge = document.createElement('span');
        badge.className = 'badge bg-secondary me-1';
        badge.textContent = tagName;
        badge.dataset.tagId = tagId ? tagId : tagName;
        badge.addEventListener('click', function () {
            removeTag(tagId || tagName);
        });
        selectedTagsContainer.appendChild(badge);
    }

    function addTag(id, name) {
        if (selectedTags.find(tag => (tag.id && tag.id === id) || tag.name === name)) {
            return;
        }
        selectedTags.push({ id: id, name: name });
        addBadge(id, name);
        updateHiddenField();
        tagInput.value = '';
        suggestionsContainer.innerHTML = '';
        suggestionsContainer.style.display = 'none'; // Hide suggestions after adding
    }

    function removeTag(identifier) {
        selectedTags = selectedTags.filter(tag => (tag.id || tag.name) !== identifier);
        const badge = selectedTagsContainer.querySelector(`[data-tag-id="${identifier}"]`);
        if (badge) {
            badge.remove();
        }
        updateHiddenField();
    }

    function updateSuggestions() {
        const query = tagInput.value.trim().toLowerCase();
        suggestionsContainer.innerHTML = '';
        if (!query) {
            suggestionsContainer.style.display = 'none'; // Hide if empty query
            return;
        }
        const filtered = availableTags.filter(tag => tag.name.toLowerCase().includes(query));

        let allowNew = tagInput.getAttribute('data-allow-new');
        if (allowNew !== "false" && !filtered.find(tag => tag.name.toLowerCase() === query)) {
            const createNew = document.createElement('div');
            createNew.className = 'list-group-item list-group-item-action';
            createNew.textContent = `Create new tag "${tagInput.value.trim()}"`;
            createNew.addEventListener('click', function () {
                addTag(null, tagInput.value.trim());
            });
            suggestionsContainer.appendChild(createNew);
        }

        filtered.forEach(tag => {
            const suggestion = document.createElement('div');
            suggestion.className = 'list-group-item list-group-item-action';
            suggestion.textContent = tag.name;
            suggestion.addEventListener('click', function () {
                addTag(tag.id, tag.name);
            });
            suggestionsContainer.appendChild(suggestion);
        });

        // Show the suggestions container if there are results
        if (suggestionsContainer.childNodes.length > 0) {
            suggestionsContainer.style.display = 'block';
        } else {
            suggestionsContainer.style.display = 'none'; // Hide if no results
        }
    }

    tagInput.addEventListener('input', updateSuggestions);

    document.addEventListener('click', function(e) {
        if (!tagInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
            suggestionsContainer.style.display = 'none'; // Hide when clicked outside
        }
    });


    
    selectedTagsContainer.addEventListener('click', function (e) {
      if (e.target.classList.contains('badge')) {
        const identifier = e.target.getAttribute('data-tag-id');
        removeTag(identifier);
      }
    });
});

