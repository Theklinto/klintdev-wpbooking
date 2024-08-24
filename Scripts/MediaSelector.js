class WPAttachmentMinified {
    /**
     * @type {number}
     */
    id;
    /**
     * @type {string}
     */
    url;
}

class MediaSelector {
    /**
     *
     * @param selectedAttachmentIds {number[]}
     * @param title {string}
     * @param buttonText {string}
     * @param allowMultiple {boolean}
     * @returns {Promise<WPAttachmentMinified[]>}
     */
    static wpMediaSelector(
        selectedAttachmentIds,
        title,
        buttonText,
        allowMultiple
    ) {
        return new Promise((resolve) => {

            // Create the media frame.
            let file_frame = wp.media.frames.file_frame = wp.media({
                title: title,
                button: {
                    text: buttonText,
                },
                multiple: allowMultiple ? "add" : false, // Set to true to allow multiple files to be selected
            });

            file_frame.on("open", function () {
                if (selectedAttachmentIds.length > 0) {
                    const selection = file_frame.state(undefined).get("selection");
                    selectedAttachmentIds.forEach((id) => {
                        const attachment = wp.media.attachment(id);
                        selection.add(attachment ? [attachment] : []);
                    });
                }
            });

            // When an image is selected, run a callback.
            file_frame.on("select", function () {
                resolve(
                    file_frame
                        .state(undefined)
                        .get("selection")
                        .map((x) => Object.assign(new WPAttachmentMinified(), x.attributes))
                );
            });

            // Finally, open the modal
            file_frame.open();
        });
    }

}

