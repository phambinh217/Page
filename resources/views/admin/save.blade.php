@extends('Cms::layouts.default',[
	'active_admin_menu' 	=> ['page', isset($page_id) ? 'page.index' : 'page.create'],
	'breadcrumbs' 			=> [
		'title'	=> ['Trang tĩnh', isset($page_id) ? 'Chỉnh sửa' : 'Thêm mới'],
		'url'	=> [
			admin_url('page')
		],
	],
])

@section('page_title', isset($page_id) ? 'Chỉnh sửa trang tĩnh' : 'Thêm trang tĩnh mới')

@if(isset($page_id))
    @section('page_sub_title', $page->title)
    @can('admin.page.create')
        @section('tool_bar')
            <a href="{{ route('admin.page.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> <span class="hidden-xs">Thêm trang tĩnh mới</span>
            </a>
        @endsection
    @endcan
@endif

@section('content')
    <div class="portlet light bordered form-fit">
        <div class="portlet-title with-tab">
            <div class="tab-default">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#page-content" data-toggle="tab" aria-expanded="true"> Nội dung </a>
                    </li>
                    <li class="">
                        <a href="#page-data" data-toggle="tab" aria-expanded="false"> Dữ liệu </a>
                    </li>
                    <li class="">
                        <a href="#page-seo" data-toggle="tab" aria-expanded="false"> SEO </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="portlet-body form">
            <form ajax-form-container method="post" action="{{ isset($page_id) ? admin_url('page/' . $page->id) : admin_url('page') }}" class="form-horizontal form-bordered form-row-stripped">
                @if(isset($page_id))
                    <input type="hidden" name="_method" value="PUT" />
                @endif
                {{ csrf_field() }}
                <div class="form-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="page-content">
                            <div class="form-group">
                                <label class="control-label col-sm-2">Tên tin</label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-sm-6">
                                        <input value="{{ $page->title }}" name="page[title]" type="text" class="form-control" />
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" name="page[slug]" value="{{ $page->slug }}" placeholder="Slug" class="form-control str-slug" value="{{ $page->slug or '' }}" />
                                            <label class="checkbox-inline">
                                                <input type="checkbox" value="true" checked="" id="create-slug">
                                                Từ tên tin
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">
                                    Nội dung<span class="required">*</span>
                                </label>
                                <div class="col-md-10">
                                    <textarea name="page[content]" class="form-control texteditor">{{ $page->content }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="page-data">
                            <div class="form-group media-box-group">
                                <label class="control-label col-md-2">
                                    Thumbnail
                                </label>
                                <div class="col-sm-10">
                                    @include('Cms::components.form-chose-media', [
                                        'name'              => 'page[thumbnail]',
                                        'value'             => old('page.thumbnail', $page->thumbnail),
                                        'url_image_preview' => old('page.thumbnail', thumbnail_url($page->thumbnail, ['width' => '100', 'height' => '100']))
                                    ])
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2">
                                    Trạng thái <span class="required">*</span>
                                </label>
                                <div class="col-sm-10">
                                    @include('Page::admin.components.form-select-status', [
                                        'statuses' => $page->getStatusAble(),
                                        'class' => 'width-auto',
                                        'name' => 'page[status]',
                                        'selected' => $page->status_slug,
                                    ])
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="page-seo">
                            <div class="form-group">
                                <label class="control-label col-md-2">
                                    Meta title
                                </label>
                                <div class="col-md-10">
                                    <input type="text" name="page[meta_title]" class="form-control" value="{{ $page->meta_title }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">
                                    Meta description
                                </label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="page[meta_description]">{{ $page->meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">
                                    Meta keyword
                                </label>
                                <div class="col-md-10">
                                    <input type="text" name="page[meta_keyword]" class="form-control" value="{{ $page->meta_keyword }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions util-btn-margin-bottom-5">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-9">
                            @if(!isset($page_id))
                                @include('Cms::components.btn-save-new')
                            @else
                                @include('Cms::components.btn-save-out')
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{ asset_url('admin', 'global/plugins/bootstrap-toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset_url('admin', 'global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('js_footer')
    <script type="text/javascript" src="{{ asset_url('admin', 'global/plugins/jquery-form/jquery.form.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset_url('admin', 'global/plugins/bootstrap-toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset_url('admin', 'global/plugins/tinymce/tinymce.min.js')}} "></script>
    <script type="text/javascript">
        $(function(){
            $('#create-slug').click(function() {
                if(this.checked) {
                    var title = $('input[name="page[title]"]').val();
                    var slug = strSlug(title);
                    $('input[name="page[slug]"]').val(slug);
                }
            });

            $('input[name="page[title]"]').keyup(function() {
                if ($('#create-slug').is(':checked')) {
                    var title = $(this).val();
                    var slug = strSlug(title);
                    $('input[name="page[slug]"]').val(slug); 
                }
            });
        });
    </script>
@endpush