const capitalizeFirstLetter = (word) => {
    return word.charAt(0).toUpperCase() + word.slice(1);
}

const resetInvalidClass = (element) => {
    const textareaError = $(element).find('.textarea-error');
    const selectError = $(element).find('.select-error');
    const inputError = $(element).find('.input-error');

    $(element).find('.badge-error').text('');
    $(element).find('.badge-error').addClass('hidden');

    if (textareaError.length > 0) textareaError.removeClass('textarea-error');
    if (selectError.length > 0) selectError.removeClass('select-error');
    if (inputError.length > 0) inputError.removeClass('input-error');
}

const textInputComponent = (title, name, type = 'text', options = '') => {
    const capitalizedTitleFirstLetter = capitalizeFirstLetter(title);
    const lowerCasedName = name.toLowerCase();

    return `
        <div class="form-control mb-4" onclick="resetInvalidClass(this)">
            <span class="label-text font-bold">${capitalizedTitleFirstLetter}</span>
            <input type="${type}" ${options} class="input input-bordered input-neutral focus:input-primary w-full mt-2 mb-3" name="${lowerCasedName}" />
            <div id="error-${lowerCasedName}" class="badge badge-error hidden"></div>
        </div>
    `;
}

const textareaComponent = (title, name, summernote = false) => {
    const capitalizedTitleFirstLetter = capitalizeFirstLetter(title);
    const lowerCasedName = name.toLowerCase();
    let attribute = `class="textarea textarea-bordered"`;

    if (summernote) {
        attribute = `id="summernote"`;
    }

    return `
        <div class="form-control mb-4" onclick="resetInvalidClass(this)">
            <span class="label-text font-bold mb-2">${capitalizedTitleFirstLetter}</span>
            <textarea ${attribute} name="${lowerCasedName}"></textarea>
            <div id="error-${lowerCasedName}" class="badge badge-error hidden mt-2"></div>
        </div>
    `;
}

const fileInputComponent = (title, name) => {
    const capitalizedTitleFirstLetter = capitalizeFirstLetter(title);
    const lowerCasedName = name.toLowerCase();

    return `
        <div class="form-control mb-4" onclick="resetInvalidClass(this)">
            <span class="label-text font-bold">${capitalizedTitleFirstLetter}</span>
            <img src="#" height="100" class="img-preview my-2">
            <input type="file" class="input input-bordered input-neutral focus:input-primary w-full mt-2 mb-3" name="${lowerCasedName}" onchange="previewImage(this)" accept="image/jpg, image/jpeg, image/png" />
            <div id="error-${lowerCasedName}" class="badge badge-error hidden"></div>
        </div>
    `;
}

const resetForm = (formId) => {
    $(`#${formId}`).trigger('reset');
    $('.img-preview').attr('src', '#');
    $('#summernote').summernote('reset');
}