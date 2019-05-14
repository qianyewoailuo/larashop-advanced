@extends('layouts.app')
@section('title', '提示')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">提示</div>
    <div class="panel-body text-center">
        <h3>邮箱未激活,请先进行验证</h3>
        <a class="btn btn-primary" href="{{ route('email_verification.send') }}">重新发送验证邮件</a>
    </div>
</div>
@endsection