<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials;

interface Mailer
{
    public function addRecipient(string $email, string $name = ''): self;

    public function addCc(string $email, string $name = ''): self;

    public function addBcc(string $email, string $name = ''): self;

    public function addReplyTo(string $email, string $name = ''): self;

    public function setFrom(string $email, string $name = ''): self;

    public function setSubject(string $subject): self;

    public function setBody(string $content): self;

    public function setAltBody(string $content): self;

    public function isHtml(bool $isHtml): self;

    public function addAttachment(string $filePath): self;

    public function send(): bool;
}
