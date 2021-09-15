<?php


namespace App\Http\Traits;

trait JSONResponseTrait
{
    /**
     * @param $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
    */

    public function success($data = [], $statusCode = 200) {

        $response = [
            'success' => true,
            'data' => $data
        ];

        if(is_null($response['data'])) {
            unset($response['data']);
        }

        if(is_null($response['data'])) {
            $statusCode = 204;
        }

        return response()->json($response)->setStatusCode($statusCode);
    }

    /**
     * @param $errors
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse|object
    */
    public function failed($errors = [], $statusCode = 422) {

        $response = [
            'success' => false
        ];

        if(!is_null($errors) && (is_array($errors) || is_object($errors))) {
            foreach ($errors as $k => $error) {
                $response['error']['message'][$k] = $error;
            }
        }

        return response()->json($response)->setStatusCode($statusCode);
    }

    public function unknownError() {
        // Error 520: Web Server Is Returning an Unknown Error ( CloudFlare )
        return $this->failed(['Unknown Error'], 520);
        // $response['error']['message'] = 'Unknown Error';
    }
}