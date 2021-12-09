<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Webpatser\Uuid\Uuid;

function searchInCollection(Collection $collection, $search)
{
    return ($collection->filter(function ($item) use ($search) {
        $attributes = array_keys($item);
        foreach ($attributes as $attribute)
            if (isset($item[$attribute]) && (! is_array($item[$attribute])))
                if (stripos($item[$attribute], $search) !== false)
                    return true;

        return false;
    }))->toArray();
}

function urlToUsername($urlString)
{
    $urlString = str_replace('http://', '', $urlString);
    $urlString = str_replace('https://', '', $urlString);
    $urlString = str_replace('www.', '', $urlString);

    $clearParams = explode('/', $urlString);
    
    $mainDomain = $clearParams[0];
    $breakMainDomain = explode('.', $mainDomain);
    $domainName = $breakMainDomain[0];
    $domainExtension = $breakMainDomain[1];

    return $domainName . $domainExtension;
}

/**
 * Check if the string contains certain item
 * 
 * @param string  $string
 * @param string  $find
 * @param bool  $strict
 * @return bool
 */
if (! function_exists('string_contains')) {
    function string_contains(string $string, string $find, bool $strict = false)
    {
        return ($strict) ? strpos($string, $find) : stripos($string, $find);
    }
}

/**
 * Concat two path (whether it's url or directory) as one
 * 
 * @param string  ...$paths
 * @return string
 */
if (! function_exists('concat_paths')) {
    function concat_paths(...$paths) {
        $paths = array_map(function ($path) {
            if (first_character($path) == '/') {
                $path = substr($path, 1);
            }

            if (last_character($path) == '/') {
                $path = substr($path, -1);
            }
        }, $paths);

        return implode('/', $paths);
    }
}

/**
 * Check the first character of a string
 * 
 * @param string  $string
 * @return string
 */
if (! function_exists('first_character')) {
    function first_character(string $string) {
        return substr($string, 1);
    }
}

function last_character(string $string)
{
    return substr($string, -1);
}

function str_to_singular(string $string)
{
    return Str::singular($string);
}

function str_to_plural(string $string)
{
    return Str::plural($string);
}

function str_snake_case(string $string)
{
    return Str::snake($string);
}

function str_camel_case(string $string)
{
    return Str::camel($string);
}

function get_lower_class($class)
{
    $lowerClassname = strtolower(get_class($class));

    if ($explode = explode('\\', $lowerClassname)) {
        return $explode[count($explode) - 1];
    }

    return $lowerClassname;
}

function get_plural_lower_class($class)
{
    return str_to_plural(get_lower_class($class));
}

function strtobool($string = null)
{   
    if ($string === null) {
        return false;
    }

    if ($string == 'true' || $string == 'false') {
        return filter_var($string, FILTER_VALIDATE_BOOLEAN);
    }

    if ($string == '1' || $string == '0') {
        return boolval($string);
    }

    return true;
}

function snakeToNormalCase($snakeCase)
{
    $spacedString = str_replace('_', ' ', $snakeCase);
    $result = ucwords($spacedString);

    return $result;
}

function snakeToCamelCase($snakeCase, $capitalizeFirstCharacter = false)
{
    $spacedString = str_replace('_', ' ', $snakeCase);
    $capitalized = ucwords($spacedString);
    $result = str_replace(' ', '', $capitalized);

    if (! $capitalizeFirstCharacter) {
        $result[0] = strtolower($result[0]);
    }

    return $result;
}

function numbertofloat($number)
{
    return sprintf('%.2f', $number);
}

function createLog($logType = 'info', $message = 'Log log log')
{
    Log::$logType($message);
}

function generateUuid()
{
    return Uuid::generate()->string;
}

function db($table = null)
{
    return ($table) ? 
        DB::table($table) :
        new DB;
}

function hashCheck($check, $hashed)
{
    return Hash::check($check, $hashed);
}

function encryptArray(array $array)
{
    return encryptString(json_encode($array));
}

function decryptArray(string $arrayString)
{
    return json_decode(decryptString($arrayString), true);
}

