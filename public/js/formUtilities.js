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
            <input type="${type}" ${options} class="input input-bordered input-base w-full my-2" name="${lowerCasedName}" />
            <div id="error-${lowerCasedName}" class="badge badge-error hidden"></div>
        </div>
    `;
}