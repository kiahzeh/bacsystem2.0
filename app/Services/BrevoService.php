<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use Symfony\Component\Mime\Email;

class BrevoService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.brevo.key');
        $this->client = new Client([
            'base_uri' => 'https://api.brevo.com/v3/',
            'headers' => [
                'api-key' => $this->apiKey,
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);
    }

    public function sendTransactionalEmail($to, $templateId, $params = [], $attachments = [])
    {
        try {
            $response = $this->client->post('smtp/email', [
                'json' => [
                    'to' => [['email' => $to]],
                    'templateId' => $templateId,
                    'params' => $params,
                    'attachment' => $attachments
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Brevo API Error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendSms($to, $message)
    {
        try {
            $response = $this->client->post('transactionalSMS/sms', [
                'json' => [
                    'recipient' => $to,
                    'content' => $message,
                    'type' => 'transactional'
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Brevo SMS API Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send an email using Brevo SMTP
     */
    public function sendEmail(string $to, string $subject, string $htmlContent, ?string $textContent = null)
    {
        try {
            Mail::html($htmlContent, function ($message) use ($to, $subject) {
                $message->to($to)
                    ->subject($subject);
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Brevo email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a template-based email
     */
    public function sendTemplate(string $to, string $templateName, array $data = [])
    {
        try {
            Mail::send($templateName, $data, function ($message) use ($to, $data) {
                $message->to($to)
                    ->subject($data['subject'] ?? 'Notification from BAC System');
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Brevo template email sending failed: ' . $e->getMessage());
            return false;
        }
    }
}