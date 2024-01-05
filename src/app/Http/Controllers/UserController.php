<?php

namespace Idev\EasyAdmin\app\Http\Controllers;

use Exception;
use Idev\EasyAdmin\app\Helpers\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Idev\EasyAdmin\app\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use PDF;
use Idev\EasyAdmin\app\Exports\DefaultExport;
use Idev\EasyAdmin\app\Helpers\Constant;
use Idev\EasyAdmin\app\Imports\DefaultImport;
use Idev\EasyAdmin\app\Models\Role;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UserController extends Controller
{
    private $title;
    private $generalUri;
    private $arrPermissions;
    private $tableHeaders;
    private $actionButtons;

    public function __construct()
    {
        $this->title = 'User';
        $this->generalUri = 'user';
        $this->arrPermissions = [];
        $this->actionButtons = ['btn_edit', 'btn_show', 'btn_destroy'];

        $this->tableHeaders = [
            ['name' => 'No', 'column' => '#', 'order' => true],
            ['name' => 'Name', 'column' => 'name', 'order' => true],
            ['name' => 'Email', 'column' => 'email', 'order' => true],
            ['name' => 'Role', 'column' => 'role_name', 'order' => true],
            ['name' => 'Created at', 'column' => 'created_at', 'order' => true],
            ['name' => 'Updated at', 'column' => 'updated_at', 'order' => true],
        ];
    }


    public function index()
    {
        $moreActions = [
            [
                'key' => 'import-excel-default',
                'name' => 'Import Excel',
                'html_button' => "<button id='import-excel' type='button' class='btn btn-sm btn-info radius-6' href='#' data-bs-toggle='modal' data-bs-target='#modalImportDefault' title='Import Excel' ><i class='ti ti-upload'></i></button>"
            ],
            [
                'key' => 'export-excel-default',
                'name' => 'Export Excel',
                'html_button' => "<a id='export-excel' class='btn btn-sm btn-success radius-6' target='_blank' href='" . url($this->generalUri . '-export-excel-default') . "'  title='Export Excel'><i class='ti ti-cloud-download'></i></a>"
            ],
            [
                'key' => 'export-pdf-default',
                'name' => 'Export Pdf',
                'html_button' => "<a id='export-pdf' class='btn btn-sm btn-danger radius-6' target='_blank' href='" . url($this->generalUri . '-export-pdf-default') . "' title='Export PDF'><i class='ti ti-file'></i></a>"
            ],
        ];

        $permissions = (new Constant())->permissionByMenu($this->generalUri);
        $data['permissions'] = $permissions;
        $data['more_actions'] = $moreActions;
        $data['table_headers'] = $this->tableHeaders;
        $data['title'] = $this->title;
        $data['uri_key'] = $this->generalUri;
        $data['uri_list_api'] = route($this->generalUri . '.listapi');
        $data['uri_create'] = route($this->generalUri . '.create');
        $data['url_store'] = route($this->generalUri . '.store');
        $data['fields'] = $this->fields();
        $data['edit_fields'] = $this->fields();
        $data['actionButtonViews'] = [
            'easyadmin::backend.idev.buttons.delete', 
            'easyadmin::backend.idev.buttons.edit', 
            'easyadmin::backend.idev.buttons.show', 
            'easyadmin::backend.idev.buttons.import_default',
        ];
        $data['templateImportExcel'] = "#";
        $data['filters'] = $this->filters();

        $layout = (request('from_ajax') && request('from_ajax') == true) ? 'easyadmin::backend.idev.list_drawer_ajax' : 'easyadmin::backend.idev.list_drawer';

        return view($layout, $data);
    }


    private function filters()
    {
        $kjs = Role::get();

        $arrRole = [];
        $arrRole[] = ['value' => "", 'text' => "All Roles"];
        foreach ($kjs as $key => $kj) {
            $arrRole[] = ['value' => $kj->id, 'text' => $kj->name];
        }

        $fields = [
            [
                'type' => 'select',
                'label' => 'Role',
                'name' => 'role_id',
                'class' => 'col-md-2',
                'options' => $arrRole,
            ],
        ];

        return $fields;
    }


    public function indexApi()
    {
        $permission = (new Constant)->permissionByMenu($this->generalUri);
        $eb = [];
        $data_columns = [];
        foreach ($this->tableHeaders as $key => $col) {
            if ($key > 0) {
                $data_columns[] = $col['column'];
            }
        }

        foreach ($this->actionButtons as $key => $ab) {
            if (in_array(str_replace("btn_", "", $ab), $permission)) {
                $eb[] = $ab;
            }
        }

        $dataQueries = $this->defaultDataQuery()->paginate(10);

        $datas['extra_buttons'] = $eb;
        $datas['data_columns'] = $data_columns;
        $datas['data_queries'] = $dataQueries;
        $datas['data_permissions'] = $permission;
        $datas['uri_key'] = $this->generalUri;

        return $datas;
    }


    private function defaultDataQuery()
    {
        $filters = [];
        $orThose = null;
        $orderBy = 'users.id';
        $orderState = 'DESC';
        if (request('search')) {
            $orThose = request('search');
        }
        if (request('order')) {
            $orderBy = request('order');
            $orderState = request('order_state');
        }
        if (request('role_id')) {
            $filters[] = ['roles.id', '=', request('role_id')];
        } 

        $dataQueries = User::join('roles', 'roles.id', 'users.role_id')
            ->where($filters)
            ->where(function ($query) use ($orThose) {
                $query->where('users.name', 'LIKE', '%' . $orThose . '%');
                $query->orWhere('email', 'LIKE', '%' . $orThose . '%');
                $query->orWhere('roles.name', 'LIKE', '%' . $orThose . '%');
            })
            ->select('users.id', 'users.name', 'users.email', 'roles.name as role_name')
            ->orderBy($orderBy, $orderState);

        return $dataQueries;
    }


    private function fields($mode = "create", $id = '-')
    {
        $edit = null;
        if ($id != '-') {
            $edit = User::where('id', $id)->first();
        }

        $roles = Role::get();
        $arrRole = [];
        foreach ($roles as $key => $role) {
            $arrRole[] = ['value' => $role->id, 'text' => $role->name];
        }

        $fields = [
            [
                'type' => 'text',
                'label' => 'Name',
                'name' => 'name',
                'class' => 'col-md-12 my-2',
                'value' => (isset($edit)) ? $edit->name : ''
            ],
            [
                'type' => 'text',
                'label' => 'Email',
                'name' => 'email',
                'class' => 'col-md-12 my-2',
                'value' => (isset($edit)) ? $edit->email : ''
            ],
            [
                'type' => 'select',
                'label' => 'Role',
                'name' => 'role_id',
                'class' => 'col-md-12 my-2',
                'value' => (isset($edit)) ? $edit->role_id : '',
                'options' => $arrRole
            ],
            [
                'type' => 'password',
                'label' => 'Password',
                'name' => 'password',
                'class' => 'col-md-12 my-2',
                'value' => ''
            ],
        ];

        return $fields;
    }


    public function show($id)
    {
        $singleData = $this->defaultDataQuery()->where('users.id', $id)->first();
        unset($singleData['id']);
        
        $data['detail'] = $singleData;

        return view('easyadmin::backend.idev.show-default', $data);
    }


    public function create()
    {
        abort(404);

        $data['fields'] = $this->fields();
        $data['title'] = $this->title;
        $data['url_store'] = route($this->generalUri . '.store');

        return view('backend.idevcrud.create', $data);
    }

    public function store(Request $request)
    {

        $rules = $this->rules();
        $name = $request->name;
        $email = $request->email;
        $roleId = $request->role_id;
        $password = $request->password;

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messageErrors = (new Validation)->modify($validator, $rules);

            return response()->json([
                'status' => false,
                'alert' => 'danger',
                'message' => 'Required Form',
                'validation_errors' => $messageErrors,
            ], 200);
        }

        DB::beginTransaction();

        try {
            $insert = new User();
            $insert->name = $name;
            $insert->email = $email;
            $insert->role_id = $roleId;
            $insert->password = bcrypt($password);
            $insert->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'alert' => 'success',
                'message' => 'Data Was Created Successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    private function rules($id = null)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email,'.$id.',id',
            // 'email' => 'required|string|unique:users',
            'password' => 'required|string',
        ];

        if ($id != null) {
            unset($rules['password']);
        }

        return $rules;
    }

    public function edit($id)
    {
        $data['fields'] = $this->fields('edit', $id);

        return $data;
    }


    public function update(Request $request, $id)
    {
        $name = $request->name;
        $email = $request->email;
        $roleId = $request->role_id;
        $password = $request->password;

        DB::beginTransaction();
        $rules = $this->rules($id);

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messageErrors = (new Validation)->modify($validator, $rules, 'edit_');

            return response()->json([
                'status' => false,
                'alert' => 'danger',
                'message' => 'Required Form',
                'validation_errors' => $messageErrors,
            ], 200);
        }

        try {
            $change = User::where('id', $id)->first();
            $change->name = $name;
            $change->email = $email;
            $change->role_id = $roleId;
            if ($password ) {
                $change->password = bcrypt($password);
            }
            $change->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'alert' => 'success',
                'message' => 'Data Was Updated Successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return response()->json([
            'status' => true,
            'alert' => 'success',
            'message' => 'Data Was Deleted Successfully',
        ], 200);
    }


    public function exportPdf()
    {
        $dataQueries = $this->defaultDataQuery()->take(1000)->get();

        $datas['title'] = $this->title;
        $datas['enable_number'] = true;
        $datas['data_headers'] = $this->tableHeaders;
        $datas['data_queries'] = $dataQueries;
        $datas['exclude_columns'] = ['id', '#'];

        $pdf = PDF::loadView('easyadmin::pdf.default', $datas);

        return $pdf->stream($this->title . '.pdf');
    }


    public function exportExcel()
    {
        $dataQueries = $this->defaultDataQuery()->get();

        $attribute['filename'] = $this->generalUri.".xlsx";
        $attribute['dataQueries'] = $dataQueries;
        $attribute['dataHeaders'] = $this->tableHeaders;

        $advExcel = new DefaultExport($attribute);

        return $advExcel->downloadExcel();
    }


    public function importExcel(Request $request)
    {
        $fileExcel = $request->file('excel_file');

        $excelHeaders = [
            ['name' => 'Name', 'column' => 'name', 'order' => true],
            ['name' => 'Email', 'column' => 'email', 'order' => true],
        ];

        DB::beginTransaction();
        try {

            $attr['fileExcel'] = $fileExcel;
            $attr['headers'] = collect($excelHeaders)->pluck('column')->toArray();
            $attr['primaryKeys'] = ['email'];
            $attr['model'] = User::class;

            $spoutImport = new DefaultImport($attr);
            $spoutImport->import();

            DB::commit();

            return response()->json([
                'status' => true,
                'alert' => 'success',
                'message' => 'Data Was Import Successfully',
                'redirect_to' => route($this->generalUri . '.index'),
                'validation_errors' => []
            ], 200);
        } catch (Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'alert' => 'danger',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function profile()
    {
        $edit = User::where('id', Auth::user()->id)->first();

        $fields = [
            [
                'type' => 'text',
                'label' => 'Name',
                'name' => 'name',
                'class' => 'col-md-12 my-2',
                'value' => (isset($edit)) ? $edit->name : ''
            ],
            [
                'type' => 'text',
                'label' => 'Email',
                'name' => 'email',
                'class' => 'col-md-12 my-2',
                'value' => (isset($edit)) ? $edit->email : ''
            ],
            [
                'type' => 'password',
                'label' => 'Password',
                'name' => 'password',
                'class' => 'col-md-12 my-2',
                'value' => ''
            ],
        ];

        $data['title'] = $this->title;
        $data['uri_key'] = $this->generalUri;
        $data['fields'] = $fields;

        return view('easyadmin::backend.idev.myaccount', $data);
    }


    public function updateProfile(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $id = Auth::user()->id;

        DB::beginTransaction();
        $rules = $this->rules($id);

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messageErrors = (new Validation)->modify($validator, $rules, 'edit_');

            return response()->json([
                'status' => false,
                'alert' => 'danger',
                'message' => 'Required Form',
                'validation_errors' => $messageErrors,
            ], 200);
        }

        try {
            $change = User::where('id', $id)->first();
            $change->name = $name;
            $change->email = $email;
            if ($password ) {
                $change->password = bcrypt($password);
            }
            $change->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'alert' => 'success',
                'message' => 'Data Was Updated Successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}