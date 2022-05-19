'use strict';

function sendSite() {
    loadJSON('sites_json/654.json').then((data) => {
        console.log(data);
        postXHR('generate_text', {
            site: JSON.stringify(data),
            action: 'generatePdf'
        }).then(function (response) {
            console.log(response);
        });
    });
}