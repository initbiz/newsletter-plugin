<?php
namespace Initbiz\Newsletter\Controllers;
use Backend\Classes\Controller;
use BackendMenu;
use DB;
use Flash;
use Initbiz\Newsletter\Models\Checkbox;
use Lang;

class Checkboxes extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
        'Backend.Behaviors.ImportExportController'

    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    public $requiredPermissions = ['initbiz.newsletter.checkboxes'];
    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Initbiz.Newsletter', 'newsletter', 'checkboxes');
    }
    public function listExtendQuery($query) {
        $query->get();
    }
    public function onRemoveCheckboxes()
    {
        if (($checkedId = post('checked')) && is_array($checkedId) && count($checkedId)) {

            foreach ($checkedId as $checkboxId) {
                if ((!$checkbox = Checkbox::where('id',$checkboxId)))
                    continue;

                $checkbox->delete();
            }

            Flash::success(Lang::get('initbiz.newsletter::lang.flash.deleted'));
        }

        return $this->listRefresh();
    }
}