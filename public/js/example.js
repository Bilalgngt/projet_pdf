'use strict';

function sendSite() {
    loadJSON('sites_json/654.json').then((data) => {
        console.log(data);
        postXHR('generate_text', {
            site: JSON.stringify(data),
            company: document.getElementById('Company').value,
            action: 'generatePdf'
        }).then(function (response) {
            console.log(response);
        });
    });
}