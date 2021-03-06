<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Models\Governorate;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AreaController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Governorate Areas';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Area);

        $grid->filter(function($filter){

            // Remove the default id filter
            $filter->disableIdFilter();

            // Add Name filter
            $filter->like('name_en', 'Name');

            // Add Status filter
            $filter->equal('is_active', 'Status')->radio([
                ''   => 'All',
                0    => 'Not Active',
                1    => 'Active',
            ]);

        });

        $grid->model()->orderBy( 'created_at', 'desc' );

        $grid->column('id', __('Id'))->sortable();
        $grid->governorate()->name_en('Governorate')->sortable();
        $grid->column('name_en', __('English Name'));
        $grid->column('name_ar', __('Arabic Name'));
        $grid->column( 'is_active', __( 'Status' ) )
            ->using( ['0' => 'Not-Active', '1' => 'Active'] )
            ->label( [
                0 => 'danger',
                1 => 'success',
            ] )->sortable();
        $grid->column('created_at', __('Created at'));

        $grid->disableBatchActions();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableView();
        });


        $grid->disableBatchActions();
        $grid->disableExport();
        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Area);

        $form->select( 'governorate_id', 'Governorate' )->options( function () {
            return Governorate::all()->pluck('name_en', 'id');
        } )->rules( 'required' );
        $form->text('name_en', __('English Name'))->rules( 'required' );
        $form->text('name_ar', __('Arabic Name'))->rules( 'required' );
        $form->switch('is_active', __('Is active'))->default(1);

        $form->tools(function (Form\Tools $tools) {
            // Disable `View` btn.
            $tools->disableView();
        });

        return $form;
    }
}
