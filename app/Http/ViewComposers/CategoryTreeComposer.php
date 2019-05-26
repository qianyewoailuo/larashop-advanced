<?php
namespace App\Http\ViewComposers;

use App\Services\CategoryService;
use Illuminate\View\View;

class CategoryTreeComposer
{

    protected $categoryService;

    // 在构造函数中使用依赖注入自动注入 CategoryService 类
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    // 当渲染指定模板时, Laravel 会调用 compose 方法
    // 所以定义好后还需告诉 Laravel 要把这个 ViewComposer 应用到哪些模板文件里
    // 可以在 app/Providers/AppServiceProvider.php 中指定应用的模板
    public function compose(View $view)
    {
        // 使用 with 方法注入变量
        $view->with('categoryTree',$this->categoryService->getCategoryTree());
    }
}