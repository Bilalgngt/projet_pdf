function generateDataForPdf(site) {
    let result = {
        company: document.getElementById('Company').value,
        siteName: site.name,
        pages: [],
        maxDepths: [],
        sampleDetails: [],
        pollutantDetails: getPollutantDetails()
    };
    let pageNumber = 0;
    console.log(site);
    for (let logId in site.logs) {
        let log = site.logs[logId];
        let waterZ = calculateWaterZMinZMax(log);

        let header = {
            name: log.about.name,
            strainer_zMin: log.about.strainer_zMin,
            strainer_zMax: log.about.strainer_zMax,
            date_campaign: log.about.date_campaign,
            type: log.about.type,
            altitude: log.about.altitude,
            gpsSystem: site.gpsPosition.system,
            longitude: null,
            latitude: null,
            waterAltitude: getWaterAltitudeCell(log, waterZ.min)
        };
        //declaration de l'objet coordonnées en fonction du système
        let coordinates = getGPSCoordinateCell(site, log);
        header.longitude = coordinates.longitude;
        header.latitude = coordinates.latitude;

        //declaration de l'altitude de la nappe si existante sinon null
        let depths = {
            waterMaxDepth: waterZ.max,
            sampleMaxDepth: calculateSampleMaxDepth(log),
            strainerMaxDepth: log.about.strainer_zMax,
        };
        result.sampleDetails[pageNumber] = getSampleDetails(log, result.pollutantDetails);
        result.pages.push(header);
        result.maxDepths.push(Math.max(...Object.values(depths)));
        pageNumber++;
    }
    console.log(result);
    return result;

    function isSamePollutant(pollutant1, pollutant2) {
        return pollutant1.id_pollutant_name === pollutant2.id_pollutant_name &&
            pollutant1.custom === pollutant2.custom;
    }

    function getPollutantDetails() {
        let pollutantDetails = [];
        let inputs = document.querySelectorAll('.polluant');
        for (let i = 0; i < inputs.length; i++) {
            if (inputs[i].checked) {
                pollutantDetails.push({id_pollutant_name: inputs[i].value, custom: "0"});
            }
        }
        return pollutantDetails;
    }

    function getGPSCoordinateCell(site, log) {
        let result = {
            longitude: '',
            latitude: ''
        };
        if (site.gpsPosition.system !== 'DD') {
            result.longitude = 'x(' + site.gpsPosition.system + '): ' + log.about.longitude;
            result.latitude = 'y(' + site.gpsPosition.system + '): ' + log.about.latitude;
        } else {
            result.longitude = 'Longitude: ' + log.about.longitude;
            result.latitude = 'Latitude: ' + log.about.latitude;
        }
        return result;
    }

    function getWaterAltitudeCell(log, waterZMin) {
        if (Object.keys(log.waterTables).length > 0) {
            return 'Altitude nappe : ' + (log.about.altitude - waterZMin).toFixed(3) + 'm NGF';
        } else {
            return '';
        }
    }

    function calculateWaterZMinZMax(log) {
        let waterZMin = null;
        let waterZMax = null;
        if (Object.keys(log.waterTables).length > 0) {
            for (let waterId in log.waterTables) {
                let water = log.waterTables[waterId];
                if (water.z_min < waterZMin || waterZMin === null) {
                    waterZMin = water.z_min;
                }
                if (water.z_max > waterZMax || waterZMax === null) {
                    waterZMax = water.z_max;
                }
            }
        }
        return {
            min: waterZMin,
            max: waterZMax
        };
    }

    function fillConcentrationCell(sample, pollutantDetails) {
        let concentrations = new Array(pollutantDetails.length).fill(null);
        pollutantDetails.forEach((pollutantDetail, i) => {
            for (let pollutant of sample.pollutants) {
                if (isSamePollutant(pollutantDetail, pollutant)) {
                    if (pollutant.under_detection === "1") {
                        concentrations[i] = "< L.D."
                    } else {
                        concentrations[i] = pollutant.concentration;
                    }
                }
            }
        });
        return concentrations;
    }

    function getSampleDetails(log, pollutantDetails) {
        if (Object.keys(log.samples).length > 0) {
            let sampleDetails = [];
            for (let samplesId in log.samples) {
                let sample = log.samples[samplesId];
                let sampleDetail = {
                    geologyId: sample.id_geology_name,
                    geologyCustom: sample.geology_custom,
                    start: sample.z_min,
                    end: sample.z_max,
                    analysis: 0,
                    comment: sample.comment,
                    concentrations: [],
                    pollutantToDraw: pollutantDetails.length,
                }
                if ((sample.pollutants.length > 0) || (sample.pollutants_eluate.length > 0)) {
                    sampleDetail.analysis = 1;
                }
                sampleDetail.concentrations = fillConcentrationCell(sample, pollutantDetails);
                sampleDetails.push(sampleDetail);
            }
            return sampleDetails;
        } else {
            return [];
        }
    }

    function calculateSampleMaxDepth(log) {
        if (Object.keys(log.samples).length === 0) {
            return null;
        } else {
            let sampleZmax = null;
            for (let samplesId in log.samples) {
                let sample = log.samples[samplesId];
                if (sample.z_max > sampleZmax || sampleZmax === null) {
                    sampleZmax = sample.z_max;
                }
            }
            return sampleZmax;
        }
    }
}


