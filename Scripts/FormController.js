class AlertFormOptions {
    /**
     * @param sucessMessage {string}
     * @param options {Partial<AlertFormOptions>|null}
     */
    constructor(
        sucessMessage,
        options = null
    ) {
        this.successMessage = sucessMessage;
        if (options) {
            Object.assign(this, options);
        }
    }

    /**
     *
     * @type {string}
     */
    errorMessage = "Der skete en fejl:";
    /**
     *
     * @type {string}
     */
    successMessage;
}

class FormElementSelectorOptions {
    /**
     * @param elementId {string}
     */
    constructor(
        elementId
    ) {
        this.elementId = elementId;
    }

    /**
     * @type {string}
     */
    elementId;
    /**
     * @returns {JQuery}
     */
    selector = () => jQuery(`#${this.elementId}`);
}

class FormActionOptions extends FormElementSelectorOptions {
    /**
     *
     * @param elementId {string}
     * @param options {Partial<FormActionOptions>|null}
     */
    constructor(
        elementId,
        options = null
    ) {
        super(elementId);
        if (options) {
            Object.assign(this, options);
        }
    }

    /**
     * @type {(Event) => void}
     */
    action;
}

class FormActionRedirectOptions extends FormActionOptions {
    /**
     *
     * @param elementId {string}
     * @param options {Partial<FormActionRedirectOptions>|null}
     */
    constructor(
        elementId,
        options = null
    ) {
        super(elementId);
        if (options) {
            Object.assign(this, options);
        }
    }

    /**
     * @type {string|null}
     */
    redirectTo = null;
    /**
     * @type {string}
     */
    endpoint;
    /**
     * @type {() => object}
     */
    dtoCreator;
}


class FormController {
    //#region properties
    /**
     * @type {string}
     * @private
     */
    _dataInitTag = "init";
    /**
     * @type {Alert|null}
     */
    alert;
    /**
     * @type {string}
     */
    wpNonce;
    /**
     * @type {FormElementSelectorOptions}
     */
    formOptions;
    /**
     * @type {FormActionRedirectOptions|null}}
     */
    cancelOptions;
    /**
     * @type {FormActionRedirectOptions|null}
     */
    submitOptions;
    /**
     * @type {FormActionOptions|null}
     */
    editOptions;
    /**
     * @type {FormActionRedirectOptions|null}
     */
    deleteOptions;
    /**
     * @type {AlertFormOptions|null}
     */
    alertOptions;

//#endregion

    /**
     * @param wpNonce {string}
     * @param formOptions {FormElementSelectorOptions}
     * @param cancelOptions {FormActionRedirectOptions|null}
     * @param submitOptions {FormActionRedirectOptions|null}
     * @param editOptions {FormActionOptions|null}
     * @param deleteOptions {FormActionRedirectOptions|null}
     * @param alertOptions {AlertFormOptions|null}
     */
    constructor(
        wpNonce,
        formOptions,
        cancelOptions,
        submitOptions,
        editOptions = null,
        deleteOptions = null,
        alertOptions = null,
    ) {
        this.wpNonce = wpNonce;
        this.formOptions = formOptions;
        this.cancelOptions = cancelOptions;
        if (this.cancelOptions?.action) {
            this.onCancel = this.cancelOptions.action;
        }
        this.cancelOptions?.selector()
            .on("click", (e) => this.onCancel(e))

        this.submitOptions = submitOptions;
        if (this.submitOptions?.action) {
            this.onSubmit = this.submitOptions.action;
        }
        this.submitOptions?.selector()
            .on("click", (e) => this.onSubmit(e));

        this.editOptions = editOptions;
        if (this.editOptions?.action) {
            this.onEdit = this.editOptions.action;
        }
        this.editOptions?.selector()
            .on("click", (e) => this.onEdit(e));

        this.deleteOptions = deleteOptions;
        if (this.deleteOptions?.action) {
            this.onDelete = this.deleteOptions.action;
        }
        this.deleteOptions?.selector()
            .on("click", (e) => this.onDelete(e));

        this.alertOptions = alertOptions;
        if (this.alertOptions) {
            this.alert = new Alert(this.formOptions.selector().find(".alert").first());
        }

        this.initForm();
    }

