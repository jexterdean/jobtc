@extends('layouts.default')
@section('content')
<div class="modal fade add_link_modal" id="add_link" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-{{ \App\Helpers\Helper::getRandomColor() }}">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Link</h4>
            </div>
            <div class="modal-body">
                @if(Auth::user('user')->user_type === 1 || Auth::user('user')->user_type === 2 || Auth::user('user')->user_type === 3)
                {!!  Form::open(['route' => 'links.store','class' => 'form-horizontal link-form'])  !!}
                @include('links/partials/_form')
                {!! Form::close()  !!}
                @else
                <div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                    <strong>You dont have to perform this action!!</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade add_category_modal" id="add_category" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-{{ \App\Helpers\Helper::getRandomColor() }}">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Add Category</h4>
            </div>
            <div class="modal-body">
                @if(Auth::user('user')->user_type === 1 || Auth::user('user')->user_type === 2 || Auth::user('user')->user_type === 3)
                {!!  Form::open(['route' => 'linkCategory.store','class' => 'form-horizontal category-form'])  !!}
                @include('linkCategory/partials/_form')
                {!! Form::close()  !!}
                @else
                <div class='alert alert-danger alert-dismissable'>
                    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'></button>
                    <strong>You dont have to perform this action!!</strong>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="ajax" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>


<div class="col-md-12">
    <div class="box box-solid box-{{ \App\Helpers\Helper::getRandomColor() }}">
        <div class="box-header">
            <h3 class="box-title">Link List</h3>
            <div class="box-tools pull-right">
                <a data-toggle="modal" href="#add_link">
                    <button class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Add New Link</button>
                </a>
                <button class="btn btn-{{ \App\Helpers\Helper::getRandomColor() }} btn-sm" data-widget="collapse"><i
                        class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">

            <?php
            $DATA = array();
            $QA = array();
            foreach ($links as $link) {
                $linkToEdit = "<a href='links/$link->id/edit' data-toggle='modal' data-target='#ajax'> <i
                    class='fa fa-edit fa-2x'></i> </a>";
                $linkToDelete = "<a href='" . route('links.destroy', $link->id) . "' class='alert_delete'> <i
                    class='fa
                    fa-trash-o fa-2x'></i> </a>";
                $Option = " <span class='hspacer'></span> $linkToEdit <span class='hspacer'></span> $linkToDelete";

                $QA[] = array(
                    "<strong><a href='" . url($link->url) . "' target='_blank'>" . ($link->title) . "</a></strong>",
                    $link->descriptions,
                    $link->category_name,
                    $link->tags,
                    $Option
                );
            }

            $cacheKey = md5('link.list.' . session()->getId());
            Cache::put($cacheKey, $QA, 100);
            ?>
            <table class="table table-striped table-responsive table-bordered table-hover dt-responsive datatableclass" id="link_table">
                <thead>
                    <tr>
                        <th>
                            Title
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Category
                        </th>
                        <th>
                            Tags
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
@stop