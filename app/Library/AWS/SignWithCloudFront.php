<?php

namespace App\Library\AWS;

use App\Http\Controllers\Controller;
use Aws\CloudFront\CloudFrontClient;
use Illuminate\Support\Facades\Storage;

class SignWithCloudFront extends Controller {

    /**
    * @param string $fileName El nombre del fichero tal y como se guarda en la base de datos
    * @param string $type El tipo de fichero (avatar, image, video...) definido en el fichero de configuración config/aws.php
    * @param int $expires Número que indica, en minutos, el tiempo de vida del enlace firmado a generar
    *
    * @return Object bool Success, string Message indicando el motivo del error (si hubiera), string Link con el enlace firmado o null si no se hubiera podido generar
    */
    
    static public function sign ( $fileName, $type, $expires ) {

        $response = (object) [
            "Success" => false,
            "Message" => "Error",
            "Link" => null
        ];
        
        // Creamos el path con la URL
        $path = self::constructFilePath($fileName, $type);

        // Validamos los datos que necesitamos para operar con esto
        if ( is_null($path) ) $response->Message = "Tipo de archivo inválido.";

        elseif ( !is_numeric($expires) ) $response->Message = "Se debe indicar un tiempo de expiración en minutos.";

        elseif ( !Storage::disk("aws")->exists(config("app.AWS_CLOUDFRONT_PK")) ) $response->Message = "No se ha podido encontrar la clave privada.";

        elseif ( is_null(config("app.AWS_CLOUDFRONT_KEY")) || config("app.AWS_CLOUDFRONT_KEY") == "" ) $response->Message = "No se ha encontrado el identificador del par de claves.";

        elseif ( is_null(config("app.AWS_CLOUDFRONT_REGION")) || config("app.AWS_CLOUDFRONT_REGION") == "" ) $response->Message = "No se ha podido encontrar la región.";

        elseif ( is_null(config("app.AWS_CLOUDFRONT_DOMAIN")) || config("app.AWS_CLOUDFRONT_DOMAIN") == "" ) $response->Message = "No se ha podido encontrar el dominio.";

        else {

            $signedUrl = self::executeSign($path, ($expires * 60));

            //echo $signedUrl;

            if ( !$signedUrl ) $response->Message = "Falló la generación del enlace firmado.";

            else {
                
                $response->Success = true;
                $response->Link = $signedUrl;

            }

        }

        return $response;

    }

    static private function constructFilePath($fileName, $type) {

        if ( is_null(config("aws.$type")) ) return null;

        return is_null(config("aws.$type")) ? null : config("app.AWS_CLOUDFRONT_DOMAIN") . config("aws.$type") . $fileName;

    }

    static private function executeSign ( $path, $expires ) {

        $cloudFrontClient = new CloudFrontClient([
            'version' => 'latest',
            'region' => config("app.AWS_CLOUDFRONT_REGION")
        ]);

        $result = false;

        $params = [
            'url' => $path,
            'expires' => time() + $expires,
            'private_key' => Storage::disk("aws")->get(config("app.AWS_CLOUDFRONT_PK")),
            'key_pair_id' => config("app.AWS_CLOUDFRONT_KEY")
        ];

        
        try {
            
            $result = $cloudFrontClient->getSignedUrl($params);
            
        }
        
        catch (\Throwable $th) {
            
            $result = false;

        }

        return $result;

    }

}