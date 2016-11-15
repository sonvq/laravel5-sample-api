<?php

namespace App\Models;

use App\Models\Common\BaseModel;

/**
 * Class Checklist
 * package App
 */
class Checklist extends BaseModel {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tblChecklist';

    /*
     * Indicate this table is not timestamps db
     */
    public $timestamps = false;

    /**
     * Define primary key
     */
    protected $primaryKey = 'cntID';

    /**
     * The database connection used by the model.
     *
     * @var string
     */
    protected $connection = 'erp_temp';

    /*
     * Define constant data array to store droplist
     */

    const LABEL_SIZE_TYPE = [
        'mm' => 'mm',
        'inch' => 'inch'
    ];
    const VDP_BY_CUST = [
        'nil' => 'Nil',
        'ttr' => 'TTR',
        'dt' => 'DT',
        'laser' => 'LASER',
        'inkject' => 'Inkjet',
        'dot_matrix' => 'Dot matrix',
        'photo_copier' => 'Photo copier',
        'rubber_stamping' => 'Rubber stamping'
    ];
    const VARMISH_TEXTURE = [
        'gloss' => 'Gloss',
        'matte' => 'Matte',
        'na' => 'NA'
    ];
    const LAMINATION = [
        'gloss' => 'Gloss',
        'matte' => 'Matte',
        'coated' => 'Coated',
        'uncoated' => 'Uncoated',
        'na' => 'NA'
    ];
    const BARCODE_REQUIRED = [
        'ean13' => 'EAN13',
        'c39' => 'C39',
        'i20f5' => 'I2of5(ITF14)',
        'upc' => 'UPC',
        'c128' => 'C128',
        'qrcode' => 'QRCode',
        'other' => 'Other',
        'na' => 'NA'
    ];
    const BARCODE_TYPE = [
        'static' => 'Static',
        'serialize' => 'Serialize',
        'random' => 'Random'
    ];
    const FACESTOCK_APPEARANCE = [
        'matte' => 'Matte',
        'semi_gloss' => 'Semi-gloss',
        'gloss' => 'Gloss',
        'brushed' => 'Brushed or Hairline'
    ];
    const FACESTOCK_TYPES = [
        'pe' => 'PE',
        'pp' => 'PP',
        'po' => 'PO',
        'pet' => 'PET',
        'paper' => 'PAPER'
    ];
    const FACESTOCK_COLOR = [
        'tranparent' => 'Transparent',
        'white' => 'White',
        'metalise' => 'Metalise',
        'silver' => 'Silver',
        'gold' => 'Gold',
        'fluorescent' => 'Fluorescent',
    ];
    const FLUORESCENT = [
        'yellow' => 'Yellow',
        'orange' => 'Orange',
        'red' => 'Red',
        'blue' => 'Blue',
        'green' => 'Green',
        'pink' => 'Pink',
        'na' => 'NA'
    ];
    const ADHESIVE = [
        'nil' => 'Nil',
        'permament' => 'Permament',
        'removable' => 'Removable',
        'repositionable' => 'Repositionable',
        'hot_melt' => 'Hot Melt',
        'block' => 'Block'
    ];
    const PACKING_TYPE = [
        'rollform' => 'RollForm',
        'sheetform' => 'SheerForm',
        'fanfold' => 'Fanfold',
        'computerform' => 'ComputerForm'
    ];
    const PACKING_SIZE = [
        'pcs' => 'Pcs',
        'roll' => 'Roll',
        'pack' => 'Pack'
    ];
    const CORE_MATERIAL = [
        'paper' => 'Paper',
        'plastic' => 'Plastic'
    ];
    const CORE_SIZE = [
        1 => '1/2"Core',
        2 => '1"Core',
        3 => '3"Core',
        4 => '40mm',
        5 => '45mm',
        6 => 'Others'
    ];
    const DURATION_BOILING_SUBMERGE = [
        'mins' => 'mins',
        'hours' => 'hours',
        'days' => 'days'
    ];
    const MATERIAL = [
        'hdpe' => 'HDPE',
        'ldpe' => 'LDPE',
        'pet' => 'PET',
        'glass' => 'Glass',
        'corrugated_box' => 'Corrugated box',
        'shrink_wrap' => 'Shrink_Wrap',
        'pvc' => 'PVC',
        'metal' => 'Metal',
        'pc' => 'PC',
        'others' => 'Others'
    ];
    const ISO_STANDARD = [
        '9001' => '9001',
        '13485' => '13485',
        '14001' => '14001',
        'other' => 'Other'
    ];
    const SURFACE = [
        'smooth' => 'Smooth',
        'rough' => 'Rough',
        'oily' => 'Oily'
    ];
    const SHAPE = [
        'flat' => 'Flat',
        'curved' => 'Curved',
        'small_diameter' => 'Small Diameter',
        'corner' => 'Round',
        'cone' => 'Cone (Cup - shape)',
        'others' => 'Others'
    ];

