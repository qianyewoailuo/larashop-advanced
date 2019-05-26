<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'is_directory', 'level', 'path',
    ];

    protected $casts = [
        'is_directory' => 'boolean',
    ];
    // 创建监听
    protected static function boot()
    {
        parent::boot();
        // 监听 category 创建事件,用于初始化 path 和 level 字段值
        static::creating(function (Category $category) {
            // 如果创建的是一个根类目
            if (is_null($category->parent_id)) {
                // 将层级设为 0
                $category->level = 0;
                // 将 path 设为 -
                $category->path = '-';
            } else {
                // 将层级设为父类目的层级 + 1
                $category->level = $category->parent->level + 1;
                // 将 path 值设为父类目的 path 追加父类目 ID 以及最后跟上一个分割符 '-'
                $category->path = $category->parent->path . $category->parent_id . '-';
            }
        });
    }

    // 内部关联 - 子级属于父级
    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    // 内部关联 - 父级拥有多个子级
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // 一个类目拥有多个商品
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // 访问器获取所有祖先类目的 ID 值
    public function getPathIdsAttribute()
    {
        // trim() 去除两端的 '-'
        // explode 以 '-' 分割字符串为数组
        // array_filter 过滤数组中的空值
        return array_filter(explode('-', trim($this->path, '-')));
    }

    // 访问器获取所有祖先类目并按层级排序
    public function getAncestorsAttribute()
    {
        return Category::query()
            ->whereIn('id', $this->path_ids)
            ->orderBy('level')
            ->get();
    }

    // 访问器获取以 '-' 为分割的所有祖先类目名称以及当前类目的名称
    public function getFullNameAttribute()
    {
        return $this->ancestors
            ->pluck('name')
            ->push($this->name)
            ->implode(' - ');
        // https://learnku.com/docs/laravel/5.8/collections/3916 集合方法参考
    }
}
