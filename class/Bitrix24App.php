<?php
namespace Bitrix24App;

require_once 'repository/Contact.php';
require_once 'repository/Lead.php';

use Bitrix24\Bitrix24;
use Bitrix24Authorization\Bitrix24Authorization;
use Bitrix24App\Repository\Contact;
use Bitrix24App\Repository\Lead;

/**
 * Class Bitrix24App
 * @package Bitrix24App
 * @param object $authorization - Bitrix24Authorization\Bitrix24Authorization
 * @param object $b24lib - Bitrix24\Bitrix24
 * @param object $auth_scope
 */
class Bitrix24App
{
    /**
     * @var Bitrix24Authorization
     */
    protected $authorization;

    /**
     * @var Bitrix24
     */
    protected $b24lib;

    /**
     * @var \Bitrix24Authorization\Bitrix24|object
     */
    protected $auth_scope;

    /**
     * @var array
     */
    protected $contact;

    /**
     * @var \Bitrix24App\Repository\Lead
     */
    protected $lead;

    /**
     * Bitrix24App constructor.
     * @param Bitrix24Authorization $authorization
     * @param Bitrix24 $b24lib
     */
    public function __construct(Bitrix24Authorization $authorization, Bitrix24 $b24lib)
    {
        $this->authorization = $authorization;
        $this->b24lib = $b24lib;
        $this->auth_scope = $this->authorization->initialize($this->b24lib);
    }

    /**
     * Get contact data from bitrix24
     *
     * @param string $email
     * @param string $phone
     * @return bool
     * @throws \Bitrix24\Bitrix24Exception
     */
    public function findContact(string $email, string $phone) :bool
    {
        $contact_obj = new Contact($this->auth_scope);

        $contact_by_email = $contact_obj->getContactWithEmail($email);

        if ($this->checkResult($contact_by_email['result'])) {
            $this->contact = $contact_by_email;

            return true;
        }

        $contact_by_phone = $contact_obj->getContactWithPhone($phone);

        if ($this->checkResult($contact_by_phone['result'])) {
            $this->contact = $contact_by_phone;

            return true;
        }

        return false;
    }

    /**
     * Find lead by id
     *
     * @param int $lead_id
     * @return bool
     * @throws \Exception
     */
    public function findLead(int $lead_id) :bool
    {
        $lead_obj = new Lead($this->auth_scope, $lead_id);
        $lead_by_id = $lead_obj->findLeadById();

        if ($this->checkResult($lead_by_id)) {
            $this->lead = $lead_obj;

            return true;
        }

        return false;
    }

    /**
     * Returned contact data
     *
     * @return array
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Returned Lead object
     *
     * @return Lead
     */
    public function getLead()
    {
        return $this->lead;
    }

    /**
     * Checking bitrix24 response from contact data
     *
     * @param array $result
     * @return bool
     */
    protected function checkResult(array $result) :bool
    {
        if(count($result) === 0) {
            return false;
        }

        return true;
    }
}