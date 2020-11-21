<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDataRequest;
use App\Services\Interfaces\HttpConnectorServiceInterface;
use App\Services\Interfaces\GenerateCommandServiceInterface;

class DataController extends Controller
{
    public function getData(GetDataRequest $request,
                            HttpConnectorServiceInterface $httpConnectorService,
                            GenerateCommandServiceInterface $generateCommandService)
    {
        $data = [];
        foreach ($request->dataQueries as $dataQuery) {
            $swetestOptions = $httpConnectorService->connectSwetestOptions($dataQuery);

            if (isset($swetestOptions['error'])) {
                return response($swetestOptions['error'], 422);
            }

            $command = $generateCommandService->generateCommand('swetest', $swetestOptions);
            $data[] = exec($command, $output);
        }

        return response($data, 200);
    }
}
