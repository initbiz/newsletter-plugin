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
        $query->where('confirmed', '=', 'true');
    }

//    public function onRemoveMessages()
//    {
//        if (($checkedSlugs = post('checked')) && is_array($checkedSlugs) && count($checkedSlugs)) {
//
//            foreach ($checkedSlugs as $messageSlug) {
//                if ((!$message = Message::where('slug', '=',$messageSlug)))
//                    continue;
//
//                $message->delete();
//            }
//
//            Flash::success('Successfully deleted those messages.');
//        }
//
//        return $this->listRefresh();
//    }
}
