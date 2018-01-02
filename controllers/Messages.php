<?php namespace Initbiz\Newsletter\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use DB;
use Flash;
use Initbiz\Newsletter\Models\Checkbox;
use Lang;
use Initbiz\Newsletter\Models\Message as Message;

class Messages extends Controller {

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['initbiz.newsletter.messages'];

    public $bodyClass = 'compact-container';

    public function __construct() {
        parent::__construct();

        if(!empty($this->params)) {
            $message = Message::where('id', $this->params[0])->first();
            $this->vars['checked_checkboxes'] = $message->checkboxes->pluck('name')->all();
        }
        $this->vars['checkboxes'] = Checkbox::all();
        BackendMenu::setContext('Initbiz.Newsletter', 'newsletter', 'messages');
    }



    public function onRemoveMessages()
    {
        //TODO: Message::beforeDelete do not run without two foreach
        if (($checkedId = post('checked')) && is_array($checkedId) && count($checkedId)) {
            $messages = Message::get();
            foreach ($checkedId as $messageId) {
                foreach ($messages as $message) {
                    trace_log($messageId);
                    if ($message->id !== (int)$messageId)
                        continue;
                    $message->delete();
                    Flash::success(Lang::get('initbiz.newsletter::lang.flash.deleted'));
                }
            }

        }

        return $this->listRefresh();
    }
}
