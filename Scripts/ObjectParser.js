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
}