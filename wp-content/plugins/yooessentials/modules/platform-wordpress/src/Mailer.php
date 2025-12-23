<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Wordpress;

use YOOtheme\File;
use YOOtheme\Path;
use YOOtheme\Str;
use ZOOlanders\YOOessentials\Mailer as MailerInterface;

class Mailer implements MailerInterface
{
    protected $headers = [];
    protected $to = [];
    protected $body = '';
    protected $altBody = '';
    protected $subject = '';
    protected $html = false;
    protected $attachments = [];

    public function addRecipient(string $email, string $name = ''): MailerInterface
    {
        $this->to[] = $email;

        return $this;
    }

    public function addCc(string $email, string $name = ''): MailerInterface
    {
        if ($name) {
            $this->headers[] = 'Cc:' . $name . ' <' . $email . '>';

            return $this;
        }

        $this->headers[] = 'Cc:' . $email;

        return $this;
    }

    public function addBcc(string $email, string $name = ''): MailerInterface
    {
        if ($name) {
            $this->headers[] = 'Bcc:' . $name . ' <' . $email . '>';

            return $this;
        }

        $this->headers[] = 'Bcc:' . $email;

        return $this;
    }

    public function addReplyTo(string $email, string $name = ''): MailerInterface
    {
        if ($name) {
            $this->headers[] = 'Reply-To:' . $name . ' <' . $email . '>';

            return $this;
        }

        $this->headers[] = 'Reply-To:' . $email;

        return $this;
    }

    public function setFrom(string $email, string $name = ''): MailerInterface
    {
        if (empty($email)) {
            // Get the site domain and get rid of www.
            $sitename = wp_parse_url(network_home_url(), PHP_URL_HOST);
            if ('www.' === substr($sitename, 0, 4)) {
                $sitename = substr($sitename, 4);
            }

            $email = 'wordpress@' . $sitename;
            $email = apply_filters('wp_mail_from', $email);
        }

        if (empty($name)) {
            $name = 'WordPress';
            $name = apply_filters('wp_mail_from_name', $name);
        }

        $this->headers[] = 'From:' . $name . ' <' . $email . '>';

        return $this;
    }

    public function setSubject(string $subject): MailerInterface
    {
        $this->subject = $subject;

        return $this;
    }

    public function setBody(string $content): MailerInterface
    {
        $this->body = $content;

        return $this;
    }

    public function setAltBody(string $content): MailerInterface
    {
        $this->altBody = $content;

        return $this;
    }

    public function isHtml(bool $isHtml): MailerInterface
    {
        $this->html = $isHtml;

        return $this;
    }

    public function addAttachment(string $filePath): MailerInterface
    {
        if (!Str::startsWith($filePath, '~') && !Path::isAbsolute($filePath)) {
            $filePath = "~/$filePath";
        }

        if (File::exists($filePath)) {
            $this->attachments[] = Path::resolve($filePath);
        }

        return $this;
    }

    public function send(): bool
    {
        if ($this->html) {
            add_filter('wp_mail_content_type', [$this, 'setEmailContentType']);
        }

        if ($this->altBody) {
            add_action('phpmailer_init', [$this, 'setMailerAltBody']);
        }

        $result = wp_mail($this->to, $this->subject, $this->body, $this->headers, $this->attachments);

        if ($this->html) {
            remove_filter('wp_mail_content_type', [$this, 'setEmailContentType']);
        }

        if ($this->altBody) {
            remove_action('phpmailer_init', [$this, 'setMailerAltBody']);
        }

        return $result;
    }

    public function setEmailContentType(): string
    {
        return 'text/html';
    }

    public function setMailerAltBody($mailer)
    {
        $mailer->AltBody = $this->altBody;
    }
}
