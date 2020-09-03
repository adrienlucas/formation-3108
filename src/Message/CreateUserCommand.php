<?php

namespace App\Message;

use App\Model\Contact;

final class CreateUserCommand
{
    /**
     * @var Contact
     */
    private $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }
}
