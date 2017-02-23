@extends('Cms::layouts.default', [
	'active_admin_menu'	=> ['page', 'page.index'],
	'breadcrumbs' 		=> [
		'title'	=>	['Trang tĩnh', 'Danh sách'],
		'url'	=>	[
			admin_url('page'),
			admin_url('page'),
		],
	],
])

@section('page_title', 'Tất cả trang tĩnh')

@section('tool_bar')
	@can('admin.page.create')
		<a href="{{ route('admin.page.create') }}" class="btn btn-primary">
			<i class="fa fa-plus"></i> <span class="hidden-xs">Thêm trang tĩnh mới</span>
		</a>
	@endcan
@endsection

@section('content')
	<div class="table-function-container">
	   	<div class="portlet light bordered">
		    <div class="portlet-title">
		        <div class="caption">
		            <i class="fa fa-filter"></i> Bộ lọc kết quả
		        </div>
		    </div>
		    <div class="portlet-body form">
		        <form class="form-horizontal form-bordered form-row-stripped">
		            <div class="form-body">
		                <div class="row">
		                    <div class="col-sm-6 md-pr-0">
		                    	<div class="form-group">
		                            <label class="control-label col-md-3">Từ khóa</label>
		                            <div class="col-md-9">
		                                 <input type="text" class="form-control" name="_keyword" value="{{ $filter['_keyword'] or '' }}" />
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <label class="control-label col-md-3">Trạng thái</label>
		                            <div class="col-md-9">
		                            	@include('Page::admin.components.form-select-status', [
					                        'statuses' => Phambinh\Page\Page::statusAble(),
					                        'name' => 'status',
					                        'selected' => isset($filter['status']) ? $filter['status'] : null,
					                    ])
		                            </div>
		                        </div>
		                    </div>
		                    <div class="col-sm-6 md-pl-0">
		                        <div class="form-group">
		                            <label class="control-label col-md-3">Tác giả</label>
		                            <div class="col-md-9">
		                                @include('Cms::components.form-find-user', [
		                            		'name' => 'author_id',
		                            		'selected' => isset($filter['author_id']) ? $filter['author_id'] : '0',
		                            	])
		                            </div>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <div class="form-actions util-btn-margin-bottom-5">
		                <div class="row">
		                    <div class="col-md-12 text-right">
		                        <button type="submit" class="btn btn-primary">
		                            <i class="fa fa-filter"></i> Lọc
								</button>
		                        <a href="{{ admin_url('page') }}" class="btn default accordion-toggle">
		                            <i class="fa fa-times"></i> Hủy
		                        </a>
		                    </div>
		                </div>
		            </div>
		        </form>
		    </div>
		</div>
	    <div class="row table-above">
		    <div class="col-sm-6">
		    	<div class="form-inline mb-10">
			    	@include('Cms::components.form-apply-action', [
			    		'actions' => [
			    			['action' => '', 'name' => ''],
			    			['action' => '', 'name' => ''],
			    			['action' => '', 'name' => ''],
			    		],
			    	])
			    </div>
		    </div>
		    <div class="col-sm-6 text-right">
		    	{!! $pages->appends($filter)->render() !!}
		    </div>
	    </div>
	    <div class="note note-success">
	        <p><i class="fa fa-info"></i> Tổng số {{ $pages->total() }} kết quả</p>
	    </div>
	    <div class="table-responsive main">
			<table class="master-table table table-striped table-hover table-checkable order-column">
				<thead>
					<tr>
						<th width="50" class="table-checkbox text-center">
							<div class="checker">
								<input type="checkbox" class="icheck check-all">
							</div>
						</th>
						<th width="50" class="text-center">
							{!! \Phambinh\Page\Page::linkSort('ID', 'id') !!}
						</th>
						<th>
							{!! \Phambinh\Page\Page::linkSort('Tên trang tĩnh', 'title') !!}
						</th>
						<th>
							Tác giả
						</th>
						<th>
							Ngày tạo
						</th>
						<th class="text-center">Thao tác</th>
					</tr>
				</thead>
				<tbody>
					@foreach($pages as $page_item)
					<tr class="odd gradeX hover-display-container {{ $page_item->statusHtmlClass() }}">
						<td width="50" class="table-checkbox text-center">
							<div class="checker">
								<input type="checkbox" class="icheck" value="{{ $page_item->id }}">
							</div>
						</td>
						<td class="text-center">
							<strong>{{ $page_item->id }}</strong>
						</td>
						<td>
							@can('admin.page.edit', $page_item)
								<a href="{{ route('admin.page.edit', ['id' => $page_item->id]) }}">
									<strong>{{ $page_item->title }}</strong>
								</a>
							@endcan
							@cannot('admin.page.edit', $page_item)
								<strong>{{ $page_item->title }}</strong>
							@endcannot
						</td>
						<td>
							{{ $page_item->author->full_name }}
						</td>
						<td>
							{{ text_time_difference($page_item->created_at) }}
						</td>

						<td>
							<div class="btn-group pull-right" table-function>
								<a href="" class="btn btn-circle btn-xs grey-salsa btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
									<span class="hidden-xs">
										Chức năng
										<span class="fa fa-angle-down"> </span>
									</span>
									<span class="visible-xs">
										<span class="fa fa-cog"> </span>
									</span>
		                        </a>
		                        <ul class="dropdown-menu pull-right">
		                            <li><a href="{{ route('admin.page.show', ['id' => $page_item->id]) }}"><i class="fa fa-eye"></i> Xem</a></li>

		                            <li role="presentation" class="divider"> </li>
		                            
		                            @can('admin.page.edit', $page_item)
			                            <li><a href="{{ route('admin.page.edit',['id' => $page_item->id]) }}"><i class="fa fa-pencil"></i> Sửa</a></li>
			                        @endcan
		                        	
		                        	@can('admin.page.disable', $page_item)
			                        	@if($page_item->isEnable())
			                        		<li><a data-function="disable" data-method="put" href="{{ route('admin.page.disable', ['id' => $page_item->id]) }}"><i class="fa fa-recycle"></i> Xóa tạm</a></li>
			                        	@endif
		                        	@endcan
	
		                            @if($page_item->isDisable())
		                        		@can('admin.page.enable', $page_item)
		                            		<li><a data-function="enable" data-method="put" href="{{ route('admin.page.enable', ['id' => $page_item->id]) }}"><i class="fa fa-recycle"></i> Khôi phục</a></li>
		                            		<li role="presentation" class="divider"></li>
		                            	@endcan

		                            	@can('admin.page.destroy', $page_item)
		                            		<li><a data-function="destroy" data-method="delete" href="{{ route('admin.page.destroy', ['id' => $page_item->id]) }}"><i class="fa fa-times"></i> Xóa</a></li>
		                            	@endcan
		                        	@endif
		                        </ul>
		                    </div>
						</td>

					</tr>
					@endforeach
				</tbody>
			</table>  
		</div>
	</div>
@endsection

@push('css')
	<link href="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ url('assets/admin/global/plugins/icheck/skins/all.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js_footer')
	<script type="text/javascript" src="{{ url('assets/admin/global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
	<script type="text/javascript" src="{{ url('assets/admin/global/plugins/icheck/icheck.min.js')}} "></script>
@endpush