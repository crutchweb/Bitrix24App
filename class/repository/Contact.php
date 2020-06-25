<?php
namespace Bitrix24App\Repository;

use Bitrix24\Bitrix24;

/**
 * Class Contact
 * @package Bitrix24App\Repository
 * @param object $auth_scope
 */
class Contact
{
    protected $auth_scope;

    public function __construct(Bitrix24 $auth_scope)
    {
        $this->auth_scope = $auth_scope;
    }

    /**
     * Find contact by phone number
     *
     * @param string $phone
     * @return array
     * @throws \Bitrix24\Bitrix24Exception
     */
    public function getContactWithPhone(string $phone) :array
    {
        return $this->findContactByCriteria(['PHONE' => $this->phoneNormalize($phone)]);
    }

    /**
     * Find contact by email
     *
     * @param string $email
     * @return array
     * @throws \Bitrix24\Bitrix24Exception
     */
    public function getContactWithEmail(string $email) :array
    {
        return $this->findContactByCriteria(['EMAIL' => $email]);
    }

    /**
     * Protected method from search contact by criteria
     *
     * @param array $criteria
     * @return array
     * @throws \Bitrix24\Bitrix24Exception
     */
    protected function findContactByCriteria(array $criteria) :array
    {
        $contact_obj = new \Bitrix24\CRM\Contact($this->auth_scope);
        $result = $contact_obj->getList([],
            $criteria,
            [
                "ID",
                "NAME",
                "LAST_NAME",
                "ASSIGNED_BY_ID",
                "HAS_EMAIL",
                "HAS_PHONE"
            ]);
        return $result;
    }

    /**
     * Phone UK normalize from filter
     *
     * @param string $phone
     * @return string
     */
    protected function phoneNormalize(string $phone) :string
    {
        if (mb_strlen($phone) == 12 && substr($phone, 0, 2) == '38') {
            $result =  '__' . substr('+' . $phone, 2, 9) . '%';
        } else {
            $result = '__' . substr($phone, 2, 9) . '%';
        }

        return $result;
    }
}