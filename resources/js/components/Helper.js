export function format_currency(value, currencyCode = 'EUR') {
    var valueString = value

    return currencyCode + ' ' + valueString
}

export function urlParams() {
    var urlString = window.location.href
    var url = new URL(urlString);
    var params = url.searchParams

    return params
}

export function formatDate(value) {
    return Date(value)
}

export function setObjectPropValue(object, newProp) {
    var propKey = Object.keys(newProp)[0]
    var propValue = newProp[propKey]

    object[propKey] = propValue

    return object
}

export function arrayFindIndex(
    array,
    element,
    arrayMatchKey = 'id',
    elementMatchKey = 'id'
) {
    var foundIndex = array.findIndex(
        (a) => a[arrayMatchKey] == element[elementMatchKey]
    )

    return foundIndex
}

export function arrayUpdate(
    array, 
    newElement, 
    arrayMatchKey = 'id', 
    elementMatchKey = 'id',
) {
    var updatedIndex = array.findIndex(
        (a) => a[arrayMatchKey] == newElement[elementMatchKey]
    )
    
    if (updatedIndex > -1)
        array[updatedIndex] = newElement
    else
        array.push(newElement)

    return array
}

export function arrayPluck(
    array, 
    element, 
    arrayMatchKey = 'id', 
    elementMatchKey = 'id'
) {
    var deletedIndex = array.findIndex(
        (a) => a[arrayMatchKey] == element[elementMatchKey]
    )
    
    // Renewing sets of array
    if (deletedIndex > -1)
        array.splice(deletedIndex, 1)

    return array
}

export function convertObjectToArray(object) {
    return Object.keys(obj).map(function (key) {
        return { [key]: obj[key] };
    })
}

export function stripos(fHaystack, fNeedle, fOffset) {
    const haystack = (fHaystack + '').toLowerCase()
    const needle = (fNeedle + '').toLowerCase()
    let index = 0

    return ((index = haystack.indexOf(needle, fOffset)) !== -1) ?
        index : false
}

import { collect } from 'collect.js'
import { update } from 'lodash';
export function searchInCollection(haystack, needle) {
    let collection = collect(haystack)

    collection = collection.filter((object) => {
        let keys = Object.keys(object)
        for (let key of keys) {
            if (stripos(object[key], needle) !== false)
                return true
        }
    })

    return collection.items
}