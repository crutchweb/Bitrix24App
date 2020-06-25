<?php
require_once 'vendor/autoload.php';
require_once 'class/Bitrix24App.php';
require_once 'class/Authorize.php';
require_once 'class/Mail.php';

use Bitrix24App\Bitrix24App;
use Bitrix24App\Authorize;
use Bitrix24App\Mail;

const CONFIG = [
    'appID'     => 'local.*************.********', // getting Bitrix24 application id
    'secretKey' => '1WEgRnCV1SuYmnbjErgLdqjZIa05MV9h2Izinw8GFBV********', // Getting Bitrix24 application secret
    'scopeInst' => 'crm,user,telephony,task', // write Bitrix24 instances which you want to use via API. They need to be chosen in application at Bitrix24
    'b24Domain' => 'domain.bitrix24.ru', // address of your Bitrix24 portal
    'b24Login'  => 'mail@mail.ru', // login of your real user, he need to be an Administrator of instance you want to use
    'b24Pass'   => 'password', // password of your real user, he need to be an Administrator of instance you want to use
    'from'      => 'mail@mail.ru', // set mail from
    'to'        => 'mail@mail.ru' // set mail to
];

//get lead id
$lead_id = (int)$_POST['data']['FIELDS']['ID'];

$b24_auth = new Authorize(CONFIG);
$authorize = $b24_auth->getAuth();

$b24_lib = new \Bitrix24\Bitrix24();
$b24_obj = new Bitrix24App($authorize, $b24_lib);

$b24_obj->findLead($lead_id);
$lead = $b24_obj->getLead();

$lead_scope = $lead->getLeadScope();

$lead_email = '{{{Почта не указана}}}';
if ($lead_scope['result']['EMAIL']) {
    foreach ($lead_scope['result']['EMAIL'] as $email) {
        if ($email['VALUE']) {
            $lead_email = $email['VALUE'];
            break;
        }
    }
}

$lead_phone = '{{{Телефон не указан}}}';
if ($lead_scope['result']['PHONE']) {
    foreach ($lead_scope['result']['PHONE'] as $phone) {
        if ($phone['VALUE']) {
            $lead_phone = $phone['VALUE'];
            break;
        }
    }
}

if ($b24_obj->findContact($lead_email, $lead_phone)) {
    $contact = $b24_obj->getContact();
    $contact_id = (int) $contact['result'][0]['ID'];
    $master = (int) $contact['result'][0]['ASSIGNED_BY_ID'];
    $contact_identity = $contact['result'][0]['NAME'] . ' ' . $contact['result'][0]['LAST_NAME'];

    $lead->saveLeadByCriteria([
        'CONTACT_ID' => $contact_id ,
        'ASSIGNED_BY_ID' => $master,
        'COMMENTS' => 'Контакт ' . $contact_identity . ' добавлен в лид'
    ]);

    print_r('Контакт ' . $contact_identity . ' добавлен в лид');

    $mail_obj = new Mail(
        CONFIG['from'],
        CONFIG['to'],
        'Успех! Контакт добавлен',
        'Контакт ' . $contact_identity . ' добавлен в лид <br> ID контакта: ' . $contact_id . '<br>Ответственный: ' . $master
    );

} else {
    $lead->saveLeadByCriteria([
        'COMMENTS' => 'Контакт с телефоном ' . $lead_phone . ' или почтой ' . $lead_email .' не найден'
    ]);

    print_r('Контакт с телефоном ' . $lead_phone . ' или почтой ' . $lead_email .' не найден');

    $mail_obj = new Mail(
        CONFIG['from'],
        CONFIG['to'],
        'Ошибка! Контакт не добавлен',
        'Контакт с телефоном ' . $lead_phone . ' или почтой ' . $lead_email .' не найден'
    );
}

$mail_obj->send();
