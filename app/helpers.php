<?php

use Illuminate\Support\Str;

function route_class()
{
    return str_replace('.', '-', \Illuminate\Support\Facades\Route::currentRouteName());
}

function category_nav_active($category_id)
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

function make_excerpt($value, $lenght = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return Str::limit($excerpt, $lenght);
}
function model_admin_link($title,$model){
    return model_link($title,$model);
}

function model_link($title,$model,$prefix=''){
    //获取数据模型的复数蛇形名称
    $model_name=model_plural_name($model);

    //初始化前缀
    $prefix=$prefix?"/$prefix/":"/";
    //使用站点URL拼接全量URL
    $url=config('app.url').$prefix.$model_name.'/'.$model->id;

    //拼接HTMl A标签，返回

//    return '<a href="'.$url.'" target="_blank">'.$title.'</a>';
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

function model_plural_name($model){
    //从实体中获取完整的类名
    $full_clasa_name=get_class($model);

    //获取基础类名
    $class_name=class_basename($full_clasa_name);

    //蛇形命名
    $snake_case_name=Str::snake($class_name);

    //获取子串的复数形式
    return Str::plural($snake_case_name);
}
