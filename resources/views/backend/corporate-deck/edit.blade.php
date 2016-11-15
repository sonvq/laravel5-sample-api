@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.corporate-deck.management') . ' | ' . trans('labels.backend.corporate-deck.edit'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.corporate-deck.management') }}
        <small>{{ trans('labels.backend.corporate-deck.edit') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::model($corporate_deck, ['route' => ['admin.corporate-deck.update', $corporate_deck], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'files' => true]) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.corporate-deck.edit') }}</h3>

                <div class="box-tools pull-right">
                    @include('backend.access.includes.partials.header-buttons')
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->

            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('name', trans('validation.attributes.backend.corporate-deck.name'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.corporate-deck.name')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->

                <div class="form-group">
                    {{ Form::label('description', trans('validation.attributes.backend.corporate-deck.description'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.corporate-deck.description')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->
                
                <div class="form-group">
                    {{ Form::label('status', trans('validation.attributes.backend.corporate-deck.status'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::select('status', $corporate_deck->getStatusCorporateDeck(), $corporate_deck->status , ['class' => 'form-control']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->  
                
                <div class="form-group">
                    {{ Form::label('pdf', trans('validation.attributes.backend.corporate-deck.pdf'), ['class' => 'col-lg-2 control-label']) }}
                    
                    <div class="col-lg-10 img-container-edit-form">                        
                        <img class="img-edit-form" src="{{ asset('/') . config('app.general_upload_folder') . '/' .  config('corporate-deck.pdf_upload_path') . '/' . $corporate_deck->thumbnail }}"/>
                    </div><!--col-lg-5-->
                    
                    <div class="col-lg-10 col-lg-offset-2">
                        {{ Form::file('pdf', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.corporate-deck.pdf')]) }}
                    </div><!--col-lg-5-->
                </div><!--form control-->                                                                                                              
                
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-success">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.corporate-deck.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.update'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
@stop