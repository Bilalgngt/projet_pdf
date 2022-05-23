function generateHeader(site){
    let result = {
        company: document.getElementById('Company').value,
        siteName: site.name,
        headers: [],

    }
    let case_x = '';
    let case_y = '';
    console.log(site);
    for(let logId in site.logs){
        let log = site.logs[logId];
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
            for(let waterId in log.waterTables){
                let water = log.waterTables[waterId];
                if(water.z_min < waterZMin || waterZMin === null){
                    waterZMin = water.z_min;
                }
            }
            header.waterAltitude = 'Altitude nappe : '+(log.about.altitude-waterZMin).toFixed(3)+'m NGF';
        }
        result.headers.push(header);
    }
    console.log(result);
    return result;
}
