<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use App\Models\Category;

abstract class CommonProductsController extends Controller
{

    use HasResourceActions;

    // 定义一个抽象方法，返回当前管理的商品类型
    abstract public function getProductType();

    // 列表
    public function index(Content $content)
    {
        return $content
            ->header(Product::$typeMap[$this->getProductType()] . '列表')
            ->body($this->grid());
    }

    // 编辑
    public function edit($id, Content $content)
    {
        return $content
            ->header('编辑' . Product::$typeMap[$this->getProductType()])
            ->body($this->form()->edit($id));
    }

    // 创建
    public function create(Content $content)
    {
        return $content
            ->header('创建' . Product::$typeMap[$this->getProductType()])
            ->body($this->form());
    }

    // grid|网格
    protected function grid()
    {
        $grid = new Grid(new Product());

        // 筛选出当前类型的商品默认 ID 倒序排序
        $grid->model()->where('type', $this->getProductType())->orderBy('id', 'desc');
        // 调用自定义抽象方法, 展示各自创建或编辑需要展示哪些字段
        $this->customGrid($grid);

        // 查看与删除按钮 - laravel-admin固定写法
        $grid->actions(function ($action) {
            $action->disableView();
            $action->disableDelete();
        });
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                // 禁用批量删除按钮
                $batch->disableDelete();
            });
        });

        return $grid;
    }

    // 定义一抽象方法，各类型控制器将实现本方法来定义列表应展示哪些字段
    abstract protected function customGrid(Grid $grid);

    protected function form()
    {
        $form = new Form(new Product());

        // 添加名为 type 隐藏字段, 值为当前商品类型
        $form->hidden('type')->value($this->getProductType());

        // 公用部分字段
        $form->text('title', '商品名称')->rules('required');
        $form->select('category_id', '类目')->options(function ($id) {
            $category = Category::find($id);
            if ($category) {
                return [$category->id => $category->full_name];
            }
        })->ajax('/admin/api/categories?is_directory=0');
        $form->image('image', '封面图片')->rules('required|image');
        $form->editor('description', '商品描述')->rules('required');
        $form->radio('on_sale', '上架')->options(['1' => '是', '0' => '否'])->default('0');

        // 调用自定义方法,可实现具体类型控制器需要显示哪些额外字段
        $this->customForm($form);

        // 公用部分字段
        $form->hasMany('skus', '商品 SKU', function (Form\NestedForm $form) {
            $form->text('title', 'SKU 名称')->rules('required');
            $form->text('description', 'SKU 描述')->rules('required');
            $form->text('price', '单价')->rules('required|numeric|min:0.01');
            $form->text('stock', '剩余库存')->rules('required|integer|min:0');
        });
        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price') ?: 0;
        });

        return $form;
    }

    // 定义一抽象方法，各类型控制器实现本方法来定义表单应有哪些额外字段
    abstract protected function customForm(Form $form);
}
