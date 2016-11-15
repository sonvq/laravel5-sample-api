@extends ('backend.layouts.master')

@section ('title', trans('labels.backend.product-catalogue.title'))

@section('after-styles-end')
    {{ Html::style("css/backend/plugin/datatables/dataTables.bootstrap.min.css") }}
@stop

@section('page-header')
    <h1>
        {{ trans('labels.backend.product-catalogue.title') }}        
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">{{ trans('labels.backend.product-catalogue.title_list') }}</h3>

            <div class="box-tools pull-right">
                {{ link_to_route('admin.product-catalogue.create', trans('labels.backend.product-catalogue.add_new'), [], ['class' => 'btn btn-primary btn-xs']) }}
            </div><!--box-tools pull-right-->
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="table-responsive">
                <table id="product-catalogue-table" class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.product-catalogue.table.id') }}</th>
                            <th>{{ trans('labels.backend.product-catalogue.table.image') }}</th>
                            <th>{{ trans('labels.backend.product-catalogue.table.user_id') }}</th>
                            <th>{{ trans('labels.backend.product-catalogue.table.name') }}</th>
                            <th>{{ trans('labels.backend.product-catalogue.table.category_name') }}</th>
                            <th>{{ trans('labels.backend.product-catalogue.table.status') }}</th>
                            <th>{{ trans('labels.backend.product-catalogue.table.description') }}</th>
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
            {!! history()->renderType('ProductCatalogue') !!}
        </div><!-- /.box-body -->
    </div><!--box box-success-->
@stop

@section('after-scripts-end')
    {{ Html::script("js/backend/plugin/datatables/jquery.dataTables.min.js") }}
    {{ Html::script("js/backend/plugin/datatables/dataTables.bootstrap.min.js") }}

    <script>
        $(function() {
            $('#product-catalogue-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.product-catalogue.get") }}',
                    type: 'get',
                    data: {status: 1, trashed: false}
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'thumbnail', name: 'thumbnail', render: function(data, type, row) { return '<img class="image-datatable" src="' + data + '" />'; }},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'name', name: 'name'},
                    {data: 'category_name', name: 'category_name'},
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