    public static function saveData($input, $action = 'add') {
        if ($action == 'add' || $action == 'revision') {
            $checklist = new Checklist();
            $checklist->dtmDateCreated = date("Y-m-d H:i:s");
        } elseif ($action == 'update') {
            $id = isset($input['cntID']) ? $input['cntID'] : '';
            $checklist = Checklist::find($id);
            $checklist->dtmDateEdit = date("Y-m-d H:i:s");
        }

        $checklist->strCustomerID = $input['strCustomerID'];
        $checklist->strContactID = $input['strContactID'];
        $checklist->strIndustryID = $input['strIndustryID'];
        $checklist->strDescriptionPart = $input['strDescriptionPart'];
        $checklist->strQuotationQuantity = $input['strQuotationQuantity'];
        $checklist->dblAnnualForecast = isset($input['dblAnnualForecast']) ? $input['dblAnnualForecast'] : null;
        $checklist->dblWidthLabelSize = $input['dblWidthLabelSize'];
        $checklist->dblHeightLabelSize = $input['dblHeightLabelSize'];
        $checklist->strNoSKU = isset($input['strNoSKU']) ? $input['strNoSKU'] : null;
        $checklist->dblPerforation = $input['dblPerforation'];
        $checklist->dblComputerPunch = $input['dblComputerPunch'];
        $checklist->dblComputerPunchPer = isset($input['dblComputerPunchPer']) ? $input['dblComputerPunchPer'] : null;
        $checklist->dblActualSample = $input['dblActualSample'];
        $checklist->dblArtWorkFile = $input['dblArtWorkFile'];
        $checklist->dblDrawing = $input['dblDrawing'];
        $checklist->strDrawingRev = isset($input['strDrawingRev']) ? $input['strDrawingRev'] : null;
        $checklist->dblCosmetic = $input['dblCosmetic'];
        $checklist->strCosmeticSpecification = isset($input['strCosmeticSpecification']) ? $input['strCosmeticSpecification'] : null;
        $checklist->strVDPByCustID = $input['strVDPByCustID'];
        $checklist->dblVDPHonsen = $input['dblVDPHonsen'];
        $checklist->dblNosOfColors = $input['dblNosOfColors'];
        $checklist->strSpecialColor = $input['strSpecialColor'];
        $checklist->strVamishTextureID = $input['strVamishTextureID'];
        $checklist->strLaminationID = $input['strLaminationID'];
        $checklist->strBarcodeRequiredID = $input['strBarcodeRequiredID'];
        $checklist->strBarcodeTypeID = $input['strBarcodeTypeID'];
        $checklist->strFacestockAppearanceID = $input['strFacestockAppearanceID'];
        $checklist->strFacestockTypesID = isset($input['strFacestockTypesID']) ? $input['strFacestockTypesID'] : null;
        $checklist->strFacestockColorID = $input['strFacestockColorID'];
        $checklist->strFluorescentID = $input['strFluorescentID'];
        $checklist->strAdhesiveID = $input['strAdhesiveID'];
        $checklist->dblPatternAdhesiveID = $input['dblPatternAdhesiveID'];
        $checklist->strPackingTypeID = $input['strPackingTypeID'];
        $checklist->dblPackingSize = isset($input['dblPackingSize']) ? $input['dblPackingSize'] : null;
        $checklist->strPackingSizeType = isset($input['strPackingSizeType']) ? $input['strPackingSizeType'] : null;
        $checklist->strSpecialPackingRequirement = isset($input['strSpecialPackingRequirement']) ? $input['strSpecialPackingRequirement'] : null;
        $checklist->strCoreMaterialID = $input['strCoreMaterialID'];
        $checklist->strCoreSizeID = $input['strCoreSizeID'];
        $checklist->dblApplicationTemperature = $input['dblApplicationTemperature'];
        $checklist->dblServiceTemperature = $input['dblServiceTemperature'];
        $checklist->dblWaterResistance = $input['dblWaterResistance'];
        $checklist->dblBoiling = $input['dblBoiling'];
        $checklist->dblSubmerge = $input['dblSubmerge'];
        $checklist->dblControlRoom = $input['dblControlRoom'];
        $checklist->dblLightFastness = $input['dblLightFastness'];
        $checklist->dblTemperatureForBoiling = $input['dblTemperatureForBoiling'];
        $checklist->dblDurationForBoiling = $input['dblDurationForBoiling'];
        $checklist->strDurationForBoilingTypeTime = $input['strDurationForBoilingTypeTime'];
        $checklist->dblTemperatureForSubmerge = $input['dblTemperatureForSubmerge'];
        $checklist->dblDurationForSubmerge = $input['dblDurationForSubmerge'];
        $checklist->strDurationForSubmergeTypeTime = $input['strDurationForSubmergeTypeTime'];
        $checklist->strOtherEnvironment = isset($input['strOtherEnvironment']) ? $input['strOtherEnvironment'] : null;
        $checklist->dblESDCompliance = $input['dblESDCompliance'];
        $checklist->dblAutoApplicator = $input['dblAutoApplicator'];
        $checklist->dblSpeed = isset($input['dblSpeed']) ? $input['dblSpeed'] : 0;
        $checklist->dblSpeedType = isset($input['dblSpeedType']) ? $input['dblSpeedType'] : null;
        $checklist->strMaterialID = $input['strMaterialID'];
        $checklist->strOtherForMaterial = $input['strOtherForMaterial'];
        $checklist->dblSubstrateSampleForMaterial = $input['dblSubstrateSampleForMaterial'];
        $checklist->strSurfaceID = $input['strSurfaceID'];
        $checklist->strShapeID = $input['strShapeID'];
        $checklist->dblSubstrateSampleForShape = $input['dblSubstrateSampleForShape'];
        $checklist->strOtherForShape = $input['strOtherForShape'];
        $checklist->dblSqueezable = $input['dblSqueezable'];
        $checklist->dblRigid = $input['dblRigid'];
        $checklist->dblQC = $input['dblQC'];
        $checklist->dblMaterialCleaningRequire = $input['dblMaterialCleaningRequire'];
        $checklist->dblFAReportNeeded = $input['dblFAReportNeeded'];
        $checklist->dblCustomerFormat = $input['dblCustomerFormat'];
        $checklist->dblCOCReportNeeded = $input['dblCOCReportNeeded'];
        $checklist->dblISOCertification = $input['dblISOCertification'];
        $checklist->strISOStandardID = $input['strISOStandardID'];
        $checklist->strOthersForStandard = $input['strOthersForStandard'];
        $checklist->dblROHCompliance = $input['dblROHCompliance'];
        $checklist->dblHalogenCompliance = $input['dblHalogenCompliance'];
        $checklist->dblReachCompliance = $input['dblReachCompliance'];
        $checklist->dblULCSA = $input['dblULCSA'];
        $checklist->dblHACCPCompliance = $input['dblHACCPCompliance'];
        $checklist->dblFDACompliance = $input['dblFDACompliance'];
        $checklist->dblBScompliance = $input['dblFDACompliance'];
        $checklist->strOthersForCertificationCompliance = isset($input['strOthersForCertificationCompliance']) ? $input['strOthersForCertificationCompliance'] : null;
        $checklist->strDimensionalTolerance = isset($input['strDimensionalTolerance']) ? $input['strDimensionalTolerance'] : null;
        $checklist->dblColorManagement = $input['dblColorManagement'];
        $checklist->strStatus = isset($input['strStatus']) ? $input['strStatus'] : 'inprogress';

        if ($checklist->save()) {
            if ($action == 'add' || $action == 'revision') {
                $checklistID = $checklist->cntID;
                Checklist::makeCodeStringID($checklistID, $action);
            }
            return $checklist;
        } else {
            return FALSE;
        }
    }

