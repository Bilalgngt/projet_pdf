function generateHeader(site){
    let result = {
        company: document.getElementById('Company').value,
        siteName: site.name,
        headers: [],
        scaleDetails: [],
        sampleDetails: [],
        pollutantDetails: []
    }
    console.log(site);
    let checked = 0;
    let inputs = document.querySelectorAll('.polluant');
    for (let i = 0; i < inputs.length; i++) {
        if(inputs[i].checked){
            checked++;
            result.pollutantDetails.push({id_pollutant_name: inputs[i].value, custom: "0"});
        }
    }
    for(let logId in site.logs){
        let log = site.logs[logId];
        let depths = {
            waterMaxDepth:null,
            sampleMaxDepth:null,
            strainerMaxDepth:log.about.strainer_zMax,
            maxDepth:null
        };

        //declaration de l'objet header
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
            waterAltitude: ''
        };
        //declaration de l'objet coordonnées en fnction du système
        if (site.gpsPosition.system !== 'DD') {
            header.longitude = 'x(' + site.gpsPosition.system + '): ' + log.about.longitude;
            header.latitude = 'y(' + site.gpsPosition.system + '): ' + log.about.latitude;
        } else {
            header.longitude = 'Longitude: ' + log.about.longitude;
            header.latitude = 'Latitude: ' + log.about.latitude;
        }
        
        //declaration de l'altitude de la nappe si existante sinon null
        if(Object.keys(log.waterTables).length > 0)
        {
            let waterZMin = null;
            let waterZMax = null;
            for(let waterId in log.waterTables){
                let water = log.waterTables[waterId];
                if(water.z_min < waterZMin || waterZMin === null){
                    waterZMin = water.z_min;
                }
                if(water.z_max > waterZMax || waterZMax === null){
                    waterZMax = water.z_max;
                }
            }
            header.waterAltitude = 'Altitude nappe : '+(log.about.altitude-waterZMin).toFixed(3)+'m NGF';
            depths.waterMaxDepth = waterZMax;
        }
        if(Object.keys(log.samples).length > 0){
            let sampleZmax = null;
            let geologies = [];
            for(let samplesId in log.samples){
                let sample = log.samples[samplesId];
                if(sample.z_max > sampleZmax || sampleZmax === null){
                   sampleZmax = sample.z_max;
                }
                let sampleDetail = {
                    sampleId: sample.id_geology_name,
                    sampleCustom: sample.geology_custom,
                    start: sample.z_min,
                    end: sample.z_max,
                    analysis: 0,
                    comment: sample.comment,
                    concentrations: [],
                    pollutantToDraw: checked,
                }
                if((sample.pollutants.length > 0) || (sample.pollutants_eluate.length > 0) ){
                    sampleDetail.analysis = 1;
                }
                let concentrations = new Array(result.pollutantDetails.length).fill(null);
                result.pollutantDetails.forEach((pollutantDetail, i)=>{
                    for(let pollutant of sample.pollutants){
                        if(isSamePollutant(pollutantDetail, pollutant)){
                            if(pollutant.under_detection === "1"){
                                concentrations[i] = "< L.D."
                            }
                            else{
                                concentrations[i] = pollutant.concentration;
                            }
                        }
                    }
                })
                sampleDetail.concentrations = concentrations;
                /*for (let i = 0; i < inputs.length; i++) {
                    let pollutantDetail = {
                        id: inputs[i].value,
                        custom: "0",
                        concentration: null
                    }
                    for(let j = 0; j < sample.pollutants.length; j++){ 
                        
                        if(inputs[i].checked && inputs[i].value === sample.pollutants[j].id_pollutant_name){
                            pollutantDetail.id = sample.pollutants[j].id_pollutant_name
                            pollutantDetail.custom = sample.pollutants[j].custom;
                            pollutantDetail.concentration = sample.pollutants[j].concentration;
                        }
                    }
                    sampleDetail.pollutantDetails.push(pollutantDetail);
                }*/
                geologies.push(sampleDetail);
            }
            depths.sampleMaxDepth = sampleZmax;
            result.sampleDetails.push(geologies);
        }
        result.headers.push(header);
        depths.maxDepth = Math.max(...Object.values(depths));
        result.scaleDetails.push(depths);
    }
    console.log(result);
    return result;
}


function isSamePollutant(pollutant1, pollutant2){
    return pollutant1.id_pollutant_name === pollutant2.id_pollutant_name &&
            pollutant1.custom === pollutant2.custom;
}