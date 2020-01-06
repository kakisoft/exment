<?php

namespace Exceedone\Exment\Enums;

use Exceedone\Exment\Model\RoleGroup;
use Exceedone\Exment\ConditionItems;

class ConditionTypeDetail extends EnumBase
{
    use EnumOptionTrait;
    
    const USER = "1";
    const ORGANIZATION = "2";
    const ROLE = "3";
    const SYSTEM = "4";
    const COLUMN = "9";
    const USERTABLE_COLUMN = "11";
    const ORGANIZATIONTABLE_COLUMN = "12";

    public static function SYSTEM_TABLE_OPTIONS($form_priority_type)
    {
        $result = [];

        switch ($form_priority_type) {
            case ConditionTypeDetail::USER:
                $model = getModelName(SystemTableName::USER)::get();
                foreach ($model as $m) {
                    $result[$m->id] = $m->getLabel();
                }
                break;
            case ConditionTypeDetail::ORGANIZATION:
                $model = getModelName(SystemTableName::ORGANIZATION)::get();
                foreach ($model as $m) {
                    $result[$m->id] = $m->getLabel();
                }
                break;
            case ConditionTypeDetail::ROLE:
                $model = RoleGroup::get();
                foreach ($model as $m) {
                    $result[$m->id] = $m->role_group_view_name;
                }
                break;
            default:
                return null;
        }
        return $result;
    }
    
    public function getConditionItem($custom_table, $target)
    {
        switch ($this) {
            case ConditionTypeDetail::USER:
                return new ConditionItems\UserItem($custom_table, $target);
            case ConditionTypeDetail::ORGANIZATION:
                return new ConditionItems\OrganizationItem($custom_table, $target);
            case ConditionTypeDetail::ROLE:
                return new ConditionItems\RoleGroupItem($custom_table, $target);
            case ConditionTypeDetail::SYSTEM:
                return new ConditionItems\SystemItem($custom_table, $target);
            case ConditionTypeDetail::COLUMN:
                return new ConditionItems\ColumnItem($custom_table, $target);
            case ConditionTypeDetail::USERTABLE_COLUMN:
                return new ConditionItems\UserTableColumnItem($custom_table, $target);
            case ConditionTypeDetail::ORGANIZATIONTABLE_COLUMN:
                return new ConditionItems\OrganizationTableColumnItem($custom_table, $target);
        }
    }
}
