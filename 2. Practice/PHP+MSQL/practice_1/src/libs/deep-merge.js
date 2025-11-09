export const deepMerge = (target, source) => {
    if (typeof target !== 'object' || target === null) return source;
    if (typeof source !== 'object' || source === null) return target;

    const output = Array.isArray(target) ? target.slice() : { ...target };

    Object.keys(source).forEach((key) => {
        const srcVal = source[key];
        const tgtVal = output[key];

        if (Array.isArray(srcVal)) {
            // Replace arrays entirely
            output[key] = srcVal.slice();
        } else if (typeof srcVal === 'object' && srcVal !== null) {
            output[key] = deepMerge(typeof tgtVal === 'object' && tgtVal !== null ? tgtVal : {}, srcVal);
        } else {
            output[key] = srcVal;
        }
    });

    return output;
};
