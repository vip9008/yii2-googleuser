<?php

namespace vip9008\googleuser;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    const VERSION = '1.0.0';

    /** @var bool Whether to show flash messages. */
    public $enableFlashMessages = true;

    /** @var bool Whether to enable registration. */
    public $enableRegistration = true;

    /** @var bool Whether user can remove his account */
    public $enableAccountDelete = false;

    /** @var array Model map */
    public $modelMap = [];

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'user';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        '<action:(index|sign-in|sign-out)>' => 'google-user/<action>',
    ];

    public $apiTokens = [];
}