    //#region Actions
    /**
     *
     * @param e {Event}
     */
    onCancel = (e) => {
        e.preventDefault();
        this.enableForm(false);

        if (this.cancelOptions?.redirectTo) {
            window.location.href = this.cancelOptions.redirectTo;
        } else {
            this.enableFormControls(false)
            this.resetForm();
            this.alert?.hideMessage(5000);
        }
    }

    /**
     *
     * @param event {SubmitEvent}
     */
    onSubmit = (event) => {
        event.preventDefault();

        this.enableForm(false);

        const url = new URL(this.submitOptions.endpoint);
        url.searchParams.append("_wpnonce", this.wpNonce);

        jQuery.ajax({
            url: url.toString(),
            data: JSON.stringify(this.submitOptions.dtoCreator()),
            contentType: "application/json",
            type: "POST"
        }).done(() => {
            this.alert?.updateMessage(this.alertOptions.successMessage, Alert.Statuses.SUCCESS);

            if (this.submitOptions?.redirectTo) {
                window.location.href = this.submitOptions.redirectTo;
            } else {
                this.initForm();
                this.onCancel(event);
            }
        }).fail((xhr) => {
            let error = "";
            try {
                error = JSON.parse(xhr.responseJSON);
            } catch {
                error = xhr.responseText
            }
            this.alert?.updateMessage([this.alertOptions?.errorMessage, error].join(" "), Alert.Statuses.ERROR);
            this.enableForm(true);
        });
    }

    /**
     *
     * @param event {Event}
     */
    onEdit = (event) => {
        event.preventDefault();
        this.enableForm(true);
        this.enableFormControls(true);
    }

    /**
     *
     * @param event {Event}
     */
    onDelete = (event) => {
        event.preventDefault();

        this.enableForm(false);

        const url = new URL(this.deleteOptions.endpoint);
        url.searchParams.append("_wpnonce", this.wpNonce);

        jQuery.ajax({
            url: url.toString(),
            type: "DELETE"
        }).done(() => {
            this.alert?.updateMessage(this.alertOptions.successMessage, Alert.Statuses.SUCCESS);

            if (this.deleteOptions?.redirectTo) {
                window.location.href = this.deleteOptions.redirectTo;
            } else {
                this.initForm();
                this.onCancel(event);
            }
        }).fail((xhr) => {
            this.alert?.updateMessage([this.alertOptions?.errorMessage, xhr.responseJSON].join(" "), Alert.Statuses.ERROR);
            this.enableForm(true);
        });
    }


    //#endregion

    //#region Utility
    /**
     * @param enabled {boolean}
     */
    enableForm = (enabled = true) => {
        this.formOptions.selector()
            .find("fieldset")
            .prop("disabled", !enabled);

    }
    enableFormControls = (enabled = true) => {

        /** @type {JQuery} */
        const bottomControlSelector = jQuery()
            .add(this.cancelOptions?.selector())
            .add(this.submitOptions?.selector())
            .add(this.deleteOptions?.selector());

        if (enabled) {
            this.editOptions?.selector()
                .fadeOut();
            bottomControlSelector
                .fadeIn();
        } else {
            this.editOptions?.selector()
                .fadeIn();
            bottomControlSelector
                .fadeOut();
        }
    }

    initForm() {
        this.formOptions?.selector()
            .find("input")
            .each((_, elem) => jQuery(elem).data(this._dataInitTag, jQuery(elem).val()));
    }

    resetForm() {
        this.formOptions?.selector()
            .find("input")
            .each((_, elem) => jQuery(elem).val(jQuery(elem).data(this._dataInitTag)));
    }

    //#endregion
}