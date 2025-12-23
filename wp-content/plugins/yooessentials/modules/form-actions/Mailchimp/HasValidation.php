<?php
/**
 * @package   Essentials YOOtheme Pro 2.4.12 build 1202.1125
 * @author    ZOOlanders https://www.zoolanders.com
 * @copyright Copyright (C) Joolanders, SL
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

namespace ZOOlanders\YOOessentials\Form\Action\Mailchimp;

use function YOOtheme\trans;
use ZOOlanders\YOOessentials\Form\Action\ActionConfigurationException;

trait HasValidation
{
    protected static function validateConfig(object $config)
    {
        if (!($config->auth ?? null)) {
            throw new ActionConfigurationException('Missing Authentication.');
        }

        if (!($config->list ?? null)) {
            throw new ActionConfigurationException('Missing Audience Listing.');
        }

        if (!($config->member_email_address ?? null)) {
            throw new ActionConfigurationException('Please provide a valid email address');
        }
    }

    protected static function mapError(string $error, object $config): ?string
    {
        if (str_contains($error, 'Please provide a valid email address') || str_contains($error, 'This value should not be blank')) {
            return trans('Please provide a valid email address.');
        }

        if (str_contains($error, 'Member Exists')) {
            return sprintf(
                trans('%s is already a member.'),
                htmlspecialchars($config->member_email_address)
            );
        }

        if (str_contains($error, 'was permanently deleted and cannot be re-imported. The contact must re-subscribe to get back on the list')) {
            return sprintf(
                trans('%s was permanently deleted and cannot be re-imported. The contact must re-subscribe to get back on the list.'),
                htmlspecialchars($config->member_email_address)
            );
        }

        if (str_contains($error, 'This list member cannot be removed')) {
            return sprintf(
                trans('%s member has already been removed.'),
                htmlspecialchars($config->member_email_address)
            );
        }

        if (str_contains($error, 'The requested resource could not be found')) {
            return sprintf(
                trans('%s member cannot be found.'),
                htmlspecialchars($config->member_email_address)
            );
        }

        if (str_contains($error, 'ADDRESS')) {
            return trans('Invalid address provided');
        }

        if (str_contains($error, 'FNAME')) {
            return trans('Invalid first name provided');
        }

        if (str_contains($error, 'LNAME')) {
            return trans('Invalid last name provided');
        }

        if (str_contains($error, 'PHONE')) {
            return trans('Invalid phone provided');
        }

        if (str_contains($error, 'BIRTHDAY')) {
            return trans('Invalid birthday provided');
        }

        return null;
    }
}
