JSON.get = function(data, key) {
    keys = key.split('.');
    if (keys.length <= 1) {
        return (isset(data[keys[0]])) ? data[keys[0]] : null;
    }
    _key = keys.shift();
    return (isset(data[_key])) ? JSON.get(data[_key], keys.join('.')) : null;
}

JSON.getBy = function(data, key, value) {
    return JSON.iterate(data, function(e){
        return (e.key == value);
    });
}

JSON.iterate = function(data, fn) {
    if (typeof(data) == 'object') {
        for(var i in data) {
            if (fn(data[i], data)) {
                return data[i];
            }
        }
    } else {
        for(var i = 0; i < data.length; i++) {
            if (fn(data[i], data)) {
                return data[i];
            }
        }
    }
    return null;
}

JSON.encode = function(param, lEscapeQuotes) {
    var _ret = JSON.stringify(param);
    return (lEscapeQuotes) ? _ret.replace(/\"/g, "'"): _ret;
}

JSON.decode = function(json, lEscapeQuotes) {
    return JSON.parse((lEscapeQuotes) ? json.replace(/\'/g, '"') : json);
}

JSON.sortBy = function(data, colKey, lDesc) {
    data.sort(function(ea, eb){
        a = JSON.get(ea, colKey);
        b = JSON.get(eb, colKey);
        if ((typeof(a) == 'number' || a === null) && (typeof(b) == 'number' || b === null)) {
            if (!isset(b) || a > b) {
                return 1;
            }
            if (!isset(a) || a < b) {
                return -1;
            }
            return 0;
        }
        if (isset(a) && isset(b)) {
            return a.localeCompare(b);
        }
        if (!isset(b)) {
            return 1;
        }
        if (!isset(a)) {
            return -1;
        }
        return 0;
    });
    if (lDesc) {
        data.reverse();
    }
    return data;
}

function in_array(needle, haystack) {
    return $.inArray(needle, haystack) > -1;
}

function isset(val) {
    return typeof(val) != 'undefined' && val !== null;
}