document.addEventListener("keypress", function (event) {
    const target = event.target;
    const character = String.fromCharCode(event.which);

    if (target.classList.contains("custom-allowed-input")) {
        const allowedRegex = /[a-zA-Z0-9\s.,@#$%*()\/\\=+!^~&_]/;
        if (!allowedRegex.test(character)) {
            event.preventDefault();
        }
    }

    if (target.classList.contains("alphanumeric-only")) {
        const allowedRegexAlphaNumeric = /[a-zA-Z0-9\s.,&]/;
        if (!allowedRegexAlphaNumeric.test(character)) {
            event.preventDefault();
        }
    }

    if (target.classList.contains("numbers-only")) {
        const allowedRegexNumeric = /[0-9]/;
        if (!allowedRegexNumeric.test(character)) {
            event.preventDefault();
        }
    }

    if (target.classList.contains("text-only")) {
        const allowedRegexAlpha = /[a-zA-Z\s]/;
        if (!allowedRegexAlpha.test(character)) {
            event.preventDefault();
        }
    }
});

document.addEventListener("paste", function (event) {
    const target = event.target;

    if (target.classList.contains("custom-allowed-input")) {
        setTimeout(() => {
            const disallowedRegex = /[^a-zA-Z0-9\s.,@#$%*()\/\\=+!^~&_]/g;
            target.value = target.value.replace(disallowedRegex, "");
        });
    }

    if (target.classList.contains("alphanumeric-only")) {
        setTimeout(() => {
            const disallowedRegexAlphaNumeric = /[^a-zA-Z0-9\s.,&]/g;
            target.value = target.value.replace(
                disallowedRegexAlphaNumeric,
                ""
            );
        });
    }

    if (target.classList.contains("numbers-only")) {
        setTimeout(() => {
            const disallowedRegexNumeric = /[^0-9]/g;
            target.value = target.value.replace(disallowedRegexNumeric, "");
        });
    }

    if (target.classList.contains("text-only")) {
        setTimeout(() => {
            const disallowedRegexAlpha = /[^a-zA-Z\s]/g;
            target.value = target.value.replace(disallowedRegexAlpha, "");
        });
    }
});
