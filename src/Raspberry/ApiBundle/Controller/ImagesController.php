<?php

namespace Raspberry\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;

class ImagesController extends Controller
{
    private $_redis;
    
    public function listAction($_format)
    {
        // Get date param
        $request = Request::createFromGlobals();
        $date = new \DateTime($request->query->get('date'));
        
        // Init array to return
        $files = array();
        
        // Build files list (must always returns a list of base64 encoded data for each found image)
        
        // First, we get list of images from Redis
        $keys = $this->_getRedis()->keys($date->format('Y') . '-' . $date->format('m') . '-' . $date->format('d') . '*.jpg');
        foreach ($keys as $key) {
        	$base64 = $this->_getRedis()->get($key);
            $files[] = array('filename' => $key, 'content' => $base64);
        }
        
        // If there is no image from Redis, try to get it from server file system
        if (count($files) === 0) {
            $finder = new Finder();
            $finder->files()
                ->in(realpath(__DIR__ . '/../../../../web/datas'))
                ->name('/^' . $date->format('Y') . '\-' . $date->format('m') . '\-' . $date->format('d') . '.*\.jpg$/')
                ->sortByName();
            foreach ($finder as $file) {
                $filepath = $file->getPathname();
                $base64 = $this->_getBase64FromImage($filepath);
                $files[] = array('filename' => $file->getFilename(), 'content' => $base64);
            }   
        }
        
        // Send response
        $response = new JsonResponse();
        $response->setData($files);
        return $response;
    }
    
    public function uploadAction() 
    {
        $uploaded = false;
        
        // Get base64 encoded data
        $data = '';
        $request = Request::createFromGlobals();
        $base64 = $request->request->get('base64');
        
        if ($base64) {
            
            // Build filename
            $now = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $filename = sprintf('%s-%s-%s %s-%s-%s.jpg', 
                $now->format('Y'), 
                $now->format('m'),
                $now->format('d'),
                $now->format('H'),
                $now->format('i'),
                $now->format('s'));
            
            // Create image to store to file system
            list($type, $data) = explode(';', $base64);
            list($encode, $data) = explode(',', $data);
            $data = base64_decode($data);
            
            // Create image resource
            $image = imagecreatefromstring($data);
            if ($image) {
                
                // Add date on image
                $textcolor = imagecolorallocate($image, 255, 255, 255);
                imagestring($image, 4, 5, 5, $now->format('Y-m-d H:i:s'), $textcolor);
                
                // Send data to image file on the server
                $filepath = realpath(__DIR__ . '/../../../../web/datas') . DIRECTORY_SEPARATOR . $filename;
                if (imagejpeg($image, $filepath)) {
                    imagedestroy($image);
                    
                    // Send base64 encoded string of image data to Redis
                    $encoded = $this->_getBase64FromImage($filepath);
                    $this->_getRedis()->set($filename, $encoded);
                    
                    // Says that the upload is ended
                    $uploaded = true;
                }
            }
        }
        
        // Send response
        $response = new JsonResponse();
        $response->setData($uploaded);
        return $response;
    }
    
    public function deleteAction() 
    {
        $deleted = false;
        
        // Get filename param
        $request = Request::createFromGlobals();
        $filename = $request->request->get('filename');
        
        // Delete the file from the file system
        @unlink(realpath(__DIR__ . '/../../../../web/datas') . DIRECTORY_SEPARATOR . $filename);

        // Then, delete the entry from Redis
        $this->_getRedis()->del($filename);

        // Says that the deletion is ended
        $deleted = true;
        
        // Send response
        $response = new JsonResponse();
        $response->setData($deleted);
        return $response;
    }
    
    private function _getRedis() 
    {
        if ($this->_redis) return $this->_redis;
        $this->_redis = $this->container->get('snc_redis.default');
        return $this->_redis;
    }
    
    private function _getBase64FromImage($filepath) 
    {
        $type = pathinfo($filepath, PATHINFO_EXTENSION);
        $data = file_get_contents($filepath);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
}