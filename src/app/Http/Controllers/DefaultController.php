<?php

namespace Idev\EasyAdmin\app\Http\Controllers;

use Exception;
use Idev\EasyAdmin\app\Exports\DefaultExport;
use Idev\EasyAdmin\app\Http\Controllers\Controller;
use Idev\EasyAdmin\app\Helpers\Constant;
use Idev\EasyAdmin\app\Helpers\Validation;
use Idev\EasyAdmin\app\Imports\DefaultImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Throwable;

class DefaultController extends Controller
{
    protected $modelClass;
    protected $title;
    protected $generalUri;
    protected $arrPermissions = ['list','show', 'create', 'edit', 'delete', 'export-excel-default', 'export-pdf-default', 'import-excel-default'];
    protected $tableHeaders;
    protected $actionButtons;
    protected $importExcelConfig;

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

        // $permissions = (new Constant())->permissionByMenu($this->generalUri);
        $data['permissions'] = $this->arrPermissions;
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

        $layout = (request('from_ajax') && request('from_ajax') == true) ? 'easyadmin::backend.idev.list_drawer_ajax' : 'easyadmin::backend.idev.list_drawer';

        return view($layout, $data);
    }


    public function indexApi()
    {
        $permission = $this->arrPermissions;// (new Constant)->permissionByMenu($this->generalUri);
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
        $orderBy = 'id';
        $orderState = 'DESC';
        if (request('search')) {
            $orThose = request('search');
        }
        if (request('order')) {
            $orderBy = request('order');
            $orderState = request('order_state');
        }

        $dataQueries = $this->modelClass::where($filters)
            ->where(function ($query) use ($orThose) {
                $efc = ['#', 'created_at', 'updated_at', 'id'];

                foreach ($this->tableHeaders as $key => $th) {
                    if (array_key_exists('search', $th) && $th['search'] == false) {
                        $efc[] = $th['column'];
                    }
                    if(!in_array($th['column'], $efc))
                    {
                        if($key == 0){
                            $query->where($th['column'], 'LIKE', '%' . $orThose . '%');
                        }else{
                            $query->orWhere($th['column'], 'LIKE', '%' . $orThose . '%');
                        }
                    }
                }
            })
            ->orderBy($orderBy, $orderState);

        return $dataQueries;
    }


    protected function fields($mode = "create", $id = '-')
    {
        $edit = null;
        if ($id != '-') {
            $edit = $this->modelClass::where('id', $id)->first();
        }

        $fields = [];
        foreach ($this->tableHeaders as $key => $th) {
            if(!in_array($th['column'], ['#', 'created_at', 'updated_at', 'id']))
            {
                $fields[] = [
                    'type' => 'text',
                    'label' => $th['name'],
                    'name' => $th['column'],
                    'class' => 'col-md-12 my-2',
                    'required' => true,
                    'value' => (isset($edit)) ? $edit->{$th['column']} : ''
                ];
            }
        }
        return $fields;
    }


    protected function rules($id = null)
    {
        $rules = [];

        foreach ($this->tableHeaders as $key => $th) {
            if(!in_array($th['column'], ['#', 'created_at', 'updated_at', 'id']))
            {
                $rules[$th['column']] = 'required|string';
            }
        }

        return $rules;
    }


    protected function flagRules($key, $id = null)
    {
        $required = false;
        if(array_key_exists($key,$this->rules())){
            if (str_contains($this->rules()[$key], 'required')) {
                $required = true;
            }
        }

        return $required;
    }


    protected function store(Request $request)
    {
        $rules = $this->rules();

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
            $insert = new $this->modelClass();
            foreach ($this->tableHeaders as $key => $th) {
                if(!in_array($th['column'], ['#', 'created_at', 'updated_at', 'id']))
                {
                    $insert->{$th['column']} = $request[$th['column']];
                }
            }
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


    protected function show($id)
    {
        $singleData = $this->defaultDataQuery()->where('id', $id)->first();
        unset($singleData['id']);

        $data['detail'] = $singleData;

        return view('easyadmin::backend.idev.show-default', $data);
    }


    protected function edit($id)
    {
        $data['fields'] = $this->fields('edit', $id);

        return $data;
    }


    protected function update(Request $request, $id)
    {
        $rules = $this->rules();
        
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
            $change = $this->modelClass::where('id', $id)->first();
            foreach ($this->tableHeaders as $key => $th) {
                if(!in_array($th['column'], ['#', 'created_at', 'updated_at', 'id']))
                {
                    $change->{$th['column']} = $request[$th['column']];
                }
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


    protected function exportPdf()
    {
        $dataQueries = $this->defaultDataQuery()->take(1000)->get();

        $datas['title'] = $this->title;
        $datas['enable_number'] = true;
        $datas['data_headers'] = $this->tableHeaders;
        $datas['data_queries'] = $dataQueries;
        $datas['exclude_columns'] = ['id', '#'];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('easyadmin::pdf.default', $datas);

        return $pdf->stream($this->title . '.pdf');
    }


    protected function exportExcel()
    {
        $dataQueries = $this->defaultDataQuery()->get();

        $attribute['filename'] = $this->generalUri.".xlsx";
        $attribute['dataQueries'] = $dataQueries;
        $attribute['dataHeaders'] = $this->tableHeaders;

        $advExcel = new DefaultExport($attribute);

        return $advExcel->downloadExcel();
    }


    protected function importExcel(Request $request)
    {
        $fileExcel = $request->file('excel_file');

        $excelHeaders = $this->importExcelConfig['headers'];
        $primaryKeys = $this->importExcelConfig['primaryKeys'];

        DB::beginTransaction();
        try {
            $attr['fileExcel'] = $fileExcel;
            $attr['headers'] = $excelHeaders;
            $attr['primaryKeys'] = $primaryKeys;
            $attr['model'] = $this->modelClass;

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


    protected function destroy($id)
    {
        $this->modelClass::where('id', $id)->delete();

        return response()->json([
            'status' => true,
            'alert' => 'success',
            'message' => 'Data Was Deleted Successfully',
        ], 200);
    }
}
