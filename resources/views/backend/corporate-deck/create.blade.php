@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.corporate-deck.title') . ' | ' . trans('labels.backend.corporate-deck.create'))

@section('page-header')
    <h1>
        {{ trans('labels.backend.corporate-deck.title') }}
        <small>{{ trans('labels.backend.corporate-deck.create') }}</small>
    </h1>
@endsection

@section('content')
    {{ Form::open(['route' => 'admin.corporate-deck.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) }}

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{ trans('labels.backend.corporate-deck.create') }}</h3>
                
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
                        {{ Form::select('status', $corporateDeck->getStatusCorporateDeck(), $corporateDeck->getDefaultStatusCorporateDeck() , ['class' => 'form-control']) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->               
                
                <div class="form-group">
                    {{ Form::label('pdf', trans('validation.attributes.backend.corporate-deck.pdf'), ['class' => 'col-lg-2 control-label']) }}

                    <div class="col-lg-10">
                        {{ Form::file('pdf', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.backend.corporate-deck.pdf')]) }}
                    </div><!--col-lg-10-->
                </div><!--form control-->
                
            </div><!-- /.box-body -->
        </div><!--box-->

        <div class="box box-info">
            <div class="box-body">
                <div class="pull-left">
                    {{ link_to_route('admin.corporate-deck.index', trans('buttons.general.cancel'), [], ['class' => 'btn btn-danger btn-xs']) }}
                </div><!--pull-left-->

                <div class="pull-right">
                    {{ Form::submit(trans('buttons.general.crud.create'), ['class' => 'btn btn-success btn-xs']) }}
                </div><!--pull-right-->

                <div class="clearfix"></div>
            </div><!-- /.box-body -->
        </div><!--box-->

    {{ Form::close() }}
@stop
