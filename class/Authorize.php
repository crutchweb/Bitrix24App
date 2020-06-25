<?php
namespace Bitrix24App;

require_once 'vendor/autoload.php';

use \Bitrix24Authorization\Bitrix24Authorization;

/**
 * Class Authorize
 * @package Bitrix24App
 * @param array $config - const CONFIG
 */
class Authorize
{
    /**
     * Scope CONFIG
     *
     * @var array
     */
    protected $config;

    /**
     * Authorize constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        return $this->config = $config;
    }

    /**
     * Returned auth scope
     *
     * @return Bitrix24Authorization
     */
    public function getAuth() :Bitrix24Authorization
    {
        $b24auth = new Bitrix24Authorization();
        $b24auth->setApplicationId($this->config['appID']);
        $b24auth->setApplicationSecret($this->config['secretKey']);
        $b24auth->setApplicationScope($this->config['scopeInst']);
        $b24auth->setBitrix24Domain($this->config['b24Domain']);
        $b24auth->setBitrix24Login($this->config['b24Login']);
        $b24auth->setBitrix24Password($this->config['b24Pass']);

        return $b24auth;
    }
}