class Alert {
    /** @type JQuery */
    containerSelector;
    static Statuses = {
        ERROR: "DANGER",
        WARNING: "WARNING",
        SUCCESS: "SUCCESS"
    };

    /**
     *
     * @param containerSelector {JQuery}
     */
    constructor(containerSelector) {
        this.containerSelector = containerSelector;
    };

    /**
     * @param status {string}
     * @param message {string}
     */
    updateMessage(message, status) {
        this.containerSelector
            .removeClass((_, className) => (className.match(/alert-.+/) || []).join(" "))
            .addClass(`alert-${status.toLowerCase()}`)
            .html(message)
            .fadeIn();
    };

    /**
     *
     * @param delay {int}
     */
    hideMessage(delay = 0) {
        setTimeout(() => {
            this.containerSelector
                .fadeOut();
        }, delay)
    }
}