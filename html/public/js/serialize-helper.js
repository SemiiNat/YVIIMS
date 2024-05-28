const serializeFormArray = (form) => {
    // Setup our serialized data
    const serialized = [];

    // Loop through each field in the form
    for (let i = 0; i < form.elements.length; i++) {
        const field = form.elements[i];

        // Don't serialize fields without a name, submits, buttons, file and reset inputs, and disabled fields
        if (
            !field.name ||
            field.disabled ||
            field.type === "file" ||
            field.type === "reset" ||
            field.type === "submit" ||
            field.type === "button"
        ) {
            /* eslint-disable no-continue */
            continue;
        }

        // If a multi-select, get all selections
        if (field.type === "select-multiple") {
            for (let n = 0; n < field.options.length; n += 1) {
                if (!field.options[n].selected) {
                    /* eslint-disable no-continue */
                    continue;
                }
                serialized.push({
                    name: field.name,
                    value: field.options[n].value,
                });
            }
        }

        // Convert field data to a query string
        else if (
            (field.type !== "checkbox" && field.type !== "radio") ||
            field.checked
        ) {
            serialized.push({
                name: field.name,
                value: field.value,
            });
        }
    }

    return serialized;
};

const objectifyForm = (form) => {
    const formArray = serializeFormArray(form);
    const formObject = {};
    for (let i = 0; i < formArray.length; i += 1) {
        let fieldName = formArray[i].name;

        if (fieldName.includes("[]")) {
            // Handle array fields
            fieldName = fieldName.replace("[]", "");
            formObject[fieldName] = formObject[fieldName] ?? [];
            formObject[fieldName].push(formArray[i].value);
        } else {
            // Simple assignment
            formObject[fieldName] = formArray[i].value;
        }
    }

    return formObject;
};

const fillForm = (form, data) => {
    Object.keys(data).forEach((key) => {
        const field = form.querySelectorAll(`[name="${key}"]`);
        /* eslint-disable no-plusplus */
        for (let counter = 0; counter < field.length; counter++) {
            if (field[counter].getAttribute("type") === "radio") {
                if (parseInt(field[counter].value, 10) === data[key]) {
                    field[counter].checked = true;
                    break;
                }
                /* eslint-disable no-continue */
                continue;
            }
            field[counter].value = data[key];
        }
    });
};
