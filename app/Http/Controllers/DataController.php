<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDataRequest;
use App\Services\Interfaces\HttpConnectorServiceInterface;
use App\Services\Interfaces\GenerateCommandServiceInterface;

class DataController extends Controller
{
    /**
     * @var HttpConnectorServiceInterface
     */
    protected HttpConnectorServiceInterface $httpConnectorService;

    /**
     * @var GenerateCommandServiceInterface
     */
    protected GenerateCommandServiceInterface $generateCommandService;

    /**
     * DataController constructor.
     *
     * @param HttpConnectorServiceInterface $httpConnectorService
     * @param GenerateCommandServiceInterface $generateCommandService
     */
    public function __construct(HttpConnectorServiceInterface $httpConnectorService, GenerateCommandServiceInterface $generateCommandService)
    {
        $this->httpConnectorService = $httpConnectorService;
        $this->generateCommandService = $generateCommandService;
    }

    public function getData(GetDataRequest $request)
    {
        $dataQueries = json_decode($request->dataQueries, true, 512, JSON_THROW_ON_ERROR);
        $data = [];
        foreach ($dataQueries as $dataQuery) {
            $swetestOptions = $this->httpConnectorService->connectSwetestOptions($dataQuery, $this->generateCommandService);

            if (isset($swetestOptions['error'])) {
                return response($swetestOptions['error'], 422);
            }

            $command = $this->generateCommandService->generateCommand('swetest', $swetestOptions);
            if ($command) {
                exec($command, $output);
                $data[] = $output;
            } else {
                $data[] = 'Not valid data have been received.';
            }
        }

        return response()->json(['data' => $data], 200);
    }
}
