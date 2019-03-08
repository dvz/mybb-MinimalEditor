let MinimalEditor = function ($textarea, jQuery, lang, rootpath, my_post_key) {
    let options = {};
    let defaultOptions = {};

    let $ = document.querySelector.bind(document);

    let $preview = $('.message-preview');
    let $label = $('.minimaleditor label');
    let $checkbox = $('.minimaleditor__checkbox');

    let cacheValid = false;
    let lastMessageSource = '';
    let lastMessageOutput = '';

    let getOutputPromise = null;

    let getOutput = source => {
        if (getOutputPromise !== null) {
            return getOutputPromise;
        } else {
            return new Promise((resolve, reject) => {
                if (cacheValid === true && source === lastMessageSource) {
                    resolve(lastMessageOutput);
                } else {
                    jQuery.post(rootpath + '/xmlhttp.php?action=get_preview', {
                        message: source,
                        options: options,
                        my_post_key: my_post_key,
                    }, response => {
                        if (typeof response === 'object') {
                            if (response.hasOwnProperty('errors')) {
                                jQuery.each(response.errors, function (i, message) {
                                    jQuery.jGrowl(lang.post_fetch_error + ' ' + message, {theme: 'jgrowl_error'});
                                });

                                reject();
                            } else {
                                lastMessageSource = source;
                                lastMessageOutput = response.output;
                                cacheValid = true;

                                resolve(response.output);
                            }
                        } else {
                            reject();
                        }

                        getOutputPromise = null;
                    });
                }
            });
        }
    };

    let loadOutput = () => {
        getOutput($textarea.value);
    };

    let handleToggle = function () {
        if (this.checked) {
            if ($textarea.value.trim() !== '') {
                $textarea.style.display = 'none';
                $preview.style.display = 'block';

                getOutput($textarea.value).then(output => {
                    $preview.innerHTML = output;
                });
            } else {
                return false;
            }
        } else {
            $textarea.style.display = 'block';
            $preview.style.display = 'none';
        }

        return false;
    };

    let handleDisableSmiliesToggle = function () {
        let smiliesOption = this.checked ? 0 : defaultOptions['smilies'];

        if (options['smilies'] !== smiliesOption) {
            cacheValid = false;
            options['smilies'] = smiliesOption;
        }
    };

    for (let i in document.currentScript.attributes) {
        let element = document.currentScript.attributes[i];

        if (element.specified && element.name === 'data-options') {
            options = JSON.parse(element.value);
            defaultOptions = JSON.parse(element.value);
        }
    }

    $textarea.parentNode.setAttribute('valign', 'top');
    $textarea.parentNode.insertBefore($('.minimaleditor'), $textarea);
    $label.addEventListener('mouseover', loadOutput);
    $checkbox.addEventListener('change', handleToggle);

    jQuery(function () {
        let $disableSmiliesToggle = $('input[name$="[disablesmilies]"]');

        if ($disableSmiliesToggle) {
            handleDisableSmiliesToggle.call($disableSmiliesToggle);
            $disableSmiliesToggle.addEventListener('change', handleDisableSmiliesToggle);
        }

        handleToggle.call($checkbox);
    });
};

if (MyBBEditor === null) {
    new MinimalEditor(document.querySelector('#message'), jQuery, lang, rootpath, my_post_key);
}
