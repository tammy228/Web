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
            "private_key": "-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCmxUtt0PuGqNva\nLJGT0dyP5gD02g9K7m/5b3zIssljciOX3XWeyc3eRIdy8969xzTziYR/KQEs9UFl\npY4vbCj48ZirB/+R1YPzNb7zbG6NkRVaq5A9fulk6IG3WNzsa25P7vluFu8cKCxh\n90uhfG4qjuVbxym0Nn7gPUJR+d29CbPEfW8hsm51VQEUYW8LOXkNqFLAGrm86MAr\n9M+rUoqh34EI5maIPSmIWXCyCP8Ln/fFq1jQfencT+sUzNFkcq2Wbywfz+Jc15wh\nyrNRauyIvV66d/o+fMXUzZKqLMOa27JvhRqTpVETh4Gyvb0x1pubaQqlS1HViTKB\nO33Y4KlVAgMBAAECggEACwOkFq2jCbgPRRqFHCvTvavldkZh6i394Th2wlPkhG0z\nt+zyvrj59WsLPbv+La8sFRreUFOnSzkrcjN774UyM4wpQWp20O02w2eno9tjyx/A\n4aB2bJ6m0COhpY1N504KbBG5ZtxG5dIqvubz7MdoszuFdF6ep334j9txN4l2wUGj\nzxAnpLCAWd+kTdgQyV9/k9OhOEX7lQL+aTuXgy6E3pYX1KWZzo5Cq/PO1jNUsEJd\nzXaw+ClCrC8I+qIXdHLNSu/ttpV2r3QOlEXFvTT7g1ZSsQdP9S6ZQIiJGr9PzrbG\nXlZOy6lbPMhkO98QyJAre9o4lYZSnF3MhhEt0wQjRQKBgQDVymGjHxjs43hYTDSn\nkCO1B1OBlBuZpHbTCdTbSesg4PZYWiw+1UnUoX3bXfUC7LktmcDFhPE4kf0FegMo\n14m8jyqp5VcFtc1Xh2MfcIRl1Ddf/Ci4xgcIqFh4TaOClUKXSYCe8nu4ubTaInE7\na6UMBMROB5d4VBwFh3jfGSfzXwKBgQDHsmMpvqcqkJS1Y/sZcySJBJ+iCfTXuMr+\nXt3igyd+ba0P/rSRj0Mk+0spzVDVArSrn44qPgf7Caz8LaQYlDB2nQKVH+LBWiCQ\nVDK9mMeSBb1LS77D0SUPGgRGgaeaIeTn0tbQpIZXOUuV8UyHADzLssZOL/RPUkiD\nHl50T4FzywKBgQCFplFHcodYgBOZz3oTTo6j+wJ/PSHL+P63i6vfsuQk173pGeYT\nGa9gF9zgKGqk+2wAT+AtGqDaJpmwtMgI3kWi9TVMpKy0SyUllOOooeSC7Bn6DV8/\ns7xt8x/rhU20sq7AeRjJRPmHT8D5pRJ4fSDe1JQL278bYClsY3Zysf1BrQKBgGSp\nE/k35DW9eWhRxIHYm4MBtKHOWP/gY1qDYV9Lcz30dPcKEeUJjPP4Q7QHjYZB/eZA\n4D2E1SsCfpMDQqMtF5zCmkmnL/r8vktiVc1iVL7Ta86nmLpGE2MgXyVXN6+nBCaj\nDatQM3OGKwajAktOoFahf/pri7/sHycQyNXiOcOvAoGAU6dnP50ITdMwidE44QSC\nZEIuSmRVdPEz6roELyHwDC1MMOrriEbHKgt7hYHgXaC+HjqzlQ6xGVpTgJAEUja/\nXbqTykLnaYknFHtUV52BM333tSQVL2ylw93RNKYkJMt+GS2RubQuO48HWj0Kztyj\nNq/5m/6B35gO1cGpUNK4HWs=\n-----END PRIVATE KEY-----\n",
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