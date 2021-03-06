<?php

namespace App\Admin\Controllers;

use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ProductAttributeImagesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product Attribute';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductAttributeValue);

        $grid->filter( function ( $filter ) {

            // Remove the default id filter
            $filter->disableIdFilter();

            $filter->in('product_id', 'Product')->multipleSelect(Product::all()->pluck('name_en', 'id'));

            // Add Status filter
            $filter->equal( 'is_active', 'Status' )->radio( [
                '' => 'All',
                0  => 'Not Active',
                1  => 'Active',
            ] );

        } );

        $grid->model()->orderBy('id', 'desc');

        $grid->column('id', __('Id'));
        $grid->product()->name_en('Product')->sortable();
        $grid->attributevalue()->value_en('Color');
        $grid->column('sku', __('Sku'));
        $grid->column('stock', __('Stock')) ;
        $grid->column( 'is_active', __( 'Status' ) )
            ->using( ['0' => 'Not-Active', '1' => 'Active'] )
            ->label( [
                0 => 'danger',
                1 => 'success',
            ] )->sortable();
        $grid->column('main_images', __('Main imagess'))->image( '', 100, 50 );
        $grid->column( 'created_at', __( 'Created' ) )->date( 'M d Y H:i' )->width( 150 )->sortable();

        $grid->disableBatchActions();
        $grid->disableExport();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(ProductAttributeValue::findOrFail($id));

        $show->field('sku', __('Sku'));
        $show->field('stock', __('Stock'));
        $show->is_active(__('Status'))
            ->using(['0' => 'Not-Active', '1' => 'Active']);
        $show->field('created_at', __('Created at'));
        $show->field('main_images', __('Main imagess'))->image( '', 100, 50 );

        return $show;
    }

    /**
     * Make a form builder.
     *
     *
     * @param  $id
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ProductAttributeValue);

//        dd($form->model()->id);

        $form->select( 'product_id', 'Product' )->options( function () {
            return Product::all()->pluck('name_en', 'id');
        } )->rules( 'required' );

        $form->select( 'attribute_value_id', 'Color' )->options( function ( $id ) {
            return AttributeValue::options($id);
        } )->rules( 'required' );

        $form->text( 'sku', 'sku' )
            ->creationRules('required|unique:product_attribute_values,sku|unique:products,sku' )
            ->updateRules( 'required|unique:product_attribute_values,sku,{{id}}|unique:products,sku' );
        $form->text( 'stock', 'stock' )->rules( 'required' );
        $form->switch( 'is_active', __( 'Is active' ) )->default( 1 );

        $form->multipleImage('main_images', __('Main images'))->removable()->creationRules( 'required' );

        return $form;
    }
}
