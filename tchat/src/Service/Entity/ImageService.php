<?php


namespace App\Service\Entity;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Google\Cloud\Storage\StorageClient;

class ImageService
{
    private $privateKeyFileContent = '{
            "type": "service_account",
            "project_id": "self-project-symfony",
            "private_key_id": "a451ca5e58894a1b4277a55063b1e31d7835ce93",
            "private_key": ""
            "client_email": "chatroom@self-project-symfony.iam.gserviceaccount.com",
            "client_id": "116895930948065870369",
            "auth_uri": "https://accounts.google.com/o/oauth2/auth",
            "token_uri": "https://oauth2.googleapis.com/token",
            "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
            "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/chatroom%40self-project-symfony.iam.gserviceaccount.com"
        }';
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function gcsUpload($imageName, $gsName)
    {

        // connect to Google Cloud Storage using private key as authentication
        $storage = new StorageClient(['keyFile' => json_decode($this->privateKeyFileContent, true)]);


        // set which bucket to work in
        $bucketName = 'self-project-symfony.appspot.com';
        $bucket = $storage->bucket($bucketName);

        // get local file for upload testing
        $fileContent = file_get_contents($gsName);

        // NOTE: if 'folder' or 'tree' is not exist then it will be automatically created !
        $cloudPath = hash('sha512',uniqid());

        $bucket->upload($fileContent, ['name' => $cloudPath]);

        $duration = 100;
        $object = $bucket->object($cloudPath);
        $url = $object->signedUrl(new \DateTime('+ ' . $duration . ' hours'));

        return $url;
    }
}
