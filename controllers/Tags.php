<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Tags Backend Controller
 *
 * @link https://docs.octobercms.com/3.x/extend/system/controllers.html
 */
class Tags extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array required permissions
     */
    public $requiredPermissions = ['initbiz.newsletter.tags'];

    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Initbiz.Newsletter', 'newsletter', 'tags');
    }
}
