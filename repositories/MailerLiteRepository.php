<?php

namespace Initbiz\Newsletter\Repositories;

use GuzzleHttp\ClientInterface;
use Initbiz\Newsletter\Repositories\BaseRepository;

class MailerLiteRepository extends BaseRepository
{
    public $accessToken;

    public $listId;

    public function __construct(?ClientInterface $client = null)
    {
        $this->accessToken = Config::get('initbiz.configurator::mailchimp.api_key');

        parent::__construct(Config::get('initbiz.configurator::mailchimp.base_uri'), $client);
    }

    public function getHeaders(): array
    {
        return [
            'Authorization' => 'Basic ' . $this->accessToken,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Connect to Mailchimp API and add or update user to Audience Dashboard
     *
     * @param User $user
     * @param array $interests
     * @return array
     */
    public function createOrUpdateUser(User $user, array $interests = [])
    {
        $body = [
            'email_address' => $user->email,
            'status_if_new' => 'subscribed',
            'merge_fields' => [
                'FNAME' => $user->name,
                'LNAME' => $user->surname ?? '',
                'PHONE' => $user->phone ?? '',
            ],
        ];
        if (!empty($interests)) {
            $body['interests'] = $interests;
        }

        $subscriberHash = md5($user->email);
        $url = 'lists/' . $this->listId . '/members/' . $subscriberHash;

        try {
            return $this->put($url, $body, $this->getHeaders());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            traceLog($e->getResponse()->getBody()->getContents());
        }
    }

    public function getInterestCategories(): ?array
    {
        try {
            return $this->get('/lists/' . $this->listId . '/interest-categories', $this->getHeaders());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            traceLog($e->getResponse()->getBody()->getContents());
        }
    }

    public function getInterestForCategoryId($apiId): ?array
    {
        try {
            return $this->get('/lists/' . $this->listId . '/interest-categories/' . $apiId . '/interests', $this->getHeaders());
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            traceLog($e->getResponse()->getBody()->getContents());
        }
    }
}