function encryptString($string)
{
    return Crypt::encryptString($string);
}

function decryptString($encrypted)
{
    try {
        $decryptString = Crypt::decryptString($encrypted);        
    } catch (Exception $e) {
        $decryptString = null;
    }

    return $decryptString;
}

function executor()
{
    return auth()->check() ?
        auth()->user()->id :
        'SYSTEM';
}

function random_string($length = 4)
{
    return randomString($length);
}

function randomString($length = 8)
{
    return Str::random($length);
}

function randomDate($format = 'd/m/Y')
{
    $date = carbon()
        ->now()
        ->addDays(rand((-5), 5))
        ->format($format);

    return $date;
}

function carbon($parameter = null)
{
    return $parameter ? 
        new Carbon() : new Carbon($parameter);
}

function carbonParseFormat($dateString, $format)
{
    return carbon()
        ->parse($dateString)
        ->format($format);
}

function carbonParseTimestamp($dateString)
{
    return carbon()
        ->parse($dateString)
        ->timestamp;
}

function currentTimestamp()
{
    return carbon()->now()->timestamp;
}

function carbonStartOfDay($date)
{
    return Carbon::parse($date)->copy()->startOfDay();
}

function carbonEndOfDay($date)
{
    return Carbon::parse($date)->copy()->endOfDay();
}

function jsonResponse($response)
{
    return response()->json($response);
}

function viewResponse($viewName, $response, $repositoryObject)
{
    $view = view($viewName, $response);
    $view = $repositoryObject ?
        $view->with(
            $repositoryObject->status, 
            $repositoryObject->message
        ) : $view;

    return $view;
}

function apiResponse($repositoryObject, $responseData = null)
{
    $response = [];
    
    if ($repositoryObject->status) {
        $response['status'] = $repositoryObject->status;
    }

    if ($repositoryObject->message) {
        $response['message'] = $repositoryObject->message;
    }

    if ($repositoryObject->queryError) {
        $response['query_error'] = $repositoryObject->queryError;
    }

    if ($responseData) {
        if (is_array($responseData)) {
            $response = array_merge($response, $responseData);
        } else {
            $response['data'] = $responseData;
        }
    }

    $httpStatus = $repositoryObject->httpStatus;
    return response()->json($response, $httpStatus);
}

function uppercaseArray($array)
{
    return array_map('strtoupper', $array);
}

function flashMessage($repositoryObject)
{
    session()->flash(
        $repositoryObject->status, 
        ($repositoryObject->status == 'success') ? 
            $repositoryObject->message : 
            $repositoryObject->queryError
    );
}

function flash_repository($repository)
{
    $status = $repository->status;
    $message = $repository->status == 'success' ?
        $repository->message : 
        $repository->queryError;

    session()->flash($status, $message);
}

function uploadFile($fileRequest, $filePath)
{
    $fileName = $filePath . Carbon::now()->format('YmdHis'); 
    $fileName .= $fileRequest->getClientOriginalName();

    $fileRequest->move(
        public_path($filePath), 
        $fileName
    );

    return $fileName;
}

function uploadBase64File($base64File, $path = 'uploads/documents', $fileName = '')
{
    if(! File::exists($path))
        File::makeDirectory($path, $mode = 0755, true, true);

    $base64String = substr($base64File, strpos($base64File, ",") + 1);
    $imageData = base64_decode($base64String);
    $extension = explode('/', explode(':', substr($base64File, 0, strpos($base64File, ';')))[1])[1];

    // Prepare image path
    $path = (substr($path, -1) == '/') ?
        $path : 
        $path . '/';
    $fileName = ($fileName ? $fileName : Carbon::now()->format('YmdHis')) . '.' . $extension;
    $putImage = File::put(public_path($path . $fileName), $imageData);

    return $putImage ? $fileName : false;
}

