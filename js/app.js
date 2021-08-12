var canShoot = true;


/*
iso             = {"0": "Auto","1":"100","2":"125","3":"160","4":"200","5":"250","6":"320","7":"400","8":"500","9":"640","10":"800","11":"1000","12":"1250","13":"1600","14":"2000","15":"2500","16":"3200","17":"4000","18":"5000","19":"6400","20":"8000","21":"10000","22":"12800"};
aperture        = {"4.5":null,"1":"5","2":"5.6","3":"6.3","4":"7.1","5":"8","6":"9","7":"10","8":"11","9":"13","10":"14","11":"16","12":"18","13":"20","14":"22"};
shutterspeed    = { "1":"25","2":"20","3":"15","4":"13","5":"10","6":"8","7":"6","8":"5","9":"4","10":"3.2","11":"2.5","12":"2","13":"1.6","14":"1.3","15":"1","16":"0.8","17":"0.6","18":"0.5","19":"0.4","20":"0.3","21":"1\/4","22":"1\/5","23":"1\/6","24":"1\/8","25":"1\/10","26":"1\/13","27":"1\/15","28":"1\/20","29":"1\/25","30":"1\/30","31":"1\/40","32":"1\/50","33":"1\/60","34":"1\/80","35":"1\/100","36":"1\/125","37":"1\/160","38":"1\/200","39":"1\/250","40":"1\/320","41":"1\/400","42":"1\/500","43":"1\/640","44":"1\/800","45":"1\/1000","46":"1\/1250","47":"1\/1600","48":"1\/2000","49":"1\/2500","50":"1\/3200","51":"1\/4000","52":"1\/5000","53":"1\/6400","54":"1\/8000"};
aeb             = {off":null,"1":"+\/-","2":"+\/-","3":"+\/-","4":"+\/-","5":"+\/-","6":"+\/-","7":"+\/-","8":"+\/-","9":"+\/-"}}},"status":"OK"}
*/



var goFS = document.getElementById("goFS");
goFS.addEventListener("click", function() {

    resetThumbnails();

    document.body.requestFullscreen();
    document.querySelector("#intro").style.display='none';
    document.querySelector("#app").style.display='block';

}, false);




cleanDebug = () => {
    debug = document.querySelector('#debug');
    debug.innerHTML = "";
    debug.scrollTop = debug.scrollHeight;
}

aggiornaDebug = (msg) => {

    debug = document.querySelector('#debug');

    debug.innerHTML += "<br>" + msg ;
    debug.scrollTop = debug.scrollHeight;

}


doAjax = ( obj = false) => {
    
    aggiornaDebug("*********************************");
    aggiornaDebug(  obj.action  );

    data = false;

    fetch("/ajax.php" , { method:"POST", body: JSON.stringify(obj) } ).then( response => response.json() ).then( data => {

        if( obj.fallback )
            window[obj.fallback](data);

        aggiornaDebug("-- fine");
        

	});
}


init = () => {
    data = doAjax( { "action" : "init", "fallback":"startApp" } );
}



startApp = (data) => {
    
    if( data.status == "OK" ){

        tgt = document.querySelector("#infoCamera");
        tgt.innerHTML = "";
        
        for ( i in data ){
            
            tgt.innerHTML += "<div>"+i+"</div><div>" + data[i] + "</div>";
        }
        
        document.querySelector("#riepilogoDatiFotocamera").innerHTML = tgt.innerHTML;

        goFS.style.display="block";

        //  lancio gli init per le varie funzioni
        //  doAjax({"action":"config", "fallback":"doConfig"});
    }
}


doConfig = ( data ) => {
    console.log( data );
}






doReset = (data) =>{

    
    console.log( "doReset...");
    aggiornaDebug(" -- reset effettuato");

    //  window.open("/gphoto2/reset.php", "secchio");

}


doListFile = () =>{

    aggiornaDebug("*********************************");
    aggiornaDebug("Carico la lista dei files sulla macchina");
    
    window.open("/gphoto2/listFiles.php", "secchio");


}




