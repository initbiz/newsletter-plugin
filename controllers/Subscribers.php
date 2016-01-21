<?php namespace Initbiz\Newsletter\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use DB;
use Flash;
use Lang;
//use Initbiz\Newsletter\Models\Messages as Message;

class Subscribers extends Controller {

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['initbiz.newsletter.subscribers'];

    public $bodyClass = 'compact-container';

    public function __construct() {
        parent::__construct();

        BackendMenu::setContext('Initbiz.Newsletter', 'newsletter', 'subscribers');
    }

    public function listExtendQuery($query) {
        $query->where('confirmed', 1);
    }
}
