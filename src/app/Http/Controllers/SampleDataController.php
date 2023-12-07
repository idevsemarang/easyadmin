<?php

namespace App\Http\Controllers;

use Exception;
use Idev\EasyAdmin\app\Helpers\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Idev\EasyAdmin\app\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Idev\EasyAdmin\app\Models\SampleData;
use PDF;
use Excel;
use Idev\EasyAdmin\app\Exports\DefaultExport;
use Idev\EasyAdmin\app\Helpers\Constant;
use App\Helpers\Sidebar;
use Idev\EasyAdmin\app\Imports\DefaultImport;
use Idev\EasyAdmin\app\Imports\SampleDataImport;
use Throwable;

class SampleDataController extends Controller
{
    private $title;
    private $generalUri;
    private $arrPermissions;
    private $tableHeaders;
    private $actionButtons;
    private $constGender;

    public function __construct()
    {
        $this->title = 'SampleData';
        $this->generalUri = 'sample-data';
        $this->arrPermissions = [];
        $this->actionButtons = ['btn_edit', 'btn_show', 'btn_destroy'];
        $this->constGender = [
            ['text' => 'Choose Gender', 'value' => ''],
            ['text' => 'Male', 'value' => 'Male'],
            ['text' => 'Female', 'value' => 'Female'],
        ];

        $this->tableHeaders = [
            ['name' => 'No', 'column' => '#', 'order' => true],
            ['name' => 'Name', 'column' => 'name', 'order' => true],
            ['name' => 'Age', 'column' => 'age', 'order' => true],
            ['name' => 'Gender', 'column' => 'gender', 'order' => true],
            ['name' => 'Address', 'column' => 'address', 'order' => true],
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


    private function filters()
    {

        $fields = [
            [
                'type' => 'select',
                'label' => 'Gender',
                'name' => 'gender',
                'class' => 'col-md-2',
                'options' => $this->constGender,
            ],
        ];

        return $fields;
    }


    private function defaultDataQuery()
    {
        $filters = [];
        $orThose = null;
        $orderBy = 'id';
        $orderState = 'DESC';
        if (request('search')) {
            $orThose = request('search');
        }
        if (request('order')) {
            $orderBy = request('order');
            $orderState = request('order_state');
        }
        if (request('gender')) {
            $filters[] = ['gender', '=', request('gender')];
        } 

        $dataQueries = SampleData::where($filters)
            ->where(function ($query) use ($orThose) {
                $query->where('name', 'LIKE', '%' . $orThose . '%');
                $query->orWhere('address', 'LIKE', '%' . $orThose . '%');
                $query->orWhere('gender', 'LIKE', '%' . $orThose . '%');
            })
            ->orderBy($orderBy, $orderState);

        return $dataQueries;
    }


    private function fields($mode = "create", $id = '-')
    {
        $edit = null;
        if ($id != '-') {
            $edit = SampleData::where('id', $id)->first();
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
                'type' => 'number',
                'label' => 'Age',
                'name' => 'age',
                'class' => 'col-md-12 my-2',
                'value' => (isset($edit)) ? $edit->age : ''
            ],
            [
                'type' => 'select',
                'label' => 'Gender',
                'name' => 'gender',
                'class' => 'col-md-12 my-2',
                'options' => $this->constGender,
                'value' => (isset($edit)) ? $edit->gender : ''
            ],
            [
                'type' => 'textarea',
                'label' => 'Address',
                'name' => 'address',
                'class' => 'col-md-12 my-2',
                'value' => (isset($edit)) ? $edit->address : ''
            ],
        ];

        return $fields;
    }


    public function show($id)
    {
        $data['detail'] = $this->defaultDataQuery()->where('id', $id)->first();

        return view('easyadmin::backend.idev.show-default', $data);
    }


    public function store(Request $request)
    {
        $rules = $this->rules();

        $name = $request->name;
        $gender = $request->gender;
        $address = $request->address;
        $age = $request->age;

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
            $insert = new SampleData();
            $insert->name = $name;
            $insert->gender = $gender;
            $insert->address = $address;
            $insert->age = $age;
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
            'age' => 'required|numeric',
            'name' => 'required|string',
            'gender' => 'required|string',
        ];

        return $rules;
    }

    public function edit($id)
    {
        $data['fields'] = $this->fields('edit', $id);

        return $data;
    }


    public function update(Request $request, $id)
    {
         $rules = $this->rules();
        
        $name = $request->name;
        $gender = $request->gender;
        $address = $request->address;
        $age = $request->age;

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
            $change = SampleData::where('id', $id)->first();
            $change->name = $name;
            $change->gender = $gender;
            $change->address = $address;
            $change->age = $age;
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
        SampleData::where('id', $id)->delete();

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
            ['name' => 'Access', 'column' => 'access', 'order' => true],
        ];

        DB::beginTransaction();
        try {

            $attr['fileExcel'] = $fileExcel;
            $attr['headers'] = collect($excelHeaders)->pluck('column')->toArray();
            $attr['primaryKeys'] = ['name'];
            $attr['model'] = SampleData::class;

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
}
