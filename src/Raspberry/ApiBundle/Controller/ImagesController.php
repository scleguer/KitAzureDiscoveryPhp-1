<?php

namespace Raspberry\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Finder\Finder;

class ImagesController extends Controller
{
    public function listAction($_format)
    {
        // Get date param
        $request = Request::createFromGlobals();
        $date = new \DateTime($request->query->get('date'));
        
        // Build files list
        $files = array();
        $finder = new Finder();
        $finder->files()
            ->in(realpath(__DIR__ . '/../../../../web/datas'))
            ->name('/^' . $date->format('Y') . '\-' . $date->format('m') . '\-' . $date->format('d') . '.*\.jpg$/')
            ->sortByName();
        foreach ($finder as $file) {
            $files[] = $file->getFilename();
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
            list($type, $data) = explode(';', $base64);
            list($encode, $data) = explode(',', $data);
            $data = base64_decode($data);
            
            // Create image resource
            $image = imagecreatefromstring($data);
            if ($image) {
                // Build filename
                $now = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
                $filename = sprintf('%s-%s-%s %s-%s-%s.jpg', 
                    $now->format('Y'), 
                    $now->format('m'),
                    $now->format('d'),
                    $now->format('H'),
                    $now->format('i'),
                    $now->format('s'));
                
                // Add date on image
                $textcolor = imagecolorallocate($image, 255, 255, 255);
                imagestring($image, 4, 5, 5, $now->format('Y-m-d H:i:s'), $textcolor);
                
                // Send data to image file on the server
                if (imagejpeg($image, realpath(__DIR__ . '/../../../../web/datas') . DIRECTORY_SEPARATOR . $filename)) {
                    imagedestroy($image);
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
        
        // Delete the file
        if (unlink(realpath(__DIR__ . '/../../../../web/datas') . DIRECTORY_SEPARATOR . $filename)) {
            $deleted = true;
        }
        
        // Send response
        $response = new JsonResponse();
        $response->setData($deleted);
        return $response;
    }
}