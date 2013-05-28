<?php

namespace Ingewikkeld\HPCloudBundle\Service;

use \HPCloud\Services as HPService;
use \HPCloud\Storage as HPStorage;

class HPCloudService
{
    /**
     * @var \HPCloud\Services\IdentityServices
     */
    protected $idService;
    /**
     * @var string
     */
    protected $idToken;
    /**
     * @var null|HPStorage\ObjectStorage
     */
    protected $store = null;

    /**
     * constructor
     *
     * @param string $account
     * @param string $password
     * @param string $endpoint
     * @param string $project
     */
    public function __construct($account, $password, $endpoint, $project)
    {
        $this->idService = $this->authenticate($account, $password, $endpoint, $project);
    }

    /**
     * Get the store object
     *
     * @return HPStorage\ObjectStorage
     */
    public function getStore()
    {
        if (is_null($this->store)) {
            $catalog = $this->idService->serviceCatalog();
            $this->store = HPStorage\ObjectStorage::newFromServiceCatalog($catalog, $this->idToken, 'NL');
        }

        return $this->store;
    }

    /**
     * Create a localObject from a local file
     *
     * @param string $currentPath
     * @param string $filename
     * @return HPStorage\ObjectStorage\Object
     */
    public function getLocalObjectFromFile($currentPath, $filename)
    {
        $file = new \SplFileInfo($currentPath);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $localObject = new HPStorage\ObjectStorage\Object(md5(time().$filename).'.'.$extension);
        $localObject->setContent(file_get_contents($file->getRealPath()));

        return $localObject;
    }

    /**
     * upload the specified object to the specified container
     *
     * @param string $containerName
     * @param HPStorage\ObjectStorage\Object $object
     * @return HPStorage\ObjectStorage\RemoteObject
     */
    public function upload($containerName, HPStorage\ObjectStorage\Object $object)
    {
        $this->getStore()->container($containerName)->save($object);

        return $this->getStore()->container($containerName)->proxyObject($object->name());
    }

    /**
     * Authenticate with the server
     *
     * @param string $account
     * @param string $password
     * @param string $endpoint
     * @param string $project
     * @return HPService\IdentityServices
     */
    private function authenticate($account, $password, $endpoint, $project)
    {
        $idService = new HPService\IdentityServices($endpoint);
        $this->idToken = $idService->authenticateAsUser($account, $password, $project);

        return $idService;
    }
}
