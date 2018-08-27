<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\App;
use App\Models\Core\AppImage;
use Storage;

class AppImageController extends Controller
{
    public function upload( Request $request )
    {
        // Validate images
        $this->validate($request, [ 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096' ]);

        // Upload file
        $imagePath = Storage::disk( 'public' )->putFile(AppImageController::getPathTmp(), $request->file('image'), 'public');

        return response()->json( [
            'res' => true,
            'data' => [
                'image' => Storage::url($imagePath)
            ]
        ], 200 );
    }

    static function getPathTmp ()
    {
        return '_tmp';
    }

    static function getPathWeb ( $appId )
    {
        return 'apps/' . $appId;
    }

    static function store ( App $app, $imageData )
    {
        $imageInfo = pathinfo($imageData['src']);
        $origin = AppImageController::getPathTmp() . '/' . $imageInfo['basename'];
        $destination = AppImageController::getPathWeb( $app->id ) . '/' . $imageInfo['basename'];

        // Move image from tmp folder to appImages folder
        if ( Storage::disk( 'public' )->exists( $origin ) ) {
            Storage::disk( 'public' )->move( $origin, $destination );
        }
        
        return AppImage::create([
            'app_id' => $app->id,
            'src' => Storage::url($destination),
            'title' => $imageData['title'],
            'priority' => $imageData['priority']
        ])->id;
    }

    static function save ( AppImage $image, $imageData )
    {
        $image->title = $imageData['title'];
        $image->priority = $imageData['priority'];
        $image->save();
    }

    static function destroy ( AppImage $image )
    {
        $imageInfo = pathinfo($image->src);
        $origin = AppImageController::getPathWeb( $image->app_id ) . '/' . $imageInfo['basename'];

        if ( Storage::disk( 'public' )->exists( $origin ) ) {
            Storage::disk( 'public' )->delete( $origin );
        }
    }

    static function destroyDirectory ( $appId ) {
        Storage::disk( 'public' )->deleteDirectory(AppImageController::getPathWeb($appId));
    }
}