/*  SCATTO SINGOLO      */

doOpzioniScattoSingolo = (data) => {

}

scattoSingolo = () => {

    doAjax({"action":"scattoSingolo"});
}


/*  Bracketing  */

bracketing = (v = 6) => {

    doAjax({"action":"bracketing", "scelta" : v } );
}




callIntervallometro = ( data ) => {

    aggiornaDebug( "Scatto " + data.n + " di "  + frames );

    intervallometro(data.n);
    
}


intervallometro = (n) =>{

    interval    = document.querySelector('#interval').value;
    frames      = document.querySelector('#frames').value;

    if( n == 0 ){

        aggiornaDebug("*********************************");
        aggiornaDebug("INTERVALLOMETRO");
        aggiornaDebug("FOTO: "          + frames + " | INTERVALLO: "    + interval);
        
    }
    
    if( n == frames ){
        return;
    }
    
    p = n+1;

    doAjax({"action": "intervallometro", "fallback" : "callIntervallometro", "n" : n, "frames" : frames, "interval" : interval });


}

hdrSeq = [];


initHdr = () => {

    aggiornaDebug("disattivata....");
    return false;
    

    aggiornaDebug("*********************************");
    aggiornaDebug("HDR");
    
    window.open("/gphoto2/hdr.php?action=init", "secchio");
    
    aggiornaDebug("Init...");
}

doHdr = (n) =>{

    if( n == hdrSeq.length ){

        window.open("/gphoto2/hdr.php?action=reset&v=" + hdrSeq[3], "secchio");
        return false;
    }

    for( i = 0 ; i < hdrSeq.length ; i++){

        if( n == i ){

            p = n+1;

            aggiornaDebug("HDR: foto " + p + " di " + hdrSeq.length);
            aggiornaDebug("Shutter speed: : " + hdrSeq[n]);
            
            window.open("/gphoto2/hdr.php?action=shoot&n="+n+"&seq=" + JSON.stringify(hdrSeq), "secchio");
            break;
        }
    }



    return n;


    console.log(n );

    interval    = document.querySelector('#interval').value;
    frames      = document.querySelector('#frames').value;

    if( n == 0 ){

        aggiornaDebug("*********************************");
        aggiornaDebug("INTERVALLOMETRO");
    
        aggiornaDebug("FOTO: "          + frames + " | INTERVALLO: "    + interval);
        
    }
    
    
    if( n == frames ){
        aggiornaDebug( "--- FINE" );
        return;
        
    }
    

    p = n+1;
    
    aggiornaDebug( "Scatto " + p + " di "  + frames );


    window.open("/gphoto2/intervallometro.php?sleep=" + interval + "&frame=" + n, "secchio");

}




aggiornaThumbnails = () => {

    document.querySelector( '#elencoFile').innerHTML = "Aggiornamento...";
    doAjax({"action":"aggiornaThumbnails", "fallback" : "mostraAnteprime" } );
}

resetThumbnails = () => {
    
    document.querySelector( '#elencoFile').innerHTML = "Aggiornamento...";
    doAjax({"action":"resetThumbnails", "fallback" : "mostraAnteprime" } );
    
}

mostraAnteprime = (data) => {
    
    console.log( data );

    document.querySelector( '#elencoFile').innerHTML = "";
    
    html = "";

    for(i in data.files ){

        
        html +=  `
        <div class='schedaImg' onclick=_openImg('${data.files[i]}')>
            <img src="http://192.168.4.1/anteprime/${data.files[i]}" loading="lazy">
            
            <div class='nome'>
            ${data.files[i]}
            </div>
            
            <!--
            <div class='azioni'>
                cancella | invia whatsapp | scarica su dispositivo
            </div>

            -->
        
        </div>
        
        `;
        
    }
    
    document.querySelector( '#elencoFile').innerHTML = html;

}


openImg = (img ) => {
    window.open("apriImg.php?img=" + img);
}



console.clear();