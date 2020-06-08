<?php


namespace OnFact\Endpoint;

use OnFact\Model\Model;

abstract class Documents extends Api
{

    public function sendEmail($document, $email) {
        $response = $this->_post(static::ENDPOINT . '/' . $document->getId() . '/emails.json', $email);
        $email->setId($response->id);

        return $response->id;
    }

    public function addDocumentEvent($document, $email) {
        $response = $this->_post(static::ENDPOINT . '/' . $document->getId() . '/document-events.json', $email);
        $email->setId($response->id);

        return $response->id;
    }

}
