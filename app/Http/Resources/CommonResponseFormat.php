<?php

namespace App\Http\Resources;

trait CommonResponseFormat
{

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'success' => $this->getSuccess(),
            'message' => $this->getMessage(),
            'errors' => $this->getErrors()
        ];
    }


    /**
     * @return string
     */
    public function getMessage() {
        return "Action successful.";
    }

    /**
     * @return bool
     */
    public function getSuccess() {
        return true;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return [];
    }
}
