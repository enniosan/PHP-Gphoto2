<?php
$entityBody = json_decode( file_get_contents('php://input') ) ;


switch( $entityBody -> action ){
    
    case "init":

            $out['Fotocamera']          = trim( str_ireplace("current:", "", explode( "\n" , shell_exec("gphoto2 --get-config cameramodel") )[3] ) );

            if( $out['Fotocamera'] ){

                
                $out['Lente']               = trim( str_ireplace("current:", "", explode( "\n" , shell_exec("gphoto2 --get-config lensname") )[3] ) );
                $out['Numero Scatti']       = trim( str_ireplace("current:", "", explode( "\n" , shell_exec("gphoto2 --get-config shuttercounter") )[3] ) );
                $out['Scatti disponibili']  = trim( str_ireplace("current:", "", explode( "\n" , shell_exec("gphoto2 --get-config availableshots") )[3] ) );
                $out['Proprietario']        = trim( str_ireplace("current:", "", explode( "\n" , shell_exec("gphoto2 --get-config ownername") )[3] ) );
                $out['status'] = "OK";    
            }
            else{

                $out['status'] = "KO";    
            }
            
    break;


    /*  congigurazioni */

    case "config":

        $out    = [];
        $opt    = [];

        
        $opt["iso"]             = explode( "\n" , shell_exec( "gphoto2 --get-config /main/imgsettings/iso" ));
        $opt["aperture"]        = explode( "\n" , shell_exec( "gphoto2 --get-config /main/capturesettings/aperture" ));
        $opt["shutterspeed"]    = explode( "\n" , shell_exec( "gphoto2 --get-config /main/capturesettings/shutterspeed" ));
        $opt["aeb"]             = explode( "\n" , shell_exec( "gphoto2 --get-config /main/capturesettings/aeb"  ));

        $config = [];

        foreach( $opt as $name => $option ){

            unset( $option[0],$option[1],$option[2]);
            
            $current = trim( str_replace( "Current:" , "" , $option[3]) );
            
            $config[ $name ] = ["current" => $current, "opzioni" => [] ];
            
            foreach( $option as $k => $v ){
                
                $v = explode( " " , $v );

                if( $v[1]){

                    $config[ $name ]['opzioni' ][ $v[1] ] = $v[2];

                }

            }
        }
        

        $out['config'] = $config;
        $out['status']  = "OK";
        


    break;





    case "reset":
          
        $a  = shell_exec( "gphoto2 --reset" );
        $out['status']  = "OK";
        
    break;


    case "resetThumbnails":

        $a  = shell_exec( "rm /var/www/html/anteprime/*");
        $a = shell_exec( "cd /var/www/html/anteprime ; gphoto2 --get-all-thumbnails --skip-existing");
        usleep(300);
        $out['x']  = shell_exec( "ls /var/www/html/anteprime/*.CR2");
        
        $dir = scandir("/var/www/html/anteprime");
        unset( $dir[0], $dir[1] );

        
        $out['files'] = array_reverse( $dir );

    break;



    case "scattoSingolo":

            $a = shell_exec( "gphoto2 --set-config capturetarget=1 --trigger-capture -q --stdout" );

            $out['status']  = "OK";
            $out['debug']   = $a;

    break;

    case "bracketing":

            shell_exec( "gphoto2 --set-config aeb=6;" );
            $a = shell_exec( "gphoto2 --capture-image -q --stdout " );
            $a = shell_exec( "gphoto2 --capture-image -q --stdout " );
            $a = shell_exec( "gphoto2 --capture-image -q --stdout " );
            shell_exec( "gphoto2 --set-config aeb=off;" );

            $out['status']  = "OK";
            $out['debug']   = $a;

    break;


    case "intervallometro":

        $s = trim( str_replace( "Current: " , "" , explode( "\n", shell_exec("gphoto2 --get-config shutterspeed"))[3] ) );

        if( strpos($s,  "/" ) !== false ){
            $s = 1000 / explode("/", $s)[1];
        }else{
            $s *= 1000;
        }

        
        $a = shell_exec( "gphoto2 --set-config capturetarget=1 --trigger-capture --stdout -q" );

        if( ! is_null ($a ) ){

            $out['n']       = $entityBody -> n;
            $out['status']  = "KO";
            
        }else{
            
            $out['n']       = $entityBody -> n + 1;
            $out['status']  = "OK";
            
        }
        
        $out['a']  = $a;
        $out['debug']   = $entityBody;

        usleep( $s );   

    break;



    case "aggiornaThumbnails":

        //  Get all thumbnails from folder.
        $a = shell_exec( "cd /var/www/html/anteprime ; gphoto2 --get-all-thumbnails --skip-existing");
        usleep(300);
        shell_exec( "rm /var/www/html/anteprime/*.CR2" );
        

        $dir = scandir("/var/www/html/anteprime");
        unset( $dir[0], $dir[1] );

        
        $out['files'] = array_reverse( $dir );


    break;


    


}

echo json_encode( $out );die;