<?php

namespace Phambinh\Page\Http\Controllers\Admin;

use Illuminate\Http\Request;
use AdminController;
use Validator;
use Phambinh\Page\Page;

class PageController extends AdminController
{
    public function index()
    {
        $filter = Page::getRequestFilter();
        $this->data['filter'] = $filter;
        $this->data['pages'] = Page::ofQuery($filter)->with('author')->paginate($this->paginate);

        \Metatag::set('title', 'Tất cả trang tĩnh');
        return view('Page::admin.list', $this->data);
    }

    public function create()
    {
        \Metatag::set('title', 'Thêm trang tĩnh mới');

        $page = new Page();
        $this->data['page'] = $page;
        
        return view('Page::admin.save', $this->data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'page.title'            =>    'required|max:255',
            'page.content'            =>    'min:0',
            'page.status'            =>    'required|in:enable,disable',
        ]);

        $page = new Page();

        $page->fill($request->page);
        
        switch ($page->status) {
            case 'disable':
                $page->status = '0';
                break;

            case 'enable':
                $page->status = '1';
                break;
        }

        if (!empty($page->slug)) {
            $page->slug = str_slug($page->title);
        }

        $page->author_id = \Auth::user()->id;
        $page->save();

        if ($request->ajax()) {
            return response()->json([
                'title'        =>    'Thành công',
                'message'    =>    'Đã thêm trang tĩnh mới',
                'redirect'    =>    isset($request->save_only) ?
                    route('admin.page.edit', ['id' => $page->id]) :
                    route('admin.page.create'),
            ], 200);
        }

        if (isset($request->save_only)) {
            return redirect()->route('admin.page.edit', ['id' => $page->id]);
        }

        return redirect()->route('admin.page.create');
    }
    
    public function edit(Page $page)
    {
        $this->data['page_id'] = $page->id;
        $this->data['page']    = $page;

        \Metatag::set('title', 'Chỉnh sửa trang tĩnh');
        return view('Page::admin.save', $this->data);
    }

    public function update(Request $request, Page $page)
    {
        $this->validate($request, [
            'page.title'            =>    'required|max:255',
            'page.content'        =>    'min:0',
            'page.status'            =>    'required|in:enable,disable',
        ]);

        $page->fill($request->page);

        switch ($page->status) {
            case 'disable':
                $page->status = '0';
                break;

            case 'enable':
                $page->status = '1';
                break;
        }

        if (!empty($page->slug)) {
            $page->slug = str_slug($page->title);
        }
        
        $page->save();

        if ($request->ajax()) {
            $response = [
                'title'        =>    'Thành công',
                'message'    =>    'Cập nhật tin thành công',
            ];
            if (isset($request->save_and_out)) {
                $response['redirect'] = admin_url('page');
            }

            return response()->json($response, 200);
        }
        
        if (isset($request->save_and_out)) {
            return redirect(admin_url('page'));
        }
                
        return redirect()->back();
    }

    public function disable(Request $request, Page $page)
    {
        $page->status = '0';
        $page->save();
        if ($request->ajax()) {
            return response()->json([
                'title'            =>    'Thành công',
                'message'        =>    'Đã ẩn tin',
            ], 200);
        }

        return redirect()->back();
    }

    public function enable(Request $request, Page $page)
    {
        $page->status = '1';
        $page->save();
        if ($request->ajax()) {
            return response()->json([
                'title'            =>    'Thành công',
                'message'        =>    'Đã công khai tin',
            ], 200);
        }

        return redirect()->back();
    }

    public function destroy(Request $request, Page $page)
    {
        $page->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'title'            =>    'Thành công',
                'message'        =>    'Đã xóa tin',
            ], 200);
        }

        return redirect()->back();
    }
}