    public function postProcessModel() {
        $attributes = $this->attributesToArray();

        foreach ($attributes as $keyAttribute => $singleAttribute) {
            if (in_array($keyAttribute, $this->dateFields) && !empty($singleAttribute)) {
                $attributes[$keyAttribute] = strtotime($singleAttribute);
            }

            if ($keyAttribute == 'strChecklistID') {
                //$attributes[$keyAttribute] = '#' . $attributes[$keyAttribute];
            }

            if ($keyAttribute == 'strVDPByCustID') {
                $vdpByCust = Checklist::VDP_BY_CUST;

                $attributes[$keyAttribute] = isset($vdpByCust[$attributes[$keyAttribute]]) ? $vdpByCust[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strVamishTextureID') {
                $varmish = Checklist::VARMISH_TEXTURE;

                $attributes[$keyAttribute] = isset($varmish[$attributes[$keyAttribute]]) ? $varmish[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strLaminationID') {
                $lamination = Checklist::LAMINATION;

                $attributes[$keyAttribute] = isset($lamination[$attributes[$keyAttribute]]) ? $lamination[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strBarcodeRequiredID') {
                $barcodeRequired = Checklist::BARCODE_REQUIRED;

                $attributes[$keyAttribute] = isset($barcodeRequired[$attributes[$keyAttribute]]) ? $barcodeRequired[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strBarcodeTypeID') {
                $barcodeType = Checklist::BARCODE_TYPE;

                $attributes[$keyAttribute] = isset($barcodeType[$attributes[$keyAttribute]]) ? $barcodeType[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strFacestockAppearanceID') {
                $facebstock = Checklist::FACESTOCK_APPEARANCE;

                $attributes[$keyAttribute] = isset($facebstock[$attributes[$keyAttribute]]) ? $facebstock[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strFacestockTypesID') {
                $facebstock = Checklist::FACESTOCK_TYPES;

                $attributes[$keyAttribute] = isset($facebstock[$attributes[$keyAttribute]]) ? $facebstock[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strFacestockColorID') {
                $facebstock = Checklist::FACESTOCK_COLOR;

                $attributes[$keyAttribute] = isset($facebstock[$attributes[$keyAttribute]]) ? $facebstock[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strFluorescentID') {
                $fluorescent = Checklist::FLUORESCENT;

                $attributes[$keyAttribute] = isset($fluorescent[$attributes[$keyAttribute]]) ? $fluorescent[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strAdhesiveID') {
                $adhesive = Checklist::ADHESIVE;

                $attributes[$keyAttribute] = isset($adhesive[$attributes[$keyAttribute]]) ? $adhesive[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strPackingTypeID') {
                $packingType = Checklist::PACKING_TYPE;

                $attributes[$keyAttribute] = isset($packingType[$attributes[$keyAttribute]]) ? $packingType[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strPackingSizeType') {
                $packingSize = Checklist::PACKING_SIZE;

                $attributes[$keyAttribute] = isset($packingSize[$attributes[$keyAttribute]]) ? $packingSize[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strCoreMaterialID') {
                $attributeArray = Checklist::CORE_MATERIAL;

                $attributes[$keyAttribute] = isset($attributeArray[$attributes[$keyAttribute]]) ? $attributeArray[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strCoreSizeID') {
                $attributeArray = Checklist::CORE_SIZE;

                $attributes[$keyAttribute] = isset($attributeArray[$attributes[$keyAttribute]]) ? $attributeArray[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strDurationForBoilingTypeTime' || $keyAttribute == 'strDurationForSubmergeTypeTime') {
                $attributeArray = Checklist::DURATION_BOILING_SUBMERGE;

                $attributes[$keyAttribute] = isset($attributeArray[$attributes[$keyAttribute]]) ? $attributeArray[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strMaterialID') {
                $attributeArray = Checklist::MATERIAL;

                $attributes[$keyAttribute] = isset($attributeArray[$attributes[$keyAttribute]]) ? $attributeArray[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strSurfaceID') {
                $attributeArray = Checklist::SURFACE;

                $attributes[$keyAttribute] = isset($attributeArray[$attributes[$keyAttribute]]) ? $attributeArray[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strShapeID') {
                $attributeArray = Checklist::SHAPE;

                $attributes[$keyAttribute] = isset($attributeArray[$attributes[$keyAttribute]]) ? $attributeArray[$attributes[$keyAttribute]] : null;
            }

            if ($keyAttribute == 'strISOStandardID') {
                $attributeArray = Checklist::ISO_STANDARD;

                $attributes[$keyAttribute] = isset($attributeArray[$attributes[$keyAttribute]]) ? $attributeArray[$attributes[$keyAttribute]] : null;
            }
        }

        $arrayCombined = array_merge($attributes, $this->relationsToArray());


        return $arrayCombined;
    }

    public static function makeCodeStringID($id, $revision = 'revision') {
        $checklist = Checklist::find($id);

        $rev = 0;
        if ($revision == 'revision') {
            $rev = ($checklist->dblRevision) + 1;
            $checklist->dblRevision = $rev;
        }

        $idCode = null;
        if ($id < 10) {
            $idCode = '0000' . $id;
        } elseif ($id >= 10 && $id < 100) {
            $idCode = '000' . $id;
        } elseif ($id >= 100 && $id < 1000) {
            $idCode = '00' . $id;
        } elseif ($id >= 1000 && $id < 10000) {
            $idCode = '0' . $id;
        }

        $codeStringID = $rev != 0 ? 'CL' . $idCode . 'R' . $rev : 'CL' . $idCode;

        $checklist->strChecklistID = $codeStringID;
        $checklist->save();

        return $codeStringID;
    }

}
