'use strict';

const RELATIVE_PATH = {
    controllers: 'app/controllers/',
};

function loadJSON(filePath) {
    return new Promise((resolve, reject) => {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', filePath, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                resolve(JSON.parse(xhr.response));
            }
        };
        xhr.send(null);
    });
}

function postXHR(controller, parameters = {}) {
    return new Promise(resolve => {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', RELATIVE_PATH.controllers + controller + '.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = (data) => {
            if (xhr.getResponseHeader('Content-Type').includes('json')) {
                resolve(JSON.parse(data.target['response']));
            } else {
                resolve(data.target['response']);
            }
        };
        xhr.send(encodeURIObject(parameters));
    });
}

function encodeURIObject(data) {
    let keys = Object.keys(data);
    let response = [];
    for (let key of keys) {
        response.push(key + '=' + data[key])
    }
    //console.log(encodeURI(response.join('&')));
    return encodeURI(response.join('&'));
}