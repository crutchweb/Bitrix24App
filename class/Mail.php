<?php

namespace Bitrix24App;

/**
 * Class Mail
 * @package Bitrix24App
 * @param string $from
 * @param string $to
 * @param string $subject
 * @param string $message
 */
class Mail
{
    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $message;

    /**
     * Mail constructor.
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $message
     */
    public function __construct(string $from, string $to, string $subject, string $message)
    {
        $this->from = $from;
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Send mail
     *
     * @return bool
     */
    public function send() :bool
    {
        $message = "
                <html>
                    <head>
                        <title>$this->subject</title>
                    </head>
                    <body>
                        <p>$this->message</p>                    
                    </body>
                </html>";
        $headers  = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: <" . $this->from . ">\r\n";

        if (mail($this->to, $this->subject, $message, $headers)) {
            return true;
        }

        return false;
    }
}