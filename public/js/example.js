'use strict';

function sendSite() {
    loadJSON('public/sites_json/31.json').then((data) => {
        //console.log(generateHeader(data));
        let site = generateDataForPdf(data);
        postXHR('generate_pdf', {
            site: site.siteName, //JSON.stringify(data),
            company: site.company,
            pages: JSON.stringify(site.pages),
            maxDepths: JSON.stringify(site.maxDepths),
            sampleDetails: JSON.stringify(site.sampleDetails),
            pollutantDetails: JSON.stringify(site.pollutantDetails),
            action: 'generatePdf'
        }).then(function (response) {
            document.getElementById("linkPdf").innerHTML = response;
        });
    });
}