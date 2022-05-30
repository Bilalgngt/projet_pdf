'use strict';

function sendSite() {
    loadJSON('sites_json/654.json').then((data) => {
        //console.log(generateHeader(data));
        let site = generateHeader(data);
        postXHR('generate_text', {
            site: site.siteName, //JSON.stringify(data),
            company: site.company,
            headers: JSON.stringify(site.headers),
            scaleDetails: JSON.stringify(site.scaleDetails),
            sampleDetails: JSON.stringify(site.sampleDetails),
            pollutantDetails: JSON.stringify(site.pollutantDetails),
            action: 'generatePdf'
        }).then(function (response) {
            document.getElementById("linkPdf").innerHTML = response;
        });
    });
}