'use strict';

function sendSite() {
    loadJSON('sites_json/31.json').then((data) => {
        //console.log(generateHeader(data));
        let site = generateHeader(data);
        postXHR('generate_text', {
            site: site.siteName, //JSON.stringify(data),
            company: site.company,
            headers: JSON.stringify(site.headers),
            coordinates: JSON.stringify(site.coordinates),
            waterAltitude: JSON.stringify(site.waterAltitude),
            action: 'generatePdf'
        }).then(function (response) {
            document.getElementById("linkPdf").innerHTML = response;
        });
    });
}