function uploadBase64Image($base64Image, $imagePath = 'uploads/test', $imageName = '')
{
    if(! File::exists($imagePath))
        File::makeDirectory($imagePath, $mode = 0755, true, true);

    $base64String = substr($base64Image, strpos($base64Image, ",") + 1);
    $imageData = base64_decode($base64String);
    $extension = explode('/', explode(':', substr($base64Image, 0, strpos($base64Image, ';')))[1])[1];

    // Prepare image path
    $imagePath = (substr($imagePath, -1) == '/') ?
        $imagePath : 
        $imagePath . '/';
    $fileName = ($imageName ? $imageName : Carbon::now()->format('YmdHis')) . '.' . $extension;

    $putImage = File::put(public_path($imagePath . $fileName), $imageData);

    return $putImage ? $fileName : false;
}

function deleteFile($filePath)
{
    return File::delete($filePath);
}

function format_money($amount)
{
    return (string) number_format($amount, 0, ',', '.');
}

function currency_format($amount, string $currencyCode = 'EUR', string $locale = 'lt_LT.UTF-8')
{
    $formatter = new \NumberFormatter($locale, NumberFormatter::CURRENCY);
    return $formatter->formatCurrency($amount, $currencyCode);

    /*setlocale(LC_MONETARY, $locale);
    return money_format('%(#1n', $amount);*/
}

function toRupiah($amount)
{
    return 'Rp. ' . format_money($amount);
}

function formatRupiah($amount)
{
    return toRupiah($amount);
}

function currentLink()
{
    return url()->current();
}

function requestMethod()
{
    return request()->method();
}

function isRoute($routeName)
{
    return Route::currentRouteName() == $routeName;
}

function isRouteStartsWith($start, $routeName = '')
{
    // if route is not defined make it current route
    $routeName = $routeName ? $routeName : Route::currentRouteName();

    return substr($routeName, 0, strlen($start)) == $start;
}

function isRouteStartsWithAny(array $starts, $routeName = '')
{

    foreach ($starts as $start) {
        $routeName = $routeName ? 
            $routeName : 
            Route::currentRouteName();

        if (substr($routeName, 0, strlen($start)) == $start)
            return true;
    }

    return false;
}

function createPagination($collections, $perPage = 10, $currentPage = 1)
{
    $pagination = new App\Repositories\PaginationRepository;

    return $pagination->paginateCollection(
        $collections, 
        $perPage, 
        $currentPage
    );
}

function generatePaginationLinks(
    $currentLink,
    array $urlParameters,
    $amountOfPage,
    $currentPage = 1
) {
    $link = [
        'prev_link' => '#',
        'next_link' => '#',
        'current_link' => '#',
        'urls' => [],
    ];
    $currentPage = isset($urlParameters['page']) ?
        $urlParameters['page'] : 1;
    $urlParameters['page'] = isset($urlParameters['page']) ?
        $urlParameters['page'] : $currentLink . '?page=' . $currentPage;

    for ($i = 1; $i <= $amountOfPage; $i++) {
        $iteration = 0;
        $amountOfParams = count($urlParameters);

        $link['urls'][$i] = $currentLink . '?';
        foreach ($urlParameters as $key => $parameter) {
            if ($key != 'page')
                $link['urls'][$i] .= $key . '=' . $parameter;
            else
                $link['urls'][$i] .= 'page' . '=' . $i;

            $iteration++;
            $link['urls'][$i] = $iteration != $amountOfParams ?
                $link['urls'][$i] . '&' : $link['urls'][$i];
        }

        if ($i == ($currentPage - 1))
            $link['prev_link'] = $link['urls'][$i];
        else if ($i == ($currentPage + 1))
            $link['next_link'] = $link['urls'][$i];
        else if ($i == ($currentPage))
            $link['current_link'] = $link['urls'][$i];
    }

    return $link;
}

function queueSendEmail($job, $delay = 1)
{
    $job->delay(carbon()->now()->addSeconds($delay));
    dispatch($job);
}

function queueJob($job, $delay = 1)
{
    $job->delay(carbon()->now()->addSeconds($delay));
    dispatch($job);
}

function record_activity($message = '', $user = null, $model = null)
{
    $activity = activity();

    if ($model !== null) {
        $activity = $activity->performedOn($model);
    }

    if ($user !== null) {
        $activity = $activity->causedBy($user);
    }

    $activity->log($message);
}