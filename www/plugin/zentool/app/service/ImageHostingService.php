<?php
namespace App\service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class ImageHostingService
{
    public static function downloadFromRemote(string $remoteUrl, string $localPath)
    {
        try {

            $client = new Client();
            $name   = basename($remoteUrl);
            $file_path = fopen($localPath,'w');
            $response = $client->get($remoteUrl, ['save_to' => $file_path]);
            @chmod($localPath, TB_FILE_PERMISSION);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileType = finfo_file($finfo, $localPath);

            return ['response_code'=>$response->getStatusCode(), 'name' => $name, 'tmp_name' => $localPath, 'file_type' => $fileType];
        } catch (ClientException $e) {
            return ['response_code'=>'404', 'message'=>$e->getMessage()];
        } catch (RequestException $e) {
            return ['response_code'=>'404', 'message'=>$e->getMessage()];
        } catch ( Exception $e ) {
            return ['response_code'=>'404', 'message'=>$e->getMessage()];
        }
    }

}
