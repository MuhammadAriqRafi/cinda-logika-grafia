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
            <img src="#" height="100" class="img-preview rounded-lg my-3">
            <input type="file" class="text-sm text-grey-500 file:mr-5 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-medium 
            file:bg-blue-50 file:text-blue-700 hover:file:cursor-pointer hover:file:bg-amber-50 hover:file:text-amber-700" name="${lowerCasedName}" onchange="previewImage(this)" accept="image/jpg, image/jpeg, image/png" />
            <div id="error-${lowerCasedName}" class="badge badge-error hidden"></div>
        </div>
    `;
}

const dropdownComponent = (title, name, options) => {
    const capitalizedTitleFirstLetter = capitalizeFirstLetter(title);
    const lowerCasedName = name.toLowerCase();
    let optionList = '';

    options.forEach(option => {
        if (Object.keys(option).length == 1) optionList += `<option value="${option[Object.keys(option)[0]]}">${option[Object.keys(option)[0]]}</option>`;
        else if (Object.keys(option).length == 2) optionList += `<option value="${option[Object.keys(option)[0]]}">${option[Object.keys(option)[1]]}</option>`
    });

    return `
        <div class="form-control mb-4" onclick="resetInvalidClass(this)">
            <span class="label-text font-bold">${capitalizedTitleFirstLetter}</span>
            <select name="${lowerCasedName}" class="select select-bordered w-full max-w-xs mt-2 mb-3">
                <option value="" hidden>-- Pilih ${capitalizedTitleFirstLetter} --</option>
                ${optionList}
            </select>
            <div id="error-${lowerCasedName}" class="badge badge-error hidden"></div>
        </div>
    `;
}

const resetForm = (formId) => {
    $(`#${formId}`).trigger('reset');
    $('.img-preview').attr('src', '#');
    $('#summernote').summernote('reset');
}