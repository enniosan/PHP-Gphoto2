<?php

?>
<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PHP GPHOTO2</title>

  
    <link rel="stylesheet" type="text/css" href="plugins/swiper/swiper.min.css">
    <script src="/plugins/swiper/swiper.min.js"></script>
    <link rel="icon" type="image/png" href="/icon.png" />

    <link rel="stylesheet" type="text/css" href="css/style.css">

<style>
    
</style>


</head>

<body>
    <div id='intro'>

        <h1>GPHOTO APP</h1>

        <div id='infoCamera'>
            cariamento in corso...
        </div>


        <button id="goFS">Accedi</button>
    </div>    

    <div id='app' > 

        <div class="swiper-container">
            <div class="swiper-wrapper">
            
                <div class="swiper-slide funzione">

                    <div class='titolo'>
                        Scatto singolo
                    </div>

                    <div class='opzioni' id='opzioniScattoSingolo'>

                    </div>

                    <div class='azioni'>
                        
                        <button onclick=scattoSingolo() >Scatta</button>
                    </div>

                </div>


                <div class="swiper-slide funzione">
                
                    <div class='titolo'>
                        Intervallometro
                    </div>
                
                    <div class='opzioni'>

                        <div class='optionBox'>

                            
                            <!-- <h3>Intervallo</h3> -->
                            <h3>Numero Foto</h3>
                        
                        
                        
                            <input type='hidden' min=0 max=100 value=0 id='interval'>
                            <input type='number' min=0 max=100 value=3 id='frames'>
                        
                            

                        </div>

                    </div>


                    <div class='azioni'>
                        
                        <button onclick=intervallometro(0);>Scatta</button>
                        
                    </div>

                </div>



                <div class="swiper-slide funzione">
                
                    <div class='titolo'>
                        BRACKETING
                    </div>
                
                    <div class='opzioni' id='opzioniBracketing'>
                        
                    </div>


                    <div class='azioni'>
                        
                        <button onclick=bracketing(6)>Scatta</button>
                        
                    </div>

                </div>



                <div class="swiper-slide funzione">
                
                    <div class='titolo'>
                        HDR
                    </div>
                
                    <div class='opzioni' id='opzioniHDR'>
                        
                    </div>


                    <div class='azioni'>
                        
                        <button onclick=initHdr()>Scatta</button>
                        
                    </div>

                </div>

                


                <div class="swiper-slide funzione">
                
                    <div class='titolo'>
                        ELENCO FILE
                    </div>
                
                    <div class='elencoFile' id='elencoFile'>
                        
                    </div>


                </div>
                
                
                
                <div class="swiper-slide funzione">
                
                    <div class='titolo'>
                        EXTRA
                    </div>
                
                    <div class='opzioni'>

                    <div id='riepilogoDatiFotocamera'>
                        
                    </div>

                    <button onclick=doAjax({"action":"reset","fallback":"doReset"}) >RESET</button> <br>
                    <button onclick=aggiornaThumbnails() >AGGIORNA FILES</button>
                        
                        
                    </div>


                    <div class='azioni'>
                
                    </div>

                </div>

                
                
            </div>
        </div>

        <div id='boxDebug' style='position:relative'>
            <div id='debug'></div>

            <div onclick='cleanDebug()' style='position:absolute; right:0px; top:0px; background-color:#A00; color: #FFF; padding:1rem; cursor: pointer'>
                X
            </div>

        </div>
        

    </div>
    
    <iframe name='secchio' id='secchio'></iframe>
</body>




<script src="/js/app.js"></script>
<script>
    const swiper = new Swiper('.swiper-container', {    loop: true, });
    init();
</script>

</html>