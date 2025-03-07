import { useSelect, select } from "@wordpress/data";
import { store as coreStore } from "@wordpress/core-data";

/**
 * Type representing the selectors available in the WordPress core store.
 *
 * @typedef {ReturnType<typeof select<typeof coreStore>>} CoreSelectors
 */

/**
 * Callback function type for selecting data from the WordPress core store.
 *
 * @callback SelectorCallback
 * @param {CoreSelectors} selectors The selectors available in the core store.
 * @returns {*} The selected data.
 */

/**
 * Custom hook to select data from the WordPress core store.
 *
 * @template T The return type of the callback function.
 * @param {(store: CoreSelectors) => T} callback The selector function.
 * @param {Array} dependencies Dependencies for useSelect to determine re-execution.
 * @returns {T} The selected data, inferred from the callback.
 */
export function useWpSelector(callback, dependencies) {
    return useSelect(
        (select) => callback(select(coreStore)),
        dependencies
    );
}

/**
 * @returns {{
 *  name: string,
 *  title: string,
 *  tagName: string,
 *  className: string | null,
 * }[]} The list of default formats for the rich text editor.
 */
export function useRichtextDefaultFormats() {
    return useSelect(
        (select) => select('core/rich-text').getFormatTypes(),
        []
    );
}
