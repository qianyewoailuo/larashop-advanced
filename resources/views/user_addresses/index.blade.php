@extends('layouts.app')
@section('title', '收货地址列表')

@section('content')
<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">收货地址列表
                <a href="{{ route('user_addresses.create') }}" class="pull-right">新增收货地址</a>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>收货人</th>
                            <th>地址</th>
                            <th>邮编</th>
                            <th>电话</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($addresses as $address)
                        <tr>
                            <td>{{ $address->contact_name }}</td>
                            <td>{{ $address->full_address }}</td>
                            <td>{{ $address->zip }}</td>
                            <td>{{ $address->contact_phone }}</td>
                            <td>
                                <!-- 修改 -->
                                <a href="{{ route('user_addresses.edit', $address->id) }}" class="btn btn-primary">
                                    修改
                                </a>
                                <!-- 删除 -->
                                <form action="{{ route('user_addresses.destroy', $address->id) }}" method="post" style="display: inline-block;" onsubmit="return confirm('您确定要删除吗？');">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button class="btn btn-danger" type="submit">删除</button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection