@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.product-catalogue.title') . ' | ' . trans('labels.backend.product-catalogue.create'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.product-catalogue.title') }}
        <small>{{ trans('labels.backend.product-catalogue.create') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.product-catalogue.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.product-catalogue.create') }}</h3>
                
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('name', trans('validation.attributes.backend.product-catalogue.name'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.product-catalogue.name')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->
                
                <div class="form-group">
                    {{ Form::label('description', trans('validation.attributes.backend.product-catalogue.description'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.product-catalogue.description')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->
                
                <div class="form-group">
                    {{ Form::label('image', trans('validation.attributes.backend.product-catalogue.image'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::file('image', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.product-catalogue.image')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->
                
                <div class="form-group">
                    {{ Form::label('status', trans('validation.attributes.backend.product-catalogue.status'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::select('status', $productCatalogue->getStatusProductCatalogue(), $productCatalogue->getDefaultStatusProductCatalogue() , ['class' => 'form-control']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->                                               
                
                <div class="form-group">
                    {{ Form::label('category_id', trans('validation.attributes.backend.product-catalogue.category_id'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::select('category_id', $arrayCategory, null, ['class' => 'form-control']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->    
                
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.product-catalogue.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
@stop
