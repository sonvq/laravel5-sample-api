@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.corporate-deck.title'))

@section('after-styles-end')
    {{ Html::style("css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
@stop

@section('page-header')
    <h1>
        {{ trans('labels.backend.corporate-deck.title') }}        
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.corporate-deck.title_list') }}</h3>

            <div class="box-tools pull-right">
                {{ link_to_route('admin.corporate-deck.create', trans('labels.backend.corporate-deck.add_new'), [], ['class' => 'btn btn-primary btn-xs']) }}
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="corporate-deck-table" class="table table-condensed table-hover table-middle-align">
                    <thead>
                        <tr>                  
                            <th>{{ trans('labels.backend.corporate-deck.table.id') }}</th>
                            <th>{{ trans('labels.backend.corporate-deck.table.image') }}</th>
                            <th>{{ trans('labels.backend.corporate-deck.table.pdf') }}</th>
                            <th>{{ trans('labels.backend.corporate-deck.table.user_id') }}</th>
                            <th>{{ trans('labels.backend.corporate-deck.table.name') }}</th>
                            <th>{{ trans('labels.backend.corporate-deck.table.status') }}</th>
                            <th>{{ trans('labels.backend.corporate-deck.table.description') }}</th>
                            <th>{{ trans('labels.general.actions') }}</th>
                        </tr>
                    </thead>
                </table>
            </div><!--table-responsive-->
        </div><!-- /.box-body -->
    </div><!--box-->

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('history.backend.recent_history') }}</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div><!-- /.box tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            {!! history()->renderType('CorporateDeck') !!}
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@stop

@section('after-scripts-end')
    {{ Html::script("js/backend/plugin/datatables/jquery.dataTables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables.bootstrap.min.js") }}

    <script>
        $(function() {
            $('#corporate-deck-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.corporate-deck.get") }}',
                    type: 'get',
                    data: {status: 1, trashed: false}
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'thumbnail', name: 'thumbnail', render: function(data, type, row) { return '<img class="image-datatable" src="' + data + '" />'; }},
                    {data: 'pdf', name: 'pdf', render: function(data, type, row) { return '<i class="fa fa-download"></i> <a download href="' + data + '">Download</a>'; }},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'name', name: 'name'},
                    {data: 'status', name: 'status'},
                    {data: 'description', name: 'description'},
                    {data: 'actions', name: 'actions'}
                ],
                order: [[0, "asc"]],
                searchDelay: 500
            });
        });
    </script>
@stop