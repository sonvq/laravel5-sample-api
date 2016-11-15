<?php

namespace App\Http\Controllers\Api\V1\Checklist;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\V1\BaseController;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use App\Repositories\Api\V1\Checklist\EloquentChecklistRepository;
use App\Models\Checklist;
use App\Common\Helper;

class ChecklistController extends BaseController {

    public function index() {
        $eloquentChecklistRepository = new EloquentChecklistRepository();
        
        $query = $this->processInput();                
        
        $checkList = $eloquentChecklistRepository->getAll($query['where'], $query['sort'], $query['limit'], $query['offset'], $query['fields']);
        
        $checkListCount = $eloquentChecklistRepository->getCount($query['where'], $query['fields']);
        
        $result = [];
        $result['items'] = $checkList;
        $result['page_size'] = $query['limit'];
        $result['page_total'] = $checkListCount;
        
        return Helper::okResponse($result); 
    }

    public function getDroplist()
    {
        $droplist = [
            'LABEL_SIZE_TYPE' => Checklist::LABEL_SIZE_TYPE,
            'VDP_BY_CUST' => Checklist::VDP_BY_CUST,
            'VARMISH_TEXTURE' => Checklist::VARMISH_TEXTURE,
            'LAMINATION' => Checklist::LAMINATION,
            'BARCODE_REQUIRED' => Checklist::BARCODE_REQUIRED,
            'BARCODE_TYPE' => Checklist::BARCODE_TYPE,
            'FACESTOCK_APPEARANCE' => Checklist::FACESTOCK_APPEARANCE,
            'FACESTOCK_TYPES' => Checklist::FACESTOCK_TYPES,
            'FACESTOCK_COLOR' => Checklist::FACESTOCK_COLOR,
            'FLUORESCENT' => Checklist::FLUORESCENT,
            'ADHESIVE' => Checklist::ADHESIVE,
            'PACKING_TYPE' => Checklist::PACKING_TYPE,
            'PACKING_SIZE' => Checklist::PACKING_SIZE,
            'CORE_MATERIAL' => Checklist::CORE_MATERIAL,
            'CORE_SIZE' => Checklist::CORE_SIZE,
            'DURATION_BOILING_SUBMERGE' => Checklist::DURATION_BOILING_SUBMERGE,
            'MATERIAL' =>  Checklist::MATERIAL,
            'SURFACE'  =>  Checklist::SURFACE,
            'SHAPE' =>  Checklist::SHAPE,
            'ISO_STANDARD'  =>  Checklist::ISO_STANDARD
        ];

        return Helper::okResponse($droplist);
    }

    /*
     * Add checklist
     */

    public function store(Request $request) {

        $method = $request->method();
        //$input1 = $request->all();

        if ($request->isMethod('post')) 
        {
            //$content =   $request->getContent();

            $rawData = file_get_contents("php://input");
            $input = (array) json_decode($rawData);

            //$input1 = $request->all(); var_dump($input1);
            //$input =  json_decode($content);
            //var_dump($input);die;
            //$input = $request->all();
            $eloquentChecklistRepository = new EloquentChecklistRepository();

            $validator = Validator::make($input, $eloquentChecklistRepository->getCreateRules($input));

            if ($validator->passes()) {
                $checklist = Checklist::saveData($input);
                if ($checklist) {
                    return Helper::okResponse($checklist);
                } else {
                    return Helper::internalServerErrorResponse(Helper::INTERNAL_SERVER_ERROR, Helper::INTERNAL_SERVER_ERROR_MSG);
                }
            } else {
                return Helper::validationErrorResponse(Helper::VALIDATION_ERROR, Helper::VALIDATION_ERROR_MSG, $validator->messages()->toArray());
            }
        }
        else
        {
            return Helper::badRequestResponse(Helper::METHOD_NOT_FOUND);
        }

    }

    public function update(Request $request) {
        //$input = $request->all();
        $method = $request->method();

        if ($request->isMethod('post')) 
        {
            $rawData = file_get_contents("php://input");
            $input = (array) json_decode($rawData);

            $id = isset($input['cntID']) ? $input['cntID'] : '';

            $checklist = Checklist::find($id);

            if ($checklist) {

                $eloquentChecklistRepository = new EloquentChecklistRepository();

                $validator = Validator::make($input, $eloquentChecklistRepository->getCreateRules($input));

                if ($validator->passes()) {

                    $checklist = Checklist::saveData($input, 'update');

                    if ($checklist) {
                        $checklist["strChecklistID"] = "#" . $checklist["strChecklistID"];
                        return Helper::okResponse($checklist);
                    } else {
                        return Helper::internalServerErrorResponse(Helper::INTERNAL_SERVER_ERROR, Helper::INTERNAL_SERVER_ERROR_MSG);
                    }
                } else {
                    return Helper::validationErrorResponse(Helper::VALIDATION_ERROR, Helper::VALIDATION_ERROR_MSG, $validator->messages()->toArray());
                }
            } else {
                return Helper::notFoundErrorResponse(Helper::CHECKLIST_NOT_FOUND, Helper::CHECKLIST_NOT_FOUND_MSG);
            }
        }
        else
        {
             return Helper::badRequestResponse(Helper::METHOD_NOT_FOUND);
        }

    }

}
