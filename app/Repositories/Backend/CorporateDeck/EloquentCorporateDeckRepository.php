<?php

namespace App\Repositories\Backend\CorporateDeck;

use App\Models\CorporateDeck;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Events\Backend\CorporateDeck\CorporateDeckCreated;
use App\Events\Backend\CorporateDeck\CorporateDeckDeleted;
use App\Events\Backend\CorporateDeck\CorporateDeckUpdated;
use Spatie\PdfToImage\Pdf;
use App\Components\SimpleImage;

/**
 * Class EloquentCorporateDeckRepository
 * @package App\Repositories\Backend\CorporateDeck
 */
class EloquentCorporateDeckRepository implements CorporateDeckRepositoryContract
{
    
	/**
     * @return mixed
     */
    public function getCount() {
        return CorporateDeck::count();
    }

    /**
     * @return mixed
     */
    public function getForDataTable() {
        $corporateDeckList = CorporateDeck::select(['id', 'thumbnail', 'pdf', 'name', 'created_at', 'updated_at', 'deleted_at', 'user_id', 'description', 'status'])->whereNull('deleted_at')
            ->get();
        
        foreach ($corporateDeckList as $key=>$singleObject) {
            if(isset($singleObject->pdf) && !empty($singleObject->pdf)) {
                $singleObject->pdf = asset('/') . config('app.general_upload_folder') . '/' .  config('corporate-deck.pdf_upload_path') . '/' . $singleObject->pdf;
            }
            
            if(isset($singleObject->thumbnail) && !empty($singleObject->thumbnail)) {
                $singleObject->thumbnail = asset('/') . config('app.general_upload_folder') . '/' .  config('corporate-deck.pdf_upload_path') . '/' . $singleObject->thumbnail;
            }
            $corporateDeckList[$key] = $singleObject;
        }
        
        return $corporateDeckList;
    }        

    /**
     * @param  $request
     * @throws GeneralException
     * @return bool
     */
    public function create($request)
    {        
        $corporateDeck = $this->createCorporateDeckStub($request, new CorporateDeck);

		DB::transaction(function() use ($corporateDeck) {
			if ($corporateDeck->save()) {

				event(new CorporateDeckCreated($corporateDeck));
				return true;
			}

        	throw new GeneralException(trans('exceptions.backend.corporate-deck.create_error'));
		});
    }

    /**
     * @param  CorporateDeck $corporateDeck
     * @param  $request
     * @return mixed
     */
    public function update(CorporateDeck $corporateDeck, $request) {
        $corporateDeck = $this->createCorporateDeckStub($request, $corporateDeck);
        DB::transaction(function() use ($corporateDeck) {            
			if ($corporateDeck->update()) {
				event(new CorporateDeckUpdated($corporateDeck));
				return true;
			}

        	throw new GeneralException(trans('exceptions.backend.corporate-deck.update_error'));
		});
    }

    /**
     * @param  CorporateDeck $corporateDeck
     * @return mixed
     */
    public function destroy(CorporateDeck $corporateDeck) {

        if ($corporateDeck->delete()) {
            event(new CorporateDeckDeleted($corporateDeck));
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.corporate-deck.delete_error'));
    }
    
    /**
     * @param  $input
     * @return mixed
     */
    private function createCorporateDeckStub($request, $corporateDeck)
    {
        $input = $request->all();

        $corporateDeck->name              = $input['name'];
        $corporateDeck->description       = $input['description'];
        $corporateDeck->status            = isset($input['status']) ? $input['status'] : $corporateDeck->getDefaultStatusCorporateDeck();
        $corporateDeck->user_id           = auth()->id();
        
        if ($request->hasFile('pdf')) {
            $pdfFile = $request->file('pdf');                        
                    
            $uploadFolder = public_path() . DIRECTORY_SEPARATOR . config('app.general_upload_folder');
            
            if (!is_dir($uploadFolder)) {
                mkdir($uploadFolder, 0775, true);
            }
            
            $destinationPath = $uploadFolder . DIRECTORY_SEPARATOR . config('corporate-deck.pdf_upload_path');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0775, true);
            }                       
            
            $extension = $pdfFile->getClientOriginalExtension();

            $fileName = 'corporate_deck_' . uniqid() . '_' . time() . '.' . $extension;
            
            $pdfFile->move($destinationPath, $fileName);
            
            
            $imageName = 'corporate_deck_image_' . uniqid() . '_' . time() . '.png';
            $imagePath = $destinationPath . DIRECTORY_SEPARATOR . $imageName;
            $pdfFilePath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;
            
            exec('convert "' . $pdfFilePath . '[0]" -colorspace RGB "' . $imagePath . '"', $output, $return_var);
            //exec('C:\ImageMagick-6.9.3-7-vc11-x86\bin\convert "' . $pdfFilePath . '[0]" -colorspace RGB "' . $imagePath . '"', $output, $return_var);
            
            sleep(1);
            
            list($width, $height) = getimagesize($imagePath);
            if ($width >= $height) {
                exec('convert "' . $imagePath . '[0]" -colorspace RGB -resize x250 "' . $imagePath . '"', $output, $return_var);
                //exec('C:\ImageMagick-6.9.3-7-vc11-x86\bin\convert "' . $imagePath . '[0]" -colorspace RGB -resize x250 "' . $imagePath . '"', $output, $return_var);
            } else {
                exec('convert "' . $imagePath . '[0]" -colorspace RGB -resize 250x "' . $imagePath . '"', $output, $return_var);
                //exec('C:\ImageMagick-6.9.3-7-vc11-x86\bin\convert "' . $imagePath . '[0]" -colorspace RGB -resize 250x "' . $imagePath . '"', $output, $return_var);
            }            
            
            
//            $img = new SimpleImage($imagePath);
//            $img->best_fit(250, 190)->save($imagePath);
        
//            $pdfImage = new \Imagick($pdfFilePath . '[0]');
//            $pdfImage->setSize(250, 190);
//            $pdfImage->setImageFormat("png");
//            $pdfImage->writeImage($imagePath);

//            $pdfImage = new Pdf($pdfFilePath);
//            $pdfImage->setOutputFormat('png')->setResolution(300, 300)->saveImage($imagePath);
            
            
            $corporateDeck->pdf = $fileName;
            $corporateDeck->thumbnail = $imageName;
        }        
        
        return $corporateDeck;
    }
}