<?php
namespace Bitrix24App\Repository;

use Bitrix24\Bitrix24;
use Exception;

/**
 * Class Lead
 * @package Bitrix24App\Repository
 * @param object $auth_scope
 * @param int $lead_id
 * @param object $lead_obj
 * @param array $lead_scope
 */
class Lead
{
    /**
     * @var Bitrix24
     */
    protected $auth_scope;

    /**
     * @var int
     */
    protected $lead_id;

    /**
     * @var \Bitrix24\CRM\Lead
     */
    protected $lead_obj;

    /**
     * @var array
     */
    protected $lead_scope;

    /**
     * Lead constructor.
     * @param Bitrix24 $auth_scope
     * @param int $lead_id
     */
    public function __construct(Bitrix24 $auth_scope, int $lead_id)
    {
        $this->auth_scope = $auth_scope;
        $this->lead_id = $lead_id;
        $this->lead_obj = new \Bitrix24\CRM\Lead($this->auth_scope);
    }

    /**
     * * Find lead by id
     *
     * @return array
     * @throws Exception
     */
    public function findLeadById() :array
    {
        try {
            return $this->lead_scope = $this->lead_obj->get($this->lead_id);
        } catch (Exception $e) {
            throw new \Exception("LEAD NOT FOUND!!!");
        }
    }

    /**
     * @return array
     */
    public function getLeadScope() :array
    {
        return $this->lead_scope;
    }

    /**
     * Update lead by new data
     *
     * @param array $criteria
     * @return bool
     */
    public function saveLeadByCriteria(array $criteria) :bool
    {
        if ($this->lead_scope) {
            $this->lead_obj->update($this->lead_id, $criteria);

            return true;
        }

        return false;
    }
}