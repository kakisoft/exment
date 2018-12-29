<?php

namespace Exceedone\Exment\Controllers;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\HasResourceActions;
use Illuminate\Http\Request;
use Exceedone\Exment\Model\Define;
use Exceedone\Exment\Model\MailTemplate;
use Exceedone\Exment\Enums\MailTemplateType;

class MailTemplateController extends AdminControllerBase
{
    use HasResourceActions;

    public function __construct(Request $request)
    {
        $this->setPageInfo(exmtrans("mail_template.header"), exmtrans("mail_template.header"), exmtrans("mail_template.description"));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MailTemplate);
        $grid->column('mail_key_name', exmtrans("mail_template.mail_key_name"));
        $grid->column('mail_view_name', exmtrans("mail_template.mail_view_name"));

        $grid->disableExport();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if (boolval($actions->row->system_flg)) {
                $actions->disableDelete();
            }
            $actions->disableView();
        });
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null)
    {
        $form = new Form(new MailTemplate);
        if (!isset($id)) {
            $form->text('mail_key_name', exmtrans("mail_template.mail_key_name"))
                ->required()
                ->rules("unique:".MailTemplate::getTableName()."|regex:/".Define::RULES_REGEX_ALPHANUMERIC_UNDER_HYPHEN."/")
                ->help(exmtrans("mail_template.help.mail_key_name").exmtrans("common.help_code"));
        } else {
            $form->display('mail_key_name', exmtrans("mail_template.mail_key_name"))
            ->help(exmtrans("mail_template.help.mail_key_name"));
        }

        // get manual url abou mail temlate valiable value
        $manual_url = url_join(config('exment.manual_url'), 'mail');
        $form->text('mail_view_name', exmtrans("mail_template.mail_view_name"))->required()
            ->help(exmtrans("mail_template.help.mail_view_name"));
        
        $form->select('mail_template_type', exmtrans("mail_template.mail_template_type"))->required()
            ->options(MailTemplateType::transArray('mail_template.mail_template_type_options'))
            ->default(MailTemplateType::BODY);

        $form->text('mail_subject', exmtrans("mail_template.mail_subject"))
            ->rules("required_if:mail_template_type,".MailTemplateType::BODY)
            ->help(exmtrans("mail_template.help.mail_subject"));
            
        $form->textarea('mail_body', exmtrans("mail_template.mail_body"))->rows(10)
            ->help(exmtrans("mail_template.help.mail_body"));
        disableFormFooter($form);
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        return $form;
    }
}
