function generateHeader(site){
    let result = {
        company: document.getElementById('Company').value,
        siteName: site.name,
        headers: [],
        body: []
    }
    console.log(site);
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
            for(let samplesId in log.samples){
                let sample = log.samples[samplesId];
                if(sample.z_max > sampleZmax || sampleZmax === null){
                   sampleZmax = sample.z_max;
                }
            }
            depths.sampleMaxDepth = sampleZmax;
        }
        result.headers.push(header);
        depths.maxDepth = Math.max(...Object.values(depths));
        result.body.push(depths);

    }
    console.log(result.body);
    return result;
}
