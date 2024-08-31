class ObjectParser {
    /**
     *
     * @param object {object}
     */
    static makeEmptyStringsNull(object) {
        Object.keys(object).forEach((key) => {
            if (typeof object[key] === 'string' && object[key].trim().length === 0) {
                object[key] = null;
            }
        });
        return object;
    }

    /**
     *
     * @param elementId {string}
     * @param targetType {"string"|"number"|"bool"|undefined}
     * @return {string|number|boolean|undefined}
     */
    static getValue(elementId, targetType = undefined) {
        const selector = jQuery(`#${elementId}`);
        /** @type {string|number|bool|undefined}  */
        let val;
        if (selector.is('[type="checkbox"]')) {
            val = selector.is(":checked");
        } else if (selector.is('[type="number"]')) {
            val = +selector.val();
        } else if (selector.is('[type="date"]')) {
            val = selector.val();
            if(!val){
                val = null;
            }
        } else {
            val = selector.val();
        }

        switch (targetType) {
            case "bool": {
                return !!val;
            }
            case "number": {
                return +val;
            }
            default: {
                return val;
            }
        }
    }
}