<?php

namespace App\Repositories\Api\V1\Checklist;

use App\Models\Checklist;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Api\V1\EloquentBaseRepository;

/**
 * Class EloquentChecklistRepository
 * @package App\Repositories\Api\V1\Quotation;
 */
class EloquentChecklistRepository extends EloquentBaseRepository {

    protected $table = 'tblChecklist';
    protected $model = \App\Models\Checklist::class;
    protected $connection_store = 'erp_temp';
    protected $connection_index = 'erp_temp';
    protected $primaryKey = 'cntID';

    protected function onPreQuery(\Illuminate\Database\Query\Builder $query, &$where = null, &$sort = null, &$fields = array()) {

        $query->addSelect('ct.strCustomerID as ct_strCustomerID', 'ct.strCompany', 'ct.strIndustrialCode' 
            //'ccm.cntID as ccm_cntID', 'ccm.strTitle as ccm_strTitle'
             ); 
        
        $query->leftJoin('tblCustomer as ct', function($join) {
            $join->on('ct.strCustomerID', '=', 'r.strCustomerID');
        });

        /*$query->leftJoin('tblCustomerContactMethod as ccm', function($join) use ($query) {
            $join->on('ccm.strCustomerID', '=', 'r.strCustomerID');
            
        });*/
        
        $query->distinct();
        
        if (isset($where['syncDate']) && $where['syncDate'] && is_numeric($where['syncDate'])) {
            $syncDate = $where['syncDate'];
            $query->where(function ($query) use ($syncDate) {
                $query->where('r.dtmDateCreated', '>=', date("Y-m-d H:i:s", $syncDate));
                $query->orWhere('r.dtmDateEdit', '>=', date("Y-m-d H:i:s", $syncDate));
            });
            unset($where['syncDate']);
        }
        
        // Search by company Name 
        if (isset($where['strCompany'])) {
            $query->where('ct.strCompany', 'like', $where['strCompany'] . '%');
            unset($where['strCompany']);
        }
        
        if (isset($where['strChecklistID'])) {
            $query->where('r.strChecklistID', 'like', $where['strChecklistID'] . '%');
            unset($where['strChecklistID']);
        }
        
        if (isset($where['strStatus'])) {
            $query->where('r.strStatus', '=', $where['strStatus']);
            unset($where['strStatus']);
        }
        
        if (isset($where['strDescriptionPart'])) {
            $query->where('r.strDescriptionPart', 'like', '%' . $where['strChecklistID'] . '%');
            unset($where['strDescriptionPart']);
        }
        
        // Missing meeting_date
        if (isset($where['meeting_date'])) 
        {

        }

    }

    public function getCreateRules() {
        return array(
            'strCustomerID' => "required|exists:$this->connection_store.tblCustomer,strCustomerID",
            'strContactID' => "required|exists:$this->connection_store.tblCustomerContactMethod,cntID",
            'strIndustryID' => 'required',
            'strDescriptionPart' => 'required',
            'dblAnnualForecast' => 'numeric',
            'strQuotationQuantity' => 'required|numeric',
            'dblWidthLabelSize' => 'required|numeric',
            'dblHeightLabelSize' => 'required|numeric',
            'dblPerforation' => 'required|numeric',
            'dblComputerPunch' => 'required|numeric',
            'dblActualSample' => 'required|numeric',
            'dblArtWorkFile' => 'required|numeric',
            'dblDrawing' => 'required|numeric',
            'dblCosmetic' => 'required|numeric',
            'strVDPByCustID' => 'required',
            'dblVDPHonsen' => 'required|numeric',
            'dblNosOfColors' => 'required|numeric',
            'strSpecialColor' => 'required',
            'strVamishTextureID' => 'required',
            'strLaminationID' => 'required',
            'strBarcodeRequiredID' => 'required',
            'strBarcodeTypeID' => 'required',
            'strFacestockAppearanceID' => 'required',
            'strFacestockColorID' => 'required',
            'strFluorescentID' => 'required',
            'strAdhesiveID' => 'required',
            'dblPatternAdhesiveID' => 'required|numeric',
            'strPackingTypeID' => 'required',
            'strCoreMaterialID' => 'required',
            'strCoreSizeID' => 'required',
            'dblApplicationTemperature' => 'required|numeric',
            'dblServiceTemperature' => 'required|numeric',
            'dblWaterResistance' => 'required|numeric',
            'dblBoiling' => 'required|numeric',
            'dblSubmerge' => 'required|numeric',
            'dblControlRoom' => 'required|numeric',
            'dblLightFastness' => 'required|numeric',
            'dblTemperatureForBoiling' => 'required|numeric',
            'dblDurationForBoiling' => 'required|numeric',
            'strDurationForBoilingTypeTime' => 'required',
            'dblTemperatureForSubmerge' => 'required|numeric',
            'dblDurationForSubmerge' => 'required|numeric',
            'strDurationForSubmergeTypeTime' => 'required',
            'dblESDCompliance' => 'required|numeric',
            'dblAutoApplicator' => 'required|numeric',
            'dblSpeed' => 'numeric',
            'strMaterialID' => 'required',
            'dblSubstrateSampleForMaterial' => 'required|numeric',
            'strOtherForMaterial' => 'required',
            'strSurfaceID' => 'required',
            'strShapeID' => 'required',
            'dblSubstrateSampleForShape' => 'required|numeric',
            'strOtherForShape' => 'required',
            'dblSqueezable' => 'required|numeric',
            'dblRigid' => 'required|numeric',
            'dblQC' => 'required|numeric',
            'dblMaterialCleaningRequire' => 'required|numeric',
            'dblFAReportNeeded' => 'required|numeric',
            'dblCustomerFormat' => 'required|numeric',
            'dblCOCReportNeeded' => 'required|numeric',
            'dblISOCertification' => 'required|numeric',
            'strISOStandardID' => 'required',
            'strOthersForStandard' => 'required',
            'dblROHCompliance' => 'required|numeric',
            'dblHalogenCompliance' => 'required|numeric',
            'dblReachCompliance' => 'required|numeric',
            'dblULCSA' => 'required|numeric',
            'dblHACCPCompliance' => 'required|numeric',
            'dblFDACompliance' => 'required|numeric',
            'dblBSCompliance' => 'required|numeric',
            'dblColorManagement' => 'required|numeric',
            'strStatus' => 'required'
        );
    }

}
