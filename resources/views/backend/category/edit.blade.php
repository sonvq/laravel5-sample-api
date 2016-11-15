@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.category.management') . ' | ' . trans('labels.backend.category.edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.category.management') }}
        <small>{{ trans('labels.backend.category.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($category, ['route' => ['admin.category.update', $category], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'files' => true]) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.category.edit') }}</h3>

                <div class="box-tools pull-right">
                    @include('backend.access.includes.partials.header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('name', trans('validation.attributes.backend.category.name'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.category.name')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('image', trans('validation.attributes.backend.category.image'), ['class' => 'col-lg-2 control-label']) }}
                    
                    <div class="col-lg-10 img-container-edit-form">                        
                        <img class="img-edit-form" src="{{ asset('/') . config('app.general_upload_folder') . '/' .  config('category.upload_path') . '/' . $category->thumbnail }}"/>
                    </div><!--col-lg-5-->
                    
                    <div class="col-lg-10 col-lg-offset-2">
                        {{ Form::file('image', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.category.image')]) }}
                    </div><!--col-lg-5-->
                </div><!--form control-->
                
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.category.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
@stop