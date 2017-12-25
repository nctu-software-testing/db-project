<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class CaptchaController extends BaseController
{
    private static $imageList = null;
    private const PATH = 'captcha';
    private const ROW_COUNT = 9;
    private const COL_COUNT = 16;
    private const GRID_SIZE = 32;
    private const CONTENT_TYPE = 'image/png';
    private const ALLOW_ERROR_OF_GRID = self::GRID_SIZE / 4;
    public const TIMEOUT = 3 * 60;

    public function __construct()
    {
        parent::__construct('captcha');
    }

    private static function getImageList()
    {
        if (self::$imageList != null) return;
        $imgList = Storage::files(self::PATH);
        self::$imageList = $imgList;
    }

    public function getList()
    {
        self::getImageList();

        return $this->result(self::$imageList, true);
    }


    public function getIndex()
    {
        return view('captcha.index');
    }


    public function getFullImage()
    {
        $captcha = $this->prepareImageConfig();
        session()->put('captcha', $captcha);

        $imgRealPath = Storage::path($captcha['path']);
        $resizedImage = $this->prepareImageSource($imgRealPath);
        $imageArray = $captcha['imageArray'];

        $fullImage = $this->shuffleImage($resizedImage, $imageArray);

        $paddingDataArray = $imageArray;
        $paddingDataArray[] = count($imageArray);
        $paddingDataArray[] = self::GRID_SIZE;
        $paddingDataArray[] = self::ROW_COUNT;
        $paddingDataArray[] = self::COL_COUNT;
        $postfix = '';
        foreach ($paddingDataArray as $p) {
            $upper = ($p >> 8);
            $lower = ($p & 0xff);
            $postfix .= chr($upper);
            $postfix .= chr($lower);
        }
        $view = view('captcha.image')
            ->with('image', $fullImage)
            ->with('postfix', $postfix);

        $response = Response::make($view, 200)
            ->header("Content-Type", self::CONTENT_TYPE)
            ->header("Cache-Control", 'no-cache');

        return $response;
    }

    public function getMaskImage()
    {
        $captcha = session('captcha');
        if (!$captcha) {
            return abort(403);
        }
        $imgRealPath = Storage::path($captcha['path']);
        $resizedImage = $this->prepareImageSource($imgRealPath);
        $imageArray = $captcha['imageArray'];
        $selected = $captcha['selected'];
        $maskedImage = $this->getMaskedImage($resizedImage, $imageArray, $selected);

        $view = view('captcha.image')
            ->with('image', $maskedImage);

        $response = Response::make($view, 200)
            ->header("Content-Type", self::CONTENT_TYPE)
            ->header("Cache-Control", 'no-cache');

        return $response;
    }


    public function getSliceImage()
    {
        $captcha = session('captcha', null);
        if (is_null($captcha)) {
            return abort(404);
        }
        $data = $this->processSliceImage($captcha);
        $view = view('captcha.image', ['image' => $data]);
        $response = Response::make($view, 200)
            ->header("Content-Type", self::CONTENT_TYPE)
            ->header("Cache-Control", 'no-cache');

        return $response;
    }

    protected function processSliceImage(array $data)
    {
        $path = $data['path'];
        $selected = $data['selected'];
        $imgRealPath = Storage::path($path);

        $resizedImage = $this->prepareImageSource($imgRealPath);

        $output = imagecreatetruecolor(self::GRID_SIZE, self::GRID_SIZE * self::ROW_COUNT);
        $black = imagecolorallocate($output, 0, 0, 0);
        $t = imagecolortransparent($output, $black);
        imagefill($output, 0, 0, $t);
        imagecopymerge(
            $output, $resizedImage,
            0, $selected['y'],
            $selected['x'], $selected['y'],
            self::GRID_SIZE, self::GRID_SIZE,
            100
        );
        imagedestroy($resizedImage);
        return $output;
    }

    protected function postVerify(Request $request)
    {
        $captcha = session('captcha');
        if ($captcha) {
            // session()->forget('captcha');
            $value = floatval(request('value', -999));
            if (abs($captcha['selected']['x'] - $value) < self::ALLOW_ERROR_OF_GRID) {
                $now = time();
                if ($now - $captcha['created_at'] > self::TIMEOUT) {
                    return $this->result('驗證碼逾時', false);
                }
                $captcha['passed'] = true;
                $captcha['passed_at'] = $now;
                session()->put('captcha', $captcha);
                return $this->result('驗證成功', true);
            } else {
                session()->forget('captcha');
            }
        }
        return $this->result('驗證碼錯誤', false);
    }

    protected function prepareImageConfig()
    {
        self::getImageList();
        $index = array_rand(self::$imageList);
        $path = self::$imageList[$index];

        $imageArray = range(0, self::COL_COUNT * self::ROW_COUNT - 1);
        shuffle($imageArray);

        $half = self::GRID_SIZE >> 1;
        $selected = [
            'x' => rand($half * 4, self::COL_COUNT * self::GRID_SIZE - $half * 4),
            'y' => rand($half * 4, self::ROW_COUNT * self::GRID_SIZE - $half * 4),
        ];

        return [
            'imageArray' => $imageArray,
            'selected' => $selected,
            'path' => $path,
            'passed' => false,
            'created_at' => time(),
        ];
    }

    protected function shuffleImage($resizedImage, $imageArray)
    {
        $nWidth = self::GRID_SIZE * self::COL_COUNT;
        $nHeight = self::GRID_SIZE * self::ROW_COUNT;
        $output = imagecreatetruecolor($nWidth, $nHeight);
        imagecolorallocate($output, 0, 0, 0);

        $i = 0;
        foreach ($imageArray as $idx) {
            $rcInfo = self::getRowAndCol($idx);
            $r = $rcInfo['r'];
            $c = $rcInfo['c'];

            $gridImage = [
                'x' => $c * self::GRID_SIZE,
                'y' => $r * self::GRID_SIZE,
                'width' => self::GRID_SIZE,
                'height' => self::GRID_SIZE,
            ];
            $dstRcInfo = self::getRowAndCol($i);
            imagecopymerge(
                $output, $resizedImage,
                $dstRcInfo['c'] * self::GRID_SIZE, $dstRcInfo['r'] * self::GRID_SIZE,
                $gridImage['x'], $gridImage['y'],
                $gridImage['width'], $gridImage['height'],
                100
            );

            $i++;
        }

        return $output;
    }

    protected function getMaskedImage($resizedImage, $imageArray, $selected)
    {
        $black = imagecolorallocatealpha($resizedImage, 0x33, 0x33, 0x33, 32);

        $x1 = $selected['x'];
        $y1 = $selected['y'];
        imagefilledrectangle(
            $resizedImage,
            $x1, $y1,
            $x1 + self::GRID_SIZE - 1, $y1 + self::GRID_SIZE - 1,
            $black
        );
        $output = $this->shuffleImage($resizedImage, $imageArray);


        return $output;
    }

    protected static function getRowAndCol($idx)
    {
        $r = floor($idx / self::COL_COUNT);
        $c = $idx % self::COL_COUNT;

        return ['r' => $r, 'c' => $c];
    }

    protected function prepareImageSource($realPath)
    {
        $originalImage = $this->createImageFromFile($realPath);
        $width = imagesx($originalImage);
        $height = imagesy($originalImage);
        $nWidth = self::GRID_SIZE * self::COL_COUNT;
        $nHeight = self::GRID_SIZE * self::ROW_COUNT;
        $resizedImage = imagecreatetruecolor($nWidth, $nHeight);
        imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $nWidth, $nHeight, $width, $height);
        imagedestroy($originalImage);
        return $resizedImage;
    }

    protected function createImageFromFile(string $path)
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                return imagecreatefromjpeg($path);
            case 'gif':
                return imagecreatefromgif($path);
            case 'png':
                return imagecreatefrompng($path);
        }

        return null;
    }
}