<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDataRequest;
use App\Services\Interfaces\ValidationServiceInterface;
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
     * @var ValidationServiceInterface
     */
    protected ValidationServiceInterface $validationService;

    /**
     * @var int
     */
    protected int $status = 200;

    /**
     * DataController constructor.
     *
     * @param HttpConnectorServiceInterface $httpConnectorService
     * @param GenerateCommandServiceInterface $generateCommandService
     * @param ValidationServiceInterface $validationService
     */
    public function __construct(HttpConnectorServiceInterface $httpConnectorService,
                                GenerateCommandServiceInterface $generateCommandService,
                                ValidationServiceInterface $validationService
    )
    {
        $this->httpConnectorService = $httpConnectorService;
        $this->generateCommandService = $generateCommandService;
        $this->validationService = $validationService;
    }

    /**
     * Get astro data endpoint
     *
     * @param GetDataRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function getData(GetDataRequest $request)
    {
        if (! ($dataQueries = $this->validationService->isJson($request->dataQueries))) {
            $this->changeResponseStatus(422);
            return response()->json(['error' => 'Not valid data have been received.'], $this->status);

        }

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
                $this->changeResponseStatus(422);
            }
        }

        return response()->json($data, $this->status);
    }

    /**
     * Change the response status
     *
     * @param int $status
     */
    protected function changeResponseStatus(int $status): void
    {
        $this->status = $status;
    }